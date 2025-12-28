<?php

namespace App\Livewire;

use App\Models\Doa;
use App\Models\Tag;
use Livewire\Component;

class RandomDoa extends Component
{
    public $doa;
    public $tags;
    public $history = [];
    public $historyIndex = -1;

    public function mount()
    {
        $this->loadInitialDoa();
        $this->tags = Tag::all();
    }

    private function loadInitialDoa()
    {
        $this->doa = Doa::with('tags')->inRandomOrder()->first();

        if ($this->doa) {
            $this->history[] = $this->doa->id;
            $this->historyIndex = 0;
        }
    }

    public function loadRandomDoa()
    {
        // 1. Ambil ID dari 5 riwayat terakhir agar tidak duplikat
        // Kita ambil dari history berdasarkan historyIndex saat ini
        $excludeIds = array_slice($this->history, max(0, $this->historyIndex - 4), 5);

        $query = Doa::with('tags');

        // 2. Cek jumlah total doa di database
        $totalDoa = Doa::count();

        // 3. Jika record > 10, terapkan filter 'whereNotIn'
        // Jika record <= 10, kita hanya exclude ID yang sedang tampil (agar tidak muncul 2x berturut-turut)
        if ($totalDoa > 10) {
            $query->whereNotIn('id', $excludeIds);
        } else {
            $query->where('id', '!=', $this->doa->id);
        }

        $newDoa = $query->inRandomOrder()->first();

        if (!$newDoa) {
            // Fallback jika karena satu dan lain hal query tidak menghasilkan data
            $newDoa = Doa::with('tags')->where('id', '!=', $this->doa->id)->inRandomOrder()->first();
        }

        if (!$newDoa) return;

        // Logika manajemen history (menghapus forward history jika kita sedang di posisi 'previous')
        if ($this->historyIndex < count($this->history) - 1) {
            $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
        }

        $this->doa = $newDoa;
        $this->history[] = $this->doa->id;
        $this->historyIndex = count($this->history) - 1;

        // Opsional: Batasi ukuran array history agar tidak terlalu besar di session/memory
        if (count($this->history) > 25) {
            array_shift($this->history);
            $this->historyIndex--;
        }
    }

    public function loadPreviousDoa()
    {
        if ($this->historyIndex > 0) {
            $this->historyIndex--;
            $previousDoaId = $this->history[$this->historyIndex];
            $this->doa = Doa::with('tags')->find($previousDoaId);
        }
    }
}