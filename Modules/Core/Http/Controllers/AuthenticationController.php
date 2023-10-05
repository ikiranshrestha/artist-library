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
    public function register(Request $request): JsonResponse
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
        $response = $this->authService->login($request);

        return $response;
    }

    /**
     * Get the authenticated user.
     *
     * @return User
     */
    public function user(): User
    {
        $user = Auth::user();

        return $user;
    }

    /**
     * Logout the user (revoke the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        $user = $this->user();
        $response = $this->authService->logout($user);

        return $response;
    }

    public function index(Request $request)
    {
        $users = $this->authService->fetchAllUsers($request, []);

        return $users;
    }

    public function show(int $id)
    {
        $user = $this->authService->fetchUser($id);

        return $user;
    }

    public function update(Request $request, int $id)
    {
        $user = $this->authService->updateUser($request, $id);

        return $user;
    }

    public function destroy(int $id)
    {
        $user = $this->authService->deleteUser($id);

        return $user;
    }
}

