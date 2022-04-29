<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\TokenCache;
use Microsoft\Graph\Graph;

trait GetGraphTrait
{
    private function getGraph(): Graph
    {
        // Get the access token from the cache
        $tokenCache = new TokenCache();
        $accessToken = $tokenCache->getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }
}
