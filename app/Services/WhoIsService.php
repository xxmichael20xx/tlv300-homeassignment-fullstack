<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class WhoIsService
{
    /** @var string|Repository|Application|mixed|null  */
    private string $apiKey;

    /** @var Http|PendingRequest $http */
    protected Http|PendingRequest $http;

    /**
     * Create a new instance of a service.
     */
    public function __construct()
    {
        $this->apiKey = config('whois.api_key');
        $this->http = Http::baseUrl('https://www.whoisxmlapi.com')
            ->withHeaders([
                'Content-Type' => 'application/json',
            ]);
    }

    /**
     * Send a get request with default query params.
     *
     * @param string $path
     * @param array|null $query
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    protected function get(string $path = 'whoisserver/WhoisService', ?array $query = []): PromiseInterface|Response
    {
        // Set the default query params
        data_set($query, 'apiKey', $this->apiKey);
        data_set($query, 'outputFormat', 'JSON');

        // Return the response from the http client
        return $this->http->get($path, $query);
    }

    /**
     * Get the domain info.
     *
     * @param string $domain
     * @return array
     * @throws ConnectionException
     */
    public function getDomainInfo(string $domain): array
    {
        // Do a get request to the http client
        $data = $this->get(query: [
            'domainName' => $domain,
        ]);

        // Return the response from API as JSON format
        return json_decode($data->body(), true);
    }
}
