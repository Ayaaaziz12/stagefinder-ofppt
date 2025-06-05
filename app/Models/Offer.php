<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Offer
 *
 * @property int $id
 * @property string $title
 * @property string $Job_Descriptin
 * @property Carbon $date
 * @property Carbon $expiration_date
 * @property int $max_applications
 * @property int $id_company
 * @property int $id_JobType
 * @property int $id_OffreStatus
 * @property Carbon|null $created_at
 *
 * @property Company $company
 * @property Jobtype $jobtype
 * @property Offrestatus $offrestatus
 * @property Collection|Application[] $applications
 * @property Collection|Automation[] $automations
 * @property Collection|SavedOffer[] $saved_offers
 *
 * @package App\Models
 */
class Offer extends Model
{
	protected $table = 'offers';
	public $timestamps = false;

	protected $casts = [
		'expiration_date' => 'date',
		'max_applications' => 'int',
		'id_company' => 'int',
		'id_JobType' => 'int',
		'id_OffreStatus' => 'int'
	];

	protected $fillable = [
		'title',
		'Job_Descriptin',
		'skills',
		'expiration_date',
		'max_applications',
		'id_company',
		'id_JobType',
		'id_OffreStatus'
	];

	public function company()
	{
		return $this->belongsTo(Company::class, 'id_company');
	}

	public function jobtype()
	{
		return $this->belongsTo(Jobtype::class, 'id_JobType');
	}

	public function offrestatus()
	{
		return $this->belongsTo(Offrestatus::class, 'id_OffreStatus');
	}

	public function applications()
	{
		return $this->hasMany(Application::class, 'id_offre');
	}

	public function automations()
	{
		return $this->hasMany(Automation::class, 'id_offre');
	}

	public function saved_offers()
	{
		return $this->hasMany(SavedOffer::class, 'id_offre');
	}
}
