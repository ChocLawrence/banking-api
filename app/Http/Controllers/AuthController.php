<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Validator;

/** 
 * @group Authentication
 *
 * Authentication functionalities
 **/
class AuthController extends Controller
{
   
    public function register(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input,[
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|unique:users',
            'gender' => 'required|in:male,female',
            'role_id' => 'min:1|max:2',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => '500',
                    'message' => 'Bad Request',
                    'error' => $validator->errors()
                ],401);
            }

            unset($input['confirm_password']);
            $input['password'] = Hash::make($input['password']);
            $query = User::create($input);

            $response['token'] = $query->createToken('users')->accessToken;
            $response['email'] = $query->email;

            return response()->json($response,Response::HTTP_CREATED);
       
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
        
    }

    public function login(Request $request)
    {
        try {
            $input = $request->all();

            $validator = Validator::make($input,[
                'email' => 'required|email',
                'password' => 'required',
                ]);

            if($validator->fails()){
                return response()->json([
                    'status' => '500',
                    'message' => 'Bad Request',
                    'error' => $validator->errors()
                ],401);
            }

            $check_users = User::where('email', $input['email'])->first();


            if (!$check_users || !Hash::check($input['password'], $check_users->password)) {
                return response()->json(['message' => 'Check your credentials again', 'status' => Response::HTTP_UNAUTHORIZED ]);
            }

            $response['token']= $check_users->createToken('users')->accessToken;
            $response['status']=200;
            $response['message'] = 'Login successful';

            return response()->json($response,200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
       
    }


    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}