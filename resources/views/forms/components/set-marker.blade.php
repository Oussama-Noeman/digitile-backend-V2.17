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
    height:400px;
  }
  /* Optional: Makes the sample page fill the window. */
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
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
    <div x-data="{ state: $wire.entangle('{{ $getStatePath('latt_long') }}') }">
        <input value="{{ $getRecord()->partner_latitude }}"id="p_latt" hidden >
         <input value="{{ $getRecord()->partner_longitude}}"id="p_long" hidden>
         <input  id="placeSearch" placeholder="Search for a place">
     
  
    <div wire:ignore>
        <div id="map"></div>
    </div>
    
    <div style=" bottom: 10%; left: 0.7%;">
      <input id="save" value="Get Coordinates" type="button" >
    </div>
    <input  x-model="state" id="resultInput"  hidden/>

    <script>
      let map; // Define map globally
      let marker; // Define marker globally
      var p_long = parseFloat(document.getElementById('p_long').value);
      var p_latt = parseFloat(document.getElementById('p_latt').value);
    
      function initMap() {
        let centerLocation = { lat: 33.880182488629316, lng: 35.517426355267865 };

        // Check if p_latt and p_long are available
        if (p_long && p_latt) {
        centerLocation = { lat: p_latt, lng: p_long };
        } else {
       // Use Geolocation API to get the user's location
       if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            centerLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };

            map.setCenter(centerLocation);
        }, function (error) {
            console.log(error.message);
        });
    }
}      console.log("centerLocation")
       console.log(centerLocation)
        map = new google.maps.Map(document.getElementById('map'), {
          center:centerLocation,
          zoom: 13
        });
        if(p_long){
            placeMarker({lat:p_latt,lng:p_long});
        }
        // Add a click event listener to the map
        map.addListener('click', function(event) {
          placeMarker(event.latLng);
        });
       
        function placeMarker(location) {
          if (marker) {
            marker.setMap(null); // Remove existing marker if one exists
          }
    
          marker = new google.maps.Marker({
            position: location,
            map: map
          });
        }
    
        google.maps.event.addDomListener(document.getElementById('save'), 'click', function () {
          if (marker) {
            const latLng = marker.getPosition();
            const resultInput = document.getElementById('resultInput');
                
                // Set the value of the input element
                resultInput.value =JSON.stringify(latLng); 
                resultInput.dispatchEvent(new Event('input'));
            alert(`Latitude: ${latLng.lat()}, Longitude: ${latLng.lng()}`);
          } else {
            alert('No marker placed. Click on the map to place a marker first.');
          }
        });
        const input = document.getElementById('placeSearch');
        const autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
           // Handle the Autocomplete place selection
    autocomplete.addListener('place_changed', function() {
      const place = autocomplete.getPlace();
      if (!place.geometry) {
        alert('Place not found');
        return;
      }

      // Center the map on the selected place
      map.setCenter(place.geometry.location);

      // Place a marker at the selected place
      placeMarker(place.geometry.location);
    });
      }
    </script>
   <script
   src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBkNrB5dUdur4Bh91AY2Ig-3ptMDl7Ap7U&libraries=drawing,places&callback=initMap">
</script>

    </div>
</x-dynamic-component>
