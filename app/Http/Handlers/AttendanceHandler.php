<?php

declare(strict_types=1);

namespace App\Http\Handlers;

use App\Services\TokenCache;
use App\Traits\GetGraphTrait;
use GuzzleHttp\Exception\GuzzleException;
use Microsoft\Graph\Exception\GraphException;
use Microsoft\Graph\Model\Event;

final class AttendanceHandler
{
    use GetGraphTrait;

    public function handle(TokenCache $tokenCache = null): array
    {
        try {
            $graph = $this->getGraph($tokenCache);

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
            $res[$key]['from'] = date('Y-m-d H:i:s', strtotime($event->getStart()->getDateTime()));
            $res[$key]['to'] = date('Y-m-d H:i:s', strtotime($event->getEnd()->getDateTime()));
        }

        return $res;
    }
}
