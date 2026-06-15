<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\DocumentAnalytics;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    private $jenisKode = [
        'Peraturan Gubernur' => 1,
        'Keputusan Gubernur' => 2,
        'Peraturan Direktur' => 3,
        'Keputusan Direktur' => 4,
        'Perizinan' => 5,
        'SOP' => 6,
    ];

    // ========================
    // AJAX UNTUK SELECT2 JUDUL
    // ========================
    public function ajaxJudul(Request $request)
    {
        $q = $request->get('q', '');

        $data = Document::query()
            ->where('judul', 'like', "%{$q}%")
            ->select('judul')
            ->distinct()
            ->limit(50)
            ->get();

        $results = $data
            ->map(function ($item) {
                return [
                    'id' => $item->judul,
                    'text' => $item->judul,
                ];
            })
            ->values();

        return response()->json(['results' => $results]);
    }

    public function index(Request $request)
    {
        $query = Document::query();
        $query->where('status_verifikasi', 2);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('tipe_dokumen', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }
        $documents = $query->latest()->paginate(10);

        // 🔹 Ambil data mapping tipe dokumen dari tabel referensi
        $tipeDokumenMap = DB::table('referensi')
            ->where('jenis', 4) // sesuai jenis_referensi untuk tipe dokumen
            ->where('status', 1) // cuma yang aktif
            ->pluck('deskripsi', 'id');

        // 🔹 Tambahkan properti nama tipe dokumen
        foreach ($documents as $doc) {
            $doc->tipe_dokumen_nama = $tipeDokumenMap[$doc->tipe_dokumen] ?? '-';
        }

        return view('content.document.index', compact('documents'));
    }

    public function indexVerifikasiTabs(Request $request)
{
    $pendingDocuments = Document::where('status_verifikasi', 1)
        ->latest()
        ->paginate(10, ['*'], 'pending_page');

    $allDocuments = Document::latest()
        ->paginate(10, ['*'], 'history_page');

    return view('content.document.index-verifikasi', compact('pendingDocuments', 'allDocuments'));
}


    public function search(Request $request)
    {
        $q = $request->input('q');

        // Ambil semua dokumen yang diverifikasi
        $results = Document::where('status_verifikasi', 2)
            ->where(function ($query) use ($q) {
                $query
                    ->where('judul', 'like', "%{$q}%")
                    ->orWhere('nomor', 'like', "%{$q}%")
                    ->orWhere('tahun', 'like', "%{$q}%");
            })
            ->paginate(10)
            ->appends(['q' => $q]);

        // Mapping jenis_dokumen ke nama deskriptif
        $jenisDokumenMap = [
            1 => 'Peraturan Gubernur',
            2 => 'Keputusan Gubernur',
            3 => 'Peraturan Direktur',
            4 => 'Keputusan Direktur',
            5 => 'Perizinan',
            6 => 'SOP',
        ];

        // Tambahkan kolom readable ke tiap hasil
        $results->transform(function ($item) use ($jenisDokumenMap) {
            $item->jenis_dokumen_nama = $jenisDokumenMap[$item->jenis_dokumen] ?? '-';
            return $item;
        });

        $pageConfigs = ['layout' => 'blank'];

        return view('content.search.result', compact('q', 'results', 'pageConfigs'));
    }

    public function create()
    {
        $documents = Document::all();

        // Ambil jenis referensi untuk label dropdown
        $jenisReferensi = DB::table('jenis_referensi')
            ->where('id', 4) // id 4 = 'Tipe Dokumen'
            ->where('status', 1) // cuma yang aktif
            ->first();

        // Kalau jenis referensi aktif, ambil data referensinya
        $tipeDokumen = collect(); // default kosong
        if ($jenisReferensi) {
            $tipeDokumen = DB::table('referensi')->where('jenis', $jenisReferensi->id)->where('status', 1)->get();
        }

        $jenisReferensi1 = DB::table('jenis_referensi')
            ->where('id', 5) // id 4 = 'Bidang Hukum'
            ->where('status', 1) // cuma yang aktif
            ->first();

        $bidangHukum = collect(); // default kosong
        if ($jenisReferensi1) {
            $bidangHukum = DB::table('referensi')->where('jenis', $jenisReferensi1->id)->where('status', 1)->get();
        }

        $jenisReferensi2 = DB::table('jenis_referensi')
            ->where('id', 6) // id 4 = 'Tipe Dokumen'
            ->where('status', 1) // cuma yang aktif
            ->first();

        $jenisHukum = collect(); // default kosong
        if ($jenisReferensi2) {
            $jenisHukum = DB::table('referensi')->where('jenis', $jenisReferensi2->id)->where('status', 1)->get();
        }

        $jenisReferensi3 = DB::table('jenis_referensi')
            ->where('id', 1) // id 4 = 'Tipe Dokumen'
            ->where('status', 1) // cuma yang aktif
            ->first();

        $jenisDokumen = collect(); // default kosong
        if ($jenisReferensi3) {
            $jenisDokumen = DB::table('referensi')->where('jenis', $jenisReferensi3->id)->where('status', 1)->get();
        }

        $jenisReferensi4 = DB::table('jenis_referensi')
            ->where('id', 2) // id 4 = 'Tipe Dokumen'
            ->where('status', 1) // cuma yang aktif
            ->first();

        $statusDokumen = collect(); // default kosong
        if ($jenisReferensi4) {
            $statusDokumen = DB::table('referensi')->where('jenis', $jenisReferensi4->id)->where('status', 1)->get();
        }
        return view('content.document.create', compact('documents', 'jenisReferensi', 'tipeDokumen', 'jenisReferensi1', 'bidangHukum', 'jenisReferensi2', 'jenisHukum', 'jenisReferensi3', 'jenisDokumen', 'jenisReferensi4', 'statusDokumen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf,docx,xlsx,pptx|max:20480',
            'judul' => 'required|string|max:255',
        ]);

        $filePath = $request->file('pdf_file')->store('documents', 'public');

        $document = Document::create(array_merge($request->except('pdf_file'), ['pdf_file' => $filePath]));

        return redirect()->route('documents.show', $document->id)->with('success', 'Dokumen berhasil disimpan!');
    }

    public function show($id)
    {
        $document = Document::with('jenisDokumenRef')->findOrFail($id);

        // Mapping referensi
        $tipeDokumenMap = DB::table('referensi')->where('jenis', 4)->where('status', 1)->pluck('deskripsi', 'id');
        $jenisDokumenMap = DB::table('referensi')->where('jenis', 1)->where('status', 1)->pluck('deskripsi', 'id');
        $bidangHukumMap = DB::table('referensi')->where('jenis', 5)->where('status', 1)->pluck('deskripsi', 'id'); // misal 5 = bidang hukum
        $jenisHukumMap = DB::table('referensi')->where('jenis', 6)->where('status', 1)->pluck('deskripsi', 'id'); // misal 6 = jenis hukum
        $statusDokumenMap = DB::table('referensi')->where('jenis', 2)->where('status', 1)->pluck('deskripsi', 'id'); // status dokumen

        // Simpan analytics
        DocumentAnalytics::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
            'visited_at' => now(),
        ]);

        // Tambah view count
        $document->increment('views');

        return view('content.document.show', compact('document', 'tipeDokumenMap', 'jenisDokumenMap', 'bidangHukumMap', 'jenisHukumMap', 'statusDokumenMap'));
    }

    public function showVerifikasi($id)
    {
        $document = Document::with(['jenisDokumenRef', 'statusDokumenRef', 'keteranganDoc'])->findOrFail($id);
        // Mapping referensi
        $tipeDokumenMap = DB::table('referensi')->where('jenis', 4)->where('status', 1)->pluck('deskripsi', 'id');
        $jenisDokumenMap = DB::table('referensi')->where('jenis', 1)->where('status', 1)->pluck('deskripsi', 'id');
        $bidangHukumMap = DB::table('referensi')->where('jenis', 5)->where('status', 1)->pluck('deskripsi', 'id'); // misal 5 = bidang hukum
        $jenisHukumMap = DB::table('referensi')->where('jenis', 6)->where('status', 1)->pluck('deskripsi', 'id'); // misal 6 = jenis hukum
        $statusDokumenMap = DB::table('referensi')->where('jenis', 2)->where('status', 1)->pluck('deskripsi', 'id'); // status dokumen

        return view('content.document.verifikasi-dokumen', compact('document', 'tipeDokumenMap', 'jenisDokumenMap', 'bidangHukumMap', 'jenisHukumMap', 'statusDokumenMap'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id); // ambil dokumen yang diedit
        $documents = Document::all(); // untuk dropdown rujukan

        // Tipe Dokumen
        $jenisReferensi = DB::table('jenis_referensi')->where('id', 4)->where('status', 1)->first();

        $tipeDokumen = collect();
        if ($jenisReferensi) {
            $tipeDokumen = DB::table('referensi')->where('jenis', $jenisReferensi->id)->where('status', 1)->get();
        }

        // Bidang Hukum
        $jenisReferensi1 = DB::table('jenis_referensi')->where('id', 5)->where('status', 1)->first();

        $bidangHukum = collect();
        if ($jenisReferensi1) {
            $bidangHukum = DB::table('referensi')->where('jenis', $jenisReferensi1->id)->where('status', 1)->get();
        }

        // Jenis Hukum
        $jenisReferensi2 = DB::table('jenis_referensi')->where('id', 6)->where('status', 1)->first();

        $jenisHukum = collect();
        if ($jenisReferensi2) {
            $jenisHukum = DB::table('referensi')->where('jenis', $jenisReferensi2->id)->where('status', 1)->get();
        }

        // Jenis Dokumen
        $jenisReferensi3 = DB::table('jenis_referensi')->where('id', 1)->where('status', 1)->first();

        $jenisDokumen = collect();
        if ($jenisReferensi3) {
            $jenisDokumen = DB::table('referensi')->where('jenis', $jenisReferensi3->id)->where('status', 1)->get();
        }

        // Status Dokumen
        $jenisReferensi4 = DB::table('jenis_referensi')->where('id', 2)->where('status', 1)->first();

        $statusDokumen = collect();
        if ($jenisReferensi4) {
            $statusDokumen = DB::table('referensi')->where('jenis', $jenisReferensi4->id)->where('status', 1)->get();
        }

        return view('content.document.edit', compact('document', 'documents', 'jenisReferensi', 'tipeDokumen', 'jenisReferensi1', 'bidangHukum', 'jenisReferensi2', 'jenisHukum', 'jenisReferensi3', 'jenisDokumen', 'jenisReferensi4', 'statusDokumen'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'status' => 'required|in:0,1,2',
            'keterangan_id' => 'nullable|integer|exists:documents,id',
            'keterangan_dokumen' => 'nullable|string|max:255',
            'pdf_file' => 'nullable|mimes:pdf|max:20480',
            'tipe_dokumen' => 'nullable|string',
            'bidang_hukum' => 'nullable|string',
            'jenis_hukum' => 'nullable|string',
            'jenis_dokumen' => 'nullable|string',
            'singkatan' => 'nullable|string|max:255',
            'nomor' => 'nullable|string|max:255',
            'tahun' => 'nullable|integer',
            'tempat_penetapan' => 'nullable|string|max:255',
            'tanggal_penetapan' => 'nullable|date',
            'tanggal_pengundangan' => 'nullable|date',
            'periode_berlaku' => 'nullable|integer|min:1|max:10',
            'sumber' => 'nullable|string|max:255',
            'subjek' => 'nullable|string|max:255',
            'bahasa' => 'nullable|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'urusan_pemerintahan' => 'nullable|string|max:255',
            'penandatanganan' => 'nullable|string|max:255',
            'pemrakarsa' => 'nullable|string|max:255',
        ]);

        $document = Document::findOrFail($id);

        if ($document->status != $request->status) {
            $document->tanggal_perubahan = now();
        }

        if ($request->hasFile('pdf_file')) {
            if ($document->pdf_file && Storage::disk('public')->exists($document->pdf_file)) {
                Storage::disk('public')->delete($document->pdf_file);
            }
            $document->pdf_file = $request->file('pdf_file')->store('documents', 'public');
        }

        // Update semua field lain (kecuali keterangan_id & keterangan_dokumen)
        $fields = ['judul', 'status', 'tipe_dokumen', 'bidang_hukum', 'jenis_hukum', 'jenis_dokumen', 'singkatan', 'nomor', 'tahun', 'tempat_penetapan', 'tanggal_penetapan', 'tanggal_pengundangan', 'periode_berlaku', 'sumber', 'subjek', 'bahasa', 'lokasi', 'urusan_pemerintahan', 'penandatanganan', 'pemrakarsa'];

        foreach ($fields as $field) {
            $document->$field = $request->$field;
        }

        if ($request->status === '2') {
            $document->keterangan_id = null;
            $document->keterangan_dokumen = null;
        } else {
            $document->keterangan_id = $request->keterangan_id;
            $document->keterangan_dokumen = $request->keterangan_dokumen;
        }

        $document->save();

        return redirect()->route('documents.show', $document->id)->with('success', 'Dokumen berhasil diperbarui!');
    }
    public function expiring()
    {
        $today = now();

        // Ambil semua dokumen yang sudah diverifikasi
        $documents = \App\Models\Document::where('jenis_dokumen', 5)->where('status_verifikasi', 2)->get();

        // Loop untuk update status dokumen yang sudah expired
        foreach ($documents as $doc) {
            $tanggalPenetapan = \Carbon\Carbon::parse($doc->tanggal_penetapan)->startOfDay();

            if ($doc->jenis_dokumen == 5 && $doc->periode_berlaku) {
                // Expired = tanggal penetapan + periode_berlaku tahun
                $expiredAt = $tanggalPenetapan->copy()->addYears($doc->periode_berlaku);
            } else {
                // Dokumen lain bisa tetap 6 bulan (opsional)
                $expiredAt = $tanggalPenetapan->copy()->addMonths(6);
            }

            // Jika sudah lewat dari tanggal expired
            if ($expiredAt->isPast()) {
                $doc->status = 0; // tidak berlaku
                $doc->keterangan_dokumen = 'Expired';
                $doc->save();
            }
        }

        // Setelah update, ambil ulang dokumen yang MASIH BERLAKU aja
        $documents = \App\Models\Document::where('jenis_dokumen', 5)->where('status_verifikasi', 2)->where('status', '!=', 0)->get();

        return view('content.document.expiring', compact('documents', 'today'));
    }

    public function updateStatusVerifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:0,1,2,3',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        $document = Document::findOrFail($id);

        $document->status_verifikasi = $request->status_verifikasi;
        $document->catatan_admin = $request->catatan_admin;

        // 🕒 Simpan tanggal verifikasi setiap kali ada aksi
        $document->tanggal_verifikasi = now();

        $document->save();

        $messages = [
            0 => 'Verifikasi dibatalkan ❌',
            1 => 'Status dikembalikan ke belum diverifikasi ⏳',
            2 => 'Dokumen berhasil diverifikasi ✅',
            3 => 'Dokumen membutuhkan perbaikan ⚠️',
        ];

        return redirect()
            ->route('documents.verifikasi')
            ->with('success', $messages[$request->status_verifikasi]);
    }

    public function destroy(Request $request, $id)
    {
        try {
            $document = Document::findOrFail($id);

            if ($document->pdf_file && Storage::disk('public')->exists($document->pdf_file)) {
                Storage::disk('public')->delete($document->pdf_file);
            }

            $document->delete();

            // Ambil return_url, kalau nggak ada default ke index
            $redirectUrl = $request->input('return_url', route('documents.index'));

            return redirect($redirectUrl)->with('success', 'Dokumen berhasil dihapus!');
        } catch (\Exception $e) {
            $redirectUrl = $request->input('return_url', route('documents.index'));
            return redirect($redirectUrl)->with('error', 'Gagal menghapus dokumen: ' . $e->getMessage());
        }
    }

    // ========================
    // BAGIAN KATEGORI
    // ========================

    private function getCategories()
    {
        return [
            'categories' => Document::select(DB::raw("'Keputusan Direktur' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 4)->where('status_verifikasi', 2)->get(),

            'categoriesPeraturanGubernur' => Document::select(DB::raw("'Peraturan Gubernur' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 1)->where('status_verifikasi', 2)->get(),

            'categoriesKeputusanGubernur' => Document::select(DB::raw("'Keputusan Gubernur' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 2)->where('status_verifikasi', 2)->get(),

            'categoriesPeraturanDirektur' => Document::select(DB::raw("'Peraturan Direktur' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 3)->where('status_verifikasi', 2)->get(),

            'categoriesPerizinan' => Document::select(DB::raw("'Perizinan' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 5)->where('status_verifikasi', 2)->get(),

            'categoriesSOP' => Document::select(DB::raw("'SOP' as kategori"), DB::raw('count(*) as total'))->where('jenis_dokumen', 6)->where('status_verifikasi', 2)->get(),
        ];
    }

    public function handleCategory(Request $request, $jenisStr, $viewVar, $viewName)
    {
        $jenis = $this->jenisKode[$jenisStr] ?? null;

        $search = $request->input('q');

        $documents = Document::where('jenis_dokumen', $jenis)
            ->where('status_verifikasi', 2)
            ->when(
                $search,
                fn($query) => $query
                    ->where('judul', 'like', "%{$search}%")
                    ->orWhere('nomor', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%"),
            )
            ->latest()
            ->paginate(10);

        return view($viewName, array_merge([$viewVar => $documents], $this->getCategories()));
    }

    public function keputusanDirektur(Request $request)
    {
        return $this->handleCategory($request, 'Keputusan Direktur', 'keputusanDirektur', 'kategori-dokumen.indexDir');
    }

    public function peraturanGubernur(Request $request)
    {
        return $this->handleCategory($request, 'Peraturan Gubernur', 'peraturanGubernur', 'kategori-dokumen.indexGub');
    }

    public function keputusanGubernur(Request $request)
    {
        return $this->handleCategory($request, 'Keputusan Gubernur', 'keputusanGubernur', 'kategori-dokumen.indexkepgub');
    }

    public function perIzinan(Request $request)
    {
        return $this->handleCategory($request, 'Perizinan', 'perIzinan', 'kategori-dokumen.indexIzin');
    }

    public function peraturanDirektur(Request $request)
    {
        return $this->handleCategory($request, 'Peraturan Direktur', 'peraturanDirektur', 'kategori-dokumen.indexperdir');
    }

    public function SOP(Request $request)
    {
        return $this->handleCategory($request, 'SOP', 'SOP', 'kategori-dokumen.indexSOP');
    }

    public function analytics()
    {
        $popularDocuments = Document::where('status_verifikasi', 2)
            ->orderByDesc('views')
            ->take(10)
            ->get(['judul', 'views', 'jenis_dokumen']);

        $byType = Document::select('jenis_dokumen', DB::raw('SUM(views) as total_views'))->groupBy('jenis_dokumen')->orderByDesc('total_views')->get();

        $totalVisits = Document::sum('views');

        return view('content.dashboard.dashboards-analytics', compact('popularDocuments', 'byType', 'totalVisits'));
    }

    public function dashboardAnalytics(Request $request)
    {
        $currentMonth = $request->input('month', date('F')); // Nama bulan
        $currentYear = $request->input('year', date('Y')); // Tahun

        $topDocuments = Document::where('status_verifikasi', 2)
            ->whereMonth('created_at', date('m', strtotime($currentMonth)))
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('views')
            ->take(5)
            ->get(['judul', 'views']);

        $byType = Document::select('jenis_dokumen', DB::raw('SUM(views) as total_views'))
            ->whereMonth('created_at', date('m', strtotime($currentMonth)))
            ->whereYear('created_at', $currentYear)
            ->groupBy('jenis_dokumen')
            ->orderByDesc('total_views')
            ->get();

        $visitsLabels = $topDocuments->pluck('judul');
        $visitsData = $topDocuments->pluck('views');

        return view('content.dashboard.dashboards-analytics', compact('topDocuments', 'byType', 'visitsLabels', 'visitsData', 'currentMonth', 'currentYear'));
    }
}
