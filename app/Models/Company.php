<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Company extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
        return [
            'model' => get_class($this)
        ];
    }

    public function profile()
    {
        return $this->hasOne(CompanyProfile::class, 'company_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'company_id', 'id');
    }

    public function locations()
    {
        return $this->hasMany(Location::class, 'company_id', 'id');
    }

    public function networks()
    {
        return $this->hasMany(Network::class, 'company_id', 'id');
    }

    public function contactPersons()
    {
        return $this->hasMany(CompanyContactPerson::class, 'company_id', 'id');
    }
    public function correspondingDepartment()
    {
        return $this->departments()->master()->first();
    }
}
