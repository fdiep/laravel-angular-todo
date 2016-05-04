<?php

namespace App\Http\Controllers;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Controllers\ErrorCode;

class TokenAuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->errorResponse(ErrorCode::NOT_AUTHORIZED);
            }
        } catch (JWTException $e) {
            return $this->errorResponse(ErrorCode::UNKNOWN_ERROR);
        }

        // if no errors are encountered we can return a JWT
        return $this->successResponse(compact('token'));
    }

    public function getAuthenticatedUser(Request $request)
    {
        return $this->successResponse($request->authUser->toArray());
    }

    public function register(Request $request)
    {
        $newuser = $request->all();

        // validate user
        $validator = $this->validator($newuser);
        if ($validator->fails()) {
          return $this->errorResponse(ErrorCode::BAD_REQUEST, $this->formatValidationErrors($validator)['error_messages']);
        }

        // validation successful
        $password = Hash::make($request->input('password'));
        $newuser['password'] = $password;

        $createdUser = User::create($newuser);
        return $this->successResponse($createdUser);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }
}
