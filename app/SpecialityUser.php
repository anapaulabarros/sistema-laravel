<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SpecialityUser extends Authenticatable
{
    use Notifiable;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public static function store($request, $user)
    {
        $specialityUser = new SpecialityUser();

        $specialityUser->name = $request->name;
        $specialityUser->user_id = $user->id;

        $specialityUser->save();

        return $specialityUser;
    }
}