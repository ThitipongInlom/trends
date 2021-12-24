<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;

class Trends 
{
    public function readJsonTrends($urlTrends)
    {
        $string = file_get_contents($urlTrends);
        $new_string = str_replace(")]}',", "", $string);
        $json = json_decode($new_string, true);
        return $json['default']['trendingSearchesDays'];
    }

    public function sendLineNotify($token, $message)
    {
        $client = new Client();
        $response = $client->request('POST', 'https://notify-api.line.me/api/notify', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'message' => $message
            ]
        ]);
    }
}
