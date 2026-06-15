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

        .badge {
            font-size: 0.8rem;
            padding: .4em .7em;
        }

        .btn-sm {
            border-radius: 6px;
            padding: .35rem .7rem;
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
    @php
        $tipeDokumenMap = \Illuminate\Support\Facades\DB::table('referensi')
            ->where('jenis', 4)
            ->where('status', 1)
            ->pluck('deskripsi', 'id');
    @endphp

    <div class="container py-4">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center">
                <ul class="nav nav-tabs card-header-tabs flex-grow-1" id="verifikasiTabs" role="tablist">
                    <li class="nav-item me-2" role="presentation">
                        <button class="nav-link active border-end" id="pending-tab" data-bs-toggle="tab"
                            data-bs-target="#pending" type="button" role="tab" aria-controls="pending"
                            aria-selected="true">
                            Dokumen yang harus diverifikasi
                        </button>
                    </li>
                    <li class="nav-item ms-2" role="presentation">
                        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                            type="button" role="tab" aria-controls="history" aria-selected="false">
                            History Verifikasi
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="verifikasiTabsContent">
                    {{-- TAB 1: Pending --}}
                    <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                        @include('content.document.partials.table-verifikasi', [
                            'documents' => $pendingDocuments,
                            'tabId' => 'pending',
                            'pageName' => 'pending_page',
                        ])
                    </div>

                    {{-- TAB 2: History --}}
                    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                        @include('content.document.partials.table-verifikasi', [
                            'documents' => $allDocuments,
                            'tabId' => 'history',
                            'pageName' => 'history_page',
                        ])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.closest('.pagination a')) {
                e.preventDefault();
                const link = e.target.closest('a').href;
                const activeTab = document.querySelector('.tab-pane.active').id;

                fetch(link)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newContent = doc.getElementById(activeTab).innerHTML;
                        document.getElementById(activeTab).innerHTML = newContent;
                    });
            }
        });
    </script>
@endsection
