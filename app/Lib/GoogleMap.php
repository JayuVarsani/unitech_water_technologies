<?php

declare(strict_types=1);

namespace App\Lib;

use Illuminate\Support\Facades\Http;

class GoogleMap
{
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.google.map_key');
    }

    public function geoCoding(float|int $lat, float|int $lng): string
    {

        $curl = Http::get("https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$this->apiKey}");
        [$result, $isSuccessful] = [$curl->json(), $curl->successful()];

        if ($isSuccessful && isset($result['results']) && count($result['results'])) {
            return $result['results'][0]['formatted_address'];
        }

        return 'N/A';
    }
}
