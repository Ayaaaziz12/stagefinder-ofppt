<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Blacklist
 * 
 * @property int $id
 * @property Carbon|null $date
 * @property string $Reason
 * @property int $IdStudent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Student $student
 *
 * @package App\Models
 */
class Blacklist extends Model
{
	protected $table = 'blacklist';

	protected $casts = [
		'date' => 'datetime',
		'IdStudent' => 'int'
	];

	protected $fillable = [
		'date',
		'Reason',
		'IdStudent'
	];

	public function student()
	{
		return $this->belongsTo(Student::class, 'IdStudent');
	}
}
