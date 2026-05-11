<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Ekstraksi Dokumen</h1>
                <p class="text-sm text-slate-500 mt-0.5">Upload dan kelola dokumen yang diproses oleh AI.</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                    <circle cx="4" cy="4" r="3"/>
                </svg>
                Queue: Aktif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Komponen Upload --}}
            <livewire:documents.upload-document />

            {{-- Komponen Daftar Dokumen --}}
            <livewire:documents.document-list />
        </div>
    </div>
</x-app-layout>
