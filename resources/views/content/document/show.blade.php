@extends('layouts.autoLayout')
@section('page-content')
    <div class="container">
        <div class="row">
            <!-- Kolom Kiri: Preview File -->
            <div class="col-md-8">
                <div class="mb-3">
                    @php
                        $filePath = url('storage/' . $document->pdf_file);
                        $extension = \Illuminate\Support\Str::lower(pathinfo($document->pdf_file, PATHINFO_EXTENSION));
                        $isPdf = $extension === 'pdf';
                        $isOffice = in_array($extension, ['docx', 'xlsx', 'pptx']);
                    @endphp

                    @if ($isPdf)
                        {{-- Preview PDF --}}
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div>
                                <button id="prev-page" class="btn btn-secondary btn-sm">⬅ Sebelumnya</button>
                                <button id="next-page" class="btn btn-secondary btn-sm">Berikutnya ➡</button>
                            </div>

                            <div>
                                <input type="number" id="page-num" class="form-control d-inline-block" value="1"
                                    min="1" style="width:80px;">
                                / <span id="page-count">0</span>
                            </div>

                            <div class="me-2">
                                <button id="zoom-in" class="btn btn-info btn-sm">Zoom +</button>
                                <button id="zoom-out" class="btn btn-info btn-sm">Zoom -</button>
                                <a href="{{ $filePath }}" class="btn btn-success btn-sm" download>Unduh</a>
                                <button onclick="window.history.back()" class="btn btn-outline-info btn-sm">⬅
                                    Kembali</button>
                            </div>
                        </div>

                        <div id="pdf-container"
                            style="border:1px solid #ccc; height:800px; overflow:auto; text-align:center;">
                            <canvas id="pdf-render" style="max-width:100%; height:auto;"></canvas>
                        </div>
                    @elseif($isOffice)
                        {{-- Preview DOCX, XLSX, PPTX pakai OnlyOffice iframe --}}
                        <iframe
                            src="http://172.20.0.59:8080/web-apps/apps/documenteditor/main/index.html?fileUrl={{ urlencode($filePath) }}"
                            width="100%" height="600" frameborder="0">
                        </iframe>

                        <div class="mt-2">
                            <a href="{{ $filePath }}" class="btn btn-success btn-sm" download>Unduh</a>
                        </div>
                    @else
                        <p>Format file tidak didukung untuk preview.</p>
                    @endif

                </div>
            </div>

            <!-- Kolom Kanan: Informasi Dokumen -->
            <div class="col-md-4">
                <h5>Informasi Dokumen</h5>
                <ul class="list-group">
                    @php
                        $fields = [
                            'Tipe Dokumen' => $tipeDokumenMap[$document->tipe_dokumen] ?? null,
                            'Jenis Dokumen' => $jenisDokumenMap[$document->jenis_dokumen] ?? null,
                            'Bidang Hukum' => $bidangHukumMap[$document->bidang_hukum] ?? null,
                            'Jenis Hukum' => $jenisHukumMap[$document->jenis_hukum] ?? null,
                            'Tahun' => $document->tahun ?? null,
                            'Judul' => $document->judul ?? null,
                            'Tempat Penetapan' => $document->tempat_penetapan ?? null,
                            'Tanggal Penetapan' => $document->tanggal_penetapan
                                ? \Carbon\Carbon::parse($document->tanggal_penetapan)->format('d-m-Y')
                                : null,
                            'Tanggal Pengundangan' => $document->tanggal_pengundangan
                                ? \Carbon\Carbon::parse($document->tanggal_pengundangan)->format('d-m-Y')
                                : null,
                            'Periode Berlaku' =>
                                $document->jenis_dokumen == 5 ? $document->periode_berlaku . ' Tahun' : null,
                            'Sumber' => $document->sumber ?? null,
                            'Subjek' => $document->subjek ?? null,
                            'Bahasa' => $document->bahasa ?? null,
                            'Lokasi' => $document->lokasi ?? null,
                            'Urusan Pemerintahan' => $document->urusan_pemerintahan ?? null,
                            'Penandatanganan' => $document->penandatanganan ?? null,
                            'Pemrakarsa' => $document->pemrakarsa ?? null,
                            'Status' => $statusDokumenMap[$document->status] ?? null,
                            'Keterangan' => $document->keterangan_dokumen ?? null,
                            'Dokumen Perubahan' => $document->keteranganDoc ?? null,
                            'Tanggal Perubahan' => $document->tanggal_perubahan
                                ? \Carbon\Carbon::parse($document->tanggal_perubahan)->format('d-m-Y')
                                : null,
                        ];
                    @endphp
                    @foreach ($fields as $label => $value)
                        @php
                            // Khusus Dokumen Perubahan, cek file-nya juga
                            $showItem = false;
                            if ($label === 'Dokumen Perubahan') {
                                $showItem = $value && $value->pdf_file;
                            } else {
                                $showItem = $value; // field lain tetap cek value
                            }
                        @endphp

                        @if ($showItem)
                            <li class="list-group-item">
                                <strong>{{ $label }}:</strong>
                                @if ($label === 'Dokumen Perubahan')
                                    <a href="{{ asset('storage/' . $value->pdf_file) }}" target="_blank">
                                        {{ $value->judul }}
                                    </a>
                                @else
                                    {{ $value }}
                                @endif
                            </li>
                        @endif
                    @endforeach

                </ul>
            </div>
        </div>
    </div>

    @if ($isPdf)
        <!-- PDF.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
        <script>
            const url = "{{ $filePath }}";

            let pdfDoc = null,
                pageNum = 1,
                pageIsRendering = false,
                pageNumIsPending = null,
                scale = 1.5;

            const canvas = document.getElementById('pdf-render'),
                ctx = canvas.getContext('2d'),
                container = document.getElementById('pdf-container');

            const renderPage = num => {
                pageIsRendering = true;
                pdfDoc.getPage(num).then(page => {
                    const viewport = page.getViewport({
                        scale: scale
                    });
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    const renderCtx = {
                        canvasContext: ctx,
                        viewport
                    };
                    page.render(renderCtx).promise.then(() => {
                        pageIsRendering = false;
                        if (pageNumIsPending !== null) {
                            renderPage(pageNumIsPending);
                            pageNumIsPending = null;
                        }
                    });

                    document.getElementById('page-num').value = num;
                    document.getElementById('page-count').textContent = pdfDoc.numPages;
                });
            };

            const queueRenderPage = num => pageIsRendering ? pageNumIsPending = num : renderPage(num);

            document.getElementById('prev-page').addEventListener('click', () => {
                if (pageNum <= 1) return;
                pageNum--;
                queueRenderPage(pageNum);
            });

            document.getElementById('next-page').addEventListener('click', () => {
                if (pageNum >= pdfDoc.numPages) return;
                pageNum++;
                queueRenderPage(pageNum);
            });

            document.getElementById('page-num').addEventListener('change', (e) => {
                let desiredPage = parseInt(e.target.value);
                if (desiredPage >= 1 && desiredPage <= pdfDoc.numPages) {
                    pageNum = desiredPage;
                    queueRenderPage(pageNum);
                }
            });

            document.getElementById('zoom-in').addEventListener('click', () => {
                scale += 0.2;
                queueRenderPage(pageNum);
            });

            document.getElementById('zoom-out').addEventListener('click', () => {
                if (scale > 0.4) scale -= 0.2;
                queueRenderPage(pageNum);
            });

            pdfjsLib.getDocument(url).promise.then(pdfDoc_ => {
                pdfDoc = pdfDoc_;
                renderPage(pageNum);
            });
        </script>
    @endif
@endsection
