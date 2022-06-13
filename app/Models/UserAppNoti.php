<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;

class UserAppNoti extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function appointments(){
        return $this->hasMany(Appointment::class, 'user_noti_id', 'id');
    }
}
