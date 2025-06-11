<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Student
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $email
 * @property Carbon $date_birth
 * @property string $sex
 * @property string|null $cv
 * @property string $phone
 * @property string $country
 * @property string $ville
 * @property string $password
 * @property string|null $profile_picture
 * @property Carbon|null $registration_date
 *
 * @property Collection|Application[] $applications
 * @property Collection|Blacklist[] $blacklists
 * @property Collection|SavedOffer[] $saved_offers
 *
 * @package App\Models
 */
class Student extends Authenticatable implements JWTSubject
{
    protected $table = 'students';
    public $timestamps = false;

    protected $casts = [
        'date_birth' => 'datetime',
        'registration_date' => 'datetime'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'email',
        'date_birth',
        'sex',
        'cv',
        'phone',
        'country',
        'ville',
        'password',
        'profile_picture',
        'registration_date',
        'skills'
    ];

    // JWT Required Methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'id_student');
    }

    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'IdStudent');
    }

    public function saved_offers()
    {
        return $this->hasMany(SavedOffer::class, 'id_student');
    }
}
