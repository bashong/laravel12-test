<?php

namespace Modules\MusicPlayer\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\MusicPlayer\Interfaces\MusicPlayerInterface;
use Modules\MusicPlayer\Models\Song;

class MusicPlayerController extends Controller
{
    protected $player;

    public function __construct(MusicPlayerInterface $player)
    {
        $this->player = $player;
    }

    public function play(Song $song)
    {
        return response()->json($this->player->play($song));
    }

    public function next($order)
    {
        return response()->json($this->player->next($order));
    }

    public function previous()
    {
        return response()->json($this->player->previous());
    }

    public function shuffle()
    {
        $song = $this->player->shuffle();

        return response()->json([
            'shuffle' => session('shuffle', false),
            'song' => $song
        ]);
    }

    public function queue()
    {
        return response()->json($this->player->queue());
    }
}
