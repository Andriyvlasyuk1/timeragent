<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function clients()
    {
        return $this->hasMany('App\Client');
    }

    public function projects()
    {
        return $this->hasManyThrough('App\Project', 'App\Client');
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
