<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PelangganController extends Controller
{
    /**
     * Tampilkan dashboard / daftar pelanggan.
     */
    public function index()
    {
        $pelanggans = Pelanggan::latest()->paginate(10);

        return view('pelanggan.index', compact('pelanggans'));
    }

    /**
     * Tampilkan form tambah pelanggan.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Simpan data pelanggan baru.
     */
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

    /**
     * Tampilkan form edit pelanggan.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update data pelanggan.
     */
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

    /**
     * Hapus data pelanggan.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Export dashboard pelanggan ke PDF.
     */
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
}


