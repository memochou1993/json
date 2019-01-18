<?php

namespace App;

use Carbon\Carbon;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'data', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'object',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'code',
    ];

    public function getCodeAttribute()
    {
        return Hashids::encode($this->attributes['id']);
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->diffForHumans();
    }

    public function getUpdatedAtAttribute()
    {
        return Carbon::parse($this->attributes['updated_at'])->diffForHumans();
    }
}
