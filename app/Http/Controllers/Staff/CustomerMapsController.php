<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerMapsController extends Controller
{
    public function resolve(Request $request)
    {
        $validated = $request->validate([
            'maps_link' => [
                'required',
                'url',
            ],
        ]);

        $mapsLink = $validated['maps_link'];

        if (
            !str_contains($mapsLink, 'maps.app.goo.gl') &&
            !str_contains($mapsLink, 'goo.gl') &&
            !str_contains($mapsLink, 'google.com/maps')
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Link yang diberikan bukan link Google Maps.',
            ], 422);
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $mapsLink,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_USERAGENT =>
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120 Safari/537.36',
        ]);

        curl_exec($ch);

        $finalUrl = curl_getinfo(
            $ch,
            CURLINFO_EFFECTIVE_URL
        );

        $curlError = curl_error($ch);

        curl_close($ch);

        if ($curlError || !$finalUrl) {
            return response()->json([
                'success' => false,
                'message' => 'Link Google Maps tidak dapat diproses.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL KOORDINAT DARI URL HASIL REDIRECT
        |--------------------------------------------------------------------------
        */

        preg_match(
            '/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/',
            $finalUrl,
            $matches
        );

        if (!$matches) {

            preg_match(
                '/[?&](?:q|query)=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/',
                $finalUrl,
                $matches
            );
        }

        if (!$matches) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Lokasi belum dapat dibaca dari link Google Maps tersebut.',
            ], 422);
        }

        $latitude = (float) $matches[1];
        $longitude = (float) $matches[2];

        if (
            $latitude < -90 ||
            $latitude > 90 ||
            $longitude < -180 ||
            $longitude > 180
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Koordinat lokasi tidak valid.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'maps_link' => $mapsLink,
            'resolved_url' => $finalUrl,
        ]);
    }
}