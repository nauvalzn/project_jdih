@extends('layouts.layoutMaster')
@section('title', 'Masa Berlaku Dokumen Perizinan')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/@form-validation/form-validation.scss'])
@endsection

@section('page-style')
    @vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection

@section('page-script')
    @vite(['resources/assets/js/pages-auth.js'])
@endsection
@php
    use Carbon\Carbon;
@endphp
@section('content')
    <div class="container mt-4">
        <h4 class="mb-4">Masa Berlaku Dokumen</h4>

        @if ($documents->count() > 0)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($documents as $doc)
                    @php
                        $tanggalPenetapan = Carbon::parse($doc->tanggal_penetapan)->startOfDay();

                        if ($doc->jenis_dokumen == 5 && $doc->periode_berlaku) {
                            // Expired = tanggal penetapan + periode_berlaku tahun
                            $expiredAt = $tanggalPenetapan->copy()->addYears($doc->periode_berlaku);
                        } else {
                            // Dokumen lain bisa tetap 6 bulan dari penetapan (opsional)
                            $expiredAt = $tanggalPenetapan->copy()->addMonths(6);
                        }

                        // Selisih hari dari sekarang
                        // Selisih hari dari sekarang, pastikan bulat
                        $daysLeft = (int) Carbon::now()->diffInDays($expiredAt, false);

                        if ($daysLeft < 0) {
                            $daysText = 'sudah expired ' . abs($daysLeft) . ' hari';
                            $badgeClass = 'bg-danger';
                        } elseif ($daysLeft > 0) {
                            $daysText = 'akan expired dalam ' . $daysLeft . ' hari';
                            $badgeClass = $daysLeft <= 30 ? 'bg-warning text-dark' : 'bg-success';
                        } else {
                            $daysText = 'expired hari ini';
                            $badgeClass = 'bg-warning text-dark';
                        }
                    @endphp



                    <div class="col d-flex">
                        <div class="card shadow-sm border-0 flex-fill">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $doc->judul ?? 'Tanpa Judul' }}</h5>
                                <p class="card-text mb-1">
                                    Expired: <strong>{{ $expiredAt->format('d M Y') }}</strong>
                                </p>
                                <div class="mt-auto">
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($daysText) }}</span>
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                        class="btn btn-sm btn-primary mt-2 w-100">Lihat Detail</a>
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">
                Belum ada dokumen yang diverifikasi.
            </div>
        @endif
    </div>
@endsection
