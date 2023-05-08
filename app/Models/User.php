<?php

namespace App\Models;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens, HasFactory, Notifiable;
    // Rest omitted for brevity
    protected $table = 'users';

    protected $fillable = [
        'company_name',
        'company_address',
        'company_industry',
        'contact_person_name',
        'contact_person_phone',
        'company_location',
        'company_size',
        'profile_photo',
        'email',
        'password',


    ];
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
