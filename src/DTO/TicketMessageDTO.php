<?php

declare(strict_types=1);

namespace Rayenbou\TicketBundle\DTO;

/**
 * Class TicketMessageDTO.
 *
 * This class represents a data transfer object for ticket messages.
 *
 * @author Rayen Boumaza
 * 
 */
final class TicketMessageDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $description,
        public readonly \DateTime $createdAt,
        public readonly string $author,
        public readonly int $ticket
    ) {
    }

    public static function fromArray(array $data): self
    {
        self::validateData($data);

        return new self(
            id: $data['id'],
            description: $data['description'],
            createdAt: new \DateTime($data['createdAt']),
            author: $data['author'],
            ticket: $data['ticket']
        );
    }

    private static function validateData(array $data): void
    {
        $requiredKeys = ['id', 'description', 'createdAt', 'author', 'ticket'];
        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $data)) {
                throw new \InvalidArgumentException("Missing required data key: $key");
            }
        }
    }
}
