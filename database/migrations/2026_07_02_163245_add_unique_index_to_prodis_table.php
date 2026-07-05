<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->unique(['fakultas_id', 'nama_prodi'], 'prodis_fakultas_nama_unique');
        });
    }

    public function down(): void
    {
        Schema::table('prodis', function (Blueprint $table) {
            $table->dropUnique('prodis_fakultas_nama_unique');
        });
    }
};
