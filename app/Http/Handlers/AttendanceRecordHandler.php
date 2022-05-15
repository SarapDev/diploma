<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Services\TokenCache;
use App\Traits\GetGraphTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Model\AttendanceRecord;
use Microsoft\Graph\Model\Event;
use Microsoft\Graph\Model\MeetingAttendanceReport;
use Microsoft\Graph\Model\OnlineMeeting;

final class AttendanceRecordHandler
{
    use GetGraphTrait;

    public function handle(string $eventId, TokenCache $tokenCache = null): array
    {
        $graph = $this->getGraph($tokenCache);

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

            $report = [];
            foreach ($attendance as $item) {
                $res = $graph->createRequest('GET', '/me/onlineMeetings/'. $meeting[0]->getId() .'/attendanceReports/'. $item->getId() .'/attendanceRecords')
                    ->setReturnType(AttendanceRecord::class)
                    ->execute();
                $report = array_merge($report, $res);
            }

            return $this->toArray($report, $event);

        } catch (GraphException|GuzzleException $e) {
            dd($e);
            return [];
        }
    }

    private function toArray(array $report, Event $event): array
    {
        $res = [];

        $res['event']['title'] = $event->getSubject();
        $res['event']['start'] = Carbon::parse($event->getStart()->getDateTime())->addHours(6);
        $res['event']['finish'] = Carbon::parse($event->getEnd()->getDateTime())->addHours(6);

        foreach ($report as $key => $user) {
            $leaveTime = Carbon::parse($user->getAttendanceIntervals()[0]['leaveDateTime'])->addHours(6);

            $res['report'][$key]['full_name'] = $user->getIdentity()->getDisplayName();
            $res['report'][$key]['email'] = $user->getEmailAddress();
            $res['report'][$key]['join'] = Carbon::parse($user->getAttendanceIntervals()[0]['joinDateTime'])->addHours(6);
            $res['report'][$key]['leave'] = $leaveTime;
            $res['report'][$key]['is_leaver'] = $this->checkLeaver($res['event']['finish'], $leaveTime);
        }

        return $res;
    }

    private function checkLeaver(Carbon $event, Carbon $leaveTime): bool
    {
        if ($event->gt($leaveTime)) {
            return true;
        }
        return false;
    }
}
