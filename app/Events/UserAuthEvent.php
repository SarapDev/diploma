<?php

declare(strict_types=1);

namespace App\Events;

use Chocofamilyme\LaravelPubSub\Events\PublishEvent;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

final class UserAuthEvent extends PublishEvent implements ShouldBroadcastNow
{
    use Dispatchable;

    protected const NAME = 'UserAuth';
    protected const ROUTING_KEY = 'diploma.user.auth';
    protected const EXCHANGE_NAME = 'diploma';
}
