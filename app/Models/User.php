<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Role;
use App\Models\Service;
use App\Models\StaffDay;
use App\Models\Appointment;
use App\Models\UserAppNoti;
use App\Models\AdminUpdateNoti;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'role_id',
        'profile_image',
        'email',
        'password',
        'phone_number',
        'visible'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role() {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function services(){
        return $this->belongsToMany(Service::class, 'service_users', 'user_id', 'service_id');
    }

    public function staff_days(){
        return $this->hasMany(StaffDay::class, 'user_id', 'id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class, 'user_id', 'id');
    }

    public function user_app_noties(){
        return $this->hasMany(UserAppNoti::class, 'user_id', 'id');
    }

    public function admin_update_noties(){
        return $this->hasMany(AdminUpdateNoti::class, 'user_id', 'id');
    }
}
