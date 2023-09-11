<?php

namespace App\Transformers;

use Dingo\Api\Http\Request;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

class UserPermissionsTransformer extends TransformerAbstract
{
    public function transform(Permission $permission)
    {
       return [
           'id'=>(int) $permission->id,
           'name'=>$permission->name,
       ];
    }
}
