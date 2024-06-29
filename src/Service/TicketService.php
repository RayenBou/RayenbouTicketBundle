<?php

namespace Rayenbou\TicketBundle\Service;

use Rayenbou\TicketBundle\DTO\TicketDTO;
use Symfony\Component\HttpFoundation\RequestStack;
use Rayenbou\TicketBundle\Constants\TicketConstants;
use Rayenbou\TicketBundle\Exception\TicketException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Rayenbou\TicketBundle\Exception\TicketFetchException;
use Rayenbou\TicketBundle\Exception\AuthenticationFailedException;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class TicketService.
 *
 * This class represents a service for managing tickets.
 *
 * @author Rayen Boumaza <https://github.com/RayenBou>
 */
class TicketService
{
    /**
     * @var string the URL for the ticket
     */
    private string $ticketUrl;

    /**
     * @var string the API token for authentication
     */
    private string $apiToken;

    /**
     * @var string the username for authentication
     */
    private string $username;

    /**
     * TicketService constructor.
     *
     * @param RequestStack          $requestStack the request stack
     * @param HttpClientInterface   $httpClient   the HTTP client
     * @param AuthenticationService $authService  the authentication service
     * @param array<string,mixed>   $params       the configuration parameters
     *
     * @throws \InvalidArgumentException when required parameters are missing
     */
    public function __construct(
        private RequestStack $requestStack,
        private HttpClientInterface $httpClient,
        private AuthenticationService $authService,
        array $params
    ) {
        if (!isset($params['ticket_url'], $params['ticket_username'])) {
            throw new \InvalidArgumentException('Les paramètres "ticket_url" et "ticket_username" sont requis.');
        }
        $this->apiToken = $params['api_token'] ?? '';
        $this->ticketUrl = $params['ticket_url'];
        $this->username = $params['ticket_username'];
    }

    /**
     * Creates a ticket using the provided ticket data.
     *
     * @param array<string> $ticketData the data for the ticket
     *
     * @return bool Returns true if the ticket is created successfully
     *
     * @throws TicketException if there is an error during the ticket creation process
     */
    public function createTicket(array $ticketData): bool
    {
        $ticketData['email'] = $this->username;
        $ticketData['domain'] = $this->requestStack->getCurrentRequest()->getHost();

        $response = $this->sendRequest('POST', TicketConstants::TICKET_URL, $ticketData, TicketConstants::CONTENT_TYPE_MAIN);

        if (201 === $response['status']) {
            return true;
        } else {
            throw new TicketException('Erreur lors de la création du ticket');
        }
    }

    /**
     * Modifies a ticket with the given data.
     *
     * @param array<string> $ticketData the data to modify the ticket
     *
     * @return bool returns true if the ticket was modified successfully, false otherwise
     *
     * @throws TicketException if there was an error modifying the ticket
     */
    public function modifyTicket(array $ticketData): bool
    {
        $ticketData['email'] = $this->username;
        $request = $this->requestStack->getCurrentRequest();
        if (null !== $request) {
            $host = $request->getHost();
        } else {
            throw new AuthenticationFailedException('Erreur lors de la récupération du domaine');
        }
        $ticketData['domain'] = $host;

        $response = $this->sendRequest('PATCH', TicketConstants::TICKET_URL . '/' . $ticketData['token'], $ticketData, TicketConstants::CONTENT_TYPE_PATCH);

        if (200 === $response['status']) {
            return true;
        } else {
            throw new TicketException('Erreur lors de la modification du ticket');
        }
    }

    /**
     * Retrieves a ticket by its ID.
     *
     * @param string $token the ID of the ticket to retrieve
     *
     * @return TicketDTO the ticket data transfer object
     *
     * @throws TicketFetchException if the ticket cannot be fetched
     */
    public function find(string $token): TicketDTO
    {
        $response = $this->sendRequest('GET', TicketConstants::TICKET_URL . '/' . $token, null, TicketConstants::CONTENT_TYPE_MAIN);

        if (200 === $response['status']) {
            if (isset($response['content'])) {
                foreach ($response['content']['ticketMessages'] as &$message) {
                    if ($message['author'] == $this->username) {
                        $message['author'] = '';
                    }
                }

                return new TicketDTO($response['content']);
            }
            throw new TicketFetchException($token);
        } else {
            throw new TicketFetchException($token);
        }
    }

    /**
     * Retrieves all tickets associated with the user's email.
     *
     * @return array<TicketDTO|string> an array of TicketDTO objects representing the filtered tickets
     *
     * @throws TicketException if the response does not contain the key "hydra:member" or if no ticket matches the user's email
     */
    public function findAll(): array
    {
        $response = $this->sendRequest('GET', TicketConstants::TICKET_URL, null, TicketConstants::CONTENT_TYPE_MAIN);

        if (!isset($response['content']['hydra:member'])) {
            throw new TicketException('La réponse ne contient pas la clé "hydra:member".');
        }

        $filteredTickets = array_filter($response['content']['hydra:member'], function ($ticket) {
            return isset($ticket['email']) && $ticket['email'] === $this->username;
        });

        if ($filteredTickets) {
            $tickets = array_map(fn ($ticket) => new TicketDTO($ticket), $filteredTickets);
            usort($tickets, function ($a, $b) {
                return $b->id - $a->id;
            });
            return $tickets;
        } else {
            return [];
        }
    }

    /**
     * Sends an HTTP request to the specified URL with the provided data and content type.
     *
     * @param string        $method      the HTTP method to use for the request
     * @param string        $url         the URL to send the request to
     * @param array<string> $data        the data to include in the request body
     * @param string        $contentType the content type of the request
     *
     * @return array{status:int,content:array|null} an array containing the status code and decoded content of the response
     *
     * @throws TicketException if an error occurs while sending the request
     */
    private function sendRequest(string $method, string $url, ?array $data, string $contentType): array
    {
        $this->authenticate();

        try {
            $response = $this->httpClient->request($method, $this->ticketUrl . $url, [
                'headers' => $this->getHeaders($contentType),
                'body' => $data ? json_encode($data) : null,
            ]);

            $content = $response->getContent();
            $decodedContent = $content ? json_decode($content, true) : null;

            return [
                'status' => $response->getStatusCode(),
                'content' => $decodedContent,
            ];
        } catch (TransportExceptionInterface $e) {
            throw new TicketException($e->getMessage());
        }
    }

    /**
     * Authenticates the user and retrieves the API token.
     *
     * @throws AuthenticationFailedException if authentication fails
     */
    private function authenticate(): void
    {
        $this->apiToken = $this->authService->authenticate();
        if (!$this->apiToken) {
            throw new AuthenticationFailedException('Non authentifié');
        }
    }

    /**
     * Retrieves the headers for the API request.
     *
     * @param string $contentType the content type of the request
     *
     * @return array<string> the headers for the API request
     */
    private function getHeaders(string $contentType): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiToken,
            'Content-Type' => $contentType,
        ];
    }
}
