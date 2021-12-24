<?php
date_default_timezone_set("Asia/Bangkok");
require 'vendor/autoload.php';
use GuzzleHttp\Client;

$urlTrends = 'https://trends.google.co.th/trends/api/dailytrends?hl=th&tz=-420&geo=TH&ns=15';
$token = 'VYf6Wy2KJaaEZtSjVKyLgGtAB7JhZzxWjeNOqOwRPbm';

$arrayTrends = readJsonTrends($urlTrends);
$nameTrends = array();
foreach ($arrayTrends as $key => $row) {
    if ($row['date'] == date('Ymd')) {
        foreach ($row['trendingSearches'] as $key => $value) {
            $nameTrends[$key]['title'] = $value['title']['query'];
            $nameTrends[$key]['traffic'] = $value['formattedTraffic'];
        }
    }
}

$message = "Google Trends TH NH: 10 คำค้นหายอดนิยมบน Google (".date('d/m/Y H:i:s').") \n\n";
foreach ($nameTrends as $key => $row) {
    $message .= ($key + 1).".".$row['title']." | ยอดค้นหา ".$row['traffic']." \n";
}
$message .= "\nติดตามเพิ่มเติมที่ https://thaip.bs/google-trends-th";

echo $message;

sendLineNotify($token, $message);

function readJsonTrends($urlTrends)
{
    $string = file_get_contents($urlTrends);
    $new_string = str_replace(")]}',", "", $string);
    $json = json_decode($new_string, true);
    return $json['default']['trendingSearchesDays'];
}

function sendLineNotify($token, $message)
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
