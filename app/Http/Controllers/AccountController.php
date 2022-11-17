<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use App\Models\Role;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Hash;

class AccountController extends Controller
{

    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAccounts(){
        try{
            $accounts= Account::latest()->get();
            return $this->successResponse($accounts);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAccount($id) {

        try{
            $account= Account::where('id', $id)->get();
            return $this->successResponse($account);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addAccount(Request $request)
    {
        try{
            $validator = $this->validateAccount();
            if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
            }


            $found = Account::where('account_number',$request->account_number)->first();

            if($found){
              return $this->errorResponse("Duplicate account numbers are not allowed: (". $request->account_number. ")", 422);
            }

            $account=new Account();

            $accountType = AccountType::findOrFail($request->account_type);
            $user = User::findOrFail($request->user_id);
            $role = Role::findOrFail($user->role_id);
            if($role->name !== "customer"){
                return $this->errorResponse("Accounts can only be created for customers", 422);
            }

            $account->account_number= $request->account_number;
            $account->account_type=$accountType->id;  
            $account->user_id=$user->id;  

            if($request->balance < $accountType->minimum_amount){
               return $this->errorResponse("The minimum balance should be ".$accountType->minimum_amount, 422);
            }else if($request->balance > $accountType->maximum_amount){
                return $this->errorResponse("The maximum balance should be ".$accountType->maximum_amount, 422);
            }

            $account->balance=$request->balance; 
            $account->transfer_without_pin=$request->transfer_without_pin; 
            $account->pin= Hash::make($request->pin);  
            $account->save();

            return $this->successResponse($account,"Saved successfully", 200);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(Request $request, $id)
    {

        try{

            $validator = $this->validateAccount();
            if($validator->fails()){
               return $this->errorResponse($validator->messages(), 422);
            }

            $account=Account::findOrFail($id);
        
            if($request->account_number){
                $found = Account::where('account_number',$request->account_number)
                ->where('id', '!=', $id)->first();

                if($found){
                  return $this->errorResponse("Duplicate account numbers are not allowed: (". $request->account_number. ")", 422);
                }

                $account->account_number = $request->account_number ;
            }
           

            $accountType = AccountType::findOrFail($request->account_type);
            $account->account_type = $accountType->id;
            
           
            if($request->user_id){
                $user = User::findOrFail($request->user_id);
                $role = Role::findOrFail($user->role_id);
                if($role->name !== "customer"){
                    return $this->errorResponse("Accounts can only be created for customers", 422);
                }
                $account->user_id = $user->id;  
            }

            if($request->balance){

              if($request->balance < $accountType->minimum_amount){
                return $this->errorResponse("The minimum balance should be ".$accountType->minimum_amount, 422);
              }else if($request->balance > $accountType->maximum_amount){
                return $this->errorResponse("The maximum balance should be ".$accountType->maximum_amount, 422);
              }

              $account->balance = $request->balance;  
            }

            if($request->transfer_without_pin == 0 || $request->transfer_without_pin == 1){
              $account->transfer_without_pin = $request->transfer_without_pin;  
            }

            if($request->pin){
              $account->pin =  Hash::make($request->pin);  
            }

            $account->save();

            return $this->successResponse($account,"Updated successfully", 200);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
        
        
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAccountBalance(Request $request, $id)
    {

        try{

            if(!$id){
              return $this->errorResponse("Missing account id", 422); 
            }
           
            $account= Account::findOrFail($id);

            return $this->successResponse($account->balance,"Account balance", 200);

        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteAccount($id)
    {
        try{

            Account::findOrFail($id)->delete();
            return $this->successResponse(null,"Deleted successfully", 200);

        }catch(\Exception $e){
            return $this->errorResponse( $e->getMessage(), 404);
        }
    }

    public function validateAccount(){
        return Validator::make(request()->all(), [
            'account_number' => 'required|string|max:10',
            'account_type' => 'required|string',
            'balance' => 'required|string|max:100',
            'user_id' => 'required|string',
            'transfer_without_pin' => 'nullable|boolean',
            'pin' => 'nullable|string|max:5',
        ]);
    }
}
