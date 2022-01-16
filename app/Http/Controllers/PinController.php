<?php

namespace App\Http\Controllers;

use App\Models\Pin;
use App\Models\Role;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


/** 
 * @group Pin Management
 *
 * Pin functionalities
 **/
class PinController extends Controller
{
    public function index(){
        return Pin::all();
    }

    public function store(Request $request){
        try {
            $userId = Auth::id();
            $user = User::where('id',$userId)->firstOrFail();

            //get authenticated users role
            $role = Role::find($user->role_id);

            //ensure it is a customer creating a pin
            if($role->name !== 'customer'){
                return response()->json([
                    'status' => '500',
                    'message' => 'Unauthorized action'
                ],401);
            }

            $input = $request->all();

            $validator = Validator::make($input,[
                'account_number' => 'required|string',
                'pin' => 'required|string|digits:5',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => '500',
                    'message' => 'Bad Request',
                    'error' => $validator->errors()
                ],401);
            }

            //get account id.
            $account = Account::where('account_number',$input['account_number'])->first();

            if(!$account){
                return response()->json(['status' => 'error', 'message' => 'Account with id not found']);
            }

            //check if account already has a pin and if account is owned by requesting user
            if($account->user_id !==  $userId ){
                return response()->json(['status' => 'error', 'message' => 'Account does not belong to you']);
            }


            $accountPin = Pin::where('account_id',$account->id)->first();
           
            if($accountPin){
                return response()->json(['status' => 'error', 'message' => 'Account already has a pin']);
            }


            //create new Pin
            $pin= new Pin();
            $pin->pin = $request->pin;
            $pin->account_id = $account->id;
            $pin->user_id = $userId;

            if ($pin->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account pin created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $userId = Auth::id();
            $user = User::where('id',$userId)->firstOrFail();

            //get authenticated users role
            $role = Role::find($user->role_id);
            $pin= Pin::findOrFail($id);

            //ensure it is a customer creating a pin
            if($role->name == 'customer'){
                if($pin->user_id !== $userId){
                    return response()->json([
                        'status' => '500',
                        'message' => 'Account does not belong to you'
                    ],401);
                }
            }

            $input = $request->all();

            $validator = Validator::make($input,[
                'pin' => 'required|string|digits:5',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => '500',
                    'message' => 'Bad Request',
                    'error' => $validator->errors()
                ],401);
            }

            if($request->account_number){
                $account = Account::where('account_number',$input['account_number'])->firstOrFail();

                if(!$account){
                    return response()->json(['status' => 'error', 'message' => 'Account not found']);
                }
  
                $pin->account_id = $account->id;

            }


            //check if pin is thesame as previous account pin
            if($pin->pin === $request->pin){
                return response()->json(['status' => 'error', 'message' => 'Pin the same: Nothing changed']);
            }



            $pin->pin = $request->pin;

            if ($pin->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account pin updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $pin= Pin::findOrFail($id);

            if ($pin->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Account pin deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
