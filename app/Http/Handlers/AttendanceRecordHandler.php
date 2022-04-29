<?php

namespace App\Http\Handlers;

use App\Traits\GetGraphTrait;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Model\AttendanceRecord;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\MeetingAttendanceReport;
use Microsoft\Graph\Model\OnlineMeeting;

class AttendanceRecordHandler
{
    use GetGraphTrait;

    public function handle(string $eventId): array
    {
        $graph = $this->getGraph();
        try {
            $event =  $graph->createRequest('GET', '/me/events/' . $eventId)
                ->setReturnType(Event::class)
                ->execute();

            $joinUrl = $event->getOnlineMeeting()->getJoinUrl();

            $meeting = $graph->createRequest('GET', '/me/onlineMeetings?$filter=JoinWebUrl eq \''. $joinUrl .'\'')
                ->setReturnType(OnlineMeeting::class)
                ->execute();


            $attendance = $graph->createRequest('GET', '/me/onlineMeetings/'.$meeting[0]->getId().'/attendanceReports')
                ->setReturnType(MeetingAttendanceReport::class)
                ->execute();

            $report = $graph->createRequest('GET', '/me/onlineMeetings/'. $meeting[0]->getId() .'/attendanceReports/'. $attendance[0]->getId() .'/attendanceRecords')
                ->setReturnType(AttendanceRecord::class)
                ->execute();

            return $this->toArray($report);

        } catch (GraphException|GuzzleException $e) {
            dd($e);
            return [];
        }
    }

    private function toArray(array $report): array
    {
        $res = [];

        foreach ($report as $key => $user) {
            $res[$key]['full_name'] = $user->getIdentity()->getDisplayName();
            $res[$key]['email'] = $user->getEmailAddress();
            $res[$key]['join'] = $user->getAttendanceIntervals()[0]['joinDateTime'];
            $res[$key]['leave'] = $user->getAttendanceIntervals()[0]['leaveDateTime'];
        }

        return $res;
    }
}
