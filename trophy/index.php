<?php
require("map-stuff.php");
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <title>A super cool map</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.2/dist/leaflet.css"
    integrity="sha256-sA+zWATbFveLLNqWO2gtiw3HL/lh1giY/Inf1BJ0z14="
    crossorigin=""/>

    <script src="https://unpkg.com/leaflet@1.9.2/dist/leaflet.js"
    integrity="sha256-o9N1jGDZrf5tS+Ft4gbIK7mYMipq9lqpVJ91xHSyKhg="
    crossorigin=""></script>
    
    <style>
        html, body { 
            margin:0; 
            padding:0; 
        }
        #map {
            width:100%;
            heigth:100vh;
            background-color:red;
            will-change: transform;
        }
    </style>
    
</head>

<body class="full-width">

    <div style='position:relative;width:100%;height:100%;'>
        <div id='map' style='width:100%;height:100vh;will-change: transform;'></div>
    </div>

    <script>

        var map = L.map('map').setView([<?=$lat1;?>, <?=$lon1;?>], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // Show polygon at the Trophy Office
        var geojson = L.geoJson(<?=geoJson($str);?>).addTo(map);

    </script>
</body>

</html>