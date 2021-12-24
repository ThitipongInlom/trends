<?php
date_default_timezone_set("Asia/Bangkok");
require 'assets/system.php';

$urlTrends = 'https://trends.google.co.th/trends/api/dailytrends?hl=th&tz=-420&geo=TH&ns=15';
$token = 'VYf6Wy2KJaaEZtSjVKyLgGtAB7JhZzxWjeNOqOwRPbm';

$arrayTrends = Trends::readJsonTrends($urlTrends);
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

Trends::sendLineNotify($token, $message);
