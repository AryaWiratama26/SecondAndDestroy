<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use RuntimeException;

class AvalancheController extends Controller
{
    
    public function index()
    {
        return view('avalanche.index');
    }

    
    public function test(Request $request)
    {
        $request->validate([
            'plaintext' => 'required|string|min:1|max:1000',
        ]);

        $plaintext = $request->input('plaintext');
        $key = $this->getEncryptionKey();
        
    
        $iv = random_bytes(16);

        
        $ciphertext1 = $this->encryptWithIV($plaintext, $key, $iv);

        
        $modifiedPlaintext = $this->flipOneBit($plaintext);

        
        $ciphertext2 = $this->encryptWithIV($modifiedPlaintext, $key, $iv);

        
        $binary1 = $this->toBinary($ciphertext1);
        $binary2 = $this->toBinary($ciphertext2);
        
        $hammingDistance = $this->hammingDistance($binary1, $binary2);
        $totalBits = strlen($binary1);
        $avalanchePercentage = ($hammingDistance / $totalBits) * 100;

        
        $bitComparison = $this->createBitComparison($binary1, $binary2);

        return view('avalanche.index', [
            'plaintext' => $plaintext,
            'modifiedPlaintext' => $modifiedPlaintext,
            'plaintextBinary' => $this->toBinary($plaintext),
            'modifiedPlaintextBinary' => $this->toBinary($modifiedPlaintext),
            'ciphertext1' => bin2hex($ciphertext1),
            'ciphertext2' => bin2hex($ciphertext2),
            'ciphertextBinary1' => $binary1,
            'ciphertextBinary2' => $binary2,
            'bitComparison' => $bitComparison,
            'hammingDistance' => $hammingDistance,
            'totalBits' => $totalBits,
            'avalanchePercentage' => round($avalanchePercentage, 2),
            'isGoodAvalanche' => $avalanchePercentage >= 40 && $avalanchePercentage <= 60,
        ]);
    }

    public function statisticTest(Request $request)
    {
        $request->validate([
            'plaintext' => 'required|string|min:1|max:1000',
            'iterations' => 'required|integer|min:5|max:100',
        ]);

        $plaintext = $request->input('plaintext');
        $iterations = $request->input('iterations');
        $key = $this->getEncryptionKey();

        $results = [];
        $allBitComparisons = [];

        for ($i = 0; $i < $iterations; $i++) {
            $iv = random_bytes(16);
            
            $ciphertext1 = $this->encryptWithIV($plaintext, $key, $iv);
            $modifiedPlaintext = $this->flipRandomBit($plaintext, $i);
            $ciphertext2 = $this->encryptWithIV($modifiedPlaintext, $key, $iv);

            $binary1 = $this->toBinary($ciphertext1);
            $binary2 = $this->toBinary($ciphertext2);
            
            $hammingDistance = $this->hammingDistance($binary1, $binary2);
            $totalBits = strlen($binary1);
            $avalanchePercentage = ($hammingDistance / $totalBits) * 100;

            $results[] = [
                'iteration' => $i + 1,
                'modifiedPlaintext' => $modifiedPlaintext,
                'hammingDistance' => $hammingDistance,
                'totalBits' => $totalBits,
                'avalanchePercentage' => round($avalanchePercentage, 2),
            ];
        }

        $percentages = array_column($results, 'avalanchePercentage');
        $statistics = [
            'count' => count($percentages),
            'min' => round(min($percentages), 2),
            'max' => round(max($percentages), 2),
            'average' => round(array_sum($percentages) / count($percentages), 2),
            'stdDev' => round($this->standardDeviation($percentages), 2),
        ];

        $statistics['isGoodAvalanche'] = $statistics['average'] >= 40 && $statistics['average'] <= 60;

        // Store in session for export
        session([
            'avalanche_results' => $results,
            'avalanche_statistics' => $statistics,
            'avalanche_plaintext' => $plaintext,
        ]);

        return view('avalanche.index', [
            'plaintext' => $plaintext,
            'iterations' => $iterations,
            'results' => $results,
            'statistics' => $statistics,
            'showStatistics' => true,
        ]);
    }

    public function exportExcel()
    {
        $results = session('avalanche_results');
        $statistics = session('avalanche_statistics');
        $plaintext = session('avalanche_plaintext');

        if (!$results || !$statistics) {
            return redirect()->route('avalanche.index')
                ->with('error', 'Tidak ada data untuk diexport. Jalankan pengujian terlebih dahulu.');
        }

        $filename = 'avalanche_effect_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($results, $statistics, $plaintext) {
            $file = fopen('php://output', 'w');
            
            // BOM for Excel UTF-8 compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header info
            fputcsv($file, ['LAPORAN PENGUJIAN AVALANCHE EFFECT']);
            fputcsv($file, ['Algoritma', 'AES-256-CBC']);
            fputcsv($file, ['Plaintext Original', $plaintext]);
            fputcsv($file, ['Tanggal Pengujian', date('d-m-Y H:i:s')]);
            fputcsv($file, []);

            // Statistics summary
            fputcsv($file, ['RINGKASAN STATISTIK']);
            fputcsv($file, ['Jumlah Iterasi', $statistics['count']]);
            fputcsv($file, ['Rata-rata (%)', $statistics['average']]);
            fputcsv($file, ['Minimum (%)', $statistics['min']]);
            fputcsv($file, ['Maximum (%)', $statistics['max']]);
            fputcsv($file, ['Standar Deviasi (%)', $statistics['stdDev']]);
            fputcsv($file, ['Status', $statistics['isGoodAvalanche'] ? 'IDEAL (40-60%)' : 'DI LUAR RANGE IDEAL']);
            fputcsv($file, []);

            // Detail results
            fputcsv($file, ['DETAIL HASIL PER ITERASI']);
            fputcsv($file, ['Iterasi', 'Plaintext Modified', 'Hamming Distance', 'Total Bit', 'Avalanche Effect (%)']);
            
            foreach ($results as $result) {
                fputcsv($file, [
                    $result['iteration'],
                    $result['modifiedPlaintext'],
                    $result['hammingDistance'],
                    $result['totalBits'],
                    $result['avalanchePercentage'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function flipRandomBit(string $data, int $seed): string
    {
        $bytes = str_split($data);
        $charIndex = $seed % strlen($data);
        $bitIndex = $seed % 8;
        
        $bytes[$charIndex] = chr(ord($bytes[$charIndex]) ^ (1 << $bitIndex));
        
        return implode('', $bytes);
    }

    private function standardDeviation(array $values): float
    {
        $count = count($values);
        if ($count < 2) return 0;

        $mean = array_sum($values) / $count;
        $sumSquaredDiff = 0;

        foreach ($values as $value) {
            $sumSquaredDiff += pow($value - $mean, 2);
        }

        return sqrt($sumSquaredDiff / ($count - 1));
    }

    
    private function encryptWithIV(string $plaintext, string $key, string $iv): string
    {
        $ciphertext = openssl_encrypt(
            $plaintext,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($ciphertext === false) {
            throw new RuntimeException('Enkripsi gagal.');
        }

        return $ciphertext;
    }

    
    private function flipOneBit(string $data): string
    {
        $bytes = str_split($data);
        
        
        $bytes[0] = chr(ord($bytes[0]) ^ 0x01);
        
        return implode('', $bytes); 
    }

    
    private function toBinary(string $data): string
    {
        $binary = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $binary .= str_pad(decbin(ord($data[$i])), 8, '0', STR_PAD_LEFT);
        }
        return $binary;
    }

    
    private function hammingDistance(string $bin1, string $bin2): int
    {
        $distance = 0;
        $length = min(strlen($bin1), strlen($bin2));
        
        for ($i = 0; $i < $length; $i++) {
            if ($bin1[$i] !== $bin2[$i]) {
                $distance++;
            }
        }
        
        $distance += abs(strlen($bin1) - strlen($bin2));
        
        return $distance;
    }

    
    private function createBitComparison(string $bin1, string $bin2): array
    {
        $comparison = [];
        $length = max(strlen($bin1), strlen($bin2));
        
        for ($i = 0; $i < $length; $i++) {
            $bit1 = $bin1[$i] ?? '0';
            $bit2 = $bin2[$i] ?? '0';
            $comparison[] = [
                'bit1' => $bit1,
                'bit2' => $bit2,
                'changed' => $bit1 !== $bit2,
            ];
        }
        
        return $comparison;
    }

    
    private function getEncryptionKey(): string
    {
        $key = Config::get('app.key');

        if (str_starts_with($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        if (strlen($key) !== 32) {
            throw new RuntimeException('App Key harus 32 byte untuk AES-256.');
        }

        return $key;
    }
}
