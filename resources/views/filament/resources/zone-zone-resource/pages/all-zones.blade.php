<x-filament::page>
    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 600px;
            /* The height is 400 pixels */
            width: 100%;
            /* The width is the width of the web page */
        }
    </style>
      
      <input id='long_lat' hidden value="{{$jsonData}}" >
      <div wire:ignore>
        <div id="map"></div>
    </div>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkNrB5dUdur4Bh91AY2Ig-3ptMDl7Ap7U"></script>
        <script>
             var jsontData = JSON.parse(document.getElementById('long_lat').value);
             var first_zone=jsontData[0].latitude_longitude;
            

            // Create a map centered on the specified coordinates
            var map = new google.maps.Map(document.getElementById('map'), {
                center:first_zone[0],
                zoom: 12
            });
            const svgMarker = {
    path: "M-1.547 12l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM0 0q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
    fillColor: "red",
    fillOpacity: 0.6,
    strokeWeight: 0,
    rotation: 0,
    scale: 2,
    anchor: new google.maps.Point(0, 20),
  };
            // Loop through inputData and create polygons for each set of coordinates
            for (var i = 0; i < jsontData.length; i++) {
               
                 var coordinates = jsontData[i].latitude_longitude
                 var name=jsontData[i].name
                var polygon = new google.maps.Polygon({
                    paths: coordinates,
                    strokeColor: jsontData[i].color,
                    strokeWeight: 5,
                    content: name,
                    map: map
                });
                new google.maps.Marker({
						
                        position:coordinates[0],
                        icon:svgMarker,
                        map,
                        title: name,
                    });
               
               
               
            }
        </script>
    

</x-filament::page>
