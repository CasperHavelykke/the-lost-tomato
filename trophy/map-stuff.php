<?php

$lat1 = "55.677324";
$lon1 = "12.569591";
$lat2 = "55.677282";
$lon2 = "12.571913";
$lat3 = "55.676079";
$lon3 = "12.571867";
$lat4 = "55.676379";
$lon4 = "12.569214";

$str = '{ "type": "FeatureCollection",
            "features": [
                { "type": "Feature",
                    "geometry": {
                        "type": "Polygon",
                        "coordinates": [
                            [ 
                                ['.$lon1.', '.$lat1.'],
                                ['.$lon2.', '.$lat2.'],
                                ['.$lon3.', '.$lat3.'],
                                ['.$lon4.', '.$lat4.']
                            ]
                        ]

                    },
                    "properties": {
                        "prop0": "value0",
                        "prop1": {"this": "that"}
                    }
                }
            ]
        }';

function geoJson($str) {
    return "JSON.parse(".json_encode($str).")";
}

?>
