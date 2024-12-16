<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'nid',
        'user_name',
        'password'
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
        'password' => 'hashed',
    ];
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->attributes['user_name'] = $this->slugify($value);
    }
    /**
     * Slugify function
     */
    private function slugify($value)
    {
        $filterValue = preg_replace('/[^a-zA-Z0-9_ -]/s', ' ', $value);
        $slug = str_replace(' ', '_', strtolower($filterValue));
        $count = User::where('user_name', 'LIKE', $slug . '%')->count();
        $suffex = $count ? $count + 1 : '';
        $slug .= $suffex;
        return $slug;
    }

    public static function updateUserPassword($userId, $password)
    {
        $user = self::find($userId);
        if (isset($user->id) && !empty($user->id)) {
            $user->password = $password;
            $user->save();
        }
    }
}
