<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEmbedding extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'descriptor'];

    protected $casts = [
        'descriptor' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
