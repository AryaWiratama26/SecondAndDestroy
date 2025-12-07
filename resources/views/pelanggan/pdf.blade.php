<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelanggan - Second and Destroy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            color: #202124;
            line-height: 1.5;
            padding: 24px;
            background: #fff;
        }
        .header {
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e8eaed;
        }
        .header h1 {
            font-size: 20px;
            font-weight: 400;
            color: #202124;
            margin-bottom: 4px;
        }
        .header .subtitle {
            font-size: 13px;
            color: #5f6368;
        }
        .meta {
            display: flex;
            gap: 24px;
            margin-bottom: 24px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        .meta-item {
            font-size: 11px;
        }
        .meta-item strong {
            color: #1a73e8;
            font-weight: 500;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        thead {
            background: #f8f9fa;
        }
        th {
            text-align: left;
            padding: 10px 12px;
            font-size: 11px;
            font-weight: 500;
            color: #5f6368;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e8eaed;
        }
        td {
            padding: 12px;
            font-size: 12px;
            color: #202124;
            border-bottom: 1px solid #f1f3f4;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            background: #e8f0fe;
            color: #1a73e8;
            border-radius: 12px;
            font-size: 11px;
        }
        .footer {
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #e8eaed;
            text-align: center;
            font-size: 10px;
            color: #5f6368;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #5f6368;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Pelanggan</h1>
        <div class="subtitle">Toko Pakaian "Second and Destroy"</div>
    </div>

    <div class="meta">
        <div class="meta-item">
            <strong>Total Pelanggan:</strong> {{ $totalPelanggan }}
        </div>
        <div class="meta-item">
            <strong>Total Belanja:</strong> Rp {{ number_format($totalBelanja, 0, ',', '.') }}
        </div>
        <div class="meta-item">
            <strong>Tanggal Cetak:</strong> {{ date('d F Y, H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">#</th>
                <th>Nama</th>
                <th>WA</th>
                <th>Alamat</th>
                <th>Jenis Barang</th>
                <th class="text-right">Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pelanggans as $index => $pelanggan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pelanggan->nama }}</td>
                    <td>
                        <span class="badge">{{ $pelanggan->wa }}</span>
                    </td>
                    <td style="max-width: 200px;">{{ $pelanggan->alamat }}</td>
                    <td>{{ $pelanggan->jenis_barang }}</td>
                    <td class="text-right">Rp {{ number_format($pelanggan->total_belanja, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Belum ada data pelanggan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis dari sistem Second and Destroy pada {{ date('d F Y, H:i') }}.
    </div>
</body>
</html>

