<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SavedOffer
 * 
 * @property int $id
 * @property int $id_student
 * @property int $id_offre
 * @property Carbon|null $date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Student $student
 * @property Offer $offer
 *
 * @package App\Models
 */
class SavedOffer extends Model
{
	protected $table = 'saved_offers';

	protected $casts = [
		'id_student' => 'int',
		'id_offre' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
		'id_student',
		'id_offre',
		'date'
	];

	public function student()
	{
		return $this->belongsTo(Student::class, 'id_student');
	}

	public function offer()
	{
		return $this->belongsTo(Offer::class, 'id_offre');
	}
}
