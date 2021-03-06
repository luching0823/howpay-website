<?php


require 'Place.php';

//連線資料庫
try {
    $pdo = new PDO('mysql:host=localhost;dbname=howpaynd_db;charset=utf8mb4','howpaynd_pulin','psw');
}
catch(PDOException $e){
    die($e->getMessage());
}

//取得前一頁選單的值
$Payment = $_GET["Payment"];
$Type = $_GET["Type"];

//SQL語法
$sql = "SELECT `city`,`name`, `lat`, `lng` FROM `info2` WHERE `type` = '".$Type."' AND `payment` LIKE '%".$Payment."%'";
// echo $sql;

$statement = $pdo->prepare($sql);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_CLASS, 'Place');

//將回傳回來的資料轉成JSON格式
$place = json_encode($results,JSON_UNESCAPED_UNICODE);
// $data = json_decode($place, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搭配商家結果</title>
    <script type = "text/javascript" src="jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="google.css">
</head>
<body>
    <div id="map"></div>
    <!-- 將JSON格式的資料匯進來 -->
    <input type="hidden" id="city" name="city" value=<?php print_r($place);?>>
    <script type="text/javascript">
        
        // 取得資料放到text
        var text = document.getElementById("city").value;
        //資料放到text會變字串，所以要再轉一次格式
        var member = JSON.parse(text);

        var marker, map, lat, lng;
        function initMap() {
            //取得目前定位
            navigator.geolocation.getCurrentPosition((position) => {
                console.log(position.coords);
                lat = position.coords.latitude;
                lng = position.coords.longitude;
                var mapcenter = new google.maps.LatLng(lat, lng);
                var options = {
                    zoom: 12,
                    center: mapcenter
                };
                // 初始化地圖
                map = new google.maps.Map(document.getElementById('map'), options);
                //放置user location的地標
                marker = new google.maps.Marker({
                    position: mapcenter,
                    map: map,
                    icon: 'img/man.png',
                    animation: google.maps.Animation.DROP,
                    label: 'You'
                });
                //資料內的地標
                for (var i = 0; i < member.length; i++) {
                    var item = member[i];
                    var infowindow = new google.maps.InfoWindow();
                    var marker = new google.maps.Marker({
                        position: {lat: parseFloat(item.lat), lng: parseFloat(item.lng)},
                        map: map,
                        title: item.name,
                        data:'名稱:' + item.name
                    });
                    //點擊marker跳出店家名稱的infoWindow
                    marker.addListener('click', function() {
                    infowindow.setContent( this.data );
                    infowindow.open(map, this);
                    });
                };
            });
        }
    </script>

<script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiwnenlYE9A8vl8ETUg4D8IQmeqTr2FWM&callback=initMap"></script>
</body>
</html>