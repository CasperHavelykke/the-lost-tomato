<?php
    session_start();

    //Database variables
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "the_lost_tomato";

    //Connect to database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //Check connection
    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }else{
        $connection = "Connected successfully to database";
    }

    //Fetch Speed 1
    $sql = "SELECT Speed, Pixels FROM speed WHERE Speed = 500";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $SpeedID1 = "Speed 1: " . $row["Speed"] . " km/h = " . $row["Pixels"] . " pixels/h";
        $pixels = $row["Pixels"];
    }
    } else {
    echo "Failed fetching Speed 1";
    }

    //Fetch speed 2
    $sql = "SELECT Speed, Pixels FROM speed WHERE Speed = 1000";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $SpeedID2 = "Speed 2: " . $row["Speed"] . " km/h = " . $row["Pixels"] . " pixels/h";
        $pixelsDbl = $row["Pixels"];
    }
    } else {
    echo "Failed fetching Speed 2";
    }

    //Submit time and session id to database
    if (!empty($_POST['timeInput'])) {
        
        //Saves time at end in variable
        $time = $_POST['timeInput'];
        $sessionID = session_id();

        $sql = "INSERT INTO `sessions`(`SessionsID`, `SessionID`, `Time`) VALUES ('', '$sessionID', '$time')";
        mysqli_query($conn, $sql);
    }
    mysqli_close($conn);

?>

<!DOCTYPE html>

<html>
    <head>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>

     <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
     integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
     crossorigin=""></script>

     <script src="https://code.jquery.com/jquery-3.6.1.min.js" 
     integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" 
     crossorigin="anonymous"></script>

     <style>
        body {
            margin:0;
        }
        .heading {
            font-family: verdana;
            font-size: 20pt;
            width:100%;
            padding-top: 50px;
            position:absolute;
            z-index: 1000;
            text-align: center;
            color: #EA5E57;
            -webkit-text-stroke-width: 2px;
            -webkit-text-stroke-color: black;
        }
        #map { 
            height: 100vh; 
        }
     </style>

    </head>



    <body>

        <div class="heading"><h1>The Lost Tomato</h1></div>
        
        <div class="finish">
            <form name="timeForm" id="timeForm" method="POST" action="index.php">
                <input id="timeInput" type="hidden" value="0" name="timeInput">
            </form>
        </div>

        <div id="map"></div>

    </body>
    


    <script>
        //Start JavaScript timer
        var TimerStart = Date.now();

        //Map
        var map = L.map('map', {
            dragging: true, //enable / disable mouse dragging
            keyboard: false //enable / disable keyboard
        });

        //Start viewpoint
        map.setView([55.683125, 12.571472], 15);

        //Map design
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 15,
            minZoom: 15,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        //Tomato icon
        var tomatoIcon = L.icon({
            iconUrl: 'images/tomato.png',
            iconSize: [50, 50]
            //popupAnchor: [-3, -76]
        });

        //Tomato fixed to center of screen
        tomato = new L.marker(map.getCenter(), {
            icon: tomatoIcon,
            zIndexOffset: 1000
        }).addTo(map);

        //Home icon
        var homeIcon = L.icon({
            iconUrl: 'images/home.png',
            iconSize: [90, 90]
        });

        //Home icon position
        home = new L.marker([55.232816, 11.767130], {icon: homeIcon}).addTo(map);

        //Pineapplle icon
        var pineappleIcon = L.icon({
            iconUrl: 'images/pineapple.png',
            iconSize: [500, 500]
        });

        //Pineapple start position
        pineapple = new L.marker([55.671340, 12.556177], {icon: pineappleIcon}).addTo(map);
        pineapple2 = new L.marker([55.42058, 11.988444], {icon: pineappleIcon}).addTo(map);
        pineapple3 = new L.marker([55.243126, 11.787796], {icon: pineappleIcon}).addTo(map);

        //Goal circle
        var goalCircle = L.circle([55.232816, 11.767130], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.2,
            radius: 1010,
            stroke: false,
            interactive: false
        }).addTo(map);
        

        <?php 
            //Time it takes for the tomato to move the given pixels
            $sec = 0.25;
        ?>

        //Configure arrow keys
        $(document).ready(function(e){
            var keys = {};

            //Register key press and release
            $(document).keydown(function(event){
                keys[event.which] = true;
            }).keyup(function(event){
                delete keys[event.which];
            });

            //Register which keys were pressed and config action
            function gameLoop() {
                if (keys[37] && keys[32]) {//left arrow key, fast
                    map.panBy([-<?=$pixelsDbl?>, 0], {duration:<?=$sec?>});
                }
                else if (keys[37]) {//left arrow key, slow
                    map.panBy([-<?=$pixels?>, 0], {duration:<?=$sec?>});
                }
                if (keys[38] && keys[32]) { //up arrow key, fast
                    map.panBy([0, -<?=$pixelsDbl?>], {duration:<?=$sec?>});
                }
                else if (keys[38]) { //up arrow key
                    map.panBy([0, -<?=$pixels?>], {duration:<?=$sec?>});
                }
                if (keys[39] && keys[32]) { //right arrow key, fast
                    map.panBy([<?=$pixelsDbl?>, 0], {duration:<?=$sec?>});
                }
                else if (keys[39]) {   //right arrow key
                    map.panBy([<?=$pixels?>, 0], {duration:<?=$sec?>});
                }
                if (keys[40] && keys[32]) {    //down arrow key, fast
                    map.panBy([0, <?=$pixelsDbl?>], {duration:<?=$sec?>});
                }
                else if (keys[40]) {    //down arrow key
                    map.panBy([0, <?=$pixels?>], {duration:<?=$sec?>});
                }

                //Loop every 0.25 second
                setTimeout(gameLoop, 25);
            }
            gameLoop();
        });

        //Tomato relocate to center of screen upon moving
        map.on('move', function(e) {
            tomato.setLatLng(map.getCenter());
        });

        //Retrieve tomato location, distance to home, and alert when tomato is within 1km of home
        var finished = false;
        map.on('move', function tomatoLatLng(tomatoLatLng) {

            //Distance to tomato
            var tomatoLatLng = tomato.getLatLng();
            var distanceToHome = tomatoLatLng.distanceTo([55.232816, 11.767130]);
            var distanceToHomeKM = distanceToHome / 1000;

            //Distance to pineapples
            var pineappleLatLng = pineapple.getLatLng();
            var distanceToPineapple = pineappleLatLng.distanceTo(tomatoLatLng);
            var distanceToPineappleKM = distanceToPineapple / 1000;
            var pineapple2LatLng = pineapple2.getLatLng();
            var distanceToPineapple2 = pineapple2LatLng.distanceTo(tomatoLatLng);
            var distanceToPineapple2KM = distanceToPineapple2 / 1000;
            var pineapple3LatLng = pineapple3.getLatLng();
            var distanceToPineapple3 = pineapple3LatLng.distanceTo(tomatoLatLng);
            var distanceToPineapple3KM = distanceToPineapple3 / 1000;

            //Runs in to pineapple
            function lost(){
                var finished = true;

                //Stop timer
                var TimerStop = Date.now();
                var Time = TimerStop - TimerStart;
                var TimeSec = Time / 1000;

                alert("The Tomato hit the evil pineapple after: " + TimeSec + " seconds. Game is reset.");

                //Insert time into value field
                document.getElementById("timeInput").value = TimeSec;

                //Submit time form via JS
                document.getElementById("timeForm").submit();

                console.log("Game finished? = " + finished);
                console.log("Tomato hit the evil pineapple after: " + TimeSec + " seconds.");
            }

            //Goal
            if(distanceToHomeKM < 1){

                var finished = true;

                //Stop timer
                var TimerStop = Date.now();
                var Time = TimerStop - TimerStart;
                var TimeSec = Time / 1000;

                alert("The Tomato reached home after: " + TimeSec + " seconds.");

                //Insert time into value field
                document.getElementById("timeInput").value = TimeSec;

                //Submit time form via JS
                document.getElementById("timeForm").submit();
                
                console.log("Game finished? = " + finished);
                console.log("Tomato reached home after: " + TimeSec + " seconds.");

            }else if(distanceToPineappleKM < 0.7){
                lost();
                
            }else if(distanceToPineapple2KM < 0.7){
                lost();
                
            }else if(distanceToPineapple3KM < 0.7){
                lost();
                
            }

            return finished;
        });


        //Calculate pixels to meters
        var centerLatLng = map.getCenter(); // get map center
        var pointC = map.latLngToContainerPoint(centerLatLng); // convert to containerpoint (pixels)
        var pointX = [pointC.x + <?=$pixels?>, pointC.y]; // add pixels to x
        var pointY = [pointC.x, pointC.y + <?=$pixels?>]; // add pixels to y

        // convert containerpoints to latlng's
        var latLngC = map.containerPointToLatLng(pointC);
        var latLngX = map.containerPointToLatLng(pointX);
        var latLngY = map.containerPointToLatLng(pointY);

        var distanceX = latLngC.distanceTo(latLngX); // calculate distance between c and x (latitude)
        var distanceY = latLngC.distanceTo(latLngY); // calculate distance between c and y (longitude)

        console.log("<?=$connection?>");
        console.log("<?=$SpeedID1?> ");
        console.log("<?=$SpeedID2?> ");
        //console.log("Distance X: " + distanceX / 1000);
        //console.log("Distance Y: " + distanceY / 1000);
    </script>
</html> 
