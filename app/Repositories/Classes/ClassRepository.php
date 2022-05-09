<?php

declare(strict_types=1);

namespace App\Repositories\Classes;

use App\Models\Classes;

final class ClassRepository implements IClassRepository
{
    public function butchCreate(array $events): void
    {
        try {
            Classes::insert($events);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function getClassesByEventIds(array $eventIds): array
    {
        return Classes::whereIn('event_id', $eventIds)->get()->toArray();
    }
}
