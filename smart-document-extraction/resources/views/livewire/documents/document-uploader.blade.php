<div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <div class="mb-6">
        <h3 class="text-xl font-bold text-slate-800">Unggah Dokumen Baru</h3>
        <p class="text-sm text-slate-500 mt-1">Pilih file struk atau invoice untuk diekstraksi menggunakan AI.</p>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center gap-3">
            <svg class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium text-sm">{{ session('message') }}</span>
        </div>
    @endif

    <div>
        <div class="mb-6">
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-indigo-400 hover:bg-slate-50 transition-colors relative">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-slate-600 justify-center">
                        <label for="file" class="relative cursor-pointer bg-transparent rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Upload a file</span>
                            <input type="file" id="file" wire:model="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-500">
                        PNG, JPG, PDF up to 10MB
                    </p>
                </div>
            </div>
            
            <div wire:loading wire:target="file" class="mt-2 text-sm text-indigo-600 font-medium flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                Menyiapkan file...
            </div>

            @if($file)
                <div class="mt-3 p-3 bg-indigo-50 rounded-lg flex items-center justify-between border border-indigo-100">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="bg-indigo-100 p-2 rounded-md">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <span class="text-sm font-medium text-indigo-900 truncate">{{ $file->getClientOriginalName() }}</span>
                    </div>
                </div>
            @endif

            @error('file') <span class="text-sm font-medium text-red-600 mt-2 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-end border-t border-slate-100 pt-5">
            <button type="button" 
                wire:click="saveDocument"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-slate-900 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-slate-800 focus:bg-slate-800 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition ease-in-out shadow-sm"
                wire:loading.attr="disabled"
                wire:target="saveDocument, file">
                <span wire:loading.remove wire:target="saveDocument, file" class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Proses Dokumen
                </span>
                <span wire:loading wire:target="saveDocument, file" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    Sedang Memproses...
                </span>
            </button>
        </div>
    </div>
</div>
