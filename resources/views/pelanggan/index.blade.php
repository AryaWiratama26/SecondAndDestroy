@extends('layouts.app')

@section('title', 'Dashboard Pelanggan')

@section('content')
    <div class="row g-3 align-items-start mb-4">
        <div class="col-12 col-md-8">
            <div class="mb-1 text-uppercase text-muted" style="font-size: 0.72rem; letter-spacing: 0.16em;">
                Snapshot • Pelanggan
            </div>
            <h3 class="mb-0 fw-semibold" style="font-size: 1.5rem;">Dashboard Pelanggan</h3>
            <div class="sd-subtle mt-1 d-none d-md-block">
                Toko pakaian "Second and Destroy" &mdash; fokus pada data, privasi, dan pengalaman belanja.
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="d-flex flex-column gap-2 align-items-stretch align-items-md-end">
                <div class="d-flex gap-2 w-100">
                    <a href="{{ route('pelanggan.export.excel') }}" class="btn btn-outline-success flex-fill" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                        Export Excel
                    </a>
                    <a href="{{ route('pelanggan.export.pdf') }}" class="btn btn-outline-secondary flex-fill" style="font-size: 0.875rem; padding: 0.5rem 1rem;" target="_blank">
                        Export PDF
                    </a>
                </div>
                <a href="{{ route('pelanggan.create') }}" class="btn sd-btn-primary w-100" style="padding: 0.625rem 1.5rem; font-size: 0.875rem;">
                    + Tambah Pelanggan
                </a>
            </div>
        </div>
    </div>

    <!-- Pencarian & Filter -->
    <div class="sd-card mb-3">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                <div>
                    <h6 class="mb-0 fw-semibold" style="font-size: 0.95rem;">Pencarian & Filter</h6>
                    <small class="text-muted">Temukan data pelanggan sesuai kriteria</small>
                </div>
                @if(request()->hasAny(['search', 'jenis_barang', 'min_belanja', 'max_belanja']))
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
                @endif
            </div>
            <form method="GET" action="{{ route('pelanggan.index') }}" class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <label class="form-label small text-muted mb-1">Pencarian</label>
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau jenis barang..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label small text-muted mb-1">Jenis Barang</label>
                    <select name="jenis_barang" class="form-select">
                        <option value="">Semua Jenis</option>
                        @if(isset($jenisBarangList))
                            @foreach($jenisBarangList as $jenis)
                                <option value="{{ $jenis }}" {{ request('jenis_barang') == $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small text-muted mb-1">Min. Belanja</label>
                    <input type="number" name="min_belanja" class="form-control" min="0" placeholder="0"
                           value="{{ request('min_belanja') }}">
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label small text-muted mb-1">Max. Belanja</label>
                    <input type="number" name="max_belanja" class="form-control" min="0" placeholder="Tidak terbatas"
                           value="{{ request('max_belanja') }}">
                </div>
                <div class="col-12 col-md-12 col-lg-1 d-flex justify-content-md-start justify-content-lg-end">
                    <button type="submit" class="btn sd-btn-primary w-100" style="min-height: 42px;">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Desktop Table View -->
    <div class="d-none d-md-block sd-card">
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

    <!-- Mobile Card View -->
    <div class="d-md-none">
        @forelse($pelanggans as $index => $pelanggan)
            <div class="sd-card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-semibold">{{ $pelanggan->nama }}</h6>
                            <span class="sd-badge-soft mb-2 d-inline-block">
                                {{ $pelanggan->wa }}
                            </span>
                        </div>
                        <span class="text-muted" style="font-size: 0.75rem;">
                            #{{ $pelanggans->firstItem() + $index }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Alamat:</small>
                        <div style="font-size: 0.85rem;">{{ $pelanggan->alamat }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Jenis Barang:</small>
                            <div style="font-size: 0.85rem;">{{ $pelanggan->jenis_barang }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block mb-1">Total Belanja:</small>
                            <div class="fw-semibold" style="font-size: 0.85rem;">
                                Rp {{ number_format($pelanggan->total_belanja, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn btn-sm btn-outline-secondary flex-fill">
                            Edit
                        </a>
                        <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" class="flex-fill"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="sd-card">
                <div class="card-body text-center text-muted py-4">
                    Belum ada data pelanggan. Mulai dengan menambahkan pelanggan pertama Anda.
                </div>
            </div>
        @endforelse

        @if($pelanggans->hasPages())
            <div class="mt-3">
                {{ $pelanggans->links() }}
            </div>
        @endif
    </div>
@endsection


