<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Log
 * 
 * @property int $id
 * @property Carbon|null $date
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Log extends Model
{
	protected $table = 'logs';

	protected $casts = [
		'date' => 'datetime'
	];

	protected $fillable = [
		'date',
		'description'
	];
}
