<?php

namespace App\Jobs;

use App\Contracts\DocumentExtractorInterface;
use App\Models\Document;
use App\Models\ExtractedData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDocumentExtraction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah maksimal percobaan ulang jika job gagal.
     */
    public int $tries = 3;

    /**
     * Timeout dalam detik untuk setiap percobaan.
     */
    public int $timeout = 120;

    /**
     * Buat instance job baru.
     */
    public function __construct(
        public readonly Document $document
    ) {}

    /**
     * Eksekusi job: panggil service OpenAI dan simpan hasilnya.
     */
    public function handle(DocumentExtractorInterface $extractor): void
    {
        Log::info('Memulai proses ekstraksi dokumen', ['document_id' => $this->document->id]);

        // Ubah status menjadi 'processing'
        $this->document->update(['status' => 'processing']);

        // Panggil service ekstraksi
        $result = $extractor->extract($this->document);

        if (is_null($result)) {
            // Jika gagal, ubah status menjadi 'failed'
            $this->document->update(['status' => 'failed']);
            Log::error('Ekstraksi gagal, status diubah ke failed', ['document_id' => $this->document->id]);
            return;
        }

        $extracted = $result['extracted'];
        $raw       = $result['raw'];

        // Simpan hasil ekstraksi ke tabel extracted_data
        ExtractedData::create([
            'document_id'      => $this->document->id,
            'vendor_name'      => $extracted['vendor_name'] ?? null,
            'transaction_date' => $extracted['transaction_date'] ?? null,
            'total_amount'     => $extracted['total_amount'] ?? null,
            'tax_amount'       => $extracted['tax_amount'] ?? null,
            'raw_api_response' => $raw,
        ]);

        // Ubah status menjadi 'needs_validation' agar user bisa mereview hasilnya
        $this->document->update(['status' => 'needs_validation']);

        Log::info('Ekstraksi berhasil, menunggu validasi user', ['document_id' => $this->document->id]);
    }

    /**
     * Tangani kegagalan job setelah semua percobaan habis.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('Job ekstraksi gagal total setelah semua percobaan', [
            'document_id' => $this->document->id,
            'error'       => $exception->getMessage(),
        ]);

        $this->document->update(['status' => 'failed']);
    }
}
