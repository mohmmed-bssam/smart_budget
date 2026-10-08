<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

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
                $response = Http::timeout(10)
                    ->get("https://api.frankfurter.dev/v2/rate/{$from}/{$to}");

                if ($response->failed()) {
                    throw new RuntimeException('Unable to fetch exchange rate.');
                }

                return (float) $response->json('rate');
            }
        );
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $rate = $this->getRate($from, $to);

        return round($amount * $rate, 2);
    }
}
