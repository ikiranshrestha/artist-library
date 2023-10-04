<?php

namespace Modules\ArtistSongs\Repositories;

use Modules\Core\Repositories\BaseRepository;

class SongRepository extends BaseRepository{
    public $model = "Song";
    public $tableName = "songs";
}