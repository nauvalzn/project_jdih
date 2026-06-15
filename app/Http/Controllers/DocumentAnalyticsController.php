<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DocumentAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // 🧩 Filter
        $filter = $request->get('filter', 'all');
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // 🔍 Query dasar analitik
        $query = DocumentAnalytics::query()->join('documents', 'document_analytics.document_id', '=', 'documents.id')->where('documents.status_verifikasi', 2); // Hanya dokumen terverifikasi

        // Filter waktu
        if ($filter === 'month') {
            $query->whereYear('visited_at', $year)->whereMonth('visited_at', $month);
        } elseif ($filter === 'year') {
            $query->whereYear('visited_at', $year);
        }

        // 📈 Statistik utama
        $totalVisits = $query->count();
        $uniqueDocuments = $query->distinct('document_id')->count('document_id');
        $uniqueUsers = $query->distinct('ip')->count('ip');
        // Query dasar dokumen (sesuaikan periode)
        $docQuery = Document::query();
        // 📦 Data detail untuk modal
        // Data detail untuk modal (ikut filter periode)
        $uniqueUserList = (clone $query)->select('ip', DB::raw('MAX(user_agent) as user_agent'), DB::raw('COUNT(*) as total_visits'))->groupBy('ip')->orderByDesc('total_visits')->limit(10)->get();

        $uniqueDocumentList = DB::table('documents')
            ->leftJoin('referensi', function ($join) {
                $join->on('documents.jenis_dokumen', '=', 'referensi.id')->where('referensi.jenis', 1);
            })
            ->leftJoin('document_analytics', 'documents.id', '=', 'document_analytics.document_id')
            ->when($filter === 'month', function ($q) use ($month, $year) {
                $q->whereYear('document_analytics.visited_at', $year)->whereMonth('document_analytics.visited_at', $month);
            })
            ->when($filter === 'year', function ($q) use ($year) {
                $q->whereYear('document_analytics.visited_at', $year);
            })
            ->select('documents.id', 'documents.judul', 'referensi.deskripsi as jenis_dokumen', DB::raw('COUNT(document_analytics.id) as total_visits'))
            ->groupBy('documents.id', 'documents.judul', 'referensi.deskripsi')
            ->orderByDesc('total_visits')
            ->limit(10)
            ->get();

        // Filter periode untuk dokumen
        if ($filter === 'month') {
            $docQuery->whereYear('tanggal_penetapan', $year)->whereMonth('tanggal_penetapan', $month);
        } elseif ($filter === 'year') {
            $docQuery->whereYear('tanggal_penetapan', $year);
        }

        // Dokumen terverifikasi (status_verifikasi = 2 & pakai tanggal_verifikasi)
        $dokumenTerverifikasi = Document::where('status_verifikasi', 2)
            ->when($filter === 'month', fn($q) => $q->whereYear('tanggal_verifikasi', $year)->whereMonth('tanggal_verifikasi', $month))
            ->when($filter === 'year', fn($q) => $q->whereYear('tanggal_verifikasi', $year))
            ->count();

        // Dokumen belum verifikasi (status_verifikasi != 2)
        $dokumenBelumVerifikasi = Document::where('status_verifikasi', 1)
            ->when($filter === 'month', fn($q) => $q->whereYear('tanggal_penetapan', $year)->whereMonth('tanggal_penetapan', $month))
            ->when($filter === 'year', fn($q) => $q->whereYear('tanggal_penetapan', $year))
            ->count();

        // Dokumen berlaku / tidak berlaku / sebagian (hanya yg terverifikasi)
        $dokumenBerlaku = (clone $docQuery)->where('status', 2)->count();
        $dokumenTidakBerlaku = (clone $docQuery)->where('status', 0)->count();
        $dokumenBerlakuSebagian = (clone $docQuery)->where('status', 1)->count();

        // Total dokumen (sesuai periode penetapan)
        $totaldokumen = (clone $docQuery)->count();

        // 🏆 Top 10 dokumen terpopuler (dalam filter)
        $topDocuments = (clone $query)->select('documents.id', 'documents.jenis_dokumen', 'documents.judul', DB::raw('COUNT(document_analytics.id) as total_visits'))->groupBy('documents.id', 'documents.jenis_dokumen', 'documents.judul')->orderByDesc('total_visits')->limit(10)->get();

        $jenisMap = [
            1 => 'Peraturan Gubernur',
            2 => 'Keputusan Gubernur',
            3 => 'Peraturan Direktur',
            4 => 'Keputusan Direktur',
            5 => 'Perizinan',
            6 => 'SOP',
        ];

        $topDocuments->transform(function ($doc) use ($jenisMap) {
            $doc->jenis_dokumen = $jenisMap[$doc->jenis_dokumen] ?? 'Tidak Diketahui';
            return $doc;
        });

        // 🍩 Donut Chart per jenis_dokumen (terfilter & semua jenis muncul)
        $rawVisits = (clone $query)->select('documents.jenis_dokumen', DB::raw('COUNT(document_analytics.id) as total_visits'))->groupBy('documents.jenis_dokumen')->get();

        $visitsByJenis = collect($jenisMap)
            ->map(function ($label, $key) use ($rawVisits) {
                $found = $rawVisits->firstWhere('jenis_dokumen', $key);
                return (object) [
                    'jenis_dokumen' => $label,
                    'total_visits' => $found ? $found->total_visits : 0,
                ];
            })
            ->values();

        $donutLabels = $visitsByJenis->pluck('jenis_dokumen');
        $donutData = $visitsByJenis->pluck('total_visits');

        // 📊 Line/Bar Chart (kunjungan harian/bulanan)
        if ($filter === 'month') {
            // Ambil data real dari DB
            $visitsPerDayRaw = (clone $query)->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as total'))->groupBy('date')->orderBy('date')->get()->keyBy('date');

            // Buat range tanggal lengkap dalam bulan tsb
            $startDate = Carbon::create($year, $month, 1);

            // Tambahin dulu biar urut & aman
            $currentYear = date('Y');
            $currentMonth = date('m');
            $today = Carbon::today();

            // Default akhir bulan
            $endDate = $startDate->copy()->endOfMonth();

            // Kalau bulan & tahun yang ditampilkan sama dengan sekarang → stop di hari ini
            if ($year == $currentYear && $month == $currentMonth) {
                $endDate = $today;
            }

            $dates = [];
            $totals = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $formatted = $date->format('Y-m-d');
                $dates[] = $formatted;
                $totals[] = $visitsPerDayRaw[$formatted]->total ?? 0;
            }

            $visitsLabels = collect($dates);
            $visitsData = collect($totals);
        } elseif ($filter === 'year') {
            $months = collect(range(1, 12));
            $visitsPerMonth = $months->map(function ($m) use ($query, $year) {
                $count = (clone $query)->whereYear('visited_at', $year)->whereMonth('visited_at', $m)->count();
                return [
                    'month' => Carbon::create($year, $m, 1)->format('F'),
                    'total' => $count ?: 0,
                ];
            });

            $visitsLabels = $visitsPerMonth->pluck('month');
            $visitsData = $visitsPerMonth->pluck('total');
        } else {
            // Default: tampilkan semua hari di bulan berjalan
            $currentMonth = date('m');
            $currentYear = date('Y');

            // Ambil data real dari DB
            $visitsPerDayRaw = (clone $query)->whereYear('visited_at', $currentYear)->whereMonth('visited_at', $currentMonth)->select(DB::raw('DATE(visited_at) as date'), DB::raw('COUNT(*) as total'))->groupBy('date')->orderBy('date')->get()->keyBy('date');

            // Buat range tanggal lengkap dalam bulan tsb
            $startDate = Carbon::create($currentYear, $currentMonth, 1);
            // Kalau bulan/tahun saat ini → stop di hari ini, bukan akhir bulan
            $today = Carbon::today();
            $endDate = $startDate->copy()->endOfMonth();

            // Cek: kalau bulan & tahun yang ditampilkan sama dengan sekarang → stop di hari ini
            if ($currentYear == $today->year && $currentMonth == $today->month) {
                $endDate = $today;
            }

            $dates = [];
            $totals = [];

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $formatted = $date->format('Y-m-d');
                $dates[] = $formatted;
                $totals[] = $visitsPerDayRaw[$formatted]->total ?? 0;
            }

            $visitsLabels = collect($dates);
            $visitsData = collect($totals);
        }

        // 🎯 View
        return view('content.dashboard.dashboards-analytics', compact('totalVisits', 'uniqueDocuments', 'uniqueUsers', 'dokumenTerverifikasi', 'dokumenBelumVerifikasi', 'dokumenBerlaku', 'dokumenTidakBerlaku', 'dokumenBerlakuSebagian', 'totaldokumen', 'topDocuments', 'filter', 'month', 'year', 'visitsLabels', 'visitsData', 'donutLabels', 'donutData', 'uniqueUserList', 'uniqueDocumentList'));
    }
    public function indexDetailUser()
    {
        $uniqueUserList = DocumentAnalytics::select('ip', DB::raw('MAX(user_agent) as user_agent'), DB::raw('COUNT(*) as total_visits'))->groupBy('ip')->orderByDesc('total_visits')->paginate(10);

        return view('content.document.indexdetailuser', compact('uniqueUserList'));
    }

    // Detail Dokumen unik
    public function indexDetailDokumen()
    {
        $uniqueDocumentList = DB::table('documents')
            ->leftJoin('referensi', function ($join) {
                $join->on('documents.jenis_dokumen', '=', 'referensi.id')->where('referensi.jenis', 1);
            })
            ->leftJoin('document_analytics', 'documents.id', '=', 'document_analytics.document_id')
            ->select('documents.id', 'documents.judul', 'referensi.deskripsi as jenis_dokumen', DB::raw('COUNT(document_analytics.id) as total_visits'))
            ->groupBy('documents.id', 'documents.judul', 'referensi.deskripsi')
            ->orderByDesc('total_visits')
            ->paginate(10); // <-- pakai paginate

        return view('content.document.indexdetaildokumen', compact('uniqueDocumentList'));
    }
}
