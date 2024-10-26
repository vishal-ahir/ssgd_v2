<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Playlist;
use App\Models\Song;
use App\Models\SongPlaylistRel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlaylistsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Playlist::query(); // Customize your query as needed

            return DataTables::of($data)
                ->make(true);
        }
        return view('admin.playlists.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $songs = Song::select('song_code', 'title_en')->get();

        // Fetch songs already associated with playlists (if any)
        $existingSongs = SongPlaylistRel::pluck('song_code')->toArray();

        return view('admin.playlists.create', compact('songs', 'existingSongs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'playlist_en' => 'required|string|max:255',
            'playlist_gu' => 'required|string|max:255',
            'song_code' => 'required|array|min:2', // Ensure at least 2 songs are selected
            'song_code.*' => 'required|string', // Each selected song must be a string
        ]);

        // Get playlist prefix from configuration table
        $config = Configuration::where('key', 'playlist_prefix')->value('value');

        // Find the last playlist with the same prefix
        $lastPlaylist = Playlist::where('playlist_code', 'LIKE', "$config%")
            ->orderBy('playlist_code', 'desc')
            ->first();

        // Determine the new playlist code
        if ($lastPlaylist) {
            // Extract the numeric part and increment it
            $lastNumber = intval(substr($lastPlaylist->playlist_code, strlen($config)));
            $newPlaylistCode = $config . ($lastNumber + 1);
        } else {
            // No playlist exists with this prefix, start with the prefix followed by 1
            $newPlaylistCode = $config . '1';
        }

        $playlist = Playlist::create([
            'playlist_code' => $newPlaylistCode,
            'playlist_en' => $request->playlist_en,
            'playlist_gu' => $request->playlist_gu
        ]);

        foreach ($request->song_code as $songCode) {
            SongPlaylistRel::create([
                'song_code' => $songCode,
                'playlist_code' => $playlist->playlist_code,
            ]);
        }

        return redirect()->route('admin.playlists.index')->with('success', 'Playlist added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $play_code)
    {
        $category = Playlist::where('playlist_code', $play_code)->firstOrFail();

        if ($request->ajax()) {
            $data = SongPlaylistRel::where('playlist_code', $play_code)
                ->join('songs', 'songs.song_code', '=', 'song_playlist_rels.song_code')
                ->select('songs.song_code', 'songs.title_en', 'songs.title_gu') // Adjust fields as needed
                ->get();

            return DataTables::of($data)
                ->make(true);
        }

        return view('admin.playlists.show', compact('category'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $play_code)
    {
        $playlist = Playlist::where('playlist_code', $play_code)->firstOrFail();

        // $songs = Song::select('song_code', 'title_en')->get();

        $songs = Song::select('songs.*')
            ->join('song_playlist_rels', 'songs.song_code', '=', 'song_playlist_rels.song_code')
            ->where('song_playlist_rels.playlist_code', $play_code) // Change here to use playlist_code
            ->get();

        $allSongs = Song::all();

        return view('admin.playlists.edit', compact('playlist', 'songs', 'allSongs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $playlist_code)
    {
        $request->validate([
            'playlist_en' => 'required|string|max:255',
            'playlist_gu' => 'required|string|max:255',
            'song_code' => 'required|array', // Ensure songs are passed as an array
            'song_code.*' => 'exists:songs,song_code', // Each song must exist in the songs table
        ]);

        // Find the playlist by its code
        $playlist = Playlist::where('playlist_code', $playlist_code)->firstOrFail();

        // Update playlist details
        $playlist->update([
            'playlist_en' => $request->playlist_en,
            'playlist_gu' => $request->playlist_gu,
        ]);

        // Get current songs associated with the playlist
        $currentSongs = DB::table('song_playlist_rels')
            ->where('playlist_code', $playlist_code)
            ->pluck('song_code')
            ->toArray();

        // Determine which songs to add and which to remove
        $songsToAdd = array_diff($request->song_code, $currentSongs);
        $songsToRemove = array_diff($currentSongs, $request->song_code);

        // Add new songs
        foreach ($songsToAdd as $songCode) {
            DB::table('song_playlist_rels')->insert([
                'song_code' => $songCode,
                'playlist_code' => $playlist->playlist_code,
            ]);
        }

        // Remove unselected songs
        foreach ($songsToRemove as $songCode) {
            DB::table('song_playlist_rels')->where('playlist_code', $playlist->playlist_code)
                ->where('song_code', $songCode)
                ->delete();
        }

        return redirect()->route('admin.playlists.index')->with('success', 'Playlist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $playlist_code)
    {
        // Find the song by playlist_code
        $playList = Playlist::where('playlist_code', $playlist_code)->firstOrFail();

        // Delete the associated subcategories
        SongPlaylistRel::where('playlist_code', $playlist_code)
            ->delete();

        // Delete the song
        $playList->delete();

        // Redirect with a success message
        return redirect()->back()->with('success', 'Playlist deleted successfully');
    }

    public function search(Request $request)
    {
        // dd($request->all());
        try {
            $query = $request->get('q');
            // dd($query);
            $songs = Song::where('title_en', 'LIKE', "%{$query}%")
                ->orWhere('title_gu', 'LIKE', "%{$query}%")
                ->orWhere('song_code', 'LIKE', $query)
                ->select('song_code', 'title_en', 'title_gu')
                ->get();
            // dd($songs);
            return response()->json($songs);
        } catch (\Exception $e) {
            return response()->json([], 404); // Return empty if there's an error
        }
    }
}