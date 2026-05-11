<?php

namespace App\Providers;

use App\Contracts\DocumentExtractorInterface;
use App\Services\OpenAIExtractorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Mengikat interface ke implementasinya
        // Jika suatu saat ingin ganti AI provider, cukup ubah di sini
        $this->app->bind(
            DocumentExtractorInterface::class,
            OpenAIExtractorService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
