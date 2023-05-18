<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,HasFactory,Notifiable;

    protected $fillable = ['name','phone', 'first_name', 'maiden_name', 'last_name', 'email', 'image', 'password', 'designation', 'is_active','fcm_token','country_id'];

    public function usercompany()
    {
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }
    public function userdocument()
    {
        return $this->hasMany(UserDocument::class, 'user_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }
    public function country()
{
    return $this->belongsTo(Country::class, 'country_id', 'id');
}

    public function getImageAttribute($path)
    {
        if ($path){
            return asset($path);
        }else{
            return null;
        }
    }

}
