<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = ['class_id', 'student_id', 'join_time', 'leave_time'];

}
