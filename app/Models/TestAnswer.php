<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class TestAnswer extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'test_answers';

    /**
     * @var array<string>
     */
    protected $fillable = ['text', 'test_question_id', 'is_right_answer'];
}
