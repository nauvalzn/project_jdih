@extends('layouts.autoLayout') <!-- ganti blankLayout supaya konsisten layout lainnya -->
@section('title', 'Hasil Pencarian - RSKK')

@section('content')
<section class="section-py first-section-pt py-4 mt-5">
    <div class="container">

        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET"
            class="input-wrapper my-4 input-group input-group-merge mx-auto" style="max-width: 480px;">
            <span class="input-group-text"><i class="ri ri-search-line"></i></span>
            <input type="text" name="q" class="form-control" placeholder="Search dokumen..." value="{{ request('q') }}" required>
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Hasil Pencarian untuk: <strong>{{ $q }}</strong></h4>
            <button onclick="goBackToStart()" class="btn btn-outline-primary">⬅ Kembali</button>
        </div>

        @if ($results->isEmpty())
            <p class="text-muted mt-3">Nggak ada dokumen yang cocok dengan pencarian kamu.</p>
        @else
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body p-2 p-md-3">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:25%">Produk Hukum</th>
                                <th style="width:45%">Tentang</th>
                                <th style="width:15%" class="text-center">Status</th>
                                <th style="width:15%" class="text-center">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $dokumen)
                                <tr>
                                    <td>
                                        <strong>{{ $dokumen->jenis_dokumen_nama }}</strong><br>
                                        Nomor {{ $dokumen->nomor }} Tahun {{ $dokumen->tahun }}
                                    </td>
                                    <td>
                                        <a href="{{ route('documents.show', $dokumen->id) }}" class="fw-semibold text-primary text-decoration-none">
                                            {{ $dokumen->judul }}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        @if ($dokumen->status == '2')
                                            <span class="badge bg-success">✅ Berlaku</span>
                                        @elseif ($dokumen->status == '0')
                                            <span class="badge bg-danger">❌ Tidak Berlaku</span>
                                        @elseif ($dokumen->status == '1')
                                            <span class="badge bg-warning text-dark">⚠️ Berlaku Sebagian</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $dokumen->pdf_file) }}" class="btn btn-sm btn-info" target="_blank" download>Unduh</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between flex-wrap align-items-center mt-3 gap-2">
                    <div class="d-flex gap-1 align-items-center">
                        @if ($results->onFirstPage())
                            <span class="btn btn-outline-secondary disabled">&laquo;</span>
                        @else
                            <a href="{{ $results->previousPageUrl() }}" class="btn btn-outline-secondary">&laquo;</a>
                        @endif

                        <form method="GET" class="d-flex align-items-center" onsubmit="return goToPage(this)">
                            <input type="number" name="page" min="1" max="{{ $results->lastPage() }}" value="{{ $results->currentPage() }}" class="form-control form-control-sm text-center" style="width:60px;">
                        </form>

                        @if ($results->hasMorePages())
                            <a href="{{ $results->nextPageUrl() }}" class="btn btn-outline-secondary">&raquo;</a>
                        @else
                            <span class="btn btn-outline-secondary disabled">&raquo;</span>
                        @endif
                    </div>
                    <div><small>Page {{ $results->currentPage() }} of {{ $results->lastPage() }}</small></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@section('page-script')
<script>
    function goBackToStart() {
        const startUrl = sessionStorage.getItem('startUrl');
        if (startUrl) window.location.href = startUrl;
        else window.history.back();
    }
</script>
@endsection
