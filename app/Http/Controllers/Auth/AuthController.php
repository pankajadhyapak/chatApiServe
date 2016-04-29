<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\InputValidationException;
use App\Http\Controllers\ApiController;
use App\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Controllers\single
     *
     * Return User Profile
     */
    public function me(Request $request)
    {
        if($request->has('token')){
            $user = JWTAuth::parseToken()->authenticate();
            $u = User::findOrFail($user->id);
            $u->token = $request->get('token');
            return $this->item($u);
        }

        throw new InputValidationException("Token is required");
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Controllers\single|\Illuminate\Http\JsonResponse
     *
     * Login User and return user with the token
     */
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Email or Password'], 401);
            }

        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        $user = getUserFromToken($token);
        $user->token = $token;

        return $this->item($user);

    }

    /**
     * @return mixed
     */
    public function validateToken()
    {
        // Our routes file should have already authenticated this token, so we just return success here
        return API::response()->array(['status' => 'success'])->statusCode(200);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Controllers\single
     *
     * Register User and return user with token
     */
    public function register(Request $request)
    {
        $newUser = [
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6'];

        $validator  = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            throw new InputValidationException($validator->errors());
        }

        $user = User::create($newUser);

        $token = JWTAuth::fromUser($user);

        $user = getUserFromToken($token);
        $user->token = $token;

        return $this->item($user);
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \App\Http\Controllers\single
     *
     * Update given user profile and return updated user and token
     */
    public function updateProfile(Request $request)
    {
        $user = \JWTAuth::parseToken()->authenticate();

        $user->update($request->all());
        $user->token = \JWTAuth::fromUser($user);

        return $this->item($user);
    }
}
