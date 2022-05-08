<?php

declare(strict_types=1);

namespace app\Repositories;

use App\Models\Attendance;

final class AttendanceRepository
{
    public function butchCreate(array $attendance): void
    {
        Attendance::insert($attendance);
    }
}
