<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Automation
 * 
 * @property int $id
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property int $id_offre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Offer $offer
 *
 * @package App\Models
 */
class Automation extends Model
{
	protected $table = 'automations';

	protected $casts = [
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'id_offre' => 'int'
	];

	protected $fillable = [
		'start_date',
		'end_date',
		'id_offre'
	];

	public function offer()
	{
		return $this->belongsTo(Offer::class, 'id_offre');
	}
}
