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
        $newDoa = Doa::with('tags')->inRandomOrder()->first();

        if (!$newDoa) {
            return;
        }

        if ($this->historyIndex < count($this->history) - 1) {
            $this->history = array_slice($this->history, 0, $this->historyIndex + 1);
        }

        $this->doa = $newDoa;

        $this->history[] = $this->doa->id;
        $this->historyIndex = count($this->history) - 1;
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
