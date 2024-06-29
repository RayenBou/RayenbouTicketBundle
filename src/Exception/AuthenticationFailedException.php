<?php

namespace Rayenbou\TicketBundle\Exception;

/**
 * Exception class for authentication failures.
 */
class AuthenticationFailedException extends \Exception
{
    /**
     * Constructor for the AuthenticationFailedException class.
     *
     * @param string $message the error message
     */
    public function __construct(string $message = '')
    {
        parent::__construct("Unauthorized Access, check your credentials in Env var. More Information: $message");
    }
}
