<?php

namespace Rayenbou\TicketBundle\Constants;

class TicketConstants
{
    public const CONTENT_TYPE_MAIN = 'application/ld+json';
    public const CONTENT_TYPE_PATCH = 'application/merge-patch+json';

    public const CHECK_URL = '/api/login_check';
    public const TICKET_URL = '/api/tickets';
}
