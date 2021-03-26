<?php
$hostname_db="localhost";
$database_db="phitsanulok";
$username_db="postgres";
$password_db="postgres";
$port_db="5432";
$db=pg_connect("host=$hostname_db user=$username_db port=$port_db password=$password_db dbname=$database_db") ;

$lat =$_GET['lat'];
$lng =$_GET['lng'];
$name =$_GET['name'];

$sql ="
INSERT INTO points(geom,name) values (ST_SetSRID(ST_Point(".$lng." ,".$lat."),4326),'".$name."');
SELECT * ,ST_AsGeoJSON(geom,5) AS geojson FROM points;
";

$query=pg_query($db,$sql);
$geojson=array(
    'type' => 'FeatureCollection',
    'features' => array()
);
while ($edge = pg_fetch_assoc($query)){
    $feature = array(
    'type'=> 'Feature',
    'geometry' => json_decode($edge['geojson'],true),
    'crs'=> array(
        'type'=>'EPSG',
        'properties'=> array('code'=>'4326')
    ),
    'properties' =>array(
        'gid'=>$edge['gid'],
        'prov_namet' => $edge['prov_namet']
    )
    );
    array_push($geojson['features'],$feature);
}
 echo json_encode($geojson);









?>
