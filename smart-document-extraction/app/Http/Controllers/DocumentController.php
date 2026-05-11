<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller
{
    /**
     * Tampilkan daftar dokumen milik user yang login.
     */
    public function index(): View
    {
        return view('documents.index');
    }

    /**
     * Tampilkan detail satu dokumen beserta hasil ekstraksinya.
     */
    public function show(Document $document): View
    {
        // Pastikan user hanya bisa melihat dokumen miliknya
        abort_if($document->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $document->load(['extractedData', 'user']);

        return view('documents.show', compact('document'));
    }

    /**
     * Konfirmasi hasil ekstraksi (ubah status dari 'needs_validation' ke 'completed').
     */
    public function validate(Document $document): RedirectResponse
    {
        abort_if($document->user_id !== Auth::id(), 403, 'Akses ditolak.');
        abort_if($document->status !== 'needs_validation', 422, 'Dokumen tidak dalam status yang tepat untuk divalidasi.');

        $document->update(['status' => 'completed']);

        return redirect()->route('documents.show', $document)
            ->with('success', 'Data berhasil dikonfirmasi!');
    }
}
