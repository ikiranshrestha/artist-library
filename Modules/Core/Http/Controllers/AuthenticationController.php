<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
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

    public function test(): void
    {
        dump("testing");
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
        $this->authService->register($request);
        // // Validation rules for registration
        // $validator = Validator::make($request->all(), [
        //     "first_name" => "required|string|max:255",
        //     "last_name" => "required|string|max:255",
        //     "email" => "required|string|email|unique:users",
        //     "password" => "required|string|min:6",
        //     "phone" => "required|string|between:10,10",
        //     "dob" => "required|date",
        //     "gender" => "required|in:m,f,o"
        // ]);

        // if ($validator->fails()) {
        //     return response()->json(["errors" => $validator->errors()], 422);
        // }

        // // Create a new user
        // $this->user->first_name = $request->first_name;
        // $this->user->last_name = $request->last_name;
        // $this->user->email = $request->email;
        // $this->user->password = bcrypt($request->password);
        // $this->user->phone = $request->phone;
        // $this->user->dob = $request->dob;
        // $this->user->gender = $request->gender;
        // $this->user->save();

        // // Generate a token for the user
        // $token = $this->user->createToken("MyApp")->accessToken;

        return view("core::register");
        // return response()->json(["token" => $token], 201);
    }

    /**
     * Function to validate and login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validation rules for login
        $validator = Validator::make($request->all(), [
            "email" => "required|string|email",
            "password" => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $token = $this->user->createToken("MyApp")->accessToken;

            return response()->json(["token" => $token], 200);
        }

        return response()->json(["error" => "Unauthorized"], 401);
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

