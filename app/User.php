<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Mpociot\Teamwork\Traits\UserHasTeams;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,UserHasTeams;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function projects() {
        return $this->belongsToMany('App\Project', 'projects_users', 'user_id', 'project_id')
            ->withPivot('billable_rate', 'cost_rate')
            ->withTimestamps();
    }
}