<?php

namespace Modules\MusicPlayer\Interfaces;

use Modules\MusicPlayer\Models\Song;

interface MusicPlayerInterface {
    public function play(Song $song): Song;
    public function next($order): Song;
    public function previous(): ?Song;
    public function shuffle(): Song;
    public function queue(): array;
}
