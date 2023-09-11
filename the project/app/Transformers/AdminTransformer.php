<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class AdminTransformer extends TransformerAbstract
{
    public function transform(User $user){

        return [
           // 'id'=>(int) $user->id,
            //'email'=>$user->email,
            'business_name'=>$user->adminProfile ? $user->adminProfile->business_name: 'business_name',
            'logo'=>$user->adminProfile ? $user->adminProfile->logo: 'logo_image',
           // 'city'=>$user->adminProfile ? $user->adminProfile->city: 'Unknown',
           // 'region'=>$user->adminProfile ? $user->adminProfile->region: 'Unknown',
            //'phone_number'=>$user->adminProfile ? $user->adminProfile->phone_number: 'Unknown',
        ];
    }
}
