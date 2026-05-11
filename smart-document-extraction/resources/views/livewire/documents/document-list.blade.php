<div class="space-y-4">
    {{-- Header + Filter --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Riwayat Dokumen</h2>
                <p class="text-sm text-slate-500">Semua dokumen yang Anda upload dan hasilnya.</p>
            </div>

            <div class="flex gap-2 flex-col sm:flex-row">
                {{-- Search --}}
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           placeholder="Cari nama file..."
                           class="w-full rounded-lg border border-slate-200 bg-slate-50 py-2 pl-9 pr-4 text-sm text-slate-700 placeholder-slate-400 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100 sm:w-56">
                </div>

                {{-- Filter Status --}}
                <select wire:model.live="statusFilter"
                        class="rounded-lg border border-slate-200 bg-slate-50 py-2 pl-3 pr-8 text-sm text-slate-700 focus:border-blue-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Diproses</option>
                    <option value="needs_validation">Perlu Validasi</option>
                    <option value="completed">Selesai</option>
                    <option value="failed">Gagal</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Nama File</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Status</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Vendor</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Tanggal Transaksi</th>
                        <th class="px-5 py-3 text-right font-semibold text-slate-500 uppercase tracking-wide text-xs">Total</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Diunggah</th>
                        <th class="px-5 py-3 text-left font-semibold text-slate-500 uppercase tracking-wide text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($documents as $document)
                        <tr class="hover:bg-slate-50 transition-colors duration-100">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    @php
                                        $ext = strtolower(pathinfo($document->file_name, PATHINFO_EXTENSION));
                                        $iconColor = match($ext) {
                                            'pdf'  => 'text-red-500 bg-red-50',
                                            'jpg', 'jpeg', 'png' => 'text-purple-500 bg-purple-50',
                                            default => 'text-slate-500 bg-slate-100',
                                        };
                                    @endphp
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $iconColor }}">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <span class="font-medium text-slate-700 truncate max-w-[180px]" title="{{ $document->file_name }}">
                                        {{ $document->file_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusConfig = [
                                        'pending'          => ['label' => 'Pending',    'class' => 'bg-slate-100 text-slate-600'],
                                        'processing'       => ['label' => 'Diproses',   'class' => 'bg-blue-100 text-blue-700'],
                                        'needs_validation' => ['label' => 'Validasi',   'class' => 'bg-amber-100 text-amber-700'],
                                        'completed'        => ['label' => 'Selesai',    'class' => 'bg-green-100 text-green-700'],
                                        'failed'           => ['label' => 'Gagal',      'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $cfg = $statusConfig[$document->status] ?? ['label' => $document->status, 'class' => 'bg-slate-100 text-slate-600'];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $cfg['class'] }}">
                                    @if ($document->status === 'processing')
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    @else
                                        <span class="h-1.5 w-1.5 rounded-full bg-current opacity-70"></span>
                                    @endif
                                    {{ $cfg['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $document->extractedData?->vendor_name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-slate-600">
                                {{ $document->extractedData?->transaction_date
                                    ? \Carbon\Carbon::parse($document->extractedData->transaction_date)->format('d M Y')
                                    : '—' }}
                            </td>
                            <td class="px-5 py-4 text-right font-medium text-slate-700">
                                {{ $document->extractedData?->total_amount
                                    ? 'Rp ' . number_format($document->extractedData->total_amount, 0, ',', '.')
                                    : '—' }}
                            </td>
                            <td class="px-5 py-4 text-slate-500 text-xs">
                                {{ $document->created_at->diffForHumans() }}
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('documents.show', $document) }}"
                                   class="inline-flex items-center gap-1 rounded-md px-3 py-1.5 text-xs font-medium text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-14 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100">
                                        <svg class="h-7 w-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Belum ada dokumen</p>
                                    <p class="text-xs text-slate-400">Upload dokumen pertama Anda di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($documents->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</div>
