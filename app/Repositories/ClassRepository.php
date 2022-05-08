<?php

declare(strict_types=1);

namespace app\Repositories;

use App\Models\Classes;

final class ClassRepository
{
    public function butchCreate(array $events): void
    {
        Classes::insert($events);
    }
}
