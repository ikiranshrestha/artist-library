<?php

namespace Modules\ArtistSongs\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\ArtistSongs\Repositories\SongRepository;

class SongService
{
    protected SongRepository $songRepository;

    function __construct(SongRepository $songRepository)
    {
        $this->songRepository = $songRepository;
    }

    public function fetchAll(Request $request)
    {
        $artists = $this->songRepository->fetchAll($request, []);

        return $artists;
    }

    public function store(Request $request): JsonResponse
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|exists:artists,id",
            "title" => "required|string",
            "album_name" => "required|string",
            "genre" => "required|string|in:rnb,country,classic,rock,jazz",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Create a new song
        $songArray = [];
        $songArray['artist_id'] = $request->artist_id;
        $songArray['title'] = $request->title;
        $songArray['album_name'] = $request->album_name;
        $songArray['genre'] = $request->genre;

        $store = $this->songRepository->store($songArray);

        if($store) {
            return response()->json(['message' => 'Song Recorded successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function fetch(int $id)
    {
        $song = $this->songRepository->fetch($id);

        return $song;
    }

    public function fetchByArtist(int $id)
    {
        $songs = $this->songRepository->fetchBy("artist_id", $id);

        return $songs;
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "artist_id" => "required|exists:artists,id",
            "title" => "required|string",
            "album_name" => "required|string",
            "genre" => "string|in:rnb,country,classic,rock,jazz",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $songArray = [];
        $songArray['artist_id'] = $request->artist_id;
        $songArray['title'] = $request->title;
        $songArray['album_name'] = $request->album_name;
        $songArray['genre'] = $request->genre;

        $store = $this->songRepository->update($songArray, $id);

        if($store) {
            return response()->json(['message' => 'Song Record Updated successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function delete(int $id)
    {
        $artist = $this->songRepository->delete($id);

        if($artist) {
            return response()->json(['message' => 'Song record erased successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
        return $artist;
    }
}