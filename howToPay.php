<?php

require 'Results.php';

// 使用PDO方式連線資料庫
try {
    $pdo = new PDO('mysql:host=localhost;dbname=howpaynd_db;charset=utf8mb4','howpaynd_pulin','cucu319044');
}
catch(PDOException $e){
    die($e->getMessage());
}

//取得前一頁選擇的選項
$card = $_GET["card"];
$week = $_GET["week"];
$type = $_GET["type"];

//SQL查詢語法
$sql = "SELECT `creditcard`,`payType`,`payment`,`sale` FROM `info` WHERE `creditcard` = '".$card."' AND `week` = '".$week."' AND `type` LIKE '%".$type."%'";

// echo $sql;
$statement = $pdo->prepare($sql);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_CLASS, 'Results');

//列出所有查詢後回傳的資料
// var_dump($results);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>幫我搭配結果</title>
    <link rel="stylesheet" href="reset.css">
    <script type = "text/javascript" src="jquery-3.5.1.js"></script>
</head>
<body>
    <nav>
        <img src="img/Howpay-320.png" alt="logo">
    </nav>

    <h2>搭配結果</h2>
    <table>
        <tr>
            <td>卡片</td>
            <td>商家</td>
            <td>支付工具</td>
            <td>優惠訊息</td>
        </tr>
        <?php foreach($results as $results) : ?>
            <?='<tr>';?>
                <?= '<td>'.$results->creditcard.'</td>';?>
                <?= '<td>'.$results->payType.'</td>';?>
                <?= '<td>'.$results->payment.'</td>';?>
                <?= '<td>'.$results->sale.'</td>';?>
            <?='</tr>';?>
        <?php endforeach; ?>
        <?='</table>';?>
    </table>

</body>
</html>
