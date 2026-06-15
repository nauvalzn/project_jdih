@php
    $configData = Helper::appClasses();
    use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Dashboard Analisis Dokumen')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/apex-charts/apex-charts.scss', 'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss', 'resources/assets/vendor/libs/swiper/swiper.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/cards-statistics.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/apex-charts/apexcharts.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/swiper/swiper.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/dashboards-analytics.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')

    {{-- 👋 Header Greeting --}}
    <div class="row g-6">
        <div class="col-12">
            <div class="card w-100">
                <div class="d-flex align-items-end row">
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                Haii <span class="fw-bold">{{ auth()->user()->name }}</span>
                                <span style="font-family:Roboto; font-weight:lighter;">[{{ Auth::user()->role }}]</span>
                            </h4>
                            <p class="mb-2">Selamat datang kembali di dashboard <i>Management!</i></p>
                            <a href="{{ route('pages-account-settings-account') }}" class="btn btn-primary">Lihat Profil</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                        <div class="card-body pb-0 px-0 pt-2">
                            <img src="{{ asset('assets/img/illustrations/illustration-john-' . $configData['theme'] . '.png') }}"
                                height="186" alt="View Profile"
                                data-app-light-img="illustrations/John-Gaya-removebg-preview.png"
                                data-app-dark-img="illustrations/John-Programmer-removebg-preview.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role !== 'operator')
        {{-- 📅 Filter Periode --}}
        <div class="card shadow-sm border-0 mb-4 mt-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 text-primary">Pilih Periode</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <label for="filter-month" class="form-label">Pilih Bulan</label>
                        <select id="filter-month" class="form-select">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                    {{ $month == $i ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter-year" class="form-label">Pilih Tahun</label>
                        <select id="filter-year" class="form-select">
                            @for ($i = date('Y'); $i >= 2000; $i--)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3 align-self-end">
                        <button id="apply-filter" class="btn btn-primary">Terapkan</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 📊 Top Small Cards --}}
        <div class="row g-3 mt-2">
            {{-- Total Akses --}}
            <div class="col-md-3 col-6">
                <div class="card p-2 rounded-3 shadow-sm" style="background-color: #f6f3ff; min-height: auto;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-bar-chart-line-fill fs-3 text-primary"></i>
                        <div class="ms-2">
                            <p class="mb-1 small text-muted">Total Akses</p>
                            <h5 class="mb-0 fw-bold">{{ $totalVisits }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dokumen Unik --}}
            <div class="col-md-3 col-6">
                <div class="card p-2 rounded-3 shadow-sm" style="background-color: #fff9ea; min-height: auto;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-text fs-3 text-warning"></i>
                            <div class="ms-2">
                                <p class="mb-1 small text-muted">Dokumen Unik</p>
                                <h5 class="mb-0 fw-bold">{{ $uniqueDocuments }}</h5>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-warning px-3 py-1" data-bs-toggle="modal"
                            data-bs-target="#modalDokumenUnik">
                            Lihat
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pengguna Unik --}}
            <div class="col-md-3 col-6">
                <div class="card p-2 rounded-3 shadow-sm" style="background-color: #f0fff6; min-height: auto;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-fill fs-3 text-success"></i>
                            <div class="ms-2">
                                <p class="mb-1 small text-muted">Pengguna Unik</p>
                                <h5 class="mb-0 fw-bold">{{ $uniqueUsers }}</h5>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-success px-3 py-1" data-bs-toggle="modal"
                            data-bs-target="#modalUserUnik">
                            Lihat
                        </button>
                    </div>
                </div>
            </div>

            {{-- Rata-rata Akses --}}
            <div class="col-md-3 col-6">
                <div class="card p-2 rounded-3 shadow-sm" style="background-color: #eef9ff; min-height: auto;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-graph-up-arrow fs-3 text-info"></i>
                        <div class="ms-2">
                            <p class="mb-1 small text-muted">Rata-rata Akses</p>
                            <h5 class="mb-0 fw-bold">
                                {{ $uniqueDocuments > 0 ? number_format($totalVisits / $uniqueDocuments, 2) : 0 }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalUserUnik" tabindex="-1" aria-labelledby="modalUserUnikLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="modalUserUnikLabel">Daftar Pengguna Unik</h5>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.analytics.users') }}" class="btn btn-sm btn-info">
                                Detail
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="modal-body">
                        <table class="table table-sm table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Alamat IP</th>
                                    <th>User Agent</th>
                                    <th>Total Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uniqueUserList as $i => $user)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $user->ip }}</td>
                                        <td class="text-truncate" style="max-width: 250px;">{{ $user->user_agent }}</td>
                                        <td>{{ $user->total_visits }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modalDokumenUnik" tabindex="-1" aria-labelledby="modalDokumenUnikLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-center">
                        <h5 class="modal-title" id="modalDokumenUnikLabel">Daftar Dokumen Unik</h5>

                        <div class="d-flex gap-2">
                            <a href="{{ route('dashboard.analytics.documents') }}" class="btn btn-sm btn-info">
                                Detail
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                    </div>

                    <div class="modal-body">
                        <table class="table table-sm table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul Dokumen</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Total Kunjungan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uniqueDocumentList as $i => $doc)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td class="text-truncate" style="max-width: 300px;">{{ $doc->judul }}</td>
                                        <td>{{ $doc->jenis_dokumen }}</td>
                                        <td>{{ $doc->total_visits }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        {{-- 📈 Statistik Akses Dokumen --}}
        <div class="row g-3 mt-3">
            <div class="col-12">
                <div class="card rounded-3 shadow-sm h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Statistik Akses Dokumen</h5>
                        <small>Total akses: {{ number_format($totalVisits) }}</small>
                    </div>
                    <div class="card-body">
                        <canvas id="shipmentStatisticsChart" height="350"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🗂 Status Dokumen + Chart --}}
        <div class="card rounded-3 shadow-sm mb-3 mt-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Status Dokumen</h6>
                <div class="row">
                    <div class="col-md-6 d-flex gap-3">
                        @php
                            $statusDokumen = [
                                [
                                    'label' => 'Terverifikasi',
                                    'value' => $dokumenTerverifikasi,
                                    'icon' => 'check-circle-fill',
                                    'bg' => '#f0fff6',
                                    'color' => 'success',
                                ],
                                [
                                    'label' => 'Belum Verifikasi',
                                    'value' => $dokumenBelumVerifikasi,
                                    'icon' => 'x-circle-fill',
                                    'bg' => '#fff4e6',
                                    'color' => 'warning',
                                ],
                                [
                                    'label' => 'Total Dokumen',
                                    'value' => $totaldokumen,
                                    'icon' => 'folder-fill',
                                    'bg' => '#f9f0ff',
                                    'color' => 'primary',
                                ],
                            ];
                        @endphp
                        @foreach ($statusDokumen as $status)
                            <div class="card p-3 rounded-3 shadow-sm text-center flex-fill"
                                style="background-color: {{ $status['bg'] }};">
                                <div class="d-flex flex-column justify-content-center align-items-center text-center"
                                    style="height: 100%;">
                                    <i class="bi bi-{{ $status['icon'] }} fs-3 text-{{ $status['color'] }}"></i>
                                    <p class="mb-1 small text-muted mt-2">{{ $status['label'] }}</p>
                                    <h5 class="mb-0 fw-bold">{{ $status['value'] }}</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-6">
                        <canvas id="statusChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🍩 Donut Chart + Top Dokumen --}}
        <div class="row g-3 mt-3">
            <div class="col-lg-6 col-md-12">
                <div class="card rounded-3 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Jenis Kategori Dokumen yang Paling Sering Dilihat</h6>
                        <canvas id="donutChartCenter" height="220"></canvas>
                        <div class="mt-3 small text-muted">
                            Legend: top jenis kategori dokumen akses berdasarkan jenis dokumen.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card rounded-3 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-2">(Top Dokumen)</h6>
                        <p class="text-muted small mb-3">{{ $topDocuments->count() }} dokumen teratas</p>
                        <div class="list-group list-group-flush">
                            @foreach ($topDocuments->take(10) as $doc)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="me-2">
                                        <div class="small text-muted">{{ $doc->jenis_dokumen }}</div>
                                        <div class="fw-semibold text-truncate">
                                            {{ \Illuminate\Support\Str::words($doc->judul, 6, '...') }}</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">{{ $doc->total_visits }}</div>
                                        <small class="text-muted">visits</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 🖥 Script Chart.js --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ----------------- Data -----------------
                const visitsLabels = @json($visitsLabels);
                const visitsData = @json($visitsData);
                const statusLabels = ['Tidak Berlaku', 'Berlaku', 'Berlaku Sebagian'];
                const statusData = [{{ $dokumenTidakBerlaku }}, {{ $dokumenBerlaku }},
                    {{ $dokumenBerlakuSebagian }}
                ];
                const donutLabels = @json($donutLabels);
                const donutData = @json($donutData);

                // ----------------- Shipment Chart -----------------
                new Chart(document.getElementById('shipmentStatisticsChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: visitsLabels,
                        datasets: [{
                                label: 'Jumlah Akses',
                                data: visitsData,
                                backgroundColor: 'rgba(114,102,246,0.6)',
                                borderColor: '#7266f6',
                                borderWidth: 1,
                                borderRadius: 6,
                                barPercentage: 0.4,
                                categoryPercentage: 0.5,
                                yAxisID: 'y'
                            },
                            {
                                type: 'line',
                                label: 'Akses per Hari',
                                data: visitsData,
                                borderColor: '#ffb020',
                                backgroundColor: 'rgba(255,176,32,0.15)',
                                borderWidth: 2,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#ffb020',
                                tension: 0.3,
                                yAxisID: 'y'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                // ----------------- Status Chart -----------------
                new Chart(document.getElementById('statusChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            label: '', // biar nggak muncul di tooltip legend
                            data: statusData,
                            backgroundColor: ['#f87171', '#34d399', '#facc15'],
                            borderColor: ['#dc2626', '#059669', '#ca8a04'],
                            borderWidth: 1.5,
                            borderRadius: 10,
                            barPercentage: 0.6,
                            categoryPercentage: 0.7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // hide legend kotak merah
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        return ctx.formattedValue; // cuma tampilkan angka, label kosong
                                    }
                                }
                            }
                        }
                    }
                });


                // ----------------- Donut Chart -----------------
                new Chart(document.getElementById('donutChartCenter').getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: donutLabels,
                        datasets: [{
                            data: donutData,
                            backgroundColor: ['#7b3ff3', '#ffd666', '#52c41a', '#40a9ff', '#fa8c16',
                                '#13c2c2'
                            ],
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    color: '#333'
                                }
                            }
                        }
                    }
                });

                // ----------------- Filter Bulan/Tahun -----------------
                document.getElementById('apply-filter').addEventListener('click', function() {
                    const month = document.getElementById('filter-month').value;
                    const year = document.getElementById('filter-year').value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('month', month);
                    url.searchParams.set('year', year);
                    url.searchParams.set('filter', 'month');
                    window.location.href = url;
                });

                // ----------------- Session Start URL -----------------
                if (!sessionStorage.getItem('startUrl')) sessionStorage.setItem('startUrl', window.location.href);
            });
        </script>

    @endif

@endsection
