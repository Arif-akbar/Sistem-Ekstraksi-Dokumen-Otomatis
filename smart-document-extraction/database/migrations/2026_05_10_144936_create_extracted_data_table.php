<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extracted_data', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke UUID dari tabel documents
            $table->foreignUuid('document_id')->constrained()->cascadeOnDelete(); 
            $table->string('vendor_name')->nullable();
            $table->date('transaction_date')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->json('raw_api_response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extracted_data');
    }
};
