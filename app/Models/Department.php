<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'departments';

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
    protected $hidden = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'department_id', 'id');
    }

}
