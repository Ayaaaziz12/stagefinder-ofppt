<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'salary',
        'type',
        'location',
        'requirements',
        'id_company'
    ];

    protected $casts = [
        'requirements' => 'array'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
