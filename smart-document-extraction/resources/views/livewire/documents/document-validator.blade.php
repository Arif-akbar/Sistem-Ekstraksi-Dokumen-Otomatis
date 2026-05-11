<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-medium text-gray-900">Validasi Hasil Ekstraksi</h3>
        
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ 
            $document->status === 'completed' ? 'bg-green-100 text-green-800' : 
            ($document->status === 'needs_validation' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') 
        }}">
            {{ strtoupper(str_replace('_', ' ', $document->status)) }}
        </span>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="save">
        <div class="space-y-4">
            {{-- Vendor Name --}}
            <div>
                <label for="vendor_name" class="block text-sm font-medium text-gray-700">Nama Vendor / Toko</label>
                <input type="text" wire:model="vendor_name" id="vendor_name" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('vendor_name') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Transaction Date --}}
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
                <input type="date" wire:model="transaction_date" id="transaction_date" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                @error('transaction_date') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Total Amount --}}
            <div>
                <label for="total_amount" class="block text-sm font-medium text-gray-700">Total Nominal (Rp)</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" wire:model="total_amount" id="total_amount" step="0.01"
                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                @error('total_amount') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Tax Amount --}}
            <div>
                <label for="tax_amount" class="block text-sm font-medium text-gray-700">Nominal Pajak (Rp)</label>
                <div class="relative mt-1 rounded-md shadow-sm">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <span class="text-gray-500 sm:text-sm">Rp</span>
                    </div>
                    <input type="number" wire:model="tax_amount" id="tax_amount" step="0.01"
                        class="block w-full rounded-md border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                @error('tax_amount') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                wire:loading.attr="disabled"
                wire:target="save">
                <span wire:loading.remove wire:target="save">
                    {{ $document->status === 'completed' ? 'Simpan Perubahan' : 'Validasi & Simpan' }}
                </span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
    
    {{-- Raw API Response Display (Opsional untuk Debug/Referensi User) --}}
    @if($document->extractedData && $document->extractedData->raw_api_response)
    <div class="mt-8 pt-6 border-t border-gray-200">
        <details class="text-sm">
            <summary class="cursor-pointer font-medium text-gray-600 hover:text-gray-900">Lihat Respons API Asli (JSON)</summary>
            <pre class="mt-2 bg-gray-50 p-4 rounded text-xs overflow-x-auto text-gray-700 border border-gray-200">{{ json_encode($document->extractedData->raw_api_response, JSON_PRETTY_PRINT) }}</pre>
        </details>
    </div>
    @endif
</div>
