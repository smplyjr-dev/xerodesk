<?php

namespace App\Models\User;

use App\Mail\EmailVerification;
use App\Mail\ResetPassword;
use App\Models\Company\Company;
use App\Models\User\UserBio;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'username',
        'email',
        'password',
        'profile_picture',
        'status',
        'type',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status'            => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ResetPassword($this, $token));
    }

    public function sendEmailVerificationNotification()
    {
        Mail::to($this->email)->send(new EmailVerification($this));
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function role()
    {
        return $this->roles[0];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bio()
    {
        return $this->hasOne(UserBio::class);
    }

    public function token()
    {
        try {
            // attempt to verify the credentials and create a token for the user
            $token = JWTAuth::getToken();
            $decoded = JWTAuth::getPayload($token)->toArray();

            return $decoded;
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], 500);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], 500);
        }
    }

    public function replies()
    {
        return $this->hasMany(UserReply::class);
    }
}
