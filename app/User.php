<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function deviceIds()
    {
        return $this->hasMany(DeviceId::class);
    }

    public function getSentMsgs()
    {
        return $this->hasMany(Message::class, 'sent_by');
    }

    public function getReceivedMsgs()
    {
        return $this->hasMany(Message::class, 'sent_to');
    }

    public function ChatMsgs($otherUser)
    {
        return Message::where(['sent_by' => $this->id, 'sent_to' =>$otherUser->id])
            ->orWhere((['sent_by' => $otherUser->id, 'sent_to' =>$this->id]))

            ->get();
    }
}
