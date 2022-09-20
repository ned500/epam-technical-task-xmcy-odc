<?php

namespace App\Tests\Service;

use App\Exception\WebAccessException;
use App\Service\Company;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class CompanyTest extends TestCase
{
    /**
     * @dataProvider getCompaniesTestCases
     *
     * @throws WebAccessException
     */
    public function testGetCompanies(int $statusCode, array $content, ?string $exception = null): void
    {
        $response = $this->createStub(MockResponse::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('toArray')->willReturn($content);
        $httpClient = $this->createStub(MockHttpClient::class);
        $httpClient->method('request')->willReturn($response);
        $companyService = new Company($httpClient, '');

        if ($exception) {
            $this->expectException($exception);
        }

        $companies = $companyService->getCompanies();
        $this->assertEquals($content, $companies);
    }

    public function getCompaniesTestCases(): array
    {
        return [
            [100, [], WebAccessException::class],
            [400, [], WebAccessException::class],
            [200, [], WebAccessException::class],
            [200, [['symbol' => 'GOOGL', 'name' => 'Google']]],
        ];
    }

    /**
     * @dataProvider getInfoTestCases
     *
     * @throws WebAccessException
     */
    public function testGetInfo(array $content, string $symbol, ?array $expected): void
    {
        $response = $this->createStub(MockResponse::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('toArray')->willReturn($content);
        $httpClient = $this->createStub(MockHttpClient::class);
        $httpClient->method('request')->willReturn($response);
        $companyService = new Company($httpClient, '');

        $info = $companyService->getInfo($symbol);
        $this->assertEquals($expected, $info);
    }

    public function getInfoTestCases(): array
    {
        $content = [
            ['Symbol' => 'a', 'Company Name' => 'A'],
            ['Symbol' => 'b', 'Company Name' => 'B'],
            ['Symbol' => 'c', 'Company Name' => 'C'],
            ['Symbol' => 'c', 'Company Name' => 'C'],
        ];

        return [
            [$content, 'X', null],
            [$content, 'b', ['Symbol' => 'b', 'Company Name' => 'B']],
            [$content, 'c', ['Symbol' => 'c', 'Company Name' => 'C']],
        ];
    }
}
