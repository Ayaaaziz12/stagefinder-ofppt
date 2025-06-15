<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Company
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property Carbon $date
 * @property string $country
 * @property string $ville
 * @property string $domain
 * @property bool|null $is_verified
 * @property string $password
 * @property string|null $website
 * @property string|null $logo
 * @property string|null $description
 * @property string $rc
 * @property Carbon|null $created_at
 *
 * @property Collection|Offer[] $offers
 *
 * @package App\Models
 */
class Company extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = 'company';
    protected $table = 'companies';
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'is_verified' => 'bool',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'domain',
        'address',
        'country',
        'ville',
        'rc',
        'date',
        'website',
        'logo',
        'description',
        'is_verified'
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

    public function offers()
    {
        return $this->hasMany(Offer::class, 'id_company');
    }

    public function blacklists()
    {
        return $this->hasMany(Blacklist::class, 'IdCompany');
    }
}
