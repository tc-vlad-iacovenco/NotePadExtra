<?php

namespace App\Models;

use App\Models\Traits\HasComments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Insight extends Model
{
    use HasComments;

    protected $fillable = ['title', 'content', 'type'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
