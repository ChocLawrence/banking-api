<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;


/** 
 * @group User Management
 *
 * User functionalities
 **/
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(User::all());
    }


     /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::where('id',$id)->first();
        if(!$user){
            return response()->json(['message'=>"User not found"],404);
        } else{
        return response(['message'=> 'User retrieved successfully','user' =>$user ]);
        }
    } 
    
     /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $user = User::find($id);

        //check if authenticated user is thesame person updating
        if($userId != $id){
            return response()->json(['message'=>"Something is wrong.Logout and login"],401); 
        }

        $user->update($request->all());
        return response(['message' => 'Record updated', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $userId = Auth::id();
        $user = User::find($id);

        //User should delete his account
        if($userId != $id){
            return response()->json(['message'=>"Deletion not possible. Logout and login"],401); 
        }
        return response(User::destroy($id));
    }

    /**
     * Search a specified resource from storage.
     * by first_name, last_name in user
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request): Response
    {
        $query = $request->input('value');

        if (isset($query)) {
            $userFound = User::where('firstname', 'like', '%' . $query . '%')
                ->orWhere('lastname', 'like', '%' . $query . '%')
                ->get();
            return response($userFound);
        }

        return response(['message' => 'Value cannot be empty'], Response::HTTP_NOT_FOUND);
    }


       /**
     * Change password
     *
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input,[
            'current_password' => 'required',
            'new_password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => '500',
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ],401);
        }


        $userId = Auth::id();
        $user = User::find($userId);
        if(isset($user["password"])){
            if(Hash::check($request->current_password, $user["password"])){
                $user->password = Hash::make($request->new_password);
                $user->save();
                return response()->json(['message'=>"Password updated"],200); 

            }else{
                return response(['message' => 'Incorrect password'], Response::HTTP_NOT_FOUND);
            }
        }else{
            return response()->json(['message'=>"User not found"],404);
        }

    }

    
}