<?php

namespace App\Service;

use App\Exception\WebAccessException;
use App\Model\CompanyOptionsInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class Company implements CompanyOptionsInterface
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
    public function getOptions(): array
    {
        $options = [];
        foreach ($this->getCompanies() as $company) {
            $options[$company['Company Name']] = $company['Symbol'];
        }
        ksort($options);

        return $options;
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
