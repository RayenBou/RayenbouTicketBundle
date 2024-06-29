<?php

namespace Rayenbou\TicketBundle\Service;

use Rayenbou\TicketBundle\DTO\TicketDTO;
use Rayenbou\TicketBundle\Exception\TicketException;

/**
 * Interface TicketServiceInterface.
 *
 * This interface defines the contract for a service that manages tickets.
 *
 * @author Rayen Boumaza <https://github.com/RayenBou>
 */
interface TicketServiceInterface
{
    /**
     * Creates a ticket using the provided ticket data.
     *
     * @param array<string> $ticketData the data for the ticket
     *
     * @return bool Returns true if the ticket is created successfully
     *
     * @throws TicketException if there is an error during the ticket creation process
     */
    public function createTicket(array $ticketData): bool;

    /**
     * Modifies a ticket with the given data.
     *
     * @param array<string> $ticketData the data to modify the ticket
     *
     * @return bool returns true if the ticket was modified successfully, false otherwise
     *
     * @throws TicketException if there was an error modifying the ticket
     */
    public function modifyTicket(array $ticketData): bool;

    /**
     * Retrieves a ticket by its ID.
     *
     * @param string $token the ID of the ticket to retrieve
     *
     * @return TicketDTO the ticket data transfer object
     *
     * @throws TicketException if the ticket cannot be fetched
     */
    public function find(string $token): TicketDTO;

    /**
     * Retrieves all tickets associated with the user's email.
     *
     * @return array<TicketDTO|string> an array of TicketDTO objects representing the filtered tickets
     *
     * @throws TicketException if the response does not contain the key "hydra:member" or if no ticket matches the user's email
     */
    public function findAll(): array;
}
