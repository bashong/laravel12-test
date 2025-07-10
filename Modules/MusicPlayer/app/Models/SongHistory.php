<?php

namespace Modules\MusicPlayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SongHistory extends Model
{
    protected $fillable = ['song_id', 'user_id'];

    public function song(): BelongsTo
    {
        return $this->belongsTo(Song::class);
    }
}
