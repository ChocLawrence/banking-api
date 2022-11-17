<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AccountTypeController extends Controller
{

    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAccountTypes(){
        try{
            $accountTypes= AccountType::latest()->get();
            return $this->successResponse($accountTypes);
        }catch(\Exception $e){
            return $this->errorResponse($e->getMessage(), 404);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAccountType($id) {

        try{
            $accountType= AccountType::where('id', $id)->get();
            return $this->successResponse($accountType);
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
    public function addAccountType(Request $request)
    {
        try{
            $validator = $this->validateAccountType();
            if($validator->fails()){
            return $this->errorResponse($validator->messages(), 422);
            }


            $found = AccountType::where('name',$request->name)->first();

            if($found){
              return $this->errorResponse("Duplicate account types are not allowed: (". $request->name. ")", 422);
            }

            $accountType=new AccountType();
            $accountType->name= $request->name;
            $accountType->minimum_amount=$request->minimum_amount;  
            $accountType->maximum_amount=$request->maximum_amount; 
            $accountType->save();

            return $this->successResponse($accountType,"Saved successfully", 200);

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
    public function updateAccountType(Request $request, $id)
    {

        try{

            $validator = $this->validateAccountType();
            if($validator->fails()){
               return $this->errorResponse($validator->messages(), 422);
            }

            $accountType=AccountType::findOrFail($id);
        
            $accountType->name=$request->name; 
            $accountType->minimum_amount=$request->minimum_amount; 
            $accountType->maximum_amount=$request->maximum_amount;  
            $accountType->save();

            return $this->successResponse($accountType,"Updated successfully", 200);

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
    public function deleteAccountType($id)
    {
        try{

            AccountType::findOrFail($id)->delete();
            return $this->successResponse(null,"Deleted successfully", 200);

        }catch(\Exception $e){
            return $this->errorResponse( $e->getMessage(), 404);
        }
    }

    public function validateAccountType(){
        return Validator::make(request()->all(), [
            'name' => 'required|string|max:20',
            'minimum_amount' => 'required|string|max:100',
            'maximum_amount' => 'required|string|max:100'
        ]);
    }
}
