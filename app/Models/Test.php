<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = ['class_id', 'teacher_id'];
}
