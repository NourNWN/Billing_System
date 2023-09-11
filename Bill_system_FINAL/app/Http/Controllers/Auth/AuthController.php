<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    //localhost:8000/api/auth/signup  /POST/
    public function signup(Request $request)
    {
        $rules=[

            'email'=>[
                'required', 'email', 'max:255', 'unique:users,email'
            ],
            'password'=>[
                'required', 'string', 'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'role_id'=>['required', 'exists:roles,id'],
        ];
        $data_info=Validator::make($request->all(), $rules);

        if ($data_info->fails()){
            return response()->json(['message'=>$data_info->errors()]);
        }

        $user=User::create([
            'email'=>$request->email,
            'password'=>$request->password,
            'role_id'=>$request->role_id,
        ]);
        $role=Role::find($request->role_id);
        $user->assignRole($role);

        try {
            $token=auth()->login($user);
        } catch (JWTException $e){
            throw $e;
        }

        return $this->respondWithToken($token);
    }

    //localhost:8000/api/auth/login  /POST/
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules=[
            'email'=>[
                'required', 'email', 'max:255'
            ],
            'password'=>[
                'required', 'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ]
        ];

        $data_info=Validator::make($request->all(), $rules);

        if ($data_info->fails()){
            return response()->json(['message'=>$data_info->errors()]);
        }

        $credentials=$request->only('email', 'password');

        try {
           if (!$token=auth()->attempt($credentials)){
               throw new UnauthorizedHttpException('Invalid credentials');
           }
        } catch (JWTException $e){
            return response()->json(['error'=>'Could not create token']);
        }

        return $this->respondWithToken($token);
    }

    //Refresh the token
    //localhost:8000/api/auth/token/refresh  /POST/
    public function refresh(){
        try {
            if (!$token=auth()->getToken()){
                throw new NotFoundHttpException('Token Dose Not Exist');
            }
            return $this->respondWithToken(auth()->refresh($token));
        } catch (JWTException $e){
            throw $e;
        }
    }

    //localhost:8000/api/auth/logout  /POST/
    public function logout(){
        try {
            auth()->logout();
        } catch (JWTException $e){
            throw $e;
        }
        return response()->json(['message'=>'User logged out successfully']);
    }

    private function respondWithToken($token){
        return response()->json([
            'access_token'=>$token,
            'token_type'=>'bearer',
            'expires_in'=>auth()->factory()->getTTL() * 60,
        ]);
    }
}
