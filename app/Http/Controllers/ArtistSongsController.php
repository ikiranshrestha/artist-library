<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArtistSongsController extends Controller
{
    public function artistTab()
    {
        return view('artist-songs.artist-tab');
    }
}
