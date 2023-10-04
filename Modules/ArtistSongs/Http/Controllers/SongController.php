<?php

namespace Modules\ArtistSongs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ArtistSongs\Services\SongService;

class SongController extends Controller
{
    protected SongService $songService;

    function __construct(SongService $songService)
    {
        $this->songService = $songService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $response = $this->songService->fetchAll($request);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $response = $this->songService->store($request);

        return $response;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(int $id)
    {
        $response = $this->songService->fetch($id);

        return $response;
    }

    public function fetchByArtist(int $id)
    {
        $response = $this->songService->fetchByArtist($id);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, int $id)
    {
        $response = $this->songService->update($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $response = $this->songService->delete($id);

        return $response;
    }
}
