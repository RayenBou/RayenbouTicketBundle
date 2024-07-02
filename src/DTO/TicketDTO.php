<?php

declare(strict_types=1);

namespace Rayenbou\TicketBundle\DTO;

final class TicketDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly \DateTime $createdAt,
        public readonly string $domain,
        public readonly bool $status,
        public readonly string $email,
        public readonly string $token,
        public readonly array $ticketMessages
    ) {
    }

    public static function fromArray(array $data): self
    {
        // Validation des données ici ou dans une classe séparée
        self::validateData($data);

        return new self(
            id: $data['id'],
            title: $data['title'],
            createdAt: new \DateTime($data['createdAt']),
            domain: $data['domain'],
            status: $data['status'],
            email: $data['email'],
            token: $data['token'],
            ticketMessages: array_map(fn ($message) => TicketMessageDTO::fromArray($message), $data['ticketMessages'] ?? [])
        );
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
