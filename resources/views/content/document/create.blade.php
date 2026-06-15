@extends('layouts.layoutMaster')

@section('title', 'Tambah Dokumen')

<!-- Vendor Styles -->
@section('vendor-style')
    @vite(['resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/tagify/tagify.scss', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.scss', 'resources/assets/vendor/libs/typeahead-js/typeahead.scss'])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
    @vite(['resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/tagify/tagify.js', 'resources/assets/vendor/libs/bootstrap-select/bootstrap-select.js', 'resources/assets/vendor/libs/typeahead-js/typeahead.js', 'resources/assets/vendor/libs/bloodhound/bloodhound.js'])
@endsection

<!-- Page Scripts -->
@section('page-script')
    @vite(['resources/assets/js/forms-selects.js', 'resources/assets/js/forms-tagify.js', 'resources/assets/js/forms-typeahead.js'])
    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 untuk Keterangan
            $('.keterangan-select').select2({
                placeholder: 'Cari judul dokumen...',
                ajax: {
                    url: "{{ route('ajax.judul') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results.map(function(item) {
                                return {
                                    id: item.id,
                                    text: item.text
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 1
            });

            // Toggle keterangan sesuai status
            function toggleKeterangan() {
                let status = $('#status').val();
                if (status === '2') { // Berlaku
                    $('.keterangan-id-wrapper, .keterangan-wrapper').hide();
                    $('.keterangan-status-wrapper, .keterangan-wrapper').hide();
                    $('#keterangan_dokumen').prop('required', false);
                    $('#keterangan').prop('required', false);
                } else {
                    $('.keterangan-id-wrapper, .keterangan-wrapper').show();
                    $('.keterangan-status-wrapper, .keterangan-wrapper').show();
                    $('#keterangan_dokumen').prop('required', false);
                    $('#keterangan').prop('required', false);
                }
            }

            // Event saat status berubah
            $('#status').on('change', toggleKeterangan);

            // Jalankan sekali saat halaman load
            toggleKeterangan();
        });

        // Toggle Periode Berlaku jika jenis_dokumen = 5
        function togglePeriodeBerlaku() {
            let jenis = $('#jenis_dokumen').val();
            if (jenis === '5') { // Perizinan
                $('#periode_berlaku_wrapper').show();
                $('#periode_berlaku').prop('required', true);
            } else {
                $('#periode_berlaku_wrapper').hide();
                $('#periode_berlaku').prop('required', false);
            }
        }

        // Event saat jenis_dokumen berubah
        $('#jenis_dokumen').on('change', togglePeriodeBerlaku);

        // Jalankan sekali saat halaman load
        togglePeriodeBerlaku();
    </script>
@endsection

@section('content')
    <div class="container">
        <h2>Tambah Dokumen</h2>
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pdf_file">Upload File PDF</label>
                        <input type="file" name="pdf_file" id="pdf_file" class="form-control" accept=".pdf" required>
                    </div>

                    @if ($jenisReferensi && $tipeDokumen->count() > 0)
                        <div class="mb-3">
                            <label for="tipe_dokumen">{{ $jenisReferensi->deskripsi }}</label>
                            <select name="tipe_dokumen" id="tipe_dokumen" class="form-control">
                                @foreach ($tipeDokumen as $tipe)
                                    <option value="{{ $tipe->id }}"
                                        {{ old('tipe_dokumen') == $tipe->id ? 'selected' : '' }}>
                                        {{ $tipe->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($jenisReferensi1 && $bidangHukum->count() > 0)
                        <div class="mb-3">
                            <label for="bidang_hukum">{{ $jenisReferensi1->deskripsi }}</label>
                            <select name="bidang_hukum" id="bidang_hukum" class="form-control">
                                @foreach ($bidangHukum as $bidang)
                                    <option value="{{ $bidang->id }}"
                                        {{ old('bidang_hukum') == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($jenisReferensi2 && $jenisHukum->count() > 0)
                        <div class="mb-3">
                            <label for="jenis_hukum">{{ $jenisReferensi2->deskripsi }}</label>
                            <select name="jenis_hukum" id="jenis_hukum" class="form-control">
                                @foreach ($jenisHukum as $jenhum)
                                    <option value="{{ $jenhum->id }}"
                                        {{ old('jenis_hukum') == $jenhum->id ? 'selected' : '' }}>
                                        {{ $jenhum->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($jenisReferensi3 && $jenisDokumen->count() > 0)
                        <div class="mb-3">
                            <label for="jenis_dokumen">{{ $jenisReferensi3->deskripsi }}</label>
                            <select name="jenis_dokumen" id="jenis_dokumen" class="form-control">
                                @foreach ($jenisDokumen as $jendok)
                                    <option value="{{ $jendok->id }}"
                                        {{ old('jenis_dokumen') == $jendok->id ? 'selected' : '' }}>
                                        {{ $jendok->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="singkatan">Singkatan</label>
                        <input type="text" name="singkatan" id="singkatan" class="form-control"
                            value="{{ old('singkatan') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nomor">Nomor</label>
                        <input type="text" name="nomor" id="nomor" class="form-control" value="{{ old('nomor') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            @for ($i = date('Y'); $i >= 1945; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="judul">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control"
                            value="{{ old('judul') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tempat_penetapan">Tempat Penetapan</label>
                        <input type="text" name="tempat_penetapan" id="tempat_penetapan" class="form-control"
                            value="{{ old('tempat_penetapan') }}">
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_penetapan">Tanggal Penetapan</label>
                        <input type="date" name="tanggal_penetapan" id="tanggal_penetapan" class="form-control"
                            value="{{ old('tanggal_penetapan') }}">
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pengundangan">Tanggal Pengundangan</label>
                        <input type="date" name="tanggal_pengundangan" id="tanggal_pengundangan" class="form-control"
                            value="{{ old('tanggal_pengundangan') }}">
                    </div>

                    <div class="mb-3" id="periode_berlaku_wrapper" style="display: none;">
                        <label for="periode_berlaku">Periode Berlaku</label>
                        <select name="periode_berlaku" id="periode_berlaku" class="form-control">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('periode_berlaku') == $i ? 'selected' : '' }}>
                                    {{ $i }} Tahun
                                </option>
                            @endfor
                        </select>
                    </div>


                    <div class="mb-3">
                        <label for="sumber">Sumber</label>
                        <input type="text" name="sumber" id="sumber" class="form-control"
                            value="{{ old('sumber') }}">
                    </div>

                    <div class="mb-3">
                        <label for="subjek">Subjek</label>
                        <input type="text" name="subjek" id="subjek" class="form-control"
                            value="{{ old('subjek') }}">
                    </div>

                    <div class="mb-3">
                        <label for="bahasa">Bahasa</label>
                        <input type="text" name="bahasa" id="bahasa" class="form-control"
                            value="{{ old('bahasa') }}">
                    </div>

                    <div class="mb-3">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control"
                            value="{{ old('lokasi') }}">
                    </div>

                    <div class="mb-3">
                        <label for="urusan_pemerintahan">Urusan Pemerintahan</label>
                        <input type="text" name="urusan_pemerintahan" id="urusan_pemerintahan" class="form-control"
                            value="{{ old('urusan_pemerintahan') }}">
                    </div>

                    <div class="mb-3">
                        <label for="penandatanganan">Penandatanganan</label>
                        <input type="text" name="penandatanganan" id="penandatanganan" class="form-control"
                            value="{{ old('penandatanganan') }}">
                    </div>

                    <div class="mb-3">
                        <label for="pemrakarsa">Pemrakarsa</label>
                        <input type="text" name="pemrakarsa" id="pemrakarsa" class="form-control"
                            value="{{ old('pemrakarsa') }}">
                    </div>

                    @if ($jenisReferensi4 && $statusDokumen->count() > 0)
                        <div class="mb-3">
                            <label for="status">{{ $jenisReferensi4->deskripsi }}</label>
                            <select name="status" id="status" class="form-control">
                                @foreach ($statusDokumen as $status)
                                    <option value="{{ $status->id }}"
                                        {{ old('status') == $status->id ? 'selected' : '' }}>
                                        {{ $status->deskripsi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="keterangan-status-wrapper mb-3">
                        <label for="keterangan_dokumen">Keterangan Status</label>
                        <input type="text" name="keterangan_dokumen" id="keterangan_dokumen"
                            class="form-control"value="{{ old('keterangan_dokumen') }}">
                    </div>

                    <div class="keterangan-wrapper mb-3">
                        <label for="keterangan" class="form-label">Keterangan Dokumen</label>
                        <select name="keterangan_id" id="keterangan" class="form-control select2">
                            <option value="">-- Pilih Dokumen Rujukan --</option>
                            @foreach ($documents as $doc)
                                <option value="{{ $doc->id }}"
                                    {{ old('keterangan_id') == $doc->id ? 'selected' : '' }}>
                                    {{ $doc->judul }} ({{ $doc->tahun }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
@endsection
