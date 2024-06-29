<?php

namespace Rayenbou\TicketBundle\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class HttpClientFactory.
 *
 * This class provides a factory method to create an instance of HttpClientInterface.
 */
class HttpClientFactory
{
    /**
     * Creates an instance of HttpClientInterface.
     *
     * @param array<string, mixed> $options an array of options to configure the HttpClient
     *
     * @return HttpClientInterface the created instance of HttpClientInterface
     */
    public static function create(array $options = []): HttpClientInterface
    {
        return HttpClient::create($options);
    }
}
