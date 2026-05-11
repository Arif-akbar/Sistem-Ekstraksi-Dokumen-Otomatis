<?php

namespace App\Contracts;

use App\Models\Document;

interface DocumentExtractorInterface
{
    /**
     * Mengekstrak data terstruktur dari sebuah dokumen.
     * Mengembalikan array hasil ekstraksi atau null jika gagal.
     */
    public function extract(Document $document): ?array;
}
