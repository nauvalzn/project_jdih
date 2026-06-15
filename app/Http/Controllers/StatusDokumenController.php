<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class StatusDokumenController extends Controller
{
    // ✅ List semua dokumen (dengan search)
    public function index(Request $request)
    {
        $query = Document::query();

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

        return view('content.document.status-dokumen.index', compact('documents'));
    }

    // ✅ Form tambah dokumen
    public function create()
    {
        // 
    }

    // ✅ Simpan dokumen baru
    public function store(Request $request)
    {
        // 
    }

    // ✅ Detail dokumen
    public function show(Document $document)
    {
        // return view('content.document.status-dokumen.show', compact('document'));
    }

    // ✅ Form edit
    public function edit(Document $document)
    {
        return view('content.document.edit', compact('document'));
    }

    // ✅ Update dokumen
    public function update(Request $request, Document $document)
    {
        // 
    }

    // ✅ Hapus dokumen
    public function destroy(Document $document)
    {
        // 
    }
}