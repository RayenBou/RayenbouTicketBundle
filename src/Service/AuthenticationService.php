<?php

namespace Rayenbou\TicketBundle\Service;

use Rayenbou\TicketBundle\Constants\TicketConstants;
use Rayenbou\TicketBundle\Exception\AuthenticationFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthenticationService
{
    private string $ticketUrl;
    private string $username;
    private string $password;
    private ?string $apiToken = null;

    /**
     * AuthenticationService constructor.
     *
     * @param HttpClientInterface  $httpClient the HTTP client used for making requests
     * @param array<string, mixed> $params     the parameters containing the ticket URL, username, and password
     */
    public function __construct(private HttpClientInterface $httpClient, array $params)
    {
        $this->ticketUrl = $params['ticket_url'];
        $this->username = $params['ticket_username'];
        $this->password = $params['ticket_password'];
    }

    /**
     * Authenticates the user and returns the API token.
     *
     * @return string|null the API token if authentication is successful, null otherwise
     *
     * @throws AuthenticationFailedException if an error occurs during authentication
     */
    public function authenticate(): ?string
    {
        try {
            $response = $this->httpClient->request('POST', $this->ticketUrl . TicketConstants::CHECK_URL, [
                'headers' => [
                    'Content-Type' => TicketConstants::CONTENT_TYPE_MAIN,
                ],
                'body' => json_encode([
                    'username' => $this->username,
                    'password' => $this->password,
                ]),
            ]);

            if (200 === $response->getStatusCode()) {
                $data = json_decode($response->getContent(), true);
                $this->apiToken = $data['token'] ?? null;

                return $this->apiToken;
            }

            return null;
        } catch (TransportExceptionInterface $e) {
            throw new AuthenticationFailedException();
        }
    }

    /**
     * Returns the API token.
     *
     * @return string|null the API token if available, null otherwise
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }
}
