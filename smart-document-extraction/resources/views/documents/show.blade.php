<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('documents.index') }}"
               class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 hover:text-slate-700 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Detail Dokumen</h1>
                <p class="text-sm text-slate-500 mt-0.5 truncate max-w-xs">{{ $document->file_name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-5">

            {{-- Status Card --}}
            @php
                $statusConfig = [
                    'pending'          => ['label' => 'Menunggu Diproses', 'class' => 'bg-slate-50 border-slate-200', 'badge' => 'bg-slate-100 text-slate-600', 'icon' => '⏳'],
                    'processing'       => ['label' => 'Sedang Diproses AI', 'class' => 'bg-blue-50 border-blue-200', 'badge' => 'bg-blue-100 text-blue-700', 'icon' => '🔄'],
                    'needs_validation' => ['label' => 'Menunggu Validasi Anda', 'class' => 'bg-amber-50 border-amber-200', 'badge' => 'bg-amber-100 text-amber-700', 'icon' => '✅'],
                    'completed'        => ['label' => 'Selesai & Divalidasi', 'class' => 'bg-green-50 border-green-200', 'badge' => 'bg-green-100 text-green-700', 'icon' => '🎉'],
                    'failed'           => ['label' => 'Gagal Diproses', 'class' => 'bg-red-50 border-red-200', 'badge' => 'bg-red-100 text-red-700', 'icon' => '❌'],
                ];
                $cfg = $statusConfig[$document->status] ?? $statusConfig['pending'];
            @endphp

            <div class="rounded-2xl border p-5 {{ $cfg['class'] }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">{{ $cfg['icon'] }}</span>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $cfg['label'] }}</p>
                            <p class="text-sm text-slate-500">Upload: {{ $document->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $cfg['badge'] }}">
                        {{ $document->status }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                {{-- Informasi Dokumen --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-sm font-semibold text-slate-500 uppercase tracking-wide">Informasi Dokumen</h2>
                    <dl class="space-y-3">
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <dt class="text-sm text-slate-500">Nama File</dt>
                            <dd class="text-sm font-medium text-slate-800 truncate max-w-[200px]" title="{{ $document->file_name }}">{{ $document->file_name }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <dt class="text-sm text-slate-500">ID Dokumen</dt>
                            <dd class="font-mono text-xs text-slate-600 bg-slate-100 px-2 py-0.5 rounded">{{ $document->id }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <dt class="text-sm text-slate-500">Diunggah Oleh</dt>
                            <dd class="text-sm font-medium text-slate-800">{{ $document->user->name }}</dd>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <dt class="text-sm text-slate-500">Waktu Upload</dt>
                            <dd class="text-sm text-slate-800">{{ $document->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Hasil Ekstraksi AI --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wide">Hasil Ekstraksi AI</h2>
                        @if ($document->extractedData && $document->status === 'needs_validation')
                            <form method="POST" action="{{ route('documents.validate', $document) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-green-700 transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                    Konfirmasi Data
                                </button>
                            </form>
                        @endif
                    </div>

                    @if ($document->extractedData)
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <dt class="text-sm text-slate-500">Nama Vendor</dt>
                                <dd class="text-sm font-medium text-slate-800">{{ $document->extractedData->vendor_name ?? '—' }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <dt class="text-sm text-slate-500">Tanggal Transaksi</dt>
                                <dd class="text-sm font-medium text-slate-800">
                                    {{ $document->extractedData->transaction_date
                                        ? \Carbon\Carbon::parse($document->extractedData->transaction_date)->format('d M Y')
                                        : '—' }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                                <dt class="text-sm text-slate-500">Total Amount</dt>
                                <dd class="text-sm font-bold text-slate-800">
                                    {{ $document->extractedData->total_amount
                                        ? 'Rp ' . number_format($document->extractedData->total_amount, 0, ',', '.')
                                        : '—' }}
                                </dd>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <dt class="text-sm text-slate-500">Tax Amount</dt>
                                <dd class="text-sm font-medium text-slate-800">
                                    {{ $document->extractedData->tax_amount
                                        ? 'Rp ' . number_format($document->extractedData->tax_amount, 0, ',', '.')
                                        : '—' }}
                                </dd>
                            </div>
                        </dl>
                    @elseif ($document->status === 'pending' || $document->status === 'processing')
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="mb-3 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-600">AI sedang memproses dokumen...</p>
                            <p class="text-xs text-slate-400 mt-1">Refresh halaman untuk melihat hasilnya.</p>
                        </div>
                    @elseif ($document->status === 'failed')
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="mb-3 h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-red-600">Gagal mengekstrak data</p>
                            <p class="text-xs text-slate-400 mt-1">Cek log aplikasi untuk detail error.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Raw API Response (Collapsible) --}}
            @if ($document->extractedData?->raw_api_response)
                <div x-data="{ open: false }" class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                    <button @click="open = !open"
                            class="flex w-full items-center justify-between px-6 py-4 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
                            </svg>
                            Raw API Response (OpenAI)
                        </span>
                        <svg class="h-4 w-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="border-t border-slate-100">
                        <pre class="overflow-x-auto bg-slate-900 p-5 text-xs text-green-400 leading-relaxed">{{ json_encode($document->extractedData->raw_api_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
