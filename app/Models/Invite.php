<?php

namespace App\Models;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invite extends Model
{
    use HasUUID, Notifiable;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'uuid';
    protected $fillable = ['id', 'email', 'role', 'token', 'used'];
}
