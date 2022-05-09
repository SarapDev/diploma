<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Handlers\AttendanceHandler;
use App\Http\Handlers\AttendanceRecordHandler;
use App\Repositories\Attendance\IAttendanceRepository;
use App\Repositories\Classes\IClassRepository;
use App\Repositories\Students\IStudentRepository;
use App\Services\TokenCache;
use Illuminate\Support\Facades\DB;

final class UserAuthListener
{
    public function __construct(
        private AttendanceHandler       $attendanceHandler,
        private AttendanceRecordHandler $attendanceRecordHandler,
        private IStudentRepository      $studentRepository,
        private IAttendanceRepository   $attendanceRepository,
        private IClassRepository        $classRepository,
    ) {
    }

    public function handle(array $tokenCache): void
    {
        $token = new TokenCache($tokenCache);

        $events = $this->attendanceHandler->handle($token);
        $eventDiff = $this->checkForExistEvent($events);

        if(empty($eventDiff)) {
            return;
        }

        $this->classRepository->butchCreate($eventDiff);
        $classes = $this->classRepository->getClassesByEventIds($this->getEventIds($eventDiff));

        foreach ($classes as $class) {
            $report = $this->attendanceRecordHandler->handle($class['event_id'], $token);

            $preparedData = $this->prepareStudentDataToSave($report);
            $studentsDiff = $this->checkForStudentExist($preparedData);

            $this->studentRepository->butchCreate($studentsDiff);
            $students = $this->studentRepository->getAllStudentsByNamesArray($this->getStudentNames($studentsDiff));


        }
    }

    private function checkForExistEvent(array $events): array
    {
        $eventsFromDb = $this->classRepository->getClassesByEventIds($this->getEventIds($events));

        $eventsFromDb = array_map(function ($item) {
            unset($item['id']);
            return $item;
        }, $eventsFromDb);

        return array_udiff($events, $eventsFromDb, function ($first, $second) {
            if ($first['event_id'] == $second['event_id']) {
                return 0;
            }
            return -1;
        });
    }

    private function getEventIds(array $events): array
    {
        $res = [];

        foreach ($events as $event) {
            $res[] = $event['event_id'];
        }

        return $res;
    }

    private function getStudentNames(array $students): array
    {
        $res = [];

        foreach ($students as $student) {
            $res[] = $student['fullname'];
        }

        return $res;
    }

    private function prepareStudentDataToSave(array $report): array
    {
        $res = [];
        foreach ($report['report'] as $item) {
            $res[]['fullname'] = $item['full_name'];
        }

        return $res;
    }

    private function checkForStudentExist(array $students): array
    {
        $studentsFromDb = $this->studentRepository->getAllStudentsByNamesArray($this->getStudentNames($students));

        return array_udiff($students, $studentsFromDb, function ($first, $second) {
            if ($first['fullname'] == $second['fullname']) {
                return 0;
            }
            return -1;
        });
    }
}
