<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceId extends Model
{
    protected $fillable = ['deviceType','registration_id'];
}