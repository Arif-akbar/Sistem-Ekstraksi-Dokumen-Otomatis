<div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Unggah Dokumen Baru</h3>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-md">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit="upload">
        <div class="mb-4">
            <label for="file" class="block text-sm font-medium text-gray-700 mb-1">Pilih File (PDF, JPG, PNG)</label>
            <input type="file" id="file" wire:model="file" accept=".pdf,.jpg,.jpeg,.png"
                class="block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-indigo-50 file:text-indigo-700
                hover:file:bg-indigo-100 border border-gray-300 rounded-md shadow-sm">
            
            @error('file') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center">
            <button type="submit" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                wire:loading.attr="disabled"
                wire:target="upload">
                <span wire:loading.remove wire:target="upload">Unggah Dokumen</span>
                <span wire:loading wire:target="upload">Mengunggah...</span>
            </button>
        </div>
    </form>
</div>
