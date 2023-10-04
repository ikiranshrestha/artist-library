<?php

namespace Modules\ArtistSongs\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Modules\ArtistSongs\Repositories\ArtistRepository;
use Modules\ArtistSongs\Rules\YearFormatRule;
use Modules\Core\Entities\User;

class ArtistService
{
    protected User $user;
    protected ArtistRepository $artistRepository;

    function __construct(User $user, ArtistRepository $artistRepository)
    {
        $this->user = $user;
        $this->artistRepository = $artistRepository;
    }

    public function fetchAll(Request $request)
    {
        $artists = $this->artistRepository->fetchAll($request, []);

        return $artists;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "dob" => "required|date",
            "gender" => "required|in:m,f,o",
            "address" => "nullable|string",
            "first_year_release" => ["nullable", new YearFormatRule],
            "no_of_albums_released" => "nullable|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $artistArray = [];
        $artistArray['name'] = $request->name;
        $artistArray['dob'] = $request->dob;
        $artistArray['gender'] = $request->gender;
        $artistArray['address'] = $request->address;
        $artistArray['first_year_release'] = $request->first_year_release;
        $artistArray['no_of_albums_released'] = $request->no_of_albums_released;

        $store = $this->artistRepository->store($artistArray);

        if($store) {
            return response()->json(['message' => 'Artist Recorded successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function fetch(int $id)
    {
        $artist = $this->artistRepository->fetch($id);

        return $artist;
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "dob" => "required|date",
            "gender" => "required|in:m,f,o",
            "address" => "nullable|string",
            "first_year_release" => ["nullable", new YearFormatRule],
            "no_of_albums_released" => "nullable|integer"
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $artistArray = [];
        $artistArray['name'] = $request->name;
        $artistArray['dob'] = $request->dob;
        $artistArray['gender'] = $request->gender;
        $artistArray['address'] = $request->address;
        $artistArray['first_year_release'] = $request->first_year_release;
        $artistArray['no_of_albums_released'] = $request->no_of_albums_released;

        $store = $this->artistRepository->update($artistArray, $id);

        if($store) {
            return response()->json(['message' => 'Artist Record Updated successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function delete(int $id)
    {
        $artist = $this->artistRepository->delete($id);

        if($artist) {
            return response()->json(['message' => 'Artist record erased successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
        return $artist;
    }

    public function exportCSV(Request $request)
    {
        $artists = $this->fetchAll($request);
        $file = fopen('artists.csv', 'w');

        fputcsv($file, ['name', 'dob', 'gender', 'address', 'first_year_release', 'no_of_albums_released']);

        foreach ($artists as $artist) {
            fputcsv($file, [$artist->name, $artist->dob, $artist->gender, $artist->address, $artist->first_year_release, $artist->no_of_albums_released]);
        }

        fclose($file);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="artists.csv"',
        ];
        return Response::download('artists.csv', 'artists.csv', $headers)->deleteFileAfterSend();
    }

    public function importCSV(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('file');

        if (($handle = fopen($file, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $name = $data[0];
                $dob = $data[1];
                $gender = $data[2];
                $address = $data[3];
                $firstYearRelease = $data[4];
                $noOfAlbums = $data[5];
                $artistArray = [
                    'name' => $name,
                    'dob' => $dob,
                    'gender' => $gender,
                    'address' => $address,
                    'first_year_release' => $firstYearRelease,
                    'no_of_albums_released' => $noOfAlbums,
                ];

                $this->artistRepository->store($artistArray);
            }
            fclose($handle);
        }

        return ["message" => "Upload Success"];
    }
}
