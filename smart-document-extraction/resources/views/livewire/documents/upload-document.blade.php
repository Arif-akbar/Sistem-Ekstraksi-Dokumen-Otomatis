<div class="space-y-4">
    {{-- Upload Success Alert --}}
    @if ($uploadSuccess)
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 flex items-start gap-3"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <svg class="h-5 w-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-green-800">Upload Berhasil!</p>
                <p class="text-sm text-green-700">Dokumen Anda sedang diproses di background. Status akan diperbarui otomatis.</p>
            </div>
        </div>
    @endif

    {{-- Upload Card --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-1 text-base font-semibold text-slate-800">Upload Dokumen Baru</h2>
        <p class="mb-5 text-sm text-slate-500">Format yang didukung: JPG, PNG, PDF. Ukuran maksimal: 10 MB.</p>

        <form wire:submit="upload">
            {{-- Dropzone Area --}}
            <div x-data="{ isDragging: false }"
                 @dragover.prevent="isDragging = true"
                 @dragleave.prevent="isDragging = false"
                 @drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.fileInput.dispatchEvent(new Event('change'))"
                 :class="isDragging ? 'border-blue-400 bg-blue-50' : 'border-slate-300 bg-slate-50 hover:bg-slate-100'"
                 class="relative cursor-pointer rounded-xl border-2 border-dashed transition-colors duration-200">

                <input type="file"
                       x-ref="fileInput"
                       wire:model="file"
                       accept=".jpg,.jpeg,.png,.pdf"
                       class="absolute inset-0 cursor-pointer opacity-0"
                       id="file-upload">

                <div class="flex flex-col items-center py-10 px-6 text-center pointer-events-none">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-700">Seret & lepas file di sini, atau <span class="text-blue-600">klik untuk memilih</span></p>
                    <p class="mt-1 text-xs text-slate-400">JPG, PNG, PDF hingga 10MB</p>
                </div>
            </div>

            {{-- Preview nama file yang dipilih --}}
            @if ($file)
                <div class="mt-3 flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-2.5">
                    <svg class="h-4 w-4 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    <span class="text-sm text-blue-700 font-medium truncate">{{ $file->getClientOriginalName() }}</span>
                    <span class="ml-auto text-xs text-blue-500">{{ number_format($file->getSize() / 1024, 1) }} KB</span>
                </div>
            @endif

            {{-- Error validasi --}}
            @error('file')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    {{ $message }}
                </p>
            @enderror

            {{-- Tombol Submit --}}
            <div class="mt-4 flex justify-end">
                <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="upload,file"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                    <span wire:loading.remove wire:target="upload">
                        <svg class="h-4 w-4 -ml-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </span>
                    <svg wire:loading wire:target="upload" class="h-4 w-4 -ml-0.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="upload">Proses Dokumen</span>
                    <span wire:loading wire:target="upload">Mengupload...</span>
                </button>
            </div>
        </form>
    </div>
</div>
