<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doa extends Model
{
    use HasFactory;

    protected $table = 'doa';

    protected $fillable = [
        'judul',
        'gambar',
        'keterangan',
        'riwayat',
        'untuk_pribadi',
        'user_id',
        'visibility',
        'ajuan',
    ];

    protected $casts = [
        'untuk_pribadi' => 'boolean',
        'visibility' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('visibility', 'love');
    }
}