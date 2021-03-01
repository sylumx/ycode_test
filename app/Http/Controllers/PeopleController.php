<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormPostRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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

    public function addFormData(FormPostRequest $request)
    {


        $name = request('name');
        $email = request('email');
        $image_URL = null;
        
        if ($request->photo) {
            $pathToFile = $request->file('photo')->store('image', 'public');
            $url = Storage::url($pathToFile);
            $image_URL = url($url);
        }

        $data = array("records" => [[
            "fields" => [
                "Name" => "$name",
                "Email" => "$email",
                "Photo" => [[
                    "url" => "{$image_URL}"
                ]]]
        ]]);

        $postdata = json_encode($data);

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
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
                "Authorization: Bearer $apikey",
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
