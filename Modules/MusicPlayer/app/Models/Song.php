<?php

namespace Modules\MusicPlayer\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\MusicPlayer\Database\Factories\SongFactory;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'artist'];

    protected static function newFactory(): SongFactory
    {
        return SongFactory::new();
    }

    public function songHistories(): HasMany
    {
        return $this->hasMany(SongHistory::class);
    }

    public function addHistory(int $userId)
    {
        return $this->songHistories()->firstOrCreate([
            'song_id' => $this->id,
            'user_id' => $userId,
        ]);
    }

    public function deleteHistory(int $userId)
    {
        return $this->songHistories()->where('user_id', $userId)->delete();
    }

    public function hasBeenPlayedBy(int $userId): bool
    {
        return $this->songHistories()
            ->where('user_id', $userId)
            ->exists();
    }

    // Get next song NOT in history for this user
    public static function nextSong(int $userId): ?self
    {
        return static::whereDoesntHave('songHistories', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->orderBy('id')->first();
    }

    // Get previous song (most recently played)
    public static function previousSong(int $userId): ?self
    {
        $history = SongHistory::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->skip(1) // skip the current song
            ->first();

        return $history ? $history->song : null;
    }

    // Shuffle: get random song NOT yet played by user
    public static function shuffleSong(int $userId): ?self
    {
        return static::whereDoesntHave('songHistories', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->inRandomOrder()->first();
    }
}
