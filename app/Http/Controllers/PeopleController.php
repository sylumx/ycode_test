<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

class PeopleController extends Controller
{

    public function index()
    {
        return view('people');
    }

    public function getPeople()
    {
        $curl = curl_init();
        $apikey = config('services.airtable.key');

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.airtable.com/v0/appyZpFaR44YQ1oiP/Grid%20view",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer $apikey"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            Log::error('cURL Error #:' . $err);
        } else {
            return $response;
        }
    }
}
