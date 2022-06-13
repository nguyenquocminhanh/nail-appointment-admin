<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StaffTime;
use App\Models\User;

class StaffDay extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function staff_times(){
        return $this->hasMany(StaffTime::class, 'staff_day_id', 'id');
    }
}
