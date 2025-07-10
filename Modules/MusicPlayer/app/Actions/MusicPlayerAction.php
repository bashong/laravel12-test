<?php

namespace Modules\MusicPlayer\Actions;

use Modules\MusicPlayer\Interfaces\MusicPlayerInterface;
use Modules\MusicPlayer\Models\Song;
use Modules\MusicPlayer\Models\SongHistory;

class MusicPlayerAction implements MusicPlayerInterface
{
    protected int $userId;

    public function __construct()
    {
        $this->userId = auth()->id();
    }

    public function play(Song $song): Song
    {
        $song->addHistory($this->userId);
        session(['current_song' => $song->id]);
        return $song;
    }

    public function next($order): Song
    {
        // Find songs not yet played
        $playedSongIds = SongHistory::where('user_id', $this->userId)->pluck('song_id')->toArray();

        if ($order === 'rand') {
            $nextSong = Song::whereNotIn('id', $playedSongIds)->inRandomOrder()->first();
        } else {
            $nextSong = Song::whereNotIn('id', $playedSongIds)->orderBy('id')->first();
        }

        if (!$nextSong) {
            // All songs played, reset history
            SongHistory::where('user_id', $this->userId)->delete();
            // start over
            $nextSong = Song::first();
        }

        if ($nextSong) {
            $this->play($nextSong);
        }

        return $nextSong;
    }

    public function previous(): ?Song
    {
        // Get last played song except current one
        $lastHistory = SongHistory::where('user_id', $this->userId)
            ->orderByDesc('id')
            ->skip(1) // skip current
            ->first();

        if ($lastHistory) {
            $song = Song::find($lastHistory->song_id);
            $lastHistory->delete();
            session(['current_song' => $song->id]);
            return $song;
        }

        return null; // no previous
    }

    public function shuffle(): Song
    {
        $current = session('shuffle', false);
        $newState = !$current;
        session(['shuffle' => $newState]);

        if ($newState) {
            // shuffle just enabled, play a random unplayed song immediately
            $playedSongIds = SongHistory::where('user_id', $this->userId)->pluck('song_id')->toArray();

            $song = Song::whereNotIn('id', $playedSongIds)
                ->inRandomOrder()
                ->first();

            if (!$song) {
                // all songs played, reset history
                SongHistory::where('user_id', $this->userId)->delete();
                $song = Song::inRandomOrder()->first();
            }

            if ($song) {
                $song->addHistory($this->userId);
                session(['current_song' => $song->id]);
            }
            return $song;
        }

        // if shuffle just turned off, just return current song
        $currentSongId = session('current_song');
        return Song::find($currentSongId);
    }

    public function queue(): array
    {
        $queueIds = session('queue', []);
        return Song::whereIn('id', $queueIds)
            ->get()
            ->sortBy(fn($song) => array_search($song->id, $queueIds))
            ->values()
            ->toArray();
    }

    protected function rebuildQueue(): array
    {
        $songs = Song::pluck('id')->toArray();
        if (session('shuffle', false)) {
            shuffle($songs);
        }
        return $songs;
    }
}