<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PassportController extends Controller
{
    /**
     * Handles Student Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Handles Student Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($credentials)) {
            if (auth()->user()->status == 1) {
                $token = auth()->user()->createToken('SPEEDGRADE')->accessToken;
                return response()->json(['success' =>  true, 'token' => $token, 'data' => auth()->user(), 'userImage' => $this->userImage()], 200);
            } else {
                return response()->json(['success' =>  false, 'error' => 'Username or Password is Invalid', 'data' => $credentials], 401);
            }
        } else {
            return response()->json(['success' =>  false, 'error' => 'Username or Password is Invalid', 'data' => $credentials], 401);
        }
    }

    /**
     * Handles Student Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'email'             => ['required', 'unique:users'],
            'firstname'         => 'required',
            'lastname'          => 'required',
            'contact_no'        => '',
            'gender'            => '',
        ]);

        $data += [
            'remember_token'    => Str::random(10),
            'password'          => Hash::make($request->password),
            'user_role_id'      => 2,
            'status'            => 1
        ];

        if (User::create($data)) {
            return response()->json(['success' => true, 'message' => 'Successfully Registered'], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'Something went wrong, please try again!'], 401);
        }
    }

    private function userImage()
    {
        $userImage = UserImage::where('user_id', auth()->user()->id)->orderBy('id', 'desc')->first();

        if ($userImage) {
            return $userImage->file_path;
        } else {
            return "";
        }
    }
}