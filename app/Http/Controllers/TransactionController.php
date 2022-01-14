<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\User;
use App\Models\Pin;
use App\Models\Account;
use Validator;


/** 
 * @group Transaction Management
 *
 * Transaction functionalities
 **/
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Transaction::latest()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JSON
     */
    public function store(Request $request)
    {

        $userId = Auth::id();
        $user = User::find($userId);

        $input = $request->all();

        //validation ensures sending account is different from receiving account
        $validator = Validator::make($input,[
            'amount' => 'required|numeric|max:9999999999.99',
            'sender_account'=>'required|different:receiver_account',
            'receiver_account'=>'required|different:sender_account'
        ]);


        if($validator->fails()){
            return response()->json([
                'status' => '500',
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ],401);
        }
        
        //check account validities
        $sender_account = Account::where('account_number', $input['sender_account'])->first();
        $receiver_account =  Account::where('account_number', $input['receiver_account'])->first();

        if (!$sender_account){
            return response()->json(['message'=>"Sending account not found"],404);
        }

        if (!$receiver_account){
            return response()->json(['message'=>"Receiver account not found"],404);
        }

        //check who is performing the transaction to invoke if using a pin is necessary

        $role = Role::find($user->role_id);


        if($role->name == 'customer'){
            //require pin

            if(!$request->pin){
                return response()->json(['message'=>"Pin is required"],403);
            }

            //verify
            $pinFound = Pin::where('pin',$request->pin)->where('account_id',$sender_account->id)->first();

            if (!$pinFound){
                return response()->json(['message'=>"Invalid account pin"],404);
            }
        }

    
        //check if sender has enough money in his account
        if($sender_account->balance < $input['amount']){
            return response()->json(['message'=>"Insufficient funds"],400);
        }


        $transaction = Transaction::create([
            'sender_account' => $input['sender_account'],
            'receiver_account'=> $input['receiver_account'],
            'amount'=> $input['amount'],
            'user_id' => $userId,
            'currency'=> 'XAF',
            'status' =>'successful'
        ]); 


       
        if($transaction){
            //substract balance from sender
            $sender_account->balance =  $sender_account->balance -  $input['amount'];
            $sender_account->save();

             //add balance of receiver
             $receiver_account->balance =  $receiver_account->balance +  $input['amount'];
             $receiver_account->save();  

        }
        
        return response()->json(['message'=>"Transaction effected successfully", 'account' => $transaction],201);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id)
    {
        $transaction = Transaction::where('id',$id)->first();
        if(!$transaction){
            return response()->json(['message'=>"transaction not found"],404);
        } else{
        return response(['message'=> 'transaction retrieved successfully','transaction' =>$transaction ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        return response(Transaction::destroy($id));
    }
}