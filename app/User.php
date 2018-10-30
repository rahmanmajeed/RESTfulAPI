<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;
    const VERIFIED_USER='1';
    const NOTVERIFIED_USER='0';

    const ADMIN_USER='true';
    const REGULAR_USER='false';
    protected $table='users';
    protected $dates=['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verified','verification_token','admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token'
    ];

    /** Verify user */
    /**
     * @return bool
     */
    public function isVerified()
    {
        return $this->verified=User::VERIFIED_USER;
    }

    /** Verify user type */
    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin=User::ADMIN_USER;
    }

    /** Generate Verification Code */
    /**
     * @return string
     */
    public static function generateVerificationCode()
    {
        return str_random(60);
    }

    /**
     * @return attributes
     */
    ## mutators define
    public function setNameAttribute($name)
    {
        $this->attributes['name']=strtolower($name);
    }

     /**
     * @return attributes
     */
    ## accessors define
    public function getNameAttribute($name)
    {
        return ucwords($name);
    }
}
