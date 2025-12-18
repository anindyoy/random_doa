<?php

use App\Livewire\RandomDoa;
use App\Models\Doa;
use App\Models\Tag;
use Livewire\Livewire;

beforeEach(function () {
    // Setup data untuk setiap test
    $this->tags = Tag::factory()->count(3)->create();
    $this->doas = Doa::factory()->count(5)->create();

    // Attach tags ke doa
    $this->doas->each(function ($doa) {
        $doa->tags()->attach($this->tags->random(2));
    });
});

test('component can be rendered', function () {
    Livewire::test(RandomDoa::class)
        ->assertStatus(200);
});

test('component loads initial doa on mount', function () {
    Livewire::test(RandomDoa::class)
        ->assertSet('doa', fn($doa) => $doa !== null)
        ->assertSet('historyIndex', 0)
        ->assertSet('history', fn($history) => count($history) === 1);
});

test('component loads all tags on mount', function () {
    Livewire::test(RandomDoa::class)
        ->assertSet('tags', fn($tags) => $tags->count() === 3);
});

test('initial doa is added to history', function () {
    $component = Livewire::test(RandomDoa::class);

    $doaId = $component->get('doa')->id;
    $history = $component->get('history');

    expect($history)->toContain($doaId);
});

test('load random doa changes current doa', function () {
    $component = Livewire::test(RandomDoa::class);

    $initialDoaId = $component->get('doa')->id;

    $component->call('loadRandomDoa');

    $newDoaId = $component->get('doa')->id;

    // Bisa jadi sama (random), tapi history harus bertambah
    $history = $component->get('history');
    expect($history)->toHaveCount(2);
});

test('load random doa adds to history', function () {
    Livewire::test(RandomDoa::class)
        ->call('loadRandomDoa')
        ->assertSet('history', fn($history) => count($history) === 2)
        ->assertSet('historyIndex', 1);
});

test('load random doa with tags relationship', function () {
    $component = Livewire::test(RandomDoa::class)
        ->call('loadRandomDoa');

    $doa = $component->get('doa');

    expect($doa->relationLoaded('tags'))->toBeTrue();
});

test('load random doa clears forward history', function () {
    $component = Livewire::test(RandomDoa::class);

    // Load beberapa doa
    $component->call('loadRandomDoa');
    $component->call('loadRandomDoa');
    $component->call('loadRandomDoa');

    // Kembali ke belakang 2 kali
    $component->call('loadPreviousDoa');
    $component->call('loadPreviousDoa');

    $component->assertSet('historyIndex', 1);

    // Load random doa baru (harus clear forward history)
    $component->call('loadRandomDoa');

    $history = $component->get('history');

    // History harus hanya sampai index 1 + doa baru
    expect($history)->toHaveCount(3);
    $component->assertSet('historyIndex', 2);
});

test('load previous doa goes back in history', function () {
    $component = Livewire::test(RandomDoa::class);

    // Load beberapa doa baru
    $component->call('loadRandomDoa');
    $component->call('loadRandomDoa');

    $secondDoaId = $component->get('history')[1];

    // Kembali ke doa sebelumnya
    $component->call('loadPreviousDoa');

    $component->assertSet('historyIndex', 1);
    $component->assertSet('doa', fn($doa) => $doa->id === $secondDoaId);
});

test('load previous doa does not go beyond first doa', function () {
    $component = Livewire::test(RandomDoa::class);

    $initialDoaId = $component->get('doa')->id;

    // Coba mundur padahal sudah di index 0
    $component->call('loadPreviousDoa');

    $component->assertSet('historyIndex', 0);
    $component->assertSet('doa', fn($doa) => $doa->id === $initialDoaId);
});

test('can navigate back and forth in history', function () {
    $component = Livewire::test(RandomDoa::class);

    $firstDoaId = $component->get('doa')->id;

    // Load 3 doa (cek apakah berbeda dari first)
    $component->call('loadRandomDoa');
    $component->call('loadRandomDoa');

    $history = $component->get('history');

    // Pastikan ada 3 item di history
    expect($history)->toHaveCount(3);

    // Mundur 2 kali ke doa pertama
    $component->call('loadPreviousDoa');
    $component->call('loadPreviousDoa');

    $component->assertSet('historyIndex', 0);
    $component->assertSet('doa', fn($doa) => $doa->id === $firstDoaId);

    // Test bahwa history tetap ada 3 item
    $history = $component->get('history');
    expect($history)->toHaveCount(3);
    expect($history[0])->toBe($firstDoaId);
});

test('history maintains correct order', function () {
    $component = Livewire::test(RandomDoa::class);

    $firstId = $component->get('doa')->id;

    $component->call('loadRandomDoa');
    $secondId = $component->get('doa')->id;

    $component->call('loadRandomDoa');
    $thirdId = $component->get('doa')->id;

    $history = $component->get('history');

    expect($history[0])->toBe($firstId);
    expect($history[1])->toBe($secondId);
    expect($history[2])->toBe($thirdId);
});

test('component handles empty doa table gracefully', function () {
    // Hapus semua doa
    Doa::query()->delete();

    Livewire::test(RandomDoa::class)
        ->assertSet('doa', null)
        ->assertSet('historyIndex', -1)
        ->assertSet('history', []);
});

test('load random doa does nothing when no doa exists', function () {
    Doa::query()->delete();

    Livewire::test(RandomDoa::class)
        ->call('loadRandomDoa')
        ->assertSet('doa', null)
        ->assertSet('history', []);
});

test('doa has tags relationship loaded', function () {
    $component = Livewire::test(RandomDoa::class);

    $doa = $component->get('doa');

    if ($doa) {
        expect($doa->relationLoaded('tags'))->toBeTrue();
        expect($doa->tags)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
    }
});

test('history index is updated correctly after multiple operations', function () {
    $component = Livewire::test(RandomDoa::class);

    // Index awal: 0
    $component->assertSet('historyIndex', 0);

    // Load random: index jadi 1
    $component->call('loadRandomDoa');
    $component->assertSet('historyIndex', 1);

    // Load random lagi: index jadi 2
    $component->call('loadRandomDoa');
    $component->assertSet('historyIndex', 2);

    // Mundur: index jadi 1
    $component->call('loadPreviousDoa');
    $component->assertSet('historyIndex', 1);

    // Mundur lagi: index jadi 0
    $component->call('loadPreviousDoa');
    $component->assertSet('historyIndex', 0);
});