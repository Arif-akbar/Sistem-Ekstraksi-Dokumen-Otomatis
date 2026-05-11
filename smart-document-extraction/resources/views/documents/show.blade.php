<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('documents.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                Validasi Dokumen: {{ $document->file_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Jika dokumen masih pending atau processing --}}
            @if(in_array($document->status, ['pending', 'processing']))
                <div class="bg-blue-50 border border-blue-200 text-blue-800 rounded-lg p-6 flex items-center justify-center space-x-4 mb-6">
                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <div>
                        <h4 class="text-lg font-bold">Sedang Diproses AI</h4>
                        <p>Dokumen Anda sedang dianalisis oleh sistem. Silakan muat ulang halaman ini dalam beberapa saat.</p>
                    </div>
                </div>
            @endif

            {{-- Split Screen Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                {{-- Bagian Kiri: Penampil Dokumen (PDF/JPG/PNG) --}}
                <div class="bg-gray-100 rounded-xl overflow-hidden shadow-inner border border-gray-300 min-h-[600px] flex items-center justify-center relative">
                    
                    {{-- Placeholder rute file, akan kita setup routingnya di tahap selanjutnya --}}
                    @php
                        $fileExt = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                        // Placeholder URL - pastikan kita membuat route ini di langkah berikutnya
                        $fileUrl = route('documents.file', $document->id); 
                    @endphp

                    @if(in_array($fileExt, ['jpg', 'jpeg', 'png']))
                        <img src="{{ $fileUrl }}" alt="{{ $document->file_name }}" class="max-w-full max-h-[800px] object-contain">
                    @elseif($fileExt === 'pdf')
                        <iframe src="{{ $fileUrl }}#toolbar=0" class="w-full h-[800px] border-0" title="{{ $document->file_name }}"></iframe>
                    @else
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="mt-2 text-sm font-medium">Format file tidak dapat dipratinjau langsung.</p>
                            <a href="{{ $fileUrl }}" target="_blank" class="mt-3 inline-block text-indigo-600 hover:text-indigo-900 underline">Unduh File</a>
                        </div>
                    @endif
                </div>

                {{-- Bagian Kanan: Form Validasi Livewire --}}
                <div>
                    {{-- Jika AI gagal memproses --}}
                    @if($document->status === 'failed')
                        <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-6 mb-6">
                            <h4 class="text-lg font-bold">Gagal Diproses</h4>
                            <p>Sistem AI kami gagal mengekstrak data dari dokumen ini. Anda dapat mengisinya secara manual di bawah ini.</p>
                        </div>
                    @endif

                    <livewire:documents.document-validator :document="$document" />
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
