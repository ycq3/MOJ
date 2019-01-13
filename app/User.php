<?php

namespace App;

use App\Model\OauthAccessToken;
use App\Model\UserInformation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Jrean\UserVerification\Traits\RedirectsUsers;
use Jrean\UserVerification\Traits\UserVerification;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use UserVerification;
    use HasApiTokens;

    protected $appends=['sex','school','quote'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];

    public function information()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function AauthAcessToken()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function getQuoteAttribute()
    {
        return $this->information->quote;
    }

    public function getSchoolAttribute()
    {
        return $this->information->school;
    }

    public function getSexAttribute()
    {
        return $this->information->sex;
    }

    public function setQuoteAttribute($value)
    {
        $this->information->quote=$value;
        $this->information->save();
    }

    public function setSchoolAttribute($value)
    {
        $this->information->school=$value;
        $this->information->save();
    }

    public function setSexAttribute($value)
    {
        $this->information->sex=$value;
        $this->information->save();
    }
}
