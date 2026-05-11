<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtractedData extends Model
{
    protected $fillable = [
        'document_id', 
        'vendor_name', 
        'transaction_date', 
        'total_amount', 
        'tax_amount', 
        'raw_api_response'
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'raw_api_response' => 'array', // Secara otomatis mengubah JSON menjadi array
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}

