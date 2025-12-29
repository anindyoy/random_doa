<?php

namespace App\Livewire;

use App\Models\Doa;
use App\Models\Tag;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RandomDoa extends Component
{
    public $doa;
    public $tags;
    public $history = [];
    public $historyIndex = -1;
    public $isLoved = false;
    public $historyDetails = [];

    public function mount()
    {
        $this->loadInitialDoa();
        $this->tags = Tag::all();
        if ($this->doa) {
            $this->updateHistoryDetails($this->doa);
            $this->checkIfLoved();
        }
    }

    public function toggleLove()
    {
        if (!Auth::check()) {
            $this->dispatch('toggle-login-modal'); // Sesuaikan dengan nama event di auth.blade.php
            return;
        }

        $userId = Auth::id();
        $doaId = $this->doa->id;

        $existing = \DB::table('doa_user')
            ->where('user_id', $userId)
            ->where('doa_id', $doaId)
            ->first();

        if ($existing && $existing->love == 1) {
            // Jika sudah love, maka unlove (hapus atau set 0)
            \DB::table('doa_user')
                ->where('user_id', $userId)
                ->where('doa_id', $doaId)
                ->update(['love' => 0, 'updated_at' => now()]);
            $this->isLoved = false;
        } else {
            // Jika belum ada atau love = 0, maka set love = 1
            \DB::table('doa_user')->updateOrInsert(
                ['user_id' => $userId, 'doa_id' => $doaId],
                ['love' => 1]
            );
            $this->isLoved = true;
        }
    }

    public function getFavoriteListProperty()
    {
        return Auth::check() ? Auth::user()->favoriteDoas()->get() : [];
    }

    // Method untuk mengecek status love dari database
    public function checkIfLoved()
    {
        if (Auth::check() && $this->doa) {
            // Cek apakah ada record di doa_user untuk user ini, doa ini, dan love = 1
            $this->isLoved = \DB::table('doa_user')
                ->where('user_id', Auth::id())
                ->where('doa_id', $this->doa->id)
                ->where('love', 1)
                ->exists();
        } else {
            $this->isLoved = false;
        }
    }

    // Update status love setiap kali doa berubah
    public function updatedDoa()
    {
        if (Auth::check() && $this->doa) {
            $this->isLoved = Auth::user()->favoriteDoas()
                ->where('doa_id', $this->doa->id)
                ->exists();
        } else {
            $this->isLoved = false;
        }
    }

    private function loadInitialDoa()
    {
        $this->doa = Doa::with('tags')->inRandomOrder()->first();

        if ($this->doa) {
            $this->history[] = $this->doa->id;
            $this->historyIndex = 0;
        }
    }

    private function updateHistoryDetails($doa)
    {
        // Cek apakah doa sudah ada di historyDetails
        $exists = false;
        foreach ($this->historyDetails as $item) {
            if ($item['id'] === $doa->id) {
                $exists = true;
                break;
            }
        }

        // Hanya tambahkan jika belum ada
        if (!$exists) {
            array_unshift($this->historyDetails, [
                'id' => $doa->id,
                'judul' => $doa->judul,
                'gambar' => $doa->gambar ?? 'https://via.placeholder.com/150',
            ]);

            // Batasi maksimal 10 data
            if (count($this->historyDetails) > 10) {
                array_pop($this->historyDetails);
            }
        }
    }

    public function loadRandomDoa()
    {
        $excludeIds = array_slice($this->history, max(0, $this->historyIndex - 4), 5);
        $query = Doa::with('tags');
        $totalDoa = Doa::count();

        if ($totalDoa > 10) {
            $query->whereNotIn('id', $excludeIds);
        } else {
            $query->where('id', '!=', $this->doa->id);
        }

        $newDoa = $query->inRandomOrder()->first();

        if (!$newDoa) {
            $newDoa = Doa::with('tags')->where('id', '!=', $this->doa->id)->inRandomOrder()->first();
        }

        if (!$newDoa) return;

        if ($this->historyIndex < count($this->history) - 1) {
            $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
        }

        $this->doa = $newDoa;
        $this->history[] = $this->doa->id;
        $this->historyIndex = count($this->history) - 1;
        $this->updateHistoryDetails($newDoa);

        if (count($this->history) > 25) {
            array_shift($this->history);
            $this->historyIndex--;
        }

        $this->checkIfLoved();
    }

    public function loadPreviousDoa()
    {
        if ($this->historyIndex > 0) {
            $this->historyIndex--;
            $previousDoaId = $this->history[$this->historyIndex];
            $this->doa = Doa::with('tags')->find($previousDoaId);
            $this->checkIfLoved();
        }
    }

    public function loadDoaFromHistory($doaId)
    {
        // Cari index doa di history
        $index = array_search($doaId, $this->history);

        if ($index !== false) {
            $this->historyIndex = $index;
            $this->doa = Doa::with('tags')->find($doaId);
        } else {
            // Jika tidak ada di history, tambahkan ke history
            $doa = Doa::with('tags')->find($doaId);
            if ($doa) {
                if ($this->historyIndex < count($this->history) - 1) {
                    $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
                }

                $this->doa = $doa;
                $this->history[] = $doa->id;
                $this->historyIndex = count($this->history) - 1;
            }
        }

        $this->checkIfLoved();
    }
}
