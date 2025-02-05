<?php

namespace App\Models;

use App\Models\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use HasComments;

    protected $fillable = ['title', 'content'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($note) {
            $note->comments()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
