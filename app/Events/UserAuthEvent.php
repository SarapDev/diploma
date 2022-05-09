<?php

declare(strict_types=1);

namespace App\Events;

use App\Services\TokenCache;
use Chocofamilyme\LaravelPubSub\Events\PublishEvent;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use JetBrains\PhpStorm\ArrayShape;

final class UserAuthEvent extends PublishEvent implements ShouldBroadcastNow
{
    use Dispatchable;

    protected const NAME = 'UserAuth';
    protected const ROUTING_KEY = 'diploma.user.auth';
    protected const EXCHANGE_NAME = 'diploma';

    public function __construct(private TokenCache $tokenCache)
    {
    }

    #[ArrayShape([
        'accessToken' => "\null|string",
        'refreshToken' => "\null|string",
        'tokenExpires' => "\int|null",
        'userName' => "\null|string",
        'userEmail' => "\null|string",
        'userTimeZone' => "\null|string"
    ])]
    public function toPayload(): array
    {
        return $this->tokenCache->toArray();
    }
}
