<!DOCTYPE html>
<html>
    <style>
       /* เพิ่ม style สำหรับ legend  */
       .legend{
            line-height:20px;
            color:#555;
        }
        .legend i {
            width:18px;
            height:18px;
            float:left;
            margin-right:8px;
            opacity:0.7;
        }
        .info{
            padding:6px 8px;
            background:white;
            background:rgba(255,255,255,0.8);
            box-shadow:0 0 15px rgba(0,0,0,0.2);
            border-radius:5px;
        }
        .info h3{
            margin:0 0 5px;
            color:#888;
        }
    </style>
    <head>
        <meta charset="utf-8">
        <title> Online GIS : Leaflet </title>
        <link rel="stylesheet" href="leaflet.css" />
        <script src="leaflet.js"></script>
        
        <!-- Library สำหรับแสดงตำแหน่งเมาส์-->
        <link rel="stylesheet" href="L.Control.MousePosition.css" />
        <script src="L.Control.MousePosition.js"></script>

         <!-- เพิ่ม Leaflet AJAX สำหรับอ่านไฟล์ GeoJSON-->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        
    </head>
    <body>
        <H2>How to Create Online Map using Leaflet</H2>
       
        <!--คำสั่งเรียกแผนที่จากตัวแปลที่ชื่อ map มาแสดงหน้าเว็บ-->
        <div id="map" style="height: 700px;  border: 3px solid #AAA;"></div>  
       
       <script> //แทรกคำสั่ง JavaScript ไว้ภายใน BODY
            
            //var mymap = L.map(map).setView([16.744,100.191],15); //ตั้งค่าจุดกึ่งกลางแผนที่ไปที่ ม.นเรศวร
            var mymap= L.map('map').setView([10.814,100.569], 5); //ตั้งค่าจุดกึ่งกลางแผนที่ไปที่ ประเทศไทย
            
            //คำสั่งเรียกค่าพิกัดมาแสดงบนแผนที่
            L.control.mousePosition().addTo(mymap);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);
            
           
            // จุดแสดงตำแหน่ง marker
            var marker=L.marker([16.745, 100.194]).bindPopup("มหาวิทยาลัยนเรศวร");
            
            // แสดงรัศมี circle
            var circle=L.circle([16.745, 100.194],radius=500);

            //คำสั่งเพิ่ม เส้น
            var polyline = L.polyline([
                [16.742, 100.180],
                [16.735, 100.189],
                [16.735, 100.190],
                [16.739, 100.189]
            ]);
            
            //คำสั่งเพิ่ม รูปพื้นที่ปิด
            var polygon = L.polygon([
                [16.753, 100.179],
                [16.743, 100.179],
                [16.743, 100.190],
                [16.753, 100.190]
            ]);

            //การเรียกแผนที่พื้นมาแสดงและ สร้าง Layer group
            var google_map=L.tileLayer('https://mt1.google.com/vt/lyrs=r&x={x}&y={y}&z={z}',{
                maxZoom:18,
                subdomains:['mt0','mt1','mt3','mt4']
            });
            var openstreetmap=L.tileLayer('http://tile.openstreetmap.org/{z}/{x}/{y}.png',{
                maxZoom:18
            });
            var EsriWorldStreetMap=L.tileLayer
            ('http://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}',{
                maxZoom:18
            });
            var EsriWorldImagery=L.tileLayer
            ('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',{
                maxZoom:18
            }).addTo(mymap);

            

        function popUp(f,l){
            var out = [];
                if (f.properties){
                for(key in f.properties){
                    out.push(key+": "+f.properties[key]);
                }
                l.bindPopup(out.join("<br />"));
            }
        }

           //คำสั่งเรียกไฟล์ GeoJSON มาแสดง
            var jsonTest = new L.GeoJSON.AJAX(["data/thailand_province.geojson"],
            {
                onEachFeature:onEachFeature, 
                style:style
            });

            var province_sql=
                        <?php
                            include 'json_sql.php'
                        ?>
           

            // ฟังก์ชัน getColor ใส่สีลงไปในข้อมูล jeoJSON
            function getColor(d) {
                return d > 70 ? '#80026':
                d>60 ? '#BD0026':
                d>50 ? '#E31A1C':
                d>40 ? '#FC4E2A':
                d>30 ? '#FD8D3C':
                d>10 ? '#FEB24C':
                d>10 ? '#FED976':
                '#FFEDA0';
                }
            // ใส่สีขอบ
            function style(feature){
                return {
                    fillColor:getColor(feature.properties.gid),
                    weight:2,
                    opacity:1,
                    color:'red',
                    dashArray:'5',
                    fillOpacity:0.7
                };
            }

            // ฟังก์ชัน zoom ToFeature
            //ฟังก์ชันการสร้าง hightlight เมื่อเมาส์ถูกวางไว้เหนือบริเวณพื้นที่จะทำให้พื้นที่นั้นเด่นขึ้นมา
            function highlightFeature(e){
                var layer =e.target;
                console.log(layer)
                layer.setStyle({
                    weight:5,
                    color:'white',
                    dashArray:'',
                    fillOpacity:0.9
            });
            if(!L.Browser.ie && !L.Browser.opera && !L.Browser.edge){
                layer.bringToFront();
            }
            info.update(layer.feature.properties);
            }

            var geojson;
            // ฟังก์ชันยกเลิก highlight เมื่อนำเมาส์ออกจากพื้นที่
            function resetHighlight(e){
                geojson.resetStyle(e.target);
            }
            // ฟังก์ชัน zoom ToFeature คือ เมื่อเมาส์มีการคลิกที่บริเวณพื้นที่จะทำการขยาย
            function zoomToFeature(e){
                mymap.fitBounds(e.target.getBounds());
            }

            // ฟังก์ชัน onEachFeature คือการนำ Feature ที่ถูกสร้างมาทำการแสดงโดยรันคำสังมาจากเมาส์ สั่งให้แสดงรายละเอียดคอลัมน์ที่เลือก
            function onEachFeature(feature,layer){
                var popupContent='<b>จังหวัด'+feature.properties.prov_nam_t;
                layer.bindPopup(popupContent)
                layer.on({
                    mouseover:highlightFeature,
                    mouseout:resetHighlight,
                    click:zoomToFeature
                });
                
            }

            //เพิ่มฟังก์ชัน legend คือ การเพิ่มคำอธิบายสัญลักษณ์
            var legend=L.control({
                position:'bottomright'
            });
            legend.onAdd = function(mymap){
                var div=L.DomUtil.create('div','info legend'),
                grades=[0,10,20,30,40,50,60,70],
                labels=[];
                for(var i=0; i< grades.length; i++){
                    div.innerHTML+=
                    '<i style="background:'+getColor(grades[i]+1)+' "></i>'+
                    grades[i]+(grades[i+1]? '&ndash;'+grades[i+1]+'<br>':'+');
                }
                return div;
            };
            legend.addTo(mymap);

            //เพิ่มฟังก์ชัน info เพื่อแสดงข้อมูลชื่อจังหวัดบนซ้ายสุด
            var info=L.control({
                position:'topleft'
            });
            info.onAdd=function(map){
                this_div=L.DomUtil.create('div','info');
                this.update();
                return this_div;
            };
            info.update=function(area){
                console.log(area)
                    this_div.innerHTML='<h4>แผนที่แสดงข้อมูล<h4>'+(area?
                    '<b><center>'+'ขอบเขตของ '+area.prov_nam_t+'</b><br/>':
                    '');
                };
                info.addTo(mymap);    

               var geojson=L.geoJson(province_sql,{
                onEachFeature:onEachFeature, 
                style:style
               }).addTo(mymap);


            //Add WMS From GeoServer
            var thailand_bound = L.tileLayer.wms('http://localhost:8080/geoserver/Online_GIS/wms', {
                layers: 'Online_GIS:Thailand_Bound',
                format: 'image/png',
                //opacity: 0.5,
                transparent:true,
                tiled: 'true'
            });   


    var showpoint=L.geoJSON();
    var marker_arr=[];
    mymap.on("click",function(e){
     if(marker_arr.length > 0){
     for(i=0;i<marker_arr.length;i++){
         mymap.removeLayer(marker_arr[i]);
     }
     }
     var ct="<center><h3>Search By Distance</h3></center>";
    ct +="Lat: <input type='number' id='lat' value=" +e.latlng.lat + ">";
    ct +="<br>Lng: <input type='number' id='lng' value=" +e.latlng.lng + "><br>";
    ct +="Distance: <input type='text' id='distance'>";
    ct +="<br><br><center><button onclick='sendtodb();'>Submit</button></center>";
    
    var marker=new L.Marker([e.latlng.lat,e.latlng.lng]).addTo(mymap).bindPopup(ct).openPopup();
     marker_arr.push(marker);
    });

    function sendtodb(){
        if (showpoint !=null){
            mymap.removeLayer(showpoint);
        }
        var url = 'query.php';
        var lat = document.getElementById("lat").value;
        var lng = document.getElementById("lng").value;
        var distance = document.getElementById("distance").value;
        console.log(lat,lng);
        var resp;
        $.ajax({
            'url': url,
            'type':'GET',
            'datatype':'text',
            'data':{lat,lng,distance},
            'async':false,
            'success':function (data) {
                resp = data;
                pointjson = JSON.parse(data);
                console.log(pointjson);
                showpoint = L.geoJSON(pointjson).addTo(mymap);

            }
        });
        console.log(resp);
    }

    var showpoint1=L.geoJSON();
    var marker_arr=[];

    mymap.on("dblclick",function(e){
     if(marker_arr.length > 0){
     for(i=0;i<marker_arr.length;i++){
        mymap.removeLayer(marker_arr[i]);
     }
     }
     var ct="<center><h3>เพิ่มข้อมูลตำแหน่งที่คลิก</h3></center>";
    ct +="Lat: <input type='number' id='lat' value=" +e.latlng.lat + ">";
    ct +="<br>Lng: <input type='number' id='lng' value=" +e.latlng.lng + "><br>";
    ct +="Name: <input type='text' id='name'>";
    ct +="<br><br><center><button onclick='inserttodb();'>Submit</button></center>";
    
    var marker1=new L.Marker([e.latlng.lat,e.latlng.lng]).addTo(mymap).bindPopup(ct).openPopup();
     marker_arr.push(marker1);
    });

    mymap.on("contextmenu",function(e){
    if(marker_arr.length > 0){
        for(i=0;i<marker_arr.length;i++){
            mymap.removeLayer(marker_arr[i]);
        }
    }});
   
    function inserttodb(){
        if (showpoint1 !=null){
            mymap.removeLayer(showpoint1);
        }
        var url = 'insert.php';
        var lat = document.getElementById("lat").value;
        var lng = document.getElementById("lng").value;
        var name = document.getElementById("name").value;
        console.log(lat,lng);
        var resp;
        $.ajax({
            'url': url,
            'type':'GET',
            'datatype':'text',
            'data':{lat,lng,name},
            'async':false,
            'success':function (data) {
                resp = data;
                pointjson1 = JSON.parse(data);
                console.log(pointjson1);
                showpoint1 = L.geoJSON(pointjson1).addTo(mymap);

            }
        });
        console.log(resp);
    }

           var baseLayers={
               "Google_map":google_map,
               "Openstreetmap":openstreetmap,
               "EsriWorldStreetMap":EsriWorldStreetMap,
               "EsriWorldImagery":EsriWorldImagery
           };

           //การประกาศตัวแปร 
           var overlays={
               "จุด":marker,
               "เส้น":polyline,
               "สี่เหลี่ยม":polygon,
               "วงกลม":circle,
               "ขอบเขตจังหวัด":jsonTest,
               "ขอบเขตจังหวัด SQL":geojson,
               "ขอบเขตประเทศไทย":thailand_bound
             
           };

           L.control.layers(baseLayers,overlays).addTo(mymap);



        </script>
    </body>
</html>

