<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Championship extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function participants()
    {
        return $this->belongsToMany('App\User');
    }

    public function races()
    {
        return $this->hasMany('App\Race');
    }
}
