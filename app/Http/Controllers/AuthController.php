<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users',
            'password'=>'required|string|confirmed',
            'address'=>'required|string',
            'phone'=>'required|number',
            'profile_img'=>'string',
            'whatsapp_url'=>'required|string',
            'facebook_url'=>'string',
        ]);
        $user = User::create([
            'name'=>$fields['name'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password']),
            'address'=> $fields['address'],
            'phone'=> $fields['phone'],
            'profile_img_url'=> $fields['profile_img_url'],
            'whatsapp_url'=> $fields['whatsapp_url'],
            'facebook_url'=> $fields['facebook_url'],
        ]);
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token' => $token
        ];

        return response($response,201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $users=User::all();
        $users= json_decode($users,true);
        $user=null ;
        foreach($users as $userIterator){
            if($fields['email']==$userIterator['email']){
                $user = $userIterator;
            }
        }
        if ($user == null || !Hash::check($fields['password'], $user['password'])) {
            return response()->json([
                'message' => 'Invalid credentials',
            ],401);
        }
        
        $token = $user->createToken('authToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response()->json($response, 201);
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return ['message'=>'logged out'];
    } 
}
