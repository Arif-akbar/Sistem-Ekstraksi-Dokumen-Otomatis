<?php

namespace App\Livewire\Documents;

use App\Jobs\ProcessDocumentExtraction;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadDocument extends Component
{
    use WithFileUploads;

    #[Validate(['file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240'])]
    public $file;

    public bool $isUploading = false;
    public bool $uploadSuccess = false;

    public function upload(): void
    {
        $this->validate();

        $this->isUploading = true;

        try {
            $originalName = $this->file->getClientOriginalName();
            // Simpan file ke storage/app/private/documents/{user_id}/
            $path = $this->file->store("documents/" . Auth::id(), 'local');

            // Buat record di tabel documents
            $document = Document::create([
                'user_id'   => Auth::id(),
                'file_name' => $originalName,
                'file_path' => $path,
                'status'    => 'pending',
            ]);

            // Kirim job ke antrian background
            ProcessDocumentExtraction::dispatch($document);

            $this->reset('file');
            $this->uploadSuccess = true;
            $this->isUploading = false;

            // Beritahu komponen lain (DocumentList) untuk refresh
            $this->dispatch('document-uploaded');

        } catch (\Throwable $e) {
            $this->isUploading = false;
            $this->addError('file', 'Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.documents.upload-document');
    }
}
