@extends('layouts.layoutMaster')
@section('title', 'Index Status Dokumen')

@section('page-style')
<style>
    .table thead {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    .table tbody tr:hover { background-color: #fdfdfd; }
    .badge { font-size: 0.8rem; padding: .4em .7em; }
    .btn-sm { border-radius: 6px; padding: .35rem .7rem; }
    .table td, .table th { vertical-align: middle; white-space: nowrap; text-overflow: ellipsis; max-width: 200px; }
    .card { border-radius: 10px; border: 1px solid #eaeaea; }

    @media(max-width:768px){
        .table td, .table th { white-space: normal; }
        .card-header .d-flex { flex-direction: column; gap: .5rem; }
        .d-flex.gap-2 { flex-wrap: wrap; }
        .d-flex.justify-content-end { flex-wrap: wrap; gap: .5rem; }
    }
</style>
@endsection

@section('page-script')
<script>
    function goToPage(form) {
        let input = form.querySelector('input[name="page"]');
        let page = parseInt(input.value);
        let max = parseInt(input.max);
        if (page < 1) page = 1;
        if (page > max) page = max;
        input.value = page;
        return true;
    }
</script>
@endsection

@section('content')
@php
    $tipeDokumenMap = \Illuminate\Support\Facades\DB::table('referensi')
        ->where('jenis', 4)
        ->where('status', 1)
        ->pluck('deskripsi', 'id');
@endphp

<div class="container py-4">
    <div class="card">
        <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
            <h4 class="mb-2 mb-md-0 fw-bold">Status Verifikasi Dokumen</h4>
            <div class="d-flex gap-2 flex-wrap">
                <form action="{{ route('status-dokumen.index') }}" method="GET" class="d-flex flex-grow-1 flex-md-grow-0 gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari dokumen..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                </form>
            </div>
        </div>

        <div class="card-body p-2 p-md-3">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Judul</th>
                            <th>Tipe Dokumen</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $doc)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td style="max-width: 250px; white-space: normal; word-wrap: break-word;">
                                <a href="{{ route('status-dokumen.show', $doc->id) }}" class="text-decoration-none text-dark fw-semibold">
                                    {{ \Illuminate\Support\Str::limit($doc->judul, 255) }}
                                </a>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark" style="min-width:140px; display:inline-flex; align-items:center; justify-content:center; font-weight:500; border-radius:.395rem;">
                                    {{ $tipeDokumenMap[$doc->tipe_dokumen] ?? $doc->tipe_dokumen }}
                                </span>
                            </td>
                            <td class="text-center">{{ $doc->tahun }}</td>
                            <td class="text-center">
                                <span class="badge d-inline-flex align-items-center justify-content-center" style="min-width: 140px; @if ($doc->status_verifikasi == '2') background-color:#28a745; color:#fff; @elseif ($doc->status_verifikasi == '0') background-color:#dc3545; color:#fff; @elseif ($doc->status_verifikasi == '1') background-color:#ffc107; color:#212529; @elseif ($doc->status_verifikasi == '3') background-color:#fd7e14; color:#fff; @endif">
                                    @if ($doc->status_verifikasi == '2') <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                    @elseif ($doc->status_verifikasi == '0') <i class="bi bi-x-circle-fill me-1"></i> Batal
                                    @elseif ($doc->status_verifikasi == '1') <i class="bi bi-clock-fill me-1"></i> Menunggu..
                                    @elseif ($doc->status_verifikasi == '3') <i class="bi bi-exclamation-triangle-fill me-1"></i> Butuh Perbaikan
                                    @endif
                                </span>
                            </td>
                            <td style="max-width: 250px; white-space: normal; word-wrap: break-word;">
                                <span title="{{ $doc->catatan_admin }}">{{ $doc->catatan_admin ? \Illuminate\Support\Str::limit($doc->catatan_admin, 255, '...') : '-' }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-info btn-sm" title="Lihat"><i class="bi bi-eye"></i></a>
                                    @if ($doc->status_verifikasi != 2)
                                    <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada dokumen</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between flex-wrap align-items-center mt-3 gap-2">
                <div class="d-flex gap-1 align-items-center">
                    @if ($documents->onFirstPage())
                        <span class="btn btn-outline-secondary disabled">&laquo;</span>
                    @else
                        <a href="{{ $documents->previousPageUrl() }}" class="btn btn-outline-secondary">&laquo;</a>
                    @endif

                    <form method="GET" class="d-flex align-items-center" onsubmit="return goToPage(this)">
                        <input type="number" name="page" min="1" max="{{ $documents->lastPage() }}" value="{{ $documents->currentPage() }}" class="form-control form-control-sm text-center" style="width:60px;">
                    </form>

                    @if ($documents->hasMorePages())
                        <a href="{{ $documents->nextPageUrl() }}" class="btn btn-outline-secondary">&raquo;</a>
                    @else
                        <span class="btn btn-outline-secondary disabled">&raquo;</span>
                    @endif
                </div>
                <div><small>Page {{ $documents->currentPage() }} of {{ $documents->lastPage() }}</small></div>
            </div>
        </div>
    </div>
</div>
@endsection
