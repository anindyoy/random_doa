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
        Schema::create('doa', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('gambar')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('sumber_desain')->nullable();
            $table->text('riwayat')->nullable();
            $table->boolean('untuk_pribadi')->default(false);
            $table->foreignId('user_id');
            $table->boolean('visibility')->default(true);
            $table->string('ajuan', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doa');
    }
};
