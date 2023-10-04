<?php

namespace Modules\ArtistSongs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "artist_id",
        "album_name",
        "genre"
    ];

}
