<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
 * @property int|null $id_rc
 * @property Carbon|null $created_at
 *
 * @property Rc|null $rc
 * @property Collection|Offer[] $offers
 *
 * @package App\Models
 */
class Company extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'companies';
    public $timestamps = false;

    protected $casts = [
        'date' => 'datetime',
        'is_verified' => 'bool',
        'id_rc' => 'int'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'name',
        'address',
        'email',
        'date',
        'country',
        'ville',
        'domain',
        'is_verified',
        'password',
        'website',
        'logo',
        'description',
        'id_rc'
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

    public function rc()
    {
        return $this->belongsTo(Rc::class, 'id_rc');
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
