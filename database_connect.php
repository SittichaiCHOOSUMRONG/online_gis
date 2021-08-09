<?php
//ชุดคำสั่งสำหรับขออนุญาติเชื่อมต่อกับฐานข้อมูล
$host = "host=localhost";//host ที่ใช้ในการติดต่อกับ Server
$port = "port=5432";//หมายเลข port ที่ใช้ (บางเครื่องอาจจะใช้ 5433 หรือเลขอื่น)
$dbname = "dbname=phitsanulok";//ใส่ชื่อฐานข้อมูลที่ต้องการทำการเชื่อมต่อ
$credentials = "user=postgres password=postgres";
$db = pg_connect( "$host $port $dbname $credentials" );
?>

