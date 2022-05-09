<?php

declare(strict_types=1);

namespace App\Traits;

use App\Services\TokenCache;
use Microsoft\Graph\Graph;

trait GetGraphTrait
{
    private function getGraph(TokenCache $tokenCache = null): Graph
    {
        if (is_null($tokenCache)) {
            // Get the access token from the cache
            $tokenCache = new TokenCache();
            $accessToken = $tokenCache->getAccessToken();
        } else {
            $accessToken = $tokenCache->getToken();
        }

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        return $graph;
    }
}
