<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_profile';

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
    public $timestamps = true;

    /**
     * Get the format for database stored dates.
     *
     * @return string
     */
    public function getDateFormat() : string
    {
        return 'U';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
