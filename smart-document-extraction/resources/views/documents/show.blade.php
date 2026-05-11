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
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- Bagian Kiri: Penampil Dokumen (PDF/JPG/PNG) --}}
                <div class="lg:col-span-7 bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-200 min-h-[600px] flex flex-col relative">
                    <div class="bg-slate-50 border-b border-slate-200 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <span class="text-sm font-semibold text-slate-600">Pratinjau Dokumen</span>
                        </div>
                        <a href="{{ route('documents.file', $document->id) }}" target="_blank" class="text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                            Buka di Tab Baru
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-4 bg-slate-100/50">
                        {{-- Placeholder rute file, akan kita setup routingnya di tahap selanjutnya --}}
                        @php
                            $fileExt = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                            $fileUrl = route('documents.file', $document->id); 
                        @endphp

                        @if(in_array($fileExt, ['jpg', 'jpeg', 'png']))
                            <img src="{{ $fileUrl }}" alt="{{ $document->file_name }}" class="max-w-full rounded-lg shadow-sm border border-slate-200 object-contain">
                        @elseif($fileExt === 'pdf')
                            <iframe src="{{ $fileUrl }}#toolbar=0" class="w-full h-[750px] border-0 rounded-lg shadow-sm bg-white" title="{{ $document->file_name }}"></iframe>
                        @else
                            <div class="text-center text-slate-500 bg-white p-8 rounded-xl border border-slate-200 shadow-sm">
                                <svg class="mx-auto h-16 w-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="text-sm font-medium text-slate-800">Format file tidak dapat dipratinjau langsung.</p>
                                <a href="{{ $fileUrl }}" target="_blank" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Unduh File
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bagian Kanan: Form Validasi Livewire --}}
                <div class="lg:col-span-5">
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
