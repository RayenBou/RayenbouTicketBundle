<?php

namespace Rayenbou\TicketBundle\Constants;

class TicketConstants
{
    /**
     * The content type for the main ticket.
     *
     * @var string
     */
    public const CONTENT_TYPE_MAIN = 'application/ld+json';

    /**
     * The content type for patching the ticket.
     *
     * @var string
     */
    public const CONTENT_TYPE_PATCH = 'application/merge-patch+json';

    /**
     * The URL for checking the login.
     *
     * @var string
     */
    public const CHECK_URL = '/api/login_check';

    /**
     * The URL for accessing the tickets.
     *
     * @var string
     */
    public const TICKET_URL = '/api/tickets';
}
