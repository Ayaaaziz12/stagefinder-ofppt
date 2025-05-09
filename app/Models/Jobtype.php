<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Jobtype
 * 
 * @property int $id
 * @property string $Libelle
 * 
 * @property Collection|Offer[] $offers
 *
 * @package App\Models
 */
class Jobtype extends Model
{
	protected $table = 'jobtype';
	public $timestamps = false;

	protected $fillable = [
		'Libelle'
	];

	public function offers()
	{
		return $this->hasMany(Offer::class, 'id_JobType');
	}
}
