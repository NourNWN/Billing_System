<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class CostumerTransformer extends TransformerAbstract
{
    public function transform(User $user){

       return [
         //  'id'=>(int) $user->id,
          // 'email'=>$user->email,
           'user_name'=>$user->costumerProfile ? $user->costumerProfile->user_name: 'User_name',
          // 'first_name'=>$user->costumerProfile ? $user->costumerProfile->first_name: 'Unknown',
          // 'last_name'=>$user->costumerProfile ? $user->costumerProfile->last_name: 'Unknown',
          // 'phone_number'=>$user->costumerProfile ? $user->costumerProfile->phone_number: 'Unknown',
       ];
    }

}
