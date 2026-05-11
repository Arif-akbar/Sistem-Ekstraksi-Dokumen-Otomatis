<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
        <div>
            <h3 class="text-xl font-bold text-slate-800">Validasi Hasil Ekstraksi</h3>
            <p class="text-sm text-slate-500 mt-1">Periksa dan perbaiki data yang diekstrak oleh AI.</p>
        </div>
        
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ 
            $document->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 
            ($document->status === 'needs_validation' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') 
        }}">
            {{ str_replace('_', ' ', $document->status) }}
        </span>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <form wire:submit="save">
        <div class="space-y-6">
            {{-- Vendor Name --}}
            <div>
                <label for="vendor_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Vendor / Toko</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <input type="text" wire:model="vendor_name" id="vendor_name" 
                        class="block w-full rounded-xl border-slate-300 pl-10 py-2.5 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors">
                </div>
                @error('vendor_name') <span class="text-sm font-medium text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Transaction Date --}}
            <div>
                <label for="transaction_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Transaksi</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="date" wire:model="transaction_date" id="transaction_date" 
                        class="block w-full rounded-xl border-slate-300 pl-10 py-2.5 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors">
                </div>
                @error('transaction_date') <span class="text-sm font-medium text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Amounts Grid --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                {{-- Total Amount --}}
                <div>
                    <label for="total_amount" class="block text-sm font-semibold text-slate-700 mb-1.5">Total Nominal (Rp)</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="font-semibold text-slate-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model="total_amount" id="total_amount" step="0.01"
                            class="block w-full rounded-xl border-slate-300 pl-10 py-2.5 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors font-mono">
                    </div>
                    @error('total_amount') <span class="text-sm font-medium text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Tax Amount --}}
                <div>
                    <label for="tax_amount" class="block text-sm font-semibold text-slate-700 mb-1.5">Nominal Pajak (Rp)</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <span class="font-semibold text-slate-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" wire:model="tax_amount" id="tax_amount" step="0.01"
                            class="block w-full rounded-xl border-slate-300 pl-10 py-2.5 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-slate-50 focus:bg-white transition-colors font-mono">
                    </div>
                    @error('tax_amount') <span class="text-sm font-medium text-red-600 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t border-slate-100">
            <button type="submit" 
                class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                wire:loading.attr="disabled"
                wire:target="save">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    {{ $document->status === 'completed' ? 'Simpan Perubahan' : 'Validasi & Simpan' }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Menyimpan...
                </span>
            </button>
        </div>
    </form>
    
    {{-- Raw API Response Display (Opsional untuk Debug/Referensi User) --}}
    @if($document->extractedData && $document->extractedData->raw_api_response)
    <div class="mt-8 pt-6 border-t border-slate-200">
        <details class="group">
            <summary class="flex cursor-pointer items-center gap-2 font-medium text-slate-500 hover:text-slate-800 transition-colors">
                <svg class="h-5 w-5 transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                Lihat Respons API Asli (JSON)
            </summary>
            <div class="mt-4 bg-slate-900 rounded-xl overflow-hidden shadow-inner">
                <div class="flex items-center px-4 py-2 bg-slate-800 border-b border-slate-700">
                    <span class="text-xs font-mono text-slate-400">response.json</span>
                </div>
                <pre class="p-4 text-xs font-mono text-emerald-400 overflow-x-auto">{{ json_encode($document->extractedData->raw_api_response, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </details>
    </div>
    @endif
</div>
