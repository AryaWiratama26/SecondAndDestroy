<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Exports\PelangganExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PelangganController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('jenis_barang', 'like', '%' . $search . '%');
            });
        }

        
        if ($request->filled('jenis_barang')) {
            $query->where('jenis_barang', $request->input('jenis_barang'));
        }

        
        if ($request->filled('min_belanja')) {
            $query->where('total_belanja', '>=', $request->input('min_belanja'));
        }
        if ($request->filled('max_belanja')) {
            $query->where('total_belanja', '<=', $request->input('max_belanja'));
        }

        $pelanggans = $query->latest()->paginate(10)->withQueryString();

        
        $jenisBarangList = Pelanggan::select('jenis_barang')->distinct()->orderBy('jenis_barang')->pluck('jenis_barang');

        return view('pelanggan.index', compact('pelanggans', 'jenisBarangList'));
    }

    
    public function create()
    {
        return view('pelanggan.create');
    }

    
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'wa' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'jenis_barang' => ['required', 'string', 'max:255'],
            'total_belanja' => ['required', 'numeric', 'min:0'],
        ]);

        Pelanggan::create($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    
    public function update(Request $request, Pelanggan $pelanggan)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'wa' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'jenis_barang' => ['required', 'string', 'max:255'],
            'total_belanja' => ['required', 'numeric', 'min:0'],
        ]);

        $pelanggan->update($data);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    
    public function exportPdf()
    {
        $pelanggans = Pelanggan::latest()->get();
        $totalBelanja = $pelanggans->sum('total_belanja');
        $totalPelanggan = $pelanggans->count();

        $pdf = Pdf::loadView('pelanggan.pdf', [
            'pelanggans' => $pelanggans,
            'totalBelanja' => $totalBelanja,
            'totalPelanggan' => $totalPelanggan,
        ]);

        return $pdf->download('dashboard-pelanggan-second-and-destroy-' . date('Y-m-d') . '.pdf');
    }

    
    public function exportExcel()
    {
        $filename = 'dashboard-pelanggan-second-and-destroy-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new PelangganExport, $filename);
    }
}


