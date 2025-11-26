<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi'];

    public function doa(): BelongsToMany
    {
        return $this->belongsToMany(Doa::class);
    }
}