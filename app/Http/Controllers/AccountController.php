<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;

/** 
 * @group Account Management
 *
 * Account functionalities
 **/
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Account::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Json
     */
    public function store(Request $request)
    {

        //check if user is authenticated
        $userId = Auth::id();
        $user = User::where('id',$userId)->firstOrFail();

        //get authenticated users role
        $role = Role::find($user->role_id);

        //ensure it is an admin or an employee creating the account
        if($role->name !== 'employee' && $role->name !== 'admin'){
            return response()->json([
                'status' => '500',
                'message' => 'Unauthorized action'
            ],401);
        }


        $input = $request->all();

        $validator = Validator::make($input,[
            'account_type' => 'required|string',
            'user_id' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => '500',
                'message' => 'Bad Request',
                'error' => $validator->errors()
            ],401);
        }

         //check if user exists
         $customerFound = User::find($input['user_id']);

         if(!$customerFound){
             return response()->json([
                 'status' => '500',
                 'message' => 'Invalid customer id'
             ],401);
         }

         if($customerFound->id == $userId){
            return response()->json([
                'status' => '500',
                'message' => 'Invalid creation.'
            ],401);  
         }

         $customerRole = Role::find($customerFound->role_id);


         if($customerRole->name != "customer"){
            return response()->json([
                'status' => '500',
                'message' => 'Only customer accounts can be created'
            ],401);  
         }

        //check if account type exists
        $accountTypeFound = AccountType::find($input['account_type']);

        if(!$accountTypeFound){
            return response()->json([
                'status' => '500',
                'message' => 'Invalid account type'
            ],401);
        }


        //generate an account number
        $account_number = mt_rand(100000000,999999999);


        //check if generated account number already exists, even though mt_rand is seeded
        if (Account::where('account_number', '=', $account_number)->exists()) {
            return response()->json([
                'status' => '500',
                'message' => 'Account number already exists : ' . $account_number
            ],401);
         }


        // Ensure balance does not exceed max defined
        if($request->balance && $request->balance > 9999999999.99){
            return response()->json([
                'status' => '500',
                'message' => 'Balance cannot exceed 9999999999.99 XAF'
            ],401);
        }
        

        $account = Account::create([
            'account_number' => $account_number,
            'account_type' => $input['account_type'],
            'balance' =>   $request->balance ?  $request->balance : 11000.00,
            'user_id' => $customerFound->id
        ]); 
        
        return response()->json(['message'=>"Account created successfully", 'account' => $account],201);   
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(Request $request, int $id)
    {
        $account = Account::where('id',$id)->first();
        if (!$account){
            return response()->json(['message'=>"Account not found"],404);
        } else{

        //get authenticated users Id
        $userId = Auth::id();
        $user = User::where('id',$userId)->firstOrFail();

        //get authenticated users role
        $role = Role::find($user->role_id);

        //ensure it is an admin or an employee creating the account
        if($role->name == 'customer'){
            if($account->user_id !== $userId){
                return response()->json([
                    'status' => '500',
                    'message' => 'Account does not belong to you'
                ],401);
            }
        }
        
        
        return response(['message'=> 'Account retrieved successfully','account' => $account ]);
        

        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function getBalance(Request $request, int $id)
    {
        $account = Account::where('id',$id)->first();
        if (!$account){
            return response()->json(['message'=>"Account not found"],404);
        } else{

        //get authenticated users Id
        $userId = Auth::id();
        $user = User::where('id',$userId)->firstOrFail();

        //get authenticated users role
        $role = Role::find($user->role_id);

        //ensure it is an admin or an employee creating the account
        if($role->name == 'customer'){
            if($account->user_id !== $userId){
                return response()->json([
                    'status' => '500',
                    'message' => 'Account does not belong to you'
                ],401);
            }
        }
        
        
        return response(['message'=> 'Account balance retrieved successfully','balance' => $account->balance ]);
        

        }
    }


     /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function history(Request $request, int $id)
    {
        $accountFound= Account::find($id);

        if(!$accountFound){
            return response()->json([
                'status' => '500',
                'message' => 'Account with id not found'
            ],401);
        }

        $transaction = Transaction::where('sender_account',$accountFound->account_number)
                                 ->orWhere('receiver_account',$accountFound->account_number)->get();
        if (!$transaction){
            return response()->json(['message'=>"Transaction with sending account not found"],404);
        } else{

        //get authenticated users Id
        $userId = Auth::id();
        $user = User::where('id',$userId)->firstOrFail();

        //get authenticated users role
        $role = Role::find($user->role_id);

        //ensure it is an admin or an employee creating the account
        if($role->name == 'customer'){
            if($accountFound->user_id !== $userId){
                return response()->json([
                    'status' => '500',
                    'message' => 'Account does not belong to you'
                ],401);
            }
        }
        
        
        return response(['message'=> 'Account history retrieved successfully','transactions' => $transaction ,'count'=> $transaction->count()]);
        

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        try {

             //check if user is authenticated
            $userId = Auth::id();
            $user = User::where('id',$userId)->firstOrFail();

            //get authenticated users role
            $role = Role::find($user->role_id);

            //ensure it is an admin or an employee updating the account
            if($role->name !== 'employee' && $role->name !== 'admin'){
                return response()->json([
                    'status' => '500',
                    'message' => 'Unauthorized action'
                ],401);
            }

            $account = Account::findOrFail($id);
            $account->account_type = $request->account_type;
            $account->user_id = $request->user_id;
            $account->balance = $request->balance;

            if ($account->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account updated successfully','account'=>$account]);
            }

            return response(['message' => 'Account does not exist']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        try {
            //get authenticated users Id
            $userId = Auth::id();
            $user = User::where('id',$userId)->firstOrFail();

            //get authenticated users role
            $role = Role::find($user->role_id);

            //ensure it is an admin performing the deletion
            if($role->name != 'admin'){
                return response()->json([
                    'status' => '500',
                    'message' => 'Unauthorized action'
                ],401);
            }

            $account = Account::findOrFail($id);

            if ($account->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Account deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}