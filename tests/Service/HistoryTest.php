<?php

namespace App\Tests\Service;

use App\Exception\WebAccessException;
use App\Service\History;
use DateTimeImmutable;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class HistoryTest extends TestCase
{
    /**
     * @dataProvider getHistoryTestCases
     *
     * @throws WebAccessException
     * @throws Exception
     */
    public function testGetHistory(array $case): void
    {
        ['statusCode' => $statusCode, 'params' => $params, 'content' => $content, 'expected' => $expected, 'exception' => $exception] = $this->normalizedCaseData($case);

        $response = $this->createStub(MockResponse::class);
        $response->method('getStatusCode')->willReturn($statusCode);
        $response->method('toArray')->willReturn($content);
        $httpClient = $this->createStub(MockHttpClient::class);
        $httpClient->method('request')->willReturn($response);
        $service = new History($httpClient, '', '');

        if ($exception) {
            $this->expectException($exception);
        }

        ['symbol' => $symbol, 'start' => $start, 'end' => $end] = $params;
        $actual = $service->getHistory($symbol, new DateTimeImmutable($start), new DateTimeImmutable($end));
        $this->assertEquals($expected, $actual);
    }

    public function getHistoryTestCases(): array
    {
        $filename = __DIR__.'/historyTestCases.json';

        /* @noinspection JsonEncodingApiUsageInspection */
        return json_decode(file_get_contents($filename), true);
    }

    /**
     * @throws Exception
     */
    private function normalizedCaseData(array $case): array
    {
        // Fill-up with default values
        $defaultCase = ['statusCode' => 200, 'params' => [], 'content' => [], 'expected' => null, 'exception' => null];
        $case += $defaultCase;

        $defaultParams = ['symbol' => '', 'start' => '', 'end' => ''];
        $case['params'] += $defaultParams;

        // Convert price date format
        foreach (['content', 'expected'] as $group) {
            if (isset($case[$group]['prices'])) {
                array_walk($case[$group]['prices'], static fn (&$price) => $price['date'] = (new DateTimeImmutable($price['date']))->getTimestamp());
            }
        }

        return $case;
    }
}
