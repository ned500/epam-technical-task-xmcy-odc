<?php

namespace App\Service;

use App\Exception\WebAccessException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class Company
{
    private array $companyData = [];

    public function __construct(private readonly HttpClientInterface $client, private readonly string $companyUrl)
    {
    }

    /**
     * @throws WebAccessException
     */
    public function getCompanies(): array
    {
        if (!$this->companyData) {
            $this->companyData = $this->downloadCompanyData();
        }

        return $this->companyData;
    }

    /**
     * @throws WebAccessException
     */
    private function downloadCompanyData(): array
    {
        try {
            $response = $this->client->request('GET', $this->companyUrl);
            $statusCode = $response->getStatusCode();
            $data = $response->toArray();
        } catch (Throwable $exception) {
            throw new WebAccessException($exception);
        }

        if (200 !== $statusCode || !$data) {
            throw new WebAccessException();
        }

        return $data;
    }
}
