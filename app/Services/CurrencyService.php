<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class CurrencyService
{
    public function getRate(string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return 1;
        }

        return Cache::remember(
            "exchange_rate_{$from}_{$to}",
            now()->addHour(),
            function () use ($from, $to) {

                try {

                    $response = Http::connectTimeout(5)
                        ->timeout(15)
                        ->retry(2, 500)
                        ->get(
                            "https://api.frankfurter.dev/v2/rate/{$from}/{$to}"
                        );

                    if ($response->failed()) {
                        throw new RuntimeException(
                            'Unable to fetch exchange rate.'
                        );
                    }

                    $rate = $response->json('rate');

                    if (!$rate || !is_numeric($rate)) {
                        throw new RuntimeException(
                            'Invalid exchange rate response.'
                        );
                    }

                    return (float) $rate;

                } catch (Throwable $e) {

                    throw new RuntimeException(
                        'Currency service is temporarily unavailable.',
                        0,
                        $e
                    );
                }
            }
        );
    }


    public function convert(
        float $amount,
        string $from,
        string $to
    ): float {
        $rate = $this->getRate($from, $to);

        return round(
            $amount * $rate,
            2
        );
    }
}