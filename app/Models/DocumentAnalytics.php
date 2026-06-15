<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentAnalytics extends Model
{
    use HasFactory;

    protected $table = 'document_analytics';

    protected $fillable = [
        'document_id',
        'user_id',
        'ip',
        'user_agent',
        'visited_at',
    ];

    public $timestamps = false;

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
