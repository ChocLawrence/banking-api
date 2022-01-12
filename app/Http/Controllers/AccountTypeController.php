<?php

namespace App\Http\Controllers;

use App\Models\AccountType;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{
    public function index(){
        return AccountType::all();
    }

    public function store(Request $request){
        try {
            $accountType = new AccountType();
            $accountType->name = $request->name;
            $accountType->minimum_amount = $request->minimum_amount;
            $accountType->maximum_amount = $request->maximum_amount;

            if ($accountType->save()) {
                return response()->json(['status' => 'success', 'message' => 'Account Type created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

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
