<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Multicaret\Acquaintances\Traits\Friendable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;
    use Friendable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    //Encrypting the password
    public function setPasswordAttribute($password){
        if (!empty($password)){
            $this->attributes['password']=bcrypt($password);
        }
    }

             ////////Relations////////
    public function costumerProfile(){
        return $this->hasOne(CostumerProfile::class, 'user_id');
    }

    public function adminProfile(){
        return $this->hasOne(AdminProfile::class, 'user_id');
    }

    public function contacts(){
        return $this->hasMany(Contact::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function bills(): BelongsToMany
    {
        return $this->belongsToMany(Bill::class,
            'user_bill',
            'user_id', 'bill_id');
    }

    public function admins()
    {
        return $this->hasMany(Friend::class, 'admin_id');
    }

    public function costumers()
    {
        return $this->hasMany(Friend::class, 'costumer_id');
    }
}
