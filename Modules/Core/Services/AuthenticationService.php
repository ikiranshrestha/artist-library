<?php

namespace Modules\Core\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email|unique:users",
            "password" => "required|string|min:6",
            "phone" => "required|string|between:10,10",
            "dob" => "required|date",
            "gender" => "required|in:m,f,o",
            "address" => "nullable|string",
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

        $store = $this->authRepository->store($userArray);

        if($store) {
            return response()->json(['message' => 'User Registered successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make($request->all(), [
            "email" => "required|string|email|exists:users",
            "password" => "required|string|min:6",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('app-token')->plainTextToken;

            return response()->json([
                'message' => "Logged in successfully",
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(User $user): JsonResponse
    {
        $user->currentAccessToken()->delete();

        return response()->json(["message" => "Successfully logged out"], 200);
    }

    public function fetchAllUsers(Request $request)
    {
        $users = $this->authRepository->fetchAll($request, []);

        return $users;
    }

    public function fetchUser(int $id)
    {
        $user = $this->authRepository->fetch($id);

        return $user;
    }

    public function updateUser(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required|string|max:255",
            "last_name" => "required|string|max:255",
            "email" => "required|string|email|unique:users,email," . $request->all()["id"],
            "password" => "required|string|min:6",
            "phone" => "required|string|between:10,10",
            "dob" => "required|date",
            "gender" => "required|in:m,f,o",
            "address" => "nullable|string",
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

        $store = $this->authRepository->update($userArray, $id);

        if($store) {
            return response()->json(['message' => 'User Updated successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
    }

    public function deleteUser(int $id)
    {
        $artist = $this->authRepository->delete($id);

        if($artist) {
            return response()->json(['message' => 'User erased successfully'], 201);
        } else {
            return response()->json(['message' => 'Something Went Wrong'], 500);
        }
        return $artist;
    }
}