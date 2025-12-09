<?php

namespace App\Traits;

use Illuminate\Support\Facades\Config;
use RuntimeException;

trait ManualEncryption
{
    
    

    public function encryptData(string $plaintext): string
    {
        $key = $this->getEncryptionKey();
        
        // 1. Generate IV (16 bytes)
        $iv = random_bytes(16);

        // 2. Encrypt
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

        // 3. Generate MAC (HMAC-SHA256)
        $mac = hash_hmac('sha256', $iv . $ciphertext, $key, true);

        // 4. Gabungkan: IV (16) + MAC (32) + Ciphertext
        
        return base64_encode($iv . $mac . $ciphertext);
    }

    /**
     * Dekripsi data
     */
    public function decryptData(?string $encryptedData): ?string
    {
        if (empty($encryptedData)) {
            return null;
        }

        $key = $this->getEncryptionKey();
        $data = base64_decode($encryptedData);

        // Validasi panjang minimal (IV=16 + MAC=32 = 48 bytes)
        if (strlen($data) < 48) {
            return null; 
        }

        // 1. Ekstrak komponen
        $iv = substr($data, 0, 16);
        $mac = substr($data, 16, 32);
        $ciphertext = substr($data, 48);

        // 2. Verifikasi MAC (Cek apakah data dimodifikasi?)
        $newMac = hash_hmac('sha256', $iv . $ciphertext, $key, true);
        
        // hash_equals digunakan untuk mencegah timing attack
        if (!hash_equals($mac, $newMac)) {
            throw new RuntimeException('Data korup atau telah dimodifikasi (MAC mismatch).');
        }

        // 3. Decrypt
        $plaintext = openssl_decrypt(
            $ciphertext,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $plaintext !== false ? $plaintext : null;
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
