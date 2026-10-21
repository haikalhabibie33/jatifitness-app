<?php

class GeoService
{
    protected string $token;
    protected string $baseUrl = 'https://ipinfo.io/';

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getLocation(string $ip): ?array
    {
        try {
            $url = $this->baseUrl . $ip . '?token=' . $this->token;

            $response = @file_get_contents($url);

            if (!$response) {
                return null;
            }

            $data = json_decode($response, true);

            return [
                'ip'      => $ip,
                'city'    => $data['city'] ?? null,
                'region'  => $data['region'] ?? null,
                'country' => $data['country'] ?? null,
                'org'     => $data['org'] ?? null
            ];

        } catch (\Throwable $e) {
            log_message('error', 'GeoService error: ' . $e->getMessage());
            return null;
        }
    }

    public function getFormatted(string $ip): string
    {
        $geo = $this->getLocation($ip);

        if (!$geo) {
            return 'Unknown';
        }

        return trim(
            ($geo['city'] ?? '') . ', ' .
            ($geo['region'] ?? '') . ', ' .
            ($geo['country'] ?? '')
        );
    }
}