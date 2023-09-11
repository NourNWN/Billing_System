<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class isBillAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $billId=$request->route('bill');

        //Does the bill request belongs to the right user?
        if (!$user=auth()->user()){
            throw new NotFoundHttpException('Uer not found');
        }
        if (!$user->hasRole('admin')){
            throw new AccessDeniedHttpException('User dose not have the right rol');
        }

        $exists= $user->bills()
                       ->where(function ($query) use ($billId, $user) {
                            $query->where('user_id', $user->id);
                            if ($billId){
                                $query->where('bill_id', $billId);
                            }
                       })
                       ->exists();
        if (!$exists){
            throw new AccessDeniedHttpException('That’s not your bill or you don’t have any bills. Do you want to create one?');
        }
        return $next($request);
    }
}
