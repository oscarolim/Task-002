<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'number', 'apikey'
    ];

    protected $hidden = [
        //'apikey', //Disabled for ease of testing. In a real world scenario, this shouldn't be returned by the api.
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function championships()
    {
        return $this->belongsToMany('App\Championship');
    }
}
