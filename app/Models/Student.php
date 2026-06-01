<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'roll_no', 'email'];

    public function embeddings()
    {
        return $this->hasMany(StudentEmbedding::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
