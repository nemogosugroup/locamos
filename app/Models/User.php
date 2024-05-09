<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    public $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'profile_id',
        'avatar',
        'first_name',
        'last_name',
        'birthday',
        'gender',
    ];

    protected $appends = [
        'fullname'
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
        'achievements' => 'json',
    ];  
    public function getFullnameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    public function getGenderAttribute()
    {
        return $this->attributes['gender'] == 1 ? 'Nam' : 'Nữ';
    }
    public function getBirthdayAttribute()
    {       
        return date('d-m-Y', strtotime($this->attributes['birthday']));
    }
    // public function getAchievementUserAttribute()
    // {
    //     $result = false;
    //     if ($this->attributes['achievements']) {
    //         $result = [];
    //         $achievements = json_decode($this->attributes['achievements'], true);
    //         if (empty($achievements)) {
    //             return null;
    //         }
    //         $result['medal'] = Medal::find($achievements['medal']);
    //         $result['category'] = CategoryMedal::find($achievements['medal_category']);
    //     }
    //     return $result;
    // }
}
