<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param $role_id
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role_id)
    {
        $user = Auth::user();

        if($request->user()->role->role_name !== 'Admin') {
            if ($user->role_id == $role_id) {
                return response()->json([
                    'message'=>'This is okay'
                ],Response::HTTP_OK);
            }

            return response()->json([
                'message' => 'You have no Permission'
            ],Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
