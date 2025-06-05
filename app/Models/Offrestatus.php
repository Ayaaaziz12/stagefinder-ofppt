<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Offrestatus
 *
 * @property int $id
 * @property string $Libelle
 *
 * @property Collection|Offer[] $offers
 *
 * @package App\Models
 */
class Offrestatus extends Model
{
	protected $table = 'offrestatuses';
	public $timestamps = false;

	protected $fillable = [
		'Libelle'
	];

	public function offers()
	{
		return $this->hasMany(Offer::class, 'id_OffreStatus');
	}
}
