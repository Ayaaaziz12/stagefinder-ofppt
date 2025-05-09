<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Application
 * 
 * @property int $id
 * @property int $id_offre
 * @property int $id_student
 * @property Carbon|null $date
 * @property Carbon|null $created_at
 * 
 * @property Offer $offer
 * @property Student $student
 *
 * @package App\Models
 */
class Application extends Model
{
	protected $table = 'applications';
	public $timestamps = false;

	protected $casts = [
		'id_offre' => 'int',
		'id_student' => 'int',
		'date' => 'datetime'
	];

	protected $fillable = [
		'id_offre',
		'id_student',
		'date'
	];

	public function offer()
	{
		return $this->belongsTo(Offer::class, 'id_offre');
	}

	public function student()
	{
		return $this->belongsTo(Student::class, 'id_student');
	}
}
