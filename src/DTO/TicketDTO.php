<?php

declare(strict_types=1);

namespace Rayenbou\TicketBundle\DTO;

class TicketDTO
{
    /**
     * @var int the ID of the ticket
     */
    public readonly int $id;

    /**
     * @var string the title of the ticket
     */
    public readonly string $title;

    /**
     * @var string the token of the ticket
     */
    public readonly string $token;

    /**
     * @var \DateTime the creation date and time of the ticket
     */
    public readonly \DateTime $createdAt;

    /**
     * @var string the domain of the ticket
     */
    public readonly string $domain;

    /**
     * @var bool the status of the ticket
     */
    public readonly bool $status;

    /**
     * @var TicketMessageDTO[] an array of ticket messages associated with the ticket
     */
    public readonly array $ticketMessages;

    /**
     * @var string the email associated with the ticket
     */
    public readonly string $email;

    /**
     * TicketDTO constructor.
     *
     * @param array <string,mixed> $data the data used to initialize the ticket DTO
     *
     * @throws \InvalidArgumentException if the required data keys are missing or if the createdAt value is invalid
     */
    public function __construct(array $data)
    {
        $this->validateData($data);
        $this->id = $data['id'];
        $this->title = $data['title'];
        try {
            $this->createdAt = new \DateTime($data['createdAt']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid createdAt value: '.$e->getMessage());
        }
        $this->domain = $data['domain'];
        $this->status = $data['status'];
        $this->email = $data['email'];
        $this->token = $data['token'];
        $this->ticketMessages = array_map(function ($message) {
            return new TicketMessageDTO($message);
        }, $data['ticketMessages'] ?? []);
    }

    /**
     * Validates the data array to ensure all required keys are present.
     *
     * @param array <string,mixed> $data the data array to validate
     *
     * @throws \InvalidArgumentException if any of the required data keys are missing
     */
    private function validateData(array $data): void
    {
        $requiredKeys = ['id', 'title', 'createdAt', 'domain', 'status', 'email', 'ticketMessages'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("Missing required data key: $key");
            }
        }
    }
}
