<?php

namespace Rayenbou\TicketBundle\Exception;

/**
 * Exception thrown when there is a failure to fetch a ticket and its messages.
 */
class TicketFetchException extends \Exception
{
    /**
     * Constructs a new TicketFetchException object.
     *
     * @param int $id the ID of the ticket that failed to be fetched
     */
    public function __construct(string $token)
    {
        $message = "Failed to fetch ticket with token $token and its messages.";
        parent::__construct($message);
    }
}
