<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CostumerProfile;
use App\Models\User;
use App\Transformers\CostumerTransformer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Dingo\Api\Http\Response|\Illuminate\Http\JsonResponse|object
     */

    //Get all users
    public function getUsers()
    {
        if (! $users=User::all()){
            throw new NotFoundHttpException('Users not found');
        }
        return $this->response
            ->collection($users, new CostumerTransformer())
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|object
     */

    //Create new costumer
    public function store(Request $request)
    {
       $validator=Validator::make($request->all(),[
           'email'=>'required|email|unique:users,email|max:30',
            'name'=>'required|string|min:3|max:10',
            'first_name'=>'required|string|min:3|max:10',
            'last_name'=>'required|string|min:3|max:10'
        ]);

       if ($validator->fails()){
           response()->json(['error'=>$validator->errors()]);
       }

        try {
            $user=User::firstOrCreate(['email'=>$request->email], [
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>'Test@123',
            ]);

            $user->userProfile()->updateOrCreate(['user_id'=>$user->id], [
                'first_name'=>$request->firstName,
                'last_name'=>$request->lastName,
                'active'=>true,
            ]);

            $user->assignRole('costumer');

        } catch (HttpException $e){
           throw $e;
        }

       //Maybe send a mail to the user about account creation and option to reset password
       $response=[
           'message'=>'User created successfully',
           'id'=>$user->id
       ];

       return response()->json($response)->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $user_name
     * @return \Illuminate\Http\Response
     */

    //Pass an id and then fetches information
    public function show($user_name)
    {
        if (!$user=CostumerProfile::find($user_name)){
            throw new NotFoundHttpException('User not found with username: '.$user_name);
        }

        return $this->response->item($user, new CostumerTransformer);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */

    //Update user’s information
    public function update(Request $request, $id)
    {
        if (!$user=User::find($id)){
            throw new NotFoundHttpException('User not found with id = '.$id);
        }

        if (!$user=auth()->user()){
            throw new NotFoundHttpException('User profile not found');
        }
        if (!empty($request->first_name)){
            $validator=Validator::make($request->all(), [
                'first_name'=>'required|string|min:3|max:30',
            ]);
            if ($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }

            $user->userProfile()->updateOrCreate(['user_id'=>$id], [
                'first_name'=>$request->first_name,
            ]);
        }

        if (!empty($request->last_name)){
            $validator=Validator::make($request->all(), [
                'last_name'=>'required|string|min:3|max:30',
            ]);
            if ($validator->fails()){
                return response()->json(['errors'=>$validator->errors()]);
            }
            $user->userProfile()->updateOrCreate(['user_id'=>$id], [
                'last_name'=>$request->last_name,
            ]);
        }

        $response=[
            'message'=>'User updated successfully',
            'id'=>$id,
        ];

        return response()->json($response)->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|NotFoundHttpException
     */

    //Get user’s permissions
    /*  public function show($id)
      {
          if (!$user=User::find($id)){
              return new NotFoundHttpException('User not found');
          }

          return $this->response
                      ->collection($user->getAllPermissions(), new UserPermissionsTransformer());
      }*/

}
