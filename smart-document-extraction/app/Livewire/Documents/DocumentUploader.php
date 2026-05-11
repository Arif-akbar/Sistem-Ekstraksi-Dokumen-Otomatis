<?php

namespace App\Livewire\Documents;

use App\Jobs\ProcessDocumentExtraction;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class DocumentUploader extends Component
{
    use WithFileUploads;

    public $file;

    public function saveDocument()
    {
        $this->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Menyimpan file ke storage lokal (private)
        $path = $this->file->store("documents/" . Auth::id(), 'local');

        // Menyimpan ke database
        $document = Document::create([
            'user_id'   => Auth::id(),
            'file_name' => $this->file->getClientOriginalName(),
            'file_path' => $path,
            'status'    => 'pending',
        ]);

        // Mengirim job ke background untuk ekstraksi OpenAI
        ProcessDocumentExtraction::dispatch($document);

        $this->reset('file');
        
        session()->flash('message', 'Dokumen berhasil diunggah dan sedang diproses!');
        
        // Memancarkan event agar daftar dokumen bisa di-refresh
        $this->dispatch('document-uploaded');
    }

    public function render()
    {
        return view('livewire.documents.document-uploader');
    }
}
