<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasUuids; // Menghasilkan UUID secara otomatis saat menyimpan data

    protected $fillable = [
        'user_id', 
        'file_name', 
        'file_path', 
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function extractedData(): HasOne
    {
        return $this->hasOne(ExtractedData::class);
    }
}

