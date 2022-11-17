<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\AccountType;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Role;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Hash;
use Auth;

class TransactionController extends Controller
{

    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getTransactions(Request $request){

        try{
            $transaction_query = Transaction::latest();


            if($request->account){
                $transaction_query->where('sender_account_number','LIKE','%'.$request->account.'%')
                ->orWhere('receiver_account_number','LIKE','%'.$request->account.'%');
            }

            if($request->user_id){
                $transaction_query->where('user_id',$request->user_id);
            }

            if($request->sortBy && in_array($request->sortBy,['id','created_at'])){
                $sortBy = $request->sortBy;
            }else{
                $sortBy = 'created_at';
            }

            if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
                $sortOrder = $request->sortOrder;
            }else{
                $sortOrder = 'desc';
            }

            if($request->page_size){
                $page_size = $request->page_size;
            }else{
                $page_size = 9;
            }

           
            $start_date =  Carbon::now()->subMonth(1)->format('Y-m-d');
            $end = Carbon::now()->format('Y-m-d');
            $end_date = Carbon::createFromFormat('Y-m-d',  $end)->endOfDay();

            
            if($request->page){

                $start_date = Carbon::parse($start_date);
                $start_date->addHours(00)
                ->addMinutes(00);

                $end_date = Carbon::parse($end_date);
                $end_date->addHours(23)
                ->addMinutes(59);

                $transactions = $transaction_query->orderBY($sortBy,$sortOrder)->whereBetween('created_at', array($start_date, $end_date))->paginate($page_size);
           
            }else{
                $transactions = $transaction_query->orderBY($sortBy,$sortOrder)->get();
            }


            return $this->successResponse($transactions);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
       
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getTransaction($id) {

        try{
            $transaction= Transaction::where('id', $id)->get();
            return $this->successResponse($transaction);
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
    public function addTransaction(Request $request)
    {
        try{
            $validator = $this->validateTransaction();
            if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
            }

            if($request->sender_account == $request->receiver_account){
                return $this->errorResponse("Sending and receiving account cannot be the same", 422);
            }

            //sender account 
            $senderAccount = Account::where('account_number',$request->sender_account)->first();

            if(!$senderAccount){
              return $this->errorResponse("Sender account not found (". $request->sender_account. ")", 422);
            }

            $senderAccountType = AccountType::find($senderAccount->account_type);
            if(!$senderAccountType){
                return $this->errorResponse("Sender account type not found (". $senderAccount->account_type. ")", 422);
            }


            $user = User::find(Auth::id());
            $role = Role::findOrFail($user->role_id);
            if($role->name == "customer"){
                if(Auth::id() !== $senderAccount->user_id){
                   return $this->errorResponse("You are not the owner of this account: Unauthorized", 422);
                } 
            }


            //receiver account
            $receiverAccount = Account::where('account_number',$request->receiver_account)->first();

            if(!$receiverAccount){
              return $this->errorResponse("Receiver account not found (". $request->receiver_account. ")", 422);
            }

            $receiverAccountType = AccountType::find($receiverAccount->account_type);
            if(!$receiverAccountType){
                return $this->errorResponse("Sender account type not found (". $receiverAccount->account_type. ")", 422);
            }


            //check if pin is required

            if(!$senderAccount->transfer_without_pin){
                //check against hashed pin
                if(!$request->pin){
                    return $this->errorResponse("PIN Required for transaction to be effected", 422);
                }else if(!Hash::check($request->pin,$senderAccount->pin)){
                    return $this->errorResponse("INVALID Pin. Try again", 422);
                }
            }

            //update account balance of both sender and receiver;
            if($request->amount> $senderAccount->balance){
                return $this->errorResponse("Insufficient balance", 422);
            }

            $sendingAccountBalance = $senderAccount->balance - $request->amount;
            
          
            if($sendingAccountBalance < $senderAccountType->minimum_amount){
               return $this->errorResponse("The minimum balance of sender account should be more than or equal to ".$senderAccountType->minimum_amount, 422);
            }else if($sendingAccountBalance > $senderAccountType->maximum_amount){
                return $this->errorResponse("The maximum balance should be ".$senderAccountType->maximum_amount, 422);
            }

            $senderAccount->balance =  $sendingAccountBalance;

            $receiverAccountBalance = $receiverAccount->balance + $request->amount;

            if($receiverAccountBalance < $receiverAccountType->minimum_amount){
                return $this->errorResponse("The minimum balance of receiving account should be more than or equal to ".$receiverAccountType->minimum_amount, 422);
            }else if($receiverAccountBalance > $receiverAccountType->maximum_amount){
                return $this->errorResponse("The maximum balance should be ".$receiverAccountType->maximum_amount, 422);
            }

            $receiverAccount->balance =  $receiverAccountBalance;

 
 
            //effecting transaction
            $transaction=new Transaction();

            $transaction->sender_account= $senderAccount->id;
            $transaction->sender_account_number= $senderAccount->account_number;
            $transaction->receiver_account= $receiverAccount->id;
            $transaction->receiver_account_number= $receiverAccount->account_number;
            $transaction->status= "successful";
            $transaction->amount= $request->amount;
            $transaction->user_id= Auth::id();
            $transaction->save();

            //update sending and receiving accounts;

            $senderAccount->save();
            $receiverAccount->save();

            return $this->successResponse($transaction,"Transaction successful", 200);

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
    public function deleteTransaction($id)
    {
        try{

            Transaction::findOrFail($id)->delete();
            return $this->successResponse(null,"Deleted successfully", 200);

        }catch(\Exception $e){
            return $this->errorResponse( $e->getMessage(), 404);
        }
    }

    public function validateTransaction(){
        return Validator::make(request()->all(), [
            'sender_account' => 'required|string|max:11',
            'receiver_account' => 'required|string|max:11',
            'amount' => 'required|string|max:100',
            'transfer_without_pin' => 'nullable|boolean',
            'pin' => 'nullable|string|max:5',
        ]);
    }
}
