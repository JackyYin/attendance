<?php

namespace App\Models;

use App\Traits\Enums;
use Illuminate\Database\Eloquent\Model;

class AttendanceDetail extends Model
{
    use Enums;

    const VIA_GPS  = 0;
    const VIA_WIFI = 1;

    protected $enumVias = [
        self::VIA_GPS   => 'GPS',
        self::VIA_WIFI  => 'WIFI'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance_detail';

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
        'id',
        'attendance_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id', 'id');
    }

    public function getViaAttribute()
    {
        return self::getEnum('via')[$this->attributes['via']];
    }
}
