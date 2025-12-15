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

        if (!$doa) {
            return back()->with('error', 'Doa tidak ditemukan dengan tag tersebut.');
        }

        session()->push('doa_history', $doa->id);

        return view('doa.show', compact('doa'));
    }

    public function indexByTag(Tag $tag)
    {
        $doa = $tag->doa()->where('untuk_pribadi', false)->get();
        return view('doa.index', compact('doa', 'tag'));
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

    public function next(Request $request)
    {
        $tags = $request->input('tags', []);

        $doa = Doa::when(!empty($tags), function ($query) use ($tags) {
            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('tags.id', $tags);
            });
        })
            ->where('untuk_pribadi', false)
            ->inRandomOrder()
            ->first();

        if (!$doa) {
            return back()->with('error', 'Doa tidak ditemukan dengan tag tersebut.');
        }

        session()->push('doa_history', $doa->id);

        return view('doa.show', compact('doa'));
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
            $user->doa()->syncWithoutDetaching([
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

    public function index()
    {
        $doa = Doa::inRandomOrder()->first(); // Fetch all doa
        $tags = Tag::all();
        return view('index', compact('doa', 'tags')); // Create a view in doa/manage/index.blade.php

    }

    public function create()
    {
        $tags = Tag::all();
        return view('doa.manage.create', compact('tags')); // Create a view in doa/manage/create.blade.php
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'keterangan' => 'nullable|string',
            'riwayat' => 'nullable|string',
            'untuk_pribadi' => 'boolean',
            'tag_id' => 'nullable|exists:tags,id',
            'visibility' => 'boolean',
            'ajuan' => 'nullable|string',
        ]);

        $doa = new Doa($request->except('gambar'));
        $doa->user_id = Auth::id();

        // Handle image upload
        if ($request->hasFile('gambar')) {
            $imagePath = $request->file('gambar')->store('doa_images', 'public'); // Store in storage/app/public/doa_images
            $doa->gambar = $imagePath;
        }

        $doa->save();

        return redirect()->route('doa.index')->with('success', 'Doa created successfully.');
    }

    public function edit(Doa $doa)
    {
        $tags = Tag::all();
        return view('doa.manage.edit', compact('doa', 'tags')); // Create a view in doa/manage/edit.blade.php
    }

    public function update(Request $request, Doa $doa)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'keterangan' => 'nullable|string',
            'riwayat' => 'nullable|string',
            'untuk_pribadi' => 'boolean',
            'tag_id' => 'nullable|exists:tags,id',
            'visibility' => 'boolean',
            'ajuan' => 'nullable|string',
        ]);

        $doa->fill($request->except('gambar')); // Use fill instead of direct assignment

        // Handle image upload
        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($doa->gambar) {
                Storage::disk('public')->delete($doa->gambar);
            }

            $imagePath = $request->file('gambar')->store('doa_images', 'public');
            $doa->gambar = $imagePath;
        }

        $doa->save();

        return redirect()->route('doa.index')->with('success', 'Doa updated successfully.');
    }

    public function destroy(Doa $doa)
    {
        // Delete image if exists
        if ($doa->gambar) {
            Storage::disk('public')->delete($doa->gambar);
        }

        $doa->delete();

        return redirect()->route('doa.index')->with('success', 'Doa deleted successfully.');
    }
}
