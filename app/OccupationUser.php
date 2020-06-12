<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OccupationUser extends Authenticatable
{
    use Notifiable;

        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'funcao', 
        'address',
        'district',
        'state',
        'city',
        'number',
        'number_doc_lincese',
        'zip_code',
        'complement',
        'country',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public static function store($request, $user)
    {
        $occupationUser = new OccupationUser();

        $occupationUser->address = $request->address;
        $occupationUser->district = $request->address_district;
        $occupationUser->state = $request->address_state;
        $occupationUser->city = $request->address_city;
        $occupationUser->number = $request->address_number;
        $occupationUser->zip_code = $request->address_zip_code;
        $occupationUser->complement = $request->address_comp;
        $occupationUser->number_doc_license = $request->number_doc_license;
        $occupationUser->country = 'BR';
        $occupationUser->phone = $request->phone_number;
        $occupationUser->user_id = $user->id;
        $occupationUser->funcao = $request->funcao;

        $occupationUser->save();

        return $occupationUser;
    }

    /**
    * Checks whether the document passed by parameter exists for the user also passed by parameter.
    *
    * @param   \App\User   $user
    * @param   string  document
    * @return  bool
    */
    public static function checkIfThisDocumentExists(User $user, string $document): bool
    {
        $checking = OccupationUser::where('number_doc_license', $document)->where('user_id', $user->id)->first();
        
        if ($checking) {
            return true;
        }

        return false;
    }

    public static function findByUser(User $user)
    {
        return OccupationUser::where('user_id', $user->id)->orderBy('id', 'desc')->first();
    }

     /**
     *  Create new occupation default
     * @param   Array  $data
     * @return  \App\OccupationUser
     */
    public static function createDefaultInfo(array $data): OccupationUser 
    {
        $occupationUser = new OccupationUser();

        $occupationUser->user_id = $data['user_id'];
        $occupationUser->phone = '(00) 00000-0000';
        $occupationUser->created_at = date('Y-m-d H:s:i');
        $occupationUser->updated_at = date('Y-m-d H:s:i');

        $occupationUser->save();

        return $occupationUser;
    }

}