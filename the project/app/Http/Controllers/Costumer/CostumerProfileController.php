<?php

namespace App\Http\Controllers\Costumer;

use App\Http\Controllers\Controller;
use App\Transformers\CostumerTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;


class CostumerProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Get costumer’s data
    //localhost:8000/api/costumer/getprofile  /GET/
    public function getProfile()
    {
        try {
            $user=auth()->user();
        } catch (NotFoundHttpException $e){
            throw $e;
        }
        return $this->response->item($user, new CostumerTransformer())->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserProfileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    //Create costumer profile
    //localhost:8000/api/costumer/editprofile   /POST/
    public function editProfile(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'first_name'=>'required|string|min:3|max:30',
            'last_name'=>'required|string|min:3|max:30',
            'user_name'=>'required|string|min:3|unique:costumer_profiles',
            'phone_number'=> 'required|string|min:10|max:15',
        ]);
        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        try {
            if (!$user=auth()->user()){
                throw new NotFoundHttpException('Costumer profile not found');
            }
            $user->costumerProfile()->updateOrCreate(['user_id'=>$user->id], [
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'user_name'=>$request->user_name,
                'phone_number'=>$request->phone_number,
                'active'=>true,
            ]);
        } catch (JWTException $e){
            throw $e;
        }
        $response=[
            'message'=>'Costumer profile created successfully',
            'user_id'=>$user->id,
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserProfileRequest  $request
     * @param  \App\Models\CostumerProfile  $userProfile
     * @return \Illuminate\Http\JsonResponse
     */

    //Edit one data or more in profile
    //localhost:8000/api/costumer/updateprofile  /PUT/
    public function updateProfile(Request $request)
    {
        try {
            if (!$user=auth()->user()){
                throw new NotFoundHttpException('Costumer profile not found');
            }
            if (!empty($request->first_name)){
                $validator=Validator::make($request->all(), [
                    'first_name'=>'required|string|min:3|max:30',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->costumerProfile()->updateOrCreate(['user_id'=>$user->id], [
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
                $user->costumerProfile()->updateOrCreate(['user_id'=>$user->id], [
                     'last_name'=>$request->last_name,
                ]);
            }

            if (!empty($request->user_name)){
                $validator=Validator::make($request->all(), [
                    'user_name'=>'required|string|min:3|unique:costumer_profiles',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->costumerProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'user_name'=>$request->user_name,
                ]);
            }

            if (!empty($request->phone_number)){
                $validator=Validator::make($request->all(), [
                    'phone_number'=> 'required|string|min:10|max:15',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->costumerProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'phone_number'=>$request->phone_number,
                ]);
            }
        } catch (JWTException $e){
            throw $e;
        }
        $response=[
            'message'=>'Costumer profile updated successfully',
            'id'=>$user->id,
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CostumerProfile  $userProfile
     * @return \Illuminate\Http\JsonResponse
     */

    //Delete User from DB and it will logged out
    //localhost:8000/api/costumer/deleteAccount  /DELETE/
    public function deleteAccount()
    {
        try {
            $user=auth()->user();
            $user->delete();
            auth()->logout();
        } catch (JWTException $e){
            throw $e;
        }
        $response=[
            'message'=>'Costumer profile deleted successfully',
            'id'=>$user->id,
        ];
        return response()->json($response, 200);
    }
}
