<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Session;
use JetBrains\PhpStorm\ArrayShape;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Microsoft\Graph\Model\User;

final class TokenCache
{
    private ?string $accessToken;
    private ?string $refreshToken;
    private ?int $tokenExpires;
    private ?string $userName;
    private ?string $userEmail;
    private ?string $userTimeZone;

    public function __construct(
        array $arrayData = null,
        AccessTokenInterface $accessToken = null,
        User $user = null,
    ) {
        if (!is_null($arrayData)) {
            $this->accessToken = $arrayData['accessToken'];
            $this->refreshToken = $arrayData['refreshToken'];
            $this->tokenExpires = $arrayData['tokenExpires'];
            $this->userName = $arrayData['userName'];
            $this->userEmail = $arrayData['userEmail'];
            $this->userTimeZone = $arrayData['userTimeZone'];
        }

        if (!is_null($accessToken) && !is_null($user)) {
            $this->accessToken = $accessToken->getToken();
            $this->refreshToken = $accessToken->getRefreshToken();
            $this->tokenExpires = $accessToken->getExpires();
            $this->userName = $user->getDisplayName();
            $this->userEmail = null !== $user->getMail() ? $user->getMail() : $user->getUserPrincipalName();
            $this->userTimeZone = $user->getMailboxSettings()->getTimeZone();
        }
    }

    #[ArrayShape([
        'accessToken' => "null|string",
        'refreshToken' => "null|string",
        'tokenExpires' => "int|null",
        'userName' => "null|string",
        'userEmail' => "null|string",
        'userTimeZone' => "null|string"])]
    public function toArray(): array
    {
        return [
            'accessToken'   => $this->accessToken,
            'refreshToken'  => $this->refreshToken,
            'tokenExpires'  => $this->tokenExpires,
            'userName'      => $this->userName,
            'userEmail'     => $this->userEmail,
            'userTimeZone'  => $this->userTimeZone,
        ];
    }

    public function storeTokens(): void
    {
        session([
            'accessToken' => $this->accessToken,
            'refreshToken' => $this->refreshToken,
            'tokenExpires' => $this->tokenExpires,
            'userName' => $this->userName,
            'userEmail' => $this->userEmail,
            'userTimeZone' => $this->userTimeZone
        ]);
    }

    public function clearTokens(): void
    {
        session()->forget('accessToken');
        session()->forget('refreshToken');
        session()->forget('tokenExpires');
        session()->forget('userName');
        session()->forget('userEmail');
        session()->forget('userTimeZone');
    }

    public function updateTokens(AccessTokenInterface $accessToken): void
    {
        session([
            'accessToken' => $accessToken->getToken(),
            'refreshToken' => $accessToken->getRefreshToken(),
            'tokenExpires' => $accessToken->getExpires()
        ]);
    }

    public function getToken(): string
    {
        return $this->accessToken;
    }

    public function getAccessToken(): Session|string
    {
        // Check if tokens exist
        if (empty(session('accessToken')) ||
            empty(session('refreshToken')) ||
            empty(session('tokenExpires'))) {
            return '';
        }

        // Check if token is expired
        //Get current time + 5 minutes (to allow for time differences)
        $now = time() + 300;
        if (session('tokenExpires') <= $now) {
            // Token is expired (or very close to it)
            // so let's refresh

            // Initialize the OAuth client
            $oauthClient = new GenericProvider([
                'clientId'                => config('azure.appId'),
                'clientSecret'            => config('azure.appSecret'),
                'redirectUri'             => config('azure.redirectUri'),
                'urlAuthorize'            => config('azure.authority').config('azure.authorizeEndpoint'),
                'urlAccessToken'          => config('azure.authority').config('azure.tokenEndpoint'),
                'urlResourceOwnerDetails' => '',
                'scopes'                  => config('azure.scopes')
            ]);

            try {
                $newToken = $oauthClient->getAccessToken('refresh_token', [
                    'refresh_token' => session('refreshToken')
                ]);

                // Store the new values
                $this->updateTokens($newToken);

                return $newToken->getToken();
            }
            catch (IdentityProviderException $e) {
                report($e);
            }
        }

        // Token is still valid, just return it
        return session('accessToken');
    }
}
