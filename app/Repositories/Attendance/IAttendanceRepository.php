<?php

declare(strict_types=1);

namespace App\Repositories\Attendance;

interface IAttendanceRepository
{
    public function butchCreate(array $attendance): void;
}
