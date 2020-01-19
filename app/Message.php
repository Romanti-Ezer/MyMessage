<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $guarded = [''];

    protected $dates = ['startDate', 'expirationDate'];

    public function path()
    {
        return url( '/messages/' . $this->id );
    }

    public function setStartDateAttribute($startDate)
    {
        $this->attributes['startDate'] = Carbon::parse($startDate);
    }

    public function setExpirationDateAttribute($expirationDate)
    {
        $this->attributes['expirationDate'] = Carbon::parse($expirationDate);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
