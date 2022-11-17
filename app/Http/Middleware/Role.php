<?php

namespace App\Http\Middleware;

use Closure;

use Auth;
use App\Traits\ApiResponser;

class Role
{

    use ApiResponser;
    

    public function handle($request, Closure $next, ... $roles)
    {

        //check if user is authenticated
        if (!Auth::check()) 
         return $this->errorResponse("Not logged in", 422);

        $user = Auth::user();

        //Ensure admin always has access to all routes
        if($user->isAdmin())
            return $next($request);

    
        foreach($roles as $role) {
            // Check if user has the role for particular route group
            if($user->hasRole($role))
                return $next($request);
        }

        return $this->errorResponse("Unauthorized action", 422);
    }
}
