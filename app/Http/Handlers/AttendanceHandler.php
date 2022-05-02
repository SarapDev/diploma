<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Traits\GetGraphTrait;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Model\Event;

final class AttendanceHandler
{
    use GetGraphTrait;

    public function handle(): array
    {
        try {
            $graph = $this->getGraph();

            $events =  $graph->createRequest('GET', '/me/events')
                ->addHeaders(['Prefer' => 'outlook.timezone="Central Asia Standard Time"'])
                ->setReturnType(Event::class)
                ->execute();

            return $this->toArray($events);
        } catch (GraphException|GuzzleException $exception) {
            report($exception);
            return [];
        }
    }

    private function toArray(array $events): array
    {
        $res = [];

        foreach ($events as $key => $event) {
            $res[$key]['event_id'] = $event->getId();
            $res[$key]['title'] = $event->getSubject();
            $res[$key]['start'] = Carbon::parse($event->getStart()->getDateTime());
            $res[$key]['finish'] = Carbon::parse($event->getEnd()->getDateTime());
        }

        return $res;
    }
}
