<?php

namespace Modules\ArtistSongs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ArtistSongs\Services\ArtistService;

class ArtistController extends Controller
{
    protected ArtistService $artistService;

    function __construct(ArtistService $artistService)
    {
        $this->artistService = $artistService;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $response = $this->artistService->fetchAll($request);

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $response = $this->artistService->store($request);

        return $response;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(int $id)
    {
        $response = $this->artistService->fetch($id);

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
        $response = $this->artistService->update($request, $id);

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $response = $this->artistService->delete($id);

        return $response;
    }
}
