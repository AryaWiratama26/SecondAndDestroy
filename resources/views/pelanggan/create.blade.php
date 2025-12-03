@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <div class="mb-3">
        <div class="text-uppercase text-muted" style="font-size: 0.72rem; letter-spacing: 0.16em;">
            Entry • Pelanggan
        </div>
        <h3 class="mb-0 fw-semibold">Tambah Pelanggan Baru</h3>
        <div class="sd-subtle mt-1">
            Simpan kontak pelanggan dan detail transaksinya secara aman.
        </div>
    </div>

    <div class="sd-card">
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger rounded-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelanggan.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <label class="form-label">No. WA</label>
                        <input type="text" name="wa" class="form-control" value="{{ old('wa') }}" required placeholder="contoh: 0812xxxxxxxx">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Jenis Barang</label>
                        <input type="text" name="jenis_barang" class="form-control" value="{{ old('jenis_barang') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total Belanja (Rp)</label>
                        <input type="number" name="total_belanja" class="form-control" value="{{ old('total_belanja') }}" min="0" step="1000" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Kembali</a>
                    <button type="submit" class="btn sd-btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection


