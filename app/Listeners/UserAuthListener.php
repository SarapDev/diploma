<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Handlers\AttendanceHandler;
use App\Http\Handlers\AttendanceRecordHandler;
use app\Repositories\AttendanceRepository;
use app\Repositories\ClassRepository;
use app\Repositories\UserRepository;

final class UserAuthListener
{
    public function __construct(
        private AttendanceHandler $attendanceHandler,
        private AttendanceRecordHandler $attendanceRecordHandler,
        private UserRepository $userRepository,
        private ClassRepository $classRepository,
        private AttendanceRepository $attendanceRepository,
    ) {
    }

    public function handle(array $event): void
    {
        $events = $this->attendanceHandler->handle();
        $this->classRepository->butchCreate($events);

        foreach ($events as $item) {
            $record = $this->attendanceRecordHandler->handle($item['event_id']);
            $this->attendanceRepository->butchCreate($record['record']);
        }
    }
}
