<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index(){
        return Role::all();
    }

    public function store(Request $request){
        try {
            $role = new Role();
            $role->name = $request->name;

            if ($role->save()) {
                return response()->json(['status' => 'success', 'message' => 'Role created successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;

            if ($role->save()) {
                return response()->json(['status' => 'success', 'message' => 'Role updated successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->delete()) {
                return response()->json(['status' => 'success', 'message' => 'Role deleted successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
