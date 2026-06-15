<div id="{{ $tabId }}">
    <div class="table-responsive mt-3">
        <table class="table table-hover align-middle">
            <thead class="table-light text-center">
                <tr>
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
                        <td style="max-width: 250px; word-wrap: break-word; white-space: normal;">
                            <a href="{{ route('documents.showVerifikasi', $doc->id) }}" class="text-decoration-none text-dark fw-semibold">
                                {{ \Illuminate\Support\Str::limit($doc->judul, 255) }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary text-white px-2 py-1" style="min-width:120px;">
                                {{ $tipeDokumenMap[$doc->tipe_dokumen] ?? $doc->tipe_dokumen }}
                            </span>
                        </td>
                        <td class="text-center">{{ $doc->tahun ?? '-' }}</td>
                        <td class="text-center">
                            @php
                                $statusColors = [
                                    '0' => 'bg-danger text-white',
                                    '1' => 'bg-warning text-dark',
                                    '2' => 'bg-success text-white',
                                    '3' => 'bg-orange text-white'
                                ];
                                $statusIcons = [
                                    '0' => 'bi-x-circle-fill',
                                    '1' => 'bi-clock-fill',
                                    '2' => 'bi-check-circle-fill',
                                    '3' => 'bi-exclamation-triangle-fill'
                                ];
                                $statusLabels = [
                                    '0' => 'Batal',
                                    '1' => 'Menunggu..',
                                    '2' => 'Terverifikasi',
                                    '3' => 'Butuh Perbaikan'
                                ];
                                $colorClass = $statusColors[$doc->status_verifikasi] ?? 'bg-secondary text-white';
                            @endphp
                            <span class="badge d-inline-flex align-items-center justify-content-center {{ $colorClass }}" style="min-width:140px;">
                                <i class="bi {{ $statusIcons[$doc->status_verifikasi] ?? 'bi-question-circle' }} me-1"></i>
                                {{ $statusLabels[$doc->status_verifikasi] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td style="max-width: 250px; word-wrap: break-word; white-space: normal;">
                            {{ $doc->catatan_admin ?? '-' }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <a href="{{ route('documents.showVerifikasi', $doc->id) }}" class="btn btn-info btn-sm" title="Lihat"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-warning btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                                <a href="{{ asset('storage/' . $doc->pdf_file) }}" class="btn btn-success btn-sm" download title="Unduh"><i class="bi bi-download"></i></a>
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin mau hapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="return_url" value="{{ url()->current() }}">
                                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada dokumen</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-3 d-flex justify-content-center">
            {{ $documents->appends(request()->except($pageName))->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Tambahan CSS untuk responsive --}}
<style>
    @media(max-width:768px){
        .table td, .table th {
            white-space: normal;
        }
        .badge {
            font-size: 0.75rem;
        }
        .btn-sm {
            padding: .25rem .5rem;
            font-size: 0.75rem;
        }
    }
</style>
