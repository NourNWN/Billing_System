<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Transformers\AdminTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminProfileController extends Controller
{
    //Get admin’s data
    //localhost:8000/api/admin/getprofile  /GET/
    public function getProfile()
    {
        try {
            $user=auth()->user();
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
        return $this->response->item($user, new AdminTransformer())->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserProfileRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */

    //Create admin profile
    //localhost:8000/api/admin/editprofile   /POST/
    public function updateProfile(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'business_name'=>'required|string|min:3|max:30|unique:admin_profiles',
            'phone_number'=> 'required|string|min:10|max:15',
            'city'=>'required|string',
            'region'=>'required|string',
            'logo' => 'required|mimes:jpg,bmp,png',
        ]);

        if ($validator->fails()){
            return response()->json(['errors'=>$validator->errors()]);
        }
        try {
            if (!$user=auth()->user()){
                throw new NotFoundHttpException('Admin profile not found');
            }
            //Assign new Name For Image Passed
            $img_name = time() . '.' . $request->logo->extension();
            //Save Image In Public/Images Folder & Assign Path
            $request->logo->move(public_path('images'), $img_name);
            //Create Image Link URL
            $img_URL = URL::asset('images/'.$img_name);
            $bill = new Bill();
            $bill->logo = $img_URL;

            $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                'business_name'=>$request->business_name,
                'phone_number'=>$request->phone_number,
                'city'=>$request->city,
                'region'=>$request->region,
                'logo'=>$img_URL,
            ]);
        } catch (JWTException $e){
            throw $e;
        }

        $response=[
            'message'=>'Admin profile created successfully',
            'user_id'=>$user->id,
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserProfileRequest  $request
     * @param  \App\Models\AdminProfile  $userProfile
     * @return \Illuminate\Http\JsonResponse
     */

    //Edit one data or more in profile
    //localhost:8000/api/admin/updateprofile  /PUT/
    public function editProfile(Request $request)
    {
        try {
            if (!$user=auth()->user()){
                throw new NotFoundHttpException('Admin profile not found');
            }
            if (!empty($request->business_name)){
                $validator=Validator::make($request->all(), [
                    'business_name'=>'required|string|min:3|max:30|unique:admin_profiles',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'business_name'=>$request->business_name,
                ]);
            }

            if (!empty($request->phone_number)){
                $validator=Validator::make($request->all(), [
                    'phone_number'=> 'required|string|min:10|max:15',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'phone_number'=>$request->phone_number,
                ]);
            }

            if (!empty($request->logo_image_url)){
                $validator=Validator::make($request->all(), [
                    'logo_image_url',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }
                $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'logo_image_url'=>$request->logo_image_url,
                ]);
            }

            if (!empty($request->city)){
                $validator=Validator::make($request->all(), [
                    'city'=>'required|string',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'city'=>$request->city,
                ]);
            }

            if (!empty($request->region)){
                $validator=Validator::make($request->all(), [
                    'region'=>'required|string',
                ]);
                if ($validator->fails()){
                    return response()->json(['errors'=>$validator->errors()]);
                }

                $user->adminProfile()->updateOrCreate(['user_id'=>$user->id], [
                    'city'=>$request->region,
                ]);
            }


        } catch (JWTException $e){
            throw $e;
        }
        $response=[
            'message'=>'Admin profile updated successfully',
            'id'=>$user->id,
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdminProfile  $userProfile
     * @return \Illuminate\Http\JsonResponse
     */

    //Delete User from DB and it will logged out
    //localhost:8000/api/admin/deleteAccount  /DELETE/
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
            'message'=>'Admin profile deleted successfully',
            'id'=>$user->id,
        ];
        return response()->json($response, 200);
    }
}
