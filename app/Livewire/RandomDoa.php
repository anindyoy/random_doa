<?php

namespace App\Livewire;

use App\Models\Doa;
use App\Models\Tag;
use Livewire\Component;

class RandomDoa extends Component
{
    public $doa;
    public $tags; // Untuk menampilkan daftar tag di view, jika diperlukan

    // Metode yang dijalankan saat komponen di-load
    public function mount()
    {
        $this->loadRandomDoa();
        $this->tags = Tag::all(); // Ambil semua tag untuk ditampilkan
    }

    // Metode untuk memuat doa acak
    public function loadRandomDoa()
    {
        // Logika yang mirip dengan DoaController@index atau DoaController@random
        // Jika Anda ingin hanya menampilkan doa yang tidak 'untuk_pribadi' (seperti di random()),
        // Anda bisa menyesuaikan query-nya.
        // Berdasarkan web.php, index() di DoaController hanya mengambil 1 doa acak.

        $this->doa = Doa::inRandomOrder()->first();
    }

    // Metode yang dibutuhkan Livewire untuk merender view
    public function render()
    {
        // Data $doa dan $tags sudah tersedia melalui properti public
        return view('livewire.random-doa');
    }
}