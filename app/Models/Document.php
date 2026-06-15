<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'pdf_file',
        'tipe_dokumen',
        'bidang_hukum',
        'jenis_hukum',
        'jenis_dokumen',
        'singkatan',
        'nomor',
        'tahun',
        'judul',
        'tempat_penetapan',
        'tanggal_penetapan',
        'tanggal_pengundangan',
        'tanggal_perubahan',
        'periode_berlaku',
        'sumber',
        'subjek',
        'bahasa',
        'lokasi',
        'urusan_pemerintahan',
        'penandatanganan',
        'pemrakarsa',
        'status',
        'keterangan_id',
        'keterangan',
        'keterangan_dokumen',
    ];

    // app/Models/Document.php
    public function jenisDokumenRef()
{
    return $this->belongsTo(Referensi::class, 'jenis_dokumen', 'id')
                ->where('jenis', 1)
                ->withDefault([
                    'deskripsi' => '-',
                ]);
}

public function statusDokumenRef()
{
    return $this->belongsTo(Referensi::class, 'status', 'id')
                ->where('jenis', 2)
                ->withDefault([
                    'deskripsi' => '-',
                ]);
}

public function keteranganDoc()
{
    return $this->belongsTo(Document::class, 'keterangan_id')
                ->withDefault([
                    'judul' => '-',
                    'pdf_file' => null,
                ]);
}

}