<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'middleName',
        'nameExt',
        'email',
        'password',
        'adminID',
        'loginNum',
        'last_login_reset',
        'otp',
        'otp_expires_at',
        'otp_verified_at',
        'role_number',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**public function updateLoginInfo()
    {
        $now = Carbon::now();
        if ($this->last_login_reset === null || $now->diffInDays($this->last_login_reset) >= 30) {
            $this->loginNum = 1;
            $this->last_login_reset = $now;
        } else {
            $this->loginNum++;
        }
        $this->save();
    }
        */
}
