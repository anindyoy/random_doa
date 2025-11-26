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
        Schema::create('doas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_doa');
            $table->string('gambar')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('riwayat')->nullable();
            $table->boolean('untuk_pribadi')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('visibility')->default(false);
            $table->boolean('ajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doas');
    }
};