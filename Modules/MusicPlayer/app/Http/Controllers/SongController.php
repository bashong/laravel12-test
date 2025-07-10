<?php

namespace Modules\MusicPlayer\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Modules\MusicPlayer\Models\Song;

class SongController extends Controller
{
    public function index()
    {
        return Inertia::render('MusicPlayer/Index', [
            'songs' => Song::all(),
            'shuffle' => session('shuffle', false),
            'queue' => session('queue', []),
            'history' => session('history', []),
        ]);
    }
}
