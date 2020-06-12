<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\OccupationUser;
use App\DocumentsUser;
use App\SpecialityUser;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email', 
        'password',
        'photo',
        'phone_number',
        'address',
        'number',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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
     * Cria usuário no sistema
     *
     * @return  \App\User
     */
    public static function createNewUser(array $data): User
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_number  = $data['phone_number'];
        $user->password = Hash::make($data['password']);
        $user->address = $data['address'];
        $user->number = $data['number'];
        $user->city = $data['city'];
        $user->state = $data['state'];
        $user->state = $data['state'];
        $user->neighborhood = $data['neighborhood'];
        $user->zip_code = $data['zip_code'];
        $user->birthday = $data['birthday'];
        if(isset($data['photo']) && !empty($data['photo'])) {
            $user->photo = $data['photo'];
        }
        $user->created_at = date('Y-m-d h:s:i');
        $user->updated_at = date('Y-m-d h:s:i');
        
        $user->save();

        return $user;
    }

     /**
     * Cria usuário padrão do sistema
     *
     * @return  \App\User
     */
    public static function createUser(array $data): User
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone_number    = $data['phone_number'];
        $user->password = Hash::make($data['password']);

        $user->save();

        $info_address_professionals = OccupationUser::createDefaultInfo(['user_id' => $user->id]);

        return $user;
    }

    public function documents()
    {
        return $this->hasMany(DocumentsUser::class, 'user_id');
    }

    public function occupation()
    {
        return $this->hasOne(\App\OccupationUser::class, 'user_id');
    }

    public function specialties()
    {
        return $this->hasOne(\App\SpecialityUser::class, 'user_id');
    }

    public function getNameOccupation()
    {
        return OccupationUser::where('user_id', $this->id)->first();
    }

    public function getSpecialties()
    {
        return SpecialityUser::where('user_id', $this->id)->get();
    }

    public function getDocuments()
    {
        return DocumentsUser::where('user_id', $this->id)->get();
    }


    /**
     * Procura e retorna usuario no banco de dados por e-mail e/ou documento.
     *
     * @param   string  $email
     * @param   string  $doc
     * @return  \App\User|null
     */
    public static function getCustomer($request)
    {
        $user = null;
        if ($request->email == null || $request->email == '') {
            $user = User::where('document', $request->doc)->first();
        }else{
            $user = User::where('email', $request->email)->first();
        }

        return $user;
    }

    # Busca um usuário pelo e-mail
    public static function searchByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public static function verifyEmailExists($email, $idUser)
    {
        return User::where('id', '!=', $idUser)->where('email', $email)->count();
    }

}
