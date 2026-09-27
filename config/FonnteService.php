<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public function sendMessage(string $target, string $message): array
    {
        $response = Http::withHeaders([
            'Authorization' => config('services.fonnte.token'),
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $target,
            'message' => $message,
            'countryCode' => '62',
        ]);

        return $response->json();
    }
}