<?php
//ชุดคำสั่งสำหรับขออนุญาติเชื่อมต่อกับฐานข้อมูล
$host = "host=localhost";//host ที่ใช้ในการติดต่อกับ Server
$port = "port=5432";//หมายเลข port ที่ใช้ (บางเครื่องอาจจะใช้ 5433 หรือเลขอื่น)
$dbname = "dbname=phitsanulok";//ใส่ชื่อฐานข้อมูลที่ต้องการทำการเชื่อมต่อ
$credentials = "user=postgres password=postgres";

//โครงสร้างชุดคำสั่งทำหรับเชื่อมต่อกับฐานข้อมูล PostgreSQL
$db = pg_connect( "$host $port $dbname $credentials" );

$admin_province=$_GET['admin_province'];

    //$sql="SELECT *, ST_AsGeoJSON(ST_Transform(geom,4326)) as geojson from tha_province limit 20;";
    if(!$admin_province){
        $sql="SELECT *,ST_AsGeoJSON(ST_Transform(geom,4326)) as geojson from tha_province LIMIT 15;";
       } else {
        $sql ="SELECT *,ST_AsGeoJSON(ST_Transform(geom,4326)) as geojson from tha_province WHERE prov_nam_t='$admin_province' ;";
       }

    $query=pg_query($db,$sql);
   
    $geojson=array(
        'type' => 'FeatureCollection',
        'features' => array()
    );
    while ($edge = pg_fetch_assoc($query)){
        $feature = array(
        'type'=> 'Feature',
		'properties'=> array('code'=>'4326'),
        'geometry' => json_decode($edge['geojson'],true),
        'crs'=> array(
            'type'=>'EPSG',
            'properties'=> array('code'=>'4326')
        ),
        'properties' =>array(
            'gid'=>$edge['gid'],
            'prov_nam_t' => $edge['prov_nam_t'],
            'prov_nam_e' => $edge['prov_nam_e'],
            'prov_code' => $edge['prov_code']
        )
        );
        array_push($geojson['features'],$feature);
    }
	
	 // Close database connection
	 pg_close($db);
     echo json_encode($geojson);
?>






