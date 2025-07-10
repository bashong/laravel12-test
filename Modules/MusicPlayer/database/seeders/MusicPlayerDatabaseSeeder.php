<?php

namespace Modules\MusicPlayer\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MusicPlayer\Models\Song;

class MusicPlayerDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $songs = [
            ['artist' => 'Ed Sheeran', 'title' => 'Shape of You'],
            ['artist' => 'Billie Eilish', 'title' => 'Bad Guy']
        ];

        foreach ($songs as $index => $song) {
            Song::create([
                'artist' => $song['artist'],
                'title'  => $song['title'],
                'image'  => "https://picsum.photos/300/300?random=" . ($index + 1),
            ]);
        }
    }
}
