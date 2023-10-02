<?php

namespace Modules\Core\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthenticationService
{
    protected User $user;

    function __construct(User $user)
    {
        $this->user = $user;
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
        dd(123);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        // Create a new user
        $this->user->first_name = $request->first_name;
        $this->user->last_name = $request->last_name;
        $this->user->email = $request->email;
        $this->user->password = bcrypt($request->password);
        $this->user->phone = $request->phone;
        $this->user->dob = $request->dob;
        $this->user->gender = $request->gender;
        $this->user->save();

        // Generate a token for the user
        $token = $this->user->createToken("MyApp")->accessToken;
    }
}