<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    
    public function index()
    {
        $pelanggans = Pelanggan::latest()->paginate(10);

        return view('pelanggan.index', compact('pelanggans'));
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
}


