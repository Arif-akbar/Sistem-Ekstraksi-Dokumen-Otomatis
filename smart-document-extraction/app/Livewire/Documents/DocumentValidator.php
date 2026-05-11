<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DocumentValidator extends Component
{
    public Document $document;

    // Properti form yang akan di-bind dengan input user
    public $vendor_name;
    public $transaction_date;
    public $total_amount;
    public $tax_amount;

    public function mount(Document $document)
    {
        $this->document = $document;

        // Inisialisasi nilai form dengan data hasil ekstraksi (jika ada)
        if ($document->extractedData) {
            $this->vendor_name = $document->extractedData->vendor_name;
            $this->transaction_date = $document->extractedData->transaction_date ? $document->extractedData->transaction_date->format('Y-m-d') : null;
            $this->total_amount = $document->extractedData->total_amount;
            $this->tax_amount = $document->extractedData->tax_amount;
        }
    }

    public function save()
    {
        // Pastikan hanya pemilik dokumen yang bisa menyimpan
        if ($this->document->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $this->validate([
            'vendor_name' => 'nullable|string|max:255',
            'transaction_date' => 'nullable|date',
            'total_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
        ]);

        // Update data ekstraksi
        if ($this->document->extractedData) {
            $this->document->extractedData->update($validated);
        }

        // Ubah status dokumen menjadi completed (telah divalidasi)
        $this->document->update(['status' => 'completed']);

        session()->flash('success', 'Data dokumen berhasil divalidasi dan disimpan.');
    }

    public function render()
    {
        return view('livewire.documents.document-validator');
    }
}
