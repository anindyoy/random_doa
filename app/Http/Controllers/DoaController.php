<?php

namespace App\Http\Controllers;

use App\Models\Doa;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DoaController extends Controller
{
    public function random(Request $request)
    {
        $tags = $request->input('tags', []); // Ambil array tag dari request
        $doa = Doa::when(!empty($tags), function ($query) use ($tags) {
            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('tags.id', $tags);
            });
        })
        ->where('untuk_pribadi', false)
        ->inRandomOrder()
        ->first();

        return view('doa.show', compact('doa'));
    }

    public function indexByTag(Tag $tag)
    {
        $doas = $tag->doas()->where('untuk_pribadi', false)->get();
        return view('doa.index', compact('doas', 'tag'));
    }

    public function show(Doa $doa)
    {
        // Logika untuk menampilkan detail doa, periksa visibilitas dan kepemilikan
        if ($doa->untuk_pribadi && Auth::id() !== $doa->user_id) {
            abort(403, 'Unauthorized.');
        }

        // Logika untuk menyimpan history doa yang dilihat user (untuk tombol sebelumnya/selanjutnya)
        session()->push('doa_history', $doa->id);

        return view('doa.show', compact('doa'));
    }

    public function previous(Request $request)
    {
        $history = session('doa_history', []);
        $currentDoaId = $request->input('current_doa_id');
        $currentIndex = array_search($currentDoaId, $history);

        if ($currentIndex === false || $currentIndex === 0) {
            // Tidak ada doa sebelumnya
            return redirect()->route('doa.random'); // Atau tampilkan pesan
        }

        $previousDoaId = $history[$currentIndex - 1];
        $doa = Doa::findOrFail($previousDoaId);

        return redirect()->route('doa.show', $doa);
    }

    public function next()
    {
        // Implementasikan logika untuk menampilkan doa berikutnya dari history
    }

    public function manageVisibility(Doa $doa, Request $request)
    {
        // Logika untuk mengelola visibilitas doa oleh user
        $visibility = $request->input('visibility');

        if (Auth::check()) {
            $user = Auth::user();

            // Periksa apakah user memiliki akses ke doa ini
            if ($doa->untuk_pribadi && $user->id !== $doa->user_id) {
                abort(403, 'Unauthorized.');
            }

            // Simpan visibilitas di tabel pivot doa_user
            $user->doas()->syncWithoutDetaching([
                $doa->id => ['visibility' => $visibility]
            ]);

            return back()->with('success', 'Visibility updated.');
        } else {
            return redirect()->route('login')->with('info', 'Please login to manage visibility.');
        }
    }

    public function propose(Doa $doa)
    {
        // Logika untuk mengajukan doa ke admin
    }
}