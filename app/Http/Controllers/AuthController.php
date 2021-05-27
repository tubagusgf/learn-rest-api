<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Token;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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
}
