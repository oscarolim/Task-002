<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Race extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'championship_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function championship()
    {
        return $this->belongsTo('App\Championship');
    }

    public function participants()
    {
        return $this->belongsToMany('App\User')->withPivot('points');
    }
}
