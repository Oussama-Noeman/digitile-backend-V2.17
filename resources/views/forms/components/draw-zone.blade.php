
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-action="$getHintAction()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <style>
        /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
        #map {
            height: 400px;
        }

        /* Optional: Makes the sample page fill the window. */

        #save {
            background: none padding-box rgb(255, 255, 255);
            display: table-cell;
            padding: 16px;
            color: rgb(86, 86, 86);
            height: 40px;
            font-family: Roboto, Arial, sans-serif;
            font-size: 18px;
            border-radius: 3px;
            box-shadow: rgb(0 0 0 / 30%) 0px 1px 4px -1px;
            border: 0px;
            cursor: pointer;
            font-weight: 500;
            line-height: 10px;
            width: 170px;
        }

        #save:hover {
            background-color: #EBEBEB;
        }
        
    </style>
    
    
    <div x-data="{ state: $wire.entangle('{{ $getStatePath('zone') }}') }">
        {{-- inputs --}}
        <input id='long_lat' hidden  value="{{ $getZones()}}" >
        <input value="{{ $getRecord()->company->resPartner->partner_latitude }}"id="p_latt" hidden >
        <input value="{{ $getRecord()->company->resPartner->partner_longitude}}"id="p_long" hidden>
        {{-- map --}}
        <div wire:ignore>
            <div id="map"></div>
        </div>
         
        <div style=" bottom: 10%; left: 0.7%;">
            <input id="save" value="GetCoordinates" type="button" />
            <div>
                <input  x-model="state" id="resultInput"  hidden/>
                <script>
                    <!-- function deleteSelectedShape() { 
                    -->
                <!-- if (selectedShape) { -->
                <!-- selectedShape.setMap(null); -->
                <!-- } -->
                <!-- } -->
                var jsontData = JSON.parse(document.getElementById('long_lat').value);
                
                var p_long = parseFloat(document.getElementById('p_long').value);
                var p_latt = parseFloat(document.getElementById('p_latt').value);
                function initMap() {
                // draw exitant zones
                var map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: p_latt,
                    lng: p_long
                },
                zoom: 13
                });
                map.data.setStyle({
					strokeColor: "red",
					strokeWeight: 1,
					editable: true,
					draggable: false,
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
            // draw new zone
              
                var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                google.maps.drawing.OverlayType.POLYGON,
                ],
                }
                });

                drawingManager.setMap(map);

                google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event){
                switch (event.type) {
                case google.maps.drawing.OverlayType.POLYGON:
                map.data.add(new google.maps.Data.Feature({
                geometry: new google.maps.Data.Polygon([event.overlay.getPath().getArray()])
                }));
                break;
                }
                });
                <!-- google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape); -->

                google.maps.event.addDomListener(document.getElementById('save'), 'click', function(){
                map.data.toGeoJson(function(obj){
                let data = JSON.stringify(obj);
                data = JSON.parse(data);
                const resultInput = document.getElementById('resultInput');
                
                // Set the value of the input element
                resultInput.value =JSON.stringify(data); 
                resultInput.dispatchEvent(new Event('input'));
                console.log(data.features[0].geometry.coordinates[0]);
             
                let zoneId = (document.location.href).substr((document.location.href).indexOf("?")+1);
                alert(zoneId);

                let myHeaders = new Headers();
                myHeaders.append("Content-Type", "application/json");
                myHeaders.append("Cookie", "session_id=0fcfccc90da3e746054343951d6f31abd888d62e");

                let raw = JSON.stringify(data.features[0].geometry.coordinates);
                <!-- { -->
                <!-- "coordinates": [ -->
                <!-- [ -->
                <!-- 35.8207421260254, -->
                <!-- 34.43644788497519 -->
                <!-- ], -->
                <!-- [ -->
                <!-- 35.822115417041026, -->
                <!-- 34.43078455723506 -->
                <!-- ], -->
                <!-- [ -->
                <!-- 35.836363311328135, -->
                <!-- 34.437438927872165 -->
                <!-- ], -->
                <!-- [ -->
                <!-- 35.8207421260254, -->
                <!-- 34.43644788497519 -->
                <!-- ] -->
                <!-- ] -->
                <!-- } -->


                let requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: raw,
                redirect: 'follow'
                };
                console.log("/api/"+zoneId+"/create-zone-coord");
               
                });
                })
                }
             
                </script>
                
                <script
                    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkNrB5dUdur4Bh91AY2Ig-3ptMDl7Ap7U&libraries=drawing&callback=initMap">
                </script>
            </div>
</x-dynamic-component>
