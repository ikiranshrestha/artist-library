<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Modules\Core\Entities\User;
use Modules\Core\Services\AuthenticationService;

class AuthenticationController extends Controller
{
    protected User $user;
    protected AuthenticationService $authService;

    /**
     * Constructor for the class
     *
     * @param User $user
     */
    public function __construct(User $user, AuthenticationService $authService)
    {
        $this->user = $user;
        $this->authService = $authService;
    }


    /**
     * Function to register new user
     *
     * @param Request $request
     * @return Illuminate\View\View
     */
    public function registerPage(Request $request): View
    {
        return view("core::register");
    }

    /**
     * Function to register new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): View|JsonResponse
    {
        $response = $this->authService->register($request);

        return $response;
    }

    /**
     * Function to validate and login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * Get the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(["user" => $user], 200);
    }

    /**
     * Logout the user (revoke the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();
        $this->user->token()->revoke();

        return response()->json(["message" => "Successfully logged out"], 200);
    }
}

