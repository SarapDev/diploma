<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Http\Handlers\AttendanceHandler;
use App\Http\Handlers\AttendanceRecordHandler;
use App\Repositories\Attendance\IAttendanceRepository;
use App\Repositories\Classes\IClassRepository;
use App\Repositories\Students\IStudentRepository;
use App\Services\TokenCache;

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
        $classes = $this->classRepository->getClassesByEventIds($this->getAttribute($eventDiff, 'event_id'));

        foreach ($classes as $class) {
            $report = $this->attendanceRecordHandler->handle($class['event_id'], $token);

            $preparedData = $this->prepareStudentDataToSave($report);
            $studentsDiff = $this->checkForStudentExist($preparedData);

            $this->studentRepository->butchCreate($studentsDiff);

            $students = $this->studentRepository->getAllStudentsByNamesArray($this->getAttribute($preparedData, 'fullname'));
            $this->attendanceRepository->butchCreate($this->prepareAttendanceDataToSave($class, $students));
        }
    }

    private function checkForExistEvent(array $events): array
    {
        $eventsFromDb = $this->classRepository->getClassesByEventIds($this->getAttribute($events, 'event_id'));

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

    private function getAttribute(array $data, string $attribute): array
    {
        $res = [];

        foreach ($data as $item) {
            $res[] = $item[$attribute];
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
        $studentsFromDb = $this->studentRepository->getAllStudentsByNamesArray($this->getAttribute($students, 'fullname'));

        return array_udiff($students, $studentsFromDb, function ($first, $second) {
            if ($first['fullname'] == $second['fullname']) {
                return 0;
            }
            return -1;
        });
    }

    private function prepareAttendanceDataToSave(array $class, array $students): array
    {
        $res = [];

        foreach ($students as $key => $student) {
            $res[$key]['class_id'] = $class['id'];
            $res[$key]['student_id'] = $student['id'];
            $res[$key]['join_time'] = $class['from'];
            $res[$key]['leave_time'] = $class['to'];
        }

        return $res;
    }
}
