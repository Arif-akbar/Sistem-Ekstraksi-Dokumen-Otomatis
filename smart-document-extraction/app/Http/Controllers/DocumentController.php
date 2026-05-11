<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Menampilkan halaman daftar dokumen.
     */
    public function index()
    {
        return view('documents.index');
    }

    /**
     * Menampilkan halaman detail/validasi dokumen.
     */
    public function show(Document $document)
    {
        // Pastikan user hanya bisa melihat dokumen miliknya
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini.');
        }

        return view('documents.show', compact('document'));
    }

    /**
     * Menyajikan file secara aman (Secure File Server).
     */
    public function serveFile(Document $document)
    {
        // Pastikan user hanya bisa mengakses file miliknya
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke file ini.');
        }

        // Ambil path file dari storage private (local disk)
        $filePath = $document->file_path;

        // Pastikan file benar-benar ada di storage
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Dapatkan absolute path file
        $absolutePath = Storage::disk('local')->path($filePath);

        // Kembalikan response file
        return response()->file($absolutePath);
    }
}
