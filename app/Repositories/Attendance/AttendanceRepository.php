<?php

declare(strict_types=1);

namespace App\Repositories\Attendance;

use App\Models\Attendance;

final class AttendanceRepository implements IAttendanceRepository
{
    public function butchCreate(array $attendance): void
    {
        Attendance::insert($attendance);
    }
}
