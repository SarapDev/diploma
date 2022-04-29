<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class TestQuestion extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = '';

    /**
     * @var array<string>
     */
    protected $fillable = ['text', 'test_id'];
}
