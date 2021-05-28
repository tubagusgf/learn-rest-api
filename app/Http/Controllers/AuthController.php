<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2|max:20',
            'last_name' => 'required|alpha|min:2|max:20',
            'username' => "required|regex:'^[a-zA-Z0-9_\.]*$'|min:5|max:12|unique:users",
            'password' => 'required|min:5|max:12'
        ], [
            'username.regex' => 'Username only consist of alphanumeric, underscore ‘_’, or dot ‘.’'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => 'invalid field',
                'error' => $validator->messages()
            ], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $token = Token::create([
            'user_id' => $user->id, 
            'token' => Hash::make(Str::random(10)),
        ]);

        return response()->json([
            'status' => true,
            'messages' => 'New account register successfully',
            'token' => $token->token
        ], 200);
    }

    public function login(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'username' => "required|regex:'^[a-zA-Z0-9_\.]*$'|min:5|max:12",
            'password' => 'required|min:5|max:12'
        ], [
            'username.regex' => 'Username only consist of alphanumeric, underscore ‘_’, or dot ‘.’'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'messages' => 'invalid login',
                'error' => $validator->messages()
            ], 401);
        }

        $check = Auth::attempt(['username' => $request->username, 'password' => $request->password]);
        if (!$check) {
            return response()->json([
                'status' => false,
                'messages' => 'invalid login',
            ], 401);
        }

        $token = Token::create([
            'user_id' => Auth::user()->id, 
            'token' => Hash::make(Str::random(10)),
        ]);

        return response()->json([
            'status' => true,
            'messages' => 'login success',
            'token' => $token->token
        ], 200);
    }

    public function logout(Request $request) 
    {
        $token = $request->get('token');
        $find = Token::where('token', $token)->first();

        $find->delete();

        return response()->json([
            'status' => true,
            'messages' => 'logout success',
            'token' => $token->token
        ], 200);
    }   
}
