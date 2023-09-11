<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminRoleController extends Controller
{
    //Get user’s role
    public function show($id)
    {
        if (!$user=User::find($id)){
            return new NotFoundHttpException('User not found');
        }

        return $user->getRoleNames();
    }

    //Change the user’s role
    public function changeRole(Request $request, $id)
    {
        if (!$user=User::find($id)){
            return new NotFoundHttpException('User not found');
        }

        try {
            $user->syncRoles([$request->role]);
        }catch (HttpException $e){
            throw $e;
        }

        return response()->json(['message'=>'User roles are updated'], 200);
    }
}
