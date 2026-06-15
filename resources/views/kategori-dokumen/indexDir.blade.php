@extends('layouts.autoLayout')

@section('title', 'Keputusan Direktur - RSKK')

@section('content')
<section class="section-py first-section-pt py-4 mt-5">
    <div class="container">
        <div class="row g-6">

            <!-- Konten Utama -->
            <div class="col-lg-9 col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-3">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="breadcrumb-item active">Keputusan Direktur</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Dokumen Keputusan Direktur</h4>
                    <button onclick="window.history.back()" class="btn btn-outline-primary">⬅ Kembali</button>
                </div>

                <!-- Search -->
                <form action="{{ route('keputusan-direktur') }}" method="GET" class="mb-4">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ri ri-search-line"></i></span>
                        <input type="text" name="q" class="form-control" placeholder="Cari dokumen..." value="{{ request('q') }}">
                    </div>
                </form>

                <!-- Table -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%">Produk Hukum</th>
                                        <th style="width: 45%">Tentang</th>
                                        <th style="width: 15%">Status</th>
                                        <th style="width: 15%">Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($keputusanDirektur as $dokumen)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $dokumen->jenisDOkumenRef->deskripsi ?? 'Tidak Diketahui' }}</span><br>
                                            {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}
                                        </td>
                                        <td>{{ $dokumen->judul }}</td>
                                        <td>
                                            @if ($dokumen->status == '2')
                                                <span class="badge bg-success">✅ Berlaku</span>
                                            @elseif ($dokumen->status == '0')
                                                <span class="badge bg-danger">❌ Tidak Berlaku</span>
                                            @elseif ($dokumen->status == '1')
                                                <span class="badge bg-warning text-dark">⚠️ Berlaku Sebagian</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ route('documents.show', $dokumen->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                                                <a href="{{ asset('storage/' . $dokumen->pdf_file) }}" class="btn btn-sm btn-info" target="_blank" download>Unduh</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada dokumen keputusan Direktur yang tersedia.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2">
                            <div class="d-flex gap-2">
                                @if ($keputusanDirektur->onFirstPage())
                                    <span class="btn btn-outline-secondary disabled">&laquo;</span>
                                @else
                                    <a href="{{ $keputusanDirektur->previousPageUrl() }}" class="btn btn-outline-secondary">&laquo;</a>
                                @endif

                                <form method="GET" class="d-flex" onsubmit="return goToPage(this)">
                                    <input type="number" name="page" min="1" max="{{ $keputusanDirektur->lastPage() }}" value="{{ $keputusanDirektur->currentPage() }}" class="form-control form-control-sm text-center" style="width:60px;">
                                </form>

                                @if ($keputusanDirektur->hasMorePages())
                                    <a href="{{ $keputusanDirektur->nextPageUrl() }}" class="btn btn-outline-secondary">&raquo;</a>
                                @else
                                    <span class="btn btn-outline-secondary disabled">&raquo;</span>
                                @endif
                            </div>
                            <span>Page {{ $keputusanDirektur->currentPage() }} of {{ $keputusanDirektur->lastPage() }}</span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3 col-12">
                <div class="bg-lightest py-2 px-3 rounded mb-3">
                    <h5 class="mb-0">Kategori Dokumen</h5>
                </div>

                @foreach ([
                    'categoriesPeraturanGubernur'=>'peraturan-gubernur',
                    'categoriesKeputusanGubernur'=>'keputusan-gubernur',
                    'categories'=>'keputusan-direktur',
                    'categoriesPeraturanDirektur'=>'peraturan-direktur',
                    'categoriesPerizinan'=>'perizinan',
                    'categoriesSOP'=>'sop'
                ] as $varName => $route)
                    <ul class="list-unstyled mb-3">
                        @foreach ($$varName as $category)
                        <li class="mb-2">
                            <a href="{{ route($route) }}" class="d-flex justify-content-between align-items-center text-heading">
                                {{ $category->kategori }}
                                <span class="badge bg-label-primary">{{ $category->total }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endforeach
            </div>

        </div>
    </div>
</section>
@endsection
