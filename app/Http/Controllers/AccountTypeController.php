<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Validator;



/** 
 * @group Account Type Management
 *
 * Account Type functionalities
 **/
class AccountTypeController extends Controller
{
     /**
     * GET account types
     * @apiResourceModel App\Models\AccountType
     * @param \Illuminate\Http\Request $request
     */
    public function index(){
        return AccountType::all();
    }

    /**
     * CREATE account type
     * @bodyParam name string required Name of the account type. Example: Savings
     * @bodyParam miniumum_amount string required Minimum amount of account. Example: 100000001
     * @bodyParam maxiumum_amount string required Maximum amount of account. Example: 999999999
     * @apiResourceModel App\Models\AccountType
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request){
        try {
            $userId = Auth::id();
            $user = User::where('id',$userId)->firstOrFail();

            $input = $request->all();

            $validator = Validator::make($input,[
                'name' => 'required|string',
                'minimum_amount' => 'required|string',
                'maximum_amount' => 'required|string'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => '500',
                    'message' => 'Bad Request',
                    'error' => $validator->errors()
                ],401);
            }

            
            $accountType = new AccountType();
            $accountType->name = $request->name;
            $accountType->minimum_amount = $input['minimum_amount'];
            $accountType->maximum_amount = $input['maximum_amount'];

            if ($accountType->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account Type created successfully'],201);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    /**
     * UPDATE account type
     * @bodyParam name string required Name of the account type. Example: Savings
     * @bodyParam miniumum_amount string required Minimum amount of account. Example: 100000001
     * @bodyParam maxiumum_amount string required Maximum amount of account. Example: 999999999
     * @apiResourceModel App\Models\AccountType
     * @param \Illuminate\Http\Request $request
     */
    public function update(Request $request, $id)
    {
        try {
            $accountType = AccountType::findOrFail($id);
            $accountType->name = $request->name;
            $accountType->minimum_amount = $request->minimum_amount;
            $accountType->maximum_amount = $request->maximum_amount;

            if ($accountType->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account type updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

     /**
     * DELETE account type
     * @apiResourceModel App\Models\AccountType
     * @param \Illuminate\Http\Request $request
     */

    public function destroy($id)
    {
        try {
            $accountType = AccountType::findOrFail($id);

            if ($accountType->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Account type deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
