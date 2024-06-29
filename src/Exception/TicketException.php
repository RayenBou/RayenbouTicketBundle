<?php

namespace Rayenbou\TicketBundle\Exception;

/**
 * Represents an exception specific to the TicketBundle.
 */
class TicketException extends \Exception
{
    /**
     * Constructs a new TicketException with the specified message.
     *
     * @param string $message the exception message
     */
    public function __construct(string $message = '')
    {
        parent::__construct($message);
    }
}
