@extends('layouts.layoutMaster')

@section('page-style')
    <style>
        .table thead {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .table tbody tr:hover {
            background-color: #fdfdfd;
        }

        .table td,
        .table th {
            vertical-align: middle;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 200px;
        }

        .card {
            border-radius: 10px;
            border: 1px solid #eaeaea;
        }
    </style>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold">Daftar Pengguna Unik</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-center">
                                <th class="text-dark fw-semibold">No</th>
                                <th class="text-dark fw-semibold">Alamat IP</th>
                                <th class="text-dark fw-semibold">User Agent</th>
                                <th class="text-dark fw-semibold">Total Kunjungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($uniqueUserList as $i => $user)
                                <tr>
                                    <td class="text-center text-dark fw-semibold">{{ $i + 1 }}</td>
                                    <td class="text-dark fw-semibold"><i>{{ $user->ip }}</i></td>
                                    <td class="text-truncate text-dark fw-semibold" style="max-width: 300px;">
                                        {{ $user->user_agent }}</td>
                                    <td class="text-center text-dark fw-semibold">{{ $user->total_visits }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted text-dark fw-semibold py-4 ">Tidak ada
                                        data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex mt-4 justify-content-end align-items-center gap-2">
                        {{-- Prev --}}
                        @if ($uniqueUserList->onFirstPage())
                            <span class="btn btn-outline-secondary rounded-circle disabled"
                                style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">&laquo;</span>
                        @else
                            <a href="{{ $uniqueUserList->previousPageUrl() }}"
                                class="btn btn-outline-secondary rounded-circle"
                                style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">&laquo;</a>
                        @endif

                        {{-- Page Input --}}
                        <form method="GET" class="d-flex align-items-center" onsubmit="return goToPage(this)">
                            <input type="number" name="page" min="1" max="{{ $uniqueUserList->lastPage() }}"
                                value="{{ $uniqueUserList->currentPage() }}"
                                class="form-control form-control-sm text-center" style="width: 60px;" placeholder="Page">
                            {{-- tombol Go dihapus, Enter aja --}}
                        </form>

                        {{-- Next --}}
                        @if ($uniqueUserList->hasMorePages())
                            <a href="{{ $uniqueUserList->nextPageUrl() }}" class="btn btn-outline-secondary rounded-circle"
                                style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">&raquo;</a>
                        @else
                            <span class="btn btn-outline-secondary rounded-circle disabled"
                                style="width:40px; height:40px; display:flex; align-items:center; justify-content:center;">&raquo;</span>
                        @endif

                        {{-- Info --}}
                        <span class="ms-2">Page {{ $uniqueUserList->currentPage() }} of
                            {{ $uniqueUserList->lastPage() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
