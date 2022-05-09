<?php

declare(strict_types=1);

namespace App\Repositories\Classes;

interface IClassRepository
{
    public function butchCreate(array $events): void;

    public function getClassesByEventIds(array $eventIds): array;
}
