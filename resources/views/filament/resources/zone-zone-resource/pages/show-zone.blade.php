<x-filament::page>
    <style>
        /* Set the size of the div element that contains the map */
        #map {
            height: 600px;
            /* The height is 400 pixels */
            width:100%;
            /* The width is the width of the web page */
        }
    </style>
    <div>
        <input value="{{ $record->lattitude_longitudes }}" id="coordinates-data" hidden>
        <input value="{{ $record->marker_color }}" hidden id="color">
        <input value="{{ $record->company->resPartner->partner_latitude }}"id="p_latt" hidden>
        <input value="{{ $record->company->resPartner->partner_longitude}}"id="p_long" hidden>
            <div id="map"></div>
     
            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkNrB5dUdur4Bh91AY2Ig-3ptMDl7Ap7U"></script>
        </div>
        <script>
          var inputData = JSON.parse(document.getElementById('coordinates-data').value);
          var p_long = parseFloat(document.getElementById('p_long').value);
          var p_latt = parseFloat(document.getElementById('p_latt').value);
        var testdata = []; // Initialize an empty array for coordinates
        var color=document.getElementById('color').value;
        // Loop through the input data and directly push coordinates into the coords array
        for (var i = 0; i < inputData.length; i++) {
            var item = inputData[i];
            testdata.push({ lat: item.latitude, lng: item.longitude });
        }  
      

            // Create a map centered on the specified coordinates
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: p_latt,
                    lng: p_long
                },
                zoom: 12
            });

            // NOTE: This uses cross-domain XHR, and may not work on older browsers.
            map.data.loadGeoJson(
                "https://storage.googleapis.com/mapsdevsite/json/google.json"
            );
            
            map.data.setStyle({
                strokeColor:color,
                strokeWeight: 5,
            });


         
         

            map.data.add({
                geometry: new google.maps.Data.Polygon([testdata])
            });



            //layer.setZIndex(100);
            map.data.setMap(map);
        </script>
       
</x-filament::page>
