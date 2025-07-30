<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'notes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sets()
    {
        return $this->hasMany(WorkoutSet::class);
    }
}
