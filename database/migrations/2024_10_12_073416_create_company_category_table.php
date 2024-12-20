<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cate_id')->constrained('categories','id')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies','id')->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_category');
    }
};
