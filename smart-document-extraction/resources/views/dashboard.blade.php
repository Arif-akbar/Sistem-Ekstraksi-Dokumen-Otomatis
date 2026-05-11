<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-6">{{ __("You're logged in!") }}</p>
                    
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Sistem Ekstraksi Dokumen Pintar</h3>
                            <p class="text-sm text-slate-600 mt-1">Upload struk atau invoice Anda, dan biarkan AI mengekstrak data penting secara otomatis.</p>
                        </div>
                        <a href="{{ route('documents.index') }}" wire:navigate
                           class="whitespace-nowrap inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                            Mulai Ekstraksi
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
