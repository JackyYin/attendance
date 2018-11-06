<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agents';

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

    public function users()
    {
        return $this->belongsToMany(User::class, 'agent_user', 'agent_id', 'user_id')
            ->withPivot('email_auth_token');
    }

}
