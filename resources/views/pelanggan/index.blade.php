@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="row g-3 align-items-center mb-3">
        <div class="col-md-8">
            <div class="mb-1 text-uppercase text-muted" style="font-size: 0.72rem; letter-spacing: 0.16em;">
                Snapshot • Pelanggan
            </div>
            <h3 class="mb-0 fw-semibold">Dashboard Pelanggan</h3>
            <div class="sd-subtle mt-1">
                Toko pakaian "Second and Destroy" &mdash; fokus pada data, privasi, dan pengalaman belanja.
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('pelanggan.export.pdf') }}" class="btn btn-outline-secondary me-2" target="_blank">
                📄 Cetak PDF
            </a>
            <a href="{{ route('pelanggan.create') }}" class="btn sd-btn-primary px-4">
                + Tambah Pelanggan
            </a>
        </div>
    </div>

    <div class="sd-card">
        <div class="card-body table-responsive">
            <table class="table sd-table align-middle mb-0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>WA</th>
                    <th>Alamat</th>
                    <th>Jenis Barang</th>
                    <th>Total Belanja</th>
                    <th class="text-end">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($pelanggans as $index => $pelanggan)
                    <tr>
                        <td>{{ $pelanggans->firstItem() + $index }}</td>
                        <td>{{ $pelanggan->nama }}</td>
                        <td>
                            <span class="sd-badge-soft">
                                {{ $pelanggan->wa }}
                            </span>
                        </td>
                        <td style="max-width: 260px;">
                            <span class="text-muted" style="font-size: 0.85rem;">
                                {{ $pelanggan->alamat }}
                            </span>
                        </td>
                        <td>{{ $pelanggan->jenis_barang }}</td>
                        <td class="fw-semibold">
                            Rp {{ number_format($pelanggan->total_belanja, 0, ',', '.') }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn btn-sm btn-outline-secondary me-1">
                                Edit
                            </a>
                            <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Belum ada data pelanggan. Mulai dengan menambahkan pelanggan pertama Anda.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($pelanggans->hasPages())
            <div class="card-footer bg-transparent border-0">
                {{ $pelanggans->links() }}
            </div>
        @endif
    </div>
@endsection


