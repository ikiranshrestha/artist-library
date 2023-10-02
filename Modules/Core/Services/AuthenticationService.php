<?php

namespace Modules\Core\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Entities\User;
use Modules\Core\Repositories\AuthenticationRepository;

class AuthenticationService
{
    protected User $user;
    protected AuthenticationRepository $authRepository;

    function __construct(User $user, AuthenticationRepository $authRepository)
    {
        $this->user = $user;
        $this->authRepository = $authRepository;
    }

    public function register(Request $request): mixed
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email|unique:users",
            "password" => "required|string|min:6",
            "phone" => "required|string|between:10,10",
            "dob" => "required|date",
            "gender" => "required|in:m,f,o"
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Create a new user
        $userArray = [];
        $userArray['first_name'] = $request->first_name;
        $userArray['last_name'] = $request->last_name;
        $userArray['email'] = $request->email;
        $userArray['password'] = bcrypt($request->password);
        $userArray['phone'] = $request->phone;
        $userArray['dob'] = $request->dob;
        $userArray['gender'] = $request->gender;
        $userArray['address'] = $request->address;

        // Generate a token for the user
        $token = $this->user->createToken("MyApp")->accessToken;
        $userArray['tokenable_id'] = $token;
        $this->authRepository->store($userArray);
    }
}