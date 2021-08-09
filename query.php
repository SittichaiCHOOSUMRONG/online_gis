<?php
$hostname_db="localhost";
$database_db="phitsanulok";
$username_db="postgres";
$password_db="postgres";
$port_db="5432";
$db=pg_connect("host=$hostname_db user=$username_db port=$port_db password=$password_db dbname=$database_db") ;

$lat =$_GET['lat'];
$lng =$_GET['lng'];
$distance =$_GET['distance'];

// $sql ="
// SELECT *,ST_AsGeoJSON(geom) AS geojson from landmark 
// where st_distancesphere(st_geomfromtext('POINT($lng $lat)',4326),landmark.geom)<= $distance;
// ";

$sql ="
SELECT *,ST_AsGeoJSON(st_transform(geom,4326)) AS geojson from tha_tambon t 
where st_dwithin(ST_Transform (ST_GeomFromText ('POINT($lng $lat)',4326),3857),t.geom, $distance);
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
            'lm_name' => $edge['lm_name']
        )
        );
        array_push($geojson['features'],$feature);
    }
     echo json_encode($geojson);
?>
