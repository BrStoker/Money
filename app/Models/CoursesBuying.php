<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesBuying extends Model
{
    use HasFactory;

    protected $table = 'courses_buying';

    public function user() {
        return $this->belongsTo(User::class);
    }
}
