<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
    use HasFactory;

    protected $table = 'urusan';

    protected $fillable = [
        'name',
        'department_id',
        'head_id',
    ];

    public $timestamps = false;

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
