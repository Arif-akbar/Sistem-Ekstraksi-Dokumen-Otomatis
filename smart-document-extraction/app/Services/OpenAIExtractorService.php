<?php

namespace App\Services;

use App\Contracts\DocumentExtractorInterface;
use App\Models\Document;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OpenAIExtractorService implements DocumentExtractorInterface
{
    private string $apiKey;
    private string $model;
    private string $apiUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.openai.key');
        $this->model   = config('services.openai.model', 'gpt-4o-mini');
        $this->apiUrl  = 'https://api.openai.com/v1/chat/completions';
    }

    public function extract(Document $document): ?array
    {
        try {
            // Ambil konten file dari storage dan encode ke base64
            $fileContent = Storage::get($document->file_path);
            $base64Image = base64_encode($fileContent);
            $mimeType    = Storage::mimeType($document->file_path);

            /* --- KODE ASLI (DIKOMENTARI UNTUK SIMULASI) ---
            $response = Http::withToken($this->apiKey)
                ->timeout(60)
                ->post($this->apiUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role'    => 'system',
                            // INSTRUKSI KETAT: Hanya kembalikan JSON murni, tanpa markdown
                            'content' => 'You are a precise document data extraction assistant. 
                                          Your ONLY output must be a single, valid JSON object. 
                                          Do NOT include markdown formatting (no ```json), 
                                          no introductory text, no explanations. 
                                          Return ONLY the raw JSON object.',
                        ],
                        [
                            'role'    => 'user',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => 'Extract the following fields from this document image and return them as a JSON object with these exact keys: 
                                               "vendor_name" (string), 
                                               "transaction_date" (string, format: YYYY-MM-DD), 
                                               "total_amount" (number, no currency symbol), 
                                               "tax_amount" (number, no currency symbol). 
                                               If a field cannot be found, set its value to null.',
                                ],
                                [
                                    'type'      => 'image_url',
                                    'image_url' => [
                                        'url'    => "data:{$mimeType};base64,{$base64Image}",
                                        'detail' => 'high',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'max_tokens'      => 500,
                    'response_format' => ['type' => 'json_object'], // Memaksa output JSON
                ]);

            if ($response->failed()) {
                Log::error('OpenAI API request gagal', [
                    'document_id' => $document->id,
                    'status'      => $response->status(),
                    'body'        => $response->body(),
                ]);
                return null;
            }

            $rawResponse = $response->json();

            // Ambil konten teks dari respons OpenAI
            $jsonString = $rawResponse['choices'][0]['message']['content'] ?? null;

            if (!$jsonString) {
                Log::error('Respons OpenAI tidak memiliki konten yang valid', [
                    'document_id' => $document->id,
                ]);
                return null;
            }

            // Decode JSON string menjadi array
            $extracted = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Gagal mendecode JSON dari respons OpenAI', [
                    'document_id' => $document->id,
                    'content'     => $jsonString,
                ]);
                return null;
            }

            // Kembalikan data ekstraksi + respons mentah untuk disimpan di DB
            return [
                'extracted' => $extracted,
                'raw'       => $rawResponse,
            ];
            --- AKHIR KODE ASLI --- */

            // --- KODE SIMULASI (MOCKING) ---
            Log::info('Memulai simulasi ekstraksi dokumen...', ['document_id' => $document->id]);
            
            // Simulasi AI sedang "berpikir" selama 3 detik
            sleep(3);

            // Kembalikan data dummy keras (hardcoded)
            return [
                'extracted' => [
                    'vendor_name'      => 'PT. Toko Maju Jaya (Data Simulasi)',
                    'transaction_date' => date('Y-m-d'), // Tanggal hari ini
                    'total_amount'     => 150000.00,
                    'tax_amount'       => 15000.00,
                ],
                'raw' => [
                    'status'  => 'simulasi',
                    'message' => 'Ini adalah hasil simulasi tanpa koneksi ke OpenAI API.'
                ],
            ];

        } catch (\Throwable $e) {
            Log::error('Exception saat memanggil OpenAI', [
                'document_id' => $document->id,
                'error'       => $e->getMessage(),
            ]);
            return null;
        }
    }
}
