<?php

namespace App\Service;

use App\Exception\WebAccessException;
use DateTimeInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

final class History
{
    private const API_HOST = 'yh-finance.p.rapidapi.com';

    private const REGION = 'US';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly string $historyUrl,
        private readonly string $historyApiKey,
    ) {
    }

    /**
     * @throws WebAccessException
     */
    public function getHistory(string $symbol, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        try {
            $response = $this->client->request('GET', $this->historyUrl, [
                'headers' => [
                    'X-RapidAPI-Key' => $this->historyApiKey,
                    'X-RapidAPI-Host' => self::API_HOST,
                ],
                'query' => [
                    'symbol' => $symbol,
                    'region' => self::REGION,
                ],
            ]);

            $statusCode = $response->getStatusCode();
            $data = $response->toArray();
        } catch (RedirectionExceptionInterface $exception) {
            throw new WebAccessException(null, 'Redirection. (You may not have permission to get these data?)');
        } catch (Throwable $exception) {
            throw new WebAccessException($exception);
        }

        if (200 !== $statusCode || !$data) {
            throw new WebAccessException();
        }

        $start = $startDate->getTimestamp();
        $end = $endDate->getTimestamp();
        $data['prices'] = array_values(array_filter($data['prices'], static fn (array $price) => $price['date'] >= $start && $price['date'] <= $end));

        return $data;
    }
}
