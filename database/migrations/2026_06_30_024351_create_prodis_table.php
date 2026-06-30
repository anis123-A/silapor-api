<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id();
            // fakultas.id bertipe int unsigned, bukan bigint, jadi harus match manual
            $table->unsignedInteger('fakultas_id');
            $table->foreign('fakultas_id')->references('id')->on('fakultas')->onDelete('cascade');
            $table->string('nama_prodi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};
