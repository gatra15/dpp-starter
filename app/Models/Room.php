<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'description',
        'available',
        'facility_id',
    ];

    public $timestamps = false;

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
}
