<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rc
 * 
 * @property int $id
 * @property string|null $value
 * 
 * @property Collection|Company[] $companies
 *
 * @package App\Models
 */
class Rc extends Model
{
	protected $table = 'rc';
	public $timestamps = false;

	protected $fillable = [
		'value'
	];

	public function companies()
	{
		return $this->hasMany(Company::class, 'id_rc');
	}
}
