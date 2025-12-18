@extends('layouts.app')

@section('title', 'Pengujian Avalanche Effect')

@section('content')
<div class="sd-card mb-4">
    <div class="sd-card-header p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1 fw-semibold">Pengujian Avalanche Effect</h5>
                <small class="sd-subtle">Uji kualitas enkripsi AES-256-CBC dengan analisis perubahan bit</small>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-sm sd-btn-ghost">← Kembali</a>
        </div>
    </div>
    
    <div class="p-4">
        {{-- Info Box --}}
        <div class="alert sd-alert mb-4">
            <strong>Apa itu Avalanche Effect?</strong><br>
            Properti kriptografi di mana perubahan 1 bit pada plaintext menghasilkan perubahan ~50% bit pada ciphertext. 
            Semakin mendekati 50%, semakin baik kualitas enkripsi.
        </div>

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs mb-4" id="testTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ !isset($showStatistics) ? 'active' : '' }}" id="single-tab" data-bs-toggle="tab" data-bs-target="#single" type="button" role="tab">
                    Pengujian Tunggal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ isset($showStatistics) ? 'active' : '' }}" id="statistic-tab" data-bs-toggle="tab" data-bs-target="#statistic" type="button" role="tab">
                    Pengujian Statistik (N Iterasi)
                </button>
            </li>
        </ul>

        <div class="tab-content" id="testTabsContent">
            {{-- Single Test Tab --}}
            <div class="tab-pane fade {{ !isset($showStatistics) ? 'show active' : '' }}" id="single" role="tabpanel">
                <form method="POST" action="{{ route('avalanche.test') }}" class="mb-4">
                    @csrf
                    <div class="mb-3">
                        <label for="plaintext" class="form-label fw-semibold">Masukkan Teks untuk Diuji</label>
                        <input type="text" 
                               class="form-control @error('plaintext') is-invalid @enderror" 
                               id="plaintext" 
                               name="plaintext" 
                               value="{{ old('plaintext', $plaintext ?? '') }}"
                               placeholder="Contoh: 08123456789 atau Jl. Sudirman No. 1"
                               required>
                        @error('plaintext')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Sistem akan membalik 1 bit dari teks ini dan membandingkan hasil enkripsi</small>
                    </div>
                    <button type="submit" class="btn sd-btn-primary">
                        Jalankan Pengujian
                    </button>
                </form>
            </div>

            {{-- Statistic Test Tab --}}
            <div class="tab-pane fade {{ isset($showStatistics) ? 'show active' : '' }}" id="statistic" role="tabpanel">
                <form method="POST" action="{{ route('avalanche.statistic') }}" class="mb-4">
                    @csrf
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="plaintext_stat" class="form-label fw-semibold">Masukkan Teks untuk Diuji</label>
                            <input type="text" 
                                   class="form-control @error('plaintext') is-invalid @enderror" 
                                   id="plaintext_stat" 
                                   name="plaintext" 
                                   value="{{ old('plaintext', $plaintext ?? '') }}"
                                   placeholder="Contoh: 08123456789 atau Jl. Sudirman No. 1"
                                   required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="iterations" class="form-label fw-semibold">Jumlah Iterasi (N)</label>
                            <input type="number" 
                                   class="form-control @error('iterations') is-invalid @enderror" 
                                   id="iterations" 
                                   name="iterations" 
                                   value="{{ old('iterations', $iterations ?? 10) }}"
                                   min="5" max="100"
                                   required>
                            @error('iterations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <small class="text-muted d-block mb-3">Sistem akan menjalankan N pengujian dengan bit yang berbeda pada setiap iterasi</small>
                    <button type="submit" class="btn sd-btn-primary">
                        Jalankan Pengujian Statistik
                    </button>
                </form>
            </div>
        </div>

        {{-- Hasil Pengujian Tunggal --}}
        @isset($avalanchePercentage)
        <hr class="my-4">
        
        {{-- Ringkasan Hasil --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Avalanche Effect</div>
                        <div class="display-5 fw-bold {{ $isGoodAvalanche ? 'text-success' : 'text-warning' }}">
                            {{ $avalanchePercentage }}%
                        </div>
                        <small class="{{ $isGoodAvalanche ? 'text-success' : 'text-warning' }}">
                            {{ $isGoodAvalanche ? '✓ Ideal (40-60%)' : '⚠ Di luar range ideal' }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Hamming Distance</div>
                        <div class="display-5 fw-bold text-primary">{{ $hammingDistance }}</div>
                        <small class="text-muted">bit yang berubah</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Total Bit Ciphertext</div>
                        <div class="display-5 fw-bold text-secondary">{{ $totalBits }}</div>
                        <small class="text-muted">bit</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Perbandingan --}}
        <div class="sd-card mb-4">
            <div class="sd-card-header p-3">
                <h6 class="mb-0 fw-semibold">Perbandingan Plaintext</h6>
            </div>
            <div class="p-3">
                <table class="table table-sm sd-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Jenis</th>
                            <th>Teks</th>
                            <th>Binary (8 bit pertama)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="sd-badge-soft">Original</span></td>
                            <td><code>{{ $plaintext }}</code></td>
                            <td><code>{{ substr($plaintextBinary, 0, 32) }}...</code></td>
                        </tr>
                        <tr>
                            <td><span class="sd-badge-soft bg-warning text-dark">Modified</span></td>
                            <td><code>{{ $modifiedPlaintext }}</code></td>
                            <td><code>{{ substr($modifiedPlaintextBinary, 0, 32) }}...</code></td>
                        </tr>
                    </tbody>
                </table>
                <small class="text-muted mt-2 d-block">
                    <strong>Catatan:</strong> 1 bit dari karakter pertama telah dibalik (XOR dengan 0x01)
                </small>
            </div>
        </div>

        <div class="sd-card mb-4">
            <div class="sd-card-header p-3">
                <h6 class="mb-0 fw-semibold">Perbandingan Ciphertext (Hex)</h6>
            </div>
            <div class="p-3">
                <table class="table table-sm sd-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 120px;">Jenis</th>
                            <th>Ciphertext (Hex)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="sd-badge-soft">Original</span></td>
                            <td><code style="word-break: break-all; font-size: 0.75rem;">{{ $ciphertext1 }}</code></td>
                        </tr>
                        <tr>
                            <td><span class="sd-badge-soft bg-warning text-dark">Modified</span></td>
                            <td><code style="word-break: break-all; font-size: 0.75rem;">{{ $ciphertext2 }}</code></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Visualisasi Bit --}}
        <div class="sd-card">
            <div class="sd-card-header p-3">
                <h6 class="mb-0 fw-semibold">Visualisasi Perubahan Bit Ciphertext</h6>
            </div>
            <div class="p-3">
                <div class="d-flex flex-wrap gap-1 mb-3" style="font-family: monospace; font-size: 0.65rem;">
                    @foreach(array_slice($bitComparison, 0, 256) as $index => $bit)
                        <span class="d-inline-block text-center" 
                              style="width: 14px; height: 18px; line-height: 18px; border-radius: 2px; {{ $bit['changed'] ? 'background-color: #ef4444; color: white;' : 'background-color: #e5e7eb; color: #6b7280;' }}"
                              title="Bit {{ $index + 1 }}: {{ $bit['bit1'] }} → {{ $bit['bit2'] }}">
                            {{ $bit['bit1'] }}
                        </span>
                    @endforeach
                    @if(count($bitComparison) > 256)
                        <span class="text-muted">... (+{{ count($bitComparison) - 256 }} bit lainnya)</span>
                    @endif
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 14px; height: 14px; background-color: #e5e7eb; border-radius: 2px; display: inline-block;"></span>
                        <small class="text-muted">Bit tidak berubah</small>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <span style="width: 14px; height: 14px; background-color: #ef4444; border-radius: 2px; display: inline-block;"></span>
                        <small class="text-muted">Bit berubah</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Interpretasi --}}
        <div class="alert mt-4 {{ $isGoodAvalanche ? 'alert-success' : 'alert-warning' }}">
            <strong>Interpretasi Hasil:</strong><br>
            @if($isGoodAvalanche)
                Enkripsi AES-256-CBC menunjukkan <strong>Avalanche Effect yang baik</strong>. 
                Perubahan 1 bit pada plaintext menghasilkan perubahan {{ $avalanchePercentage }}% bit pada ciphertext, 
                yang berada dalam range ideal (40-60%). Ini menandakan bahwa algoritma enkripsi bekerja dengan baik 
                dalam menyebarkan perubahan input ke seluruh output.
            @else
                Persentase Avalanche Effect ({{ $avalanchePercentage }}%) berada di luar range ideal (40-60%). 
                Ini mungkin terjadi karena:
                <ul class="mb-0 mt-2">
                    <li>Plaintext terlalu pendek</li>
                    <li>Variasi statistik normal pada pengujian tunggal</li>
                </ul>
                Coba uji beberapa kali dengan teks berbeda untuk hasil yang lebih akurat.
            @endif
        </div>
        @endisset

        {{-- Hasil Pengujian Statistik --}}
        @isset($showStatistics)
        <hr class="my-4">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-semibold mb-0">Hasil Pengujian Statistik ({{ $statistics['count'] }} Iterasi)</h5>
            <a href="{{ route('avalanche.export') }}" class="btn btn-sm btn-outline-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-spreadsheet me-1" viewBox="0 0 16 16">
                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5zM3 12v-2h2v2zm0 1h2v2H4a1 1 0 0 1-1-1zm3 2v-2h3v2zm4 0v-2h3v1a1 1 0 0 1-1 1zm3-3h-3v-2h3zm-7 0v-2h3v2z"/>
                </svg>
                Export Excel
            </a>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Rata-rata</div>
                        <div class="display-6 fw-bold {{ $statistics['isGoodAvalanche'] ? 'text-success' : 'text-warning' }}">
                            {{ $statistics['average'] }}%
                        </div>
                        <small class="{{ $statistics['isGoodAvalanche'] ? 'text-success' : 'text-warning' }}">
                            {{ $statistics['isGoodAvalanche'] ? '✓ Ideal' : '⚠ Di luar range' }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Minimum</div>
                        <div class="display-6 fw-bold text-info">{{ $statistics['min'] }}%</div>
                        <small class="text-muted">nilai terendah</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Maximum</div>
                        <div class="display-6 fw-bold text-primary">{{ $statistics['max'] }}%</div>
                        <small class="text-muted">nilai tertinggi</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="sd-card h-100">
                    <div class="p-3 text-center">
                        <div class="sd-subtle mb-1">Std. Deviasi</div>
                        <div class="display-6 fw-bold text-secondary">{{ $statistics['stdDev'] }}%</div>
                        <small class="text-muted">variasi data</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Grafik Visualisasi --}}
        <div class="sd-card mb-4">
            <div class="sd-card-header p-3">
                <h6 class="mb-0 fw-semibold">Grafik Avalanche Effect per Iterasi</h6>
            </div>
            <div class="p-3">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <canvas id="barChart" height="250"></canvas>
                    </div>
                    <div class="col-md-6 mb-3">
                        <canvas id="lineChart" height="250"></canvas>
                    </div>
                </div>
                <div class="d-flex justify-content-center gap-4 mt-2">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 20px; height: 12px; background: linear-gradient(90deg, #3b82f6, #60a5fa); border-radius: 2px;"></span>
                        <small class="text-muted">Avalanche Effect (%)</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 20px; height: 2px; background-color: #22c55e;"></span>
                        <small class="text-muted">Nilai Ideal (50%)</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 20px; height: 2px; background-color: #ef4444; border-style: dashed;"></span>
                        <small class="text-muted">Rata-rata ({{ $statistics['average'] }}%)</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Detail Hasil --}}
        <div class="sd-card mb-4">
            <div class="sd-card-header p-3">
                <h6 class="mb-0 fw-semibold">Detail Hasil Setiap Iterasi</h6>
            </div>
            <div class="p-3">
                <div class="table-responsive">
                    <table class="table table-sm sd-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Iterasi</th>
                                <th>Plaintext Modified</th>
                                <th style="width: 120px;">Hamming Dist.</th>
                                <th style="width: 100px;">Total Bit</th>
                                <th style="width: 140px;">Avalanche (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $result)
                            <tr>
                                <td><span class="sd-badge-soft">{{ $result['iteration'] }}</span></td>
                                <td><code>{{ $result['modifiedPlaintext'] }}</code></td>
                                <td>{{ $result['hammingDistance'] }}</td>
                                <td>{{ $result['totalBits'] }}</td>
                                <td>
                                    <span class="fw-semibold {{ $result['avalanchePercentage'] >= 40 && $result['avalanchePercentage'] <= 60 ? 'text-success' : 'text-warning' }}">
                                        {{ $result['avalanchePercentage'] }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Interpretasi Statistik --}}
        <div class="alert mt-4 {{ $statistics['isGoodAvalanche'] ? 'alert-success' : 'alert-warning' }}">
            <strong>Interpretasi Statistik:</strong><br>
            @if($statistics['isGoodAvalanche'])
                Dari {{ $statistics['count'] }} pengujian, rata-rata Avalanche Effect adalah <strong>{{ $statistics['average'] }}%</strong> 
                dengan standar deviasi {{ $statistics['stdDev'] }}%. Nilai ini berada dalam range ideal (40-60%), 
                menunjukkan bahwa enkripsi AES-256-CBC memiliki <strong>Avalanche Effect yang konsisten dan baik</strong>.
            @else
                Rata-rata Avalanche Effect ({{ $statistics['average'] }}%) berada di luar range ideal (40-60%). 
                Standar deviasi {{ $statistics['stdDev'] }}% menunjukkan tingkat variasi pada hasil.
            @endif
            <br><br>
            <small class="text-muted">
                <strong>Catatan untuk jurnal:</strong> Range 40-60% dianggap ideal karena Avalanche Effect yang sempurna 
                adalah 50% (setengah bit berubah). Standar deviasi rendah menunjukkan konsistensi algoritma.
            </small>
        </div>
        @endisset
    </div>
</div>
@endsection

@isset($showStatistics)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const labels = {!! json_encode(array_column($results, 'iteration')) !!};
    const data = {!! json_encode(array_column($results, 'avalanchePercentage')) !!};
    const average = {{ $statistics['average'] }};
    const isDarkMode = document.body.classList.contains('dark-mode');
    
    // Improved dark mode colors
    const gridColor = isDarkMode ? 'rgba(148, 163, 184, 0.2)' : 'rgba(0, 0, 0, 0.1)';
    const textColor = isDarkMode ? '#f1f5f9' : '#374151';
    const canvasBackground = isDarkMode ? '#1e293b' : '#ffffff';
    
    // Bar colors with better contrast in dark mode
    const greenColor = isDarkMode ? 'rgba(74, 222, 128, 0.85)' : 'rgba(34, 197, 94, 0.7)';
    const greenBorder = isDarkMode ? 'rgb(74, 222, 128)' : 'rgb(34, 197, 94)';
    const yellowColor = isDarkMode ? 'rgba(250, 204, 21, 0.85)' : 'rgba(234, 179, 8, 0.7)';
    const yellowBorder = isDarkMode ? 'rgb(250, 204, 21)' : 'rgb(234, 179, 8)';

    // Add background to canvas for better visibility
    function addCanvasBackground(chart, color) {
        const ctx = chart.ctx;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: labels.map(i => 'Iterasi ' + i),
            datasets: [{
                label: 'Avalanche Effect (%)',
                data: data,
                backgroundColor: data.map(v => v >= 40 && v <= 60 ? greenColor : yellowColor),
                borderColor: data.map(v => v >= 40 && v <= 60 ? greenBorder : yellowBorder),
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                title: {
                    display: true,
                    text: 'Bar Chart - Avalanche Effect',
                    color: textColor,
                    font: { size: 15, weight: '600' },
                    padding: 15
                },
                annotation: {
                    annotations: {
                        idealLine: {
                            type: 'line',
                            yMin: 50,
                            yMax: 50,
                            borderColor: isDarkMode ? '#4ade80' : '#22c55e',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            label: {
                                display: true,
                                content: 'Ideal (50%)',
                                position: 'end',
                                backgroundColor: isDarkMode ? '#4ade80' : '#22c55e',
                                color: isDarkMode ? '#0f172a' : 'white',
                                font: { size: 10, weight: '600' }
                            }
                        },
                        avgLine: {
                            type: 'line',
                            yMin: average,
                            yMax: average,
                            borderColor: isDarkMode ? '#fb923c' : '#ef4444',
                            borderWidth: 2,
                            label: {
                                display: true,
                                content: 'Avg: ' + average + '%',
                                position: 'start',
                                backgroundColor: isDarkMode ? '#fb923c' : '#ef4444',
                                color: isDarkMode ? '#0f172a' : 'white',
                                font: { size: 10, weight: '600' }
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { 
                        color: gridColor,
                        lineWidth: 1
                    },
                    ticks: { 
                        color: textColor,
                        font: { size: 11 }
                    },
                    border: {
                        color: isDarkMode ? '#475569' : '#e5e7eb'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: textColor,
                        maxRotation: 45,
                        font: { size: 10 }
                    },
                    border: {
                        color: isDarkMode ? '#475569' : '#e5e7eb'
                    }
                }
            }
        },
        plugins: [{
            beforeDraw: (chart) => addCanvasBackground(chart, canvasBackground)
        }]
    });

    // Line Chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: labels.map(i => 'Iterasi ' + i),
            datasets: [{
                label: 'Avalanche Effect (%)',
                data: data,
                borderColor: isDarkMode ? '#60a5fa' : '#3b82f6',
                backgroundColor: isDarkMode ? 'rgba(96, 165, 250, 0.15)' : 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: data.map(v => v >= 40 && v <= 60 ? 
                    (isDarkMode ? '#4ade80' : '#22c55e') : 
                    (isDarkMode ? '#fbbf24' : '#eab308')),
                pointBorderColor: data.map(v => v >= 40 && v <= 60 ? 
                    (isDarkMode ? '#4ade80' : '#22c55e') : 
                    (isDarkMode ? '#fbbf24' : '#eab308')),
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBorderWidth: 2,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                title: {
                    display: true,
                    text: 'Line Chart - Trend Avalanche Effect',
                    color: textColor,
                    font: { size: 15, weight: '600' },
                    padding: 15
                },
                annotation: {
                    annotations: {
                        idealLine: {
                            type: 'line',
                            yMin: 50,
                            yMax: 50,
                            borderColor: isDarkMode ? '#4ade80' : '#22c55e',
                            borderWidth: 2,
                            borderDash: [5, 5],
                        },
                        avgLine: {
                            type: 'line',
                            yMin: average,
                            yMax: average,
                            borderColor: isDarkMode ? '#fb923c' : '#ef4444',
                            borderWidth: 2,
                        }
                    }
                },
                tooltip: {
                    backgroundColor: isDarkMode ? '#1e293b' : 'rgba(0, 0, 0, 0.8)',
                    titleColor: isDarkMode ? '#f1f5f9' : '#fff',
                    bodyColor: isDarkMode ? '#e2e8f0' : '#fff',
                    borderColor: isDarkMode ? '#475569' : 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { 
                        color: gridColor,
                        lineWidth: 1
                    },
                    ticks: { 
                        color: textColor,
                        font: { size: 11 }
                    },
                    border: {
                        color: isDarkMode ? '#475569' : '#e5e7eb'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { 
                        color: textColor,
                        maxRotation: 45,
                        font: { size: 10 }
                    },
                    border: {
                        color: isDarkMode ? '#475569' : '#e5e7eb'
                    }
                }
            }
        },
        plugins: [{
            beforeDraw: (chart) => addCanvasBackground(chart, canvasBackground)
        }]
    });

    // Update charts when dark mode changes
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            setTimeout(() => {
                location.reload();
            }, 100);
        });
    }
});
</script>
@endpush
@endisset

