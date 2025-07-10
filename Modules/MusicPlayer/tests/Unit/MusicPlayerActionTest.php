<?php

namespace Modules\MusicPlayer\Tests\Unit;

use Tests\TestCase;
use Modules\MusicPlayer\Models\Song;
use Modules\MusicPlayer\Models\SongHistory;
use Modules\MusicPlayer\Actions\MusicPlayerAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use PHPUnit\Framework\Attributes\Test;

class MusicPlayerActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and fake authentication
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        // Start session
        Session::start();
    }

    #[Test]
    public function it_can_play_a_song_and_add_to_history()
    {
        $song = Song::factory()->create();

        $player = new MusicPlayerAction();
        $playedSong = $player->play($song);

        $this->assertEquals($song->id, $playedSong->id);
        $this->assertDatabaseHas('song_histories', [
            'user_id' => auth()->id(),
            'song_id' => $song->id
        ]);
        $this->assertEquals($song->id, session('current_song'));
    }

    #[Test]
    public function it_can_get_next_song_ordered()
    {
        $songs = Song::factory()->count(3)->create();

        $player = new MusicPlayerAction();
        $nextSong = $player->next('asc');

        $this->assertNotNull($nextSong);
        $this->assertDatabaseHas('song_histories', [
            'user_id' => auth()->id(),
            'song_id' => $nextSong->id
        ]);
    }

    #[Test]
    public function it_resets_history_when_all_songs_are_played()
    {
        $songs = Song::factory()->count(2)->create();

        $player = new MusicPlayerAction();
        // Play all songs
        $player->next('asc');
        $player->next('asc');

        $this->assertCount(2, SongHistory::where('user_id', auth()->id())->get());

        // Next should reset
        $nextSong = $player->next('asc');
        $this->assertCount(1, SongHistory::where('user_id', auth()->id())->get());
    }

    #[Test]
    public function it_can_go_to_previous_song()
    {
        $songs = Song::factory()->count(3)->create();

        $player = new MusicPlayerAction();
        $first = $player->next('asc');
        $second = $player->next('asc');

        $previous = $player->previous();

        $this->assertEquals($first->id, $previous->id);
        $this->assertEquals($previous->id, session('current_song'));
    }

    #[Test]
    public function it_toggles_shuffle_and_plays_random_song()
    {
        $songs = Song::factory()->count(5)->create();

        $player = new MusicPlayerAction();
        $song = $player->shuffle();

        $this->assertTrue(session('shuffle'));
        $this->assertNotNull($song);
        $this->assertDatabaseHas('song_histories', [
            'user_id' => auth()->id(),
            'song_id' => $song->id
        ]);
    }

    #[Test]
    public function it_turns_off_shuffle_and_returns_current_song()
    {
        $songs = Song::factory()->count(5)->create();

        $player = new MusicPlayerAction();
        $player->shuffle(); // turn on
        $song = $player->shuffle(); // turn off

        $this->assertFalse(session('shuffle'));
        $this->assertEquals($song->id, Song::find(session('current_song'))->id);
    }
}
