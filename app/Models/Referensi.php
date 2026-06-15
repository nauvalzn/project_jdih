<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referensi extends Model
{
    protected $table = 'referensi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tabel_id', 'jenis', 'deskripsi', 'ref_id', 'teks', 'status',
    ];
}
