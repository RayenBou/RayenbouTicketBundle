<?php

declare(strict_types=1);

namespace Rayenbou\TicketBundle\DTO;

class TicketMessageDTO
{
    /**
     * @var int the ID of the ticket message
     */
    public readonly int $id;

    /**
     * @var string the description of the ticket message
     */
    public readonly string $description;

    /**
     * @var \DateTime the creation date and time of the ticket message
     */
    public readonly \DateTime $createdAt;

    /**
     * @var string the author of the ticket message
     */
    public readonly string $author;

    /**
     * @var string the ticket associated with the message
     */
    public readonly string $ticket;

    /**
     * TicketMessageDTO constructor.
     *
     * @param array<string,mixed> $data the data to initialize the ticket message
     *
     * @throws \InvalidArgumentException if the required data is missing or invalid
     */
    public function __construct(array $data)
    {
        $this->validateData($data);
        $this->id = $data['id'];
        $this->description = $data['description'];
        try {
            $this->createdAt = new \DateTime($data['createdAt']);
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid createdAt value: ' . $e->getMessage());
        }
        $this->author = $data['author'];
        $this->ticket = $data['ticket'];
    }

    /**
     * Validates the data array to ensure all required keys are present.
     *
     * @param array<string, mixed> $data the data array to validate
     *
     * @throws \InvalidArgumentException if any required data key is missing
     */
    private function validateData(array $data): void
    {
        $requiredKeys = ['id', 'description', 'createdAt', 'author', 'ticket'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("Missing required data key: $key");
            }
        }
    }
}
