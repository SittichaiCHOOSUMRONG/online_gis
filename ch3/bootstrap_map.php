<!DOCTYPE html>
<html lang="en">
<?php include 'database_connect.php' ?>
<head>
 <title>Leaflet with Bootstrap Example</title>
 <meta charset="utf-8">
 <meta name ="viewport" content="width=device-width,initial-scale=1">
 <link rel="stylesheet"
 href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script scr="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script scr="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.js"></script>
<!--บรรทัดนี้ คือไลบารี่สำหรับเรียกการตกแต่ง style ให้กับหน้าเว็บแผนที่-->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css">
<link href="//cdn.bootcss.com/leaflet/1.0.0-rc.3/leaflet.css" rel="stylesheet">
</head>
<!--ส่วนที่จะพิมพ์คำสั่ง JavaScript สำหรับสร้างหน้าเว็บและเรียกแผนที่มาแสดง โดยจะใส่ชุดคำสั่งนี้ไว้ใต้ BODY-->
<body>
       <!--สำหรับการดึงข้อมูลมาแสดงให้เลือก-->
<form action="my_first_leaflet.php" target="my_map">
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <ul class="navbar-nav">
        <li>
        <select name="admin_province" class="form-control">
            <?php
                $sql="select prov_nam_t from tha_province ";
                $query = pg_query($sql);
                while($row=pg_fetch_array($query)){
                    echo "<option value= '".$row['prov_nam_t']."'>" .$row['prov_nam_t']."</option>";
                }
            ?>
        </select>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Link2</a>
        </li>
    </ul>
        <li>
            <button type="submit" class="btn btn-info">SUBMIT</button>
        </li>
    </nav> 
</form>
<!--เรียกแผนที่มาแสดงบนเว็บ-->
<div>
<iframe id="iframe_target" name="my_map" src ="my_first_leaflet.php" width="100%" height="800px" farmeborder="0"></iframe>
</div>
</body>
</html>