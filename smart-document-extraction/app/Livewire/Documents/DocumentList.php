<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class DocumentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    protected $listeners = ['document-uploaded' => '$refresh'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $documents = Document::query()
            ->where('user_id', Auth::id())
            ->when($this->search, function ($query) {
                $query->where('file_name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with('extractedData')
            ->latest()
            ->paginate(10);

        return view('livewire.documents.document-list', compact('documents'));
    }
}
