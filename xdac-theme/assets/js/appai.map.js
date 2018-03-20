// When the window has finished loading create our google map below

(function($) {
google.maps.event.addDomListener(window, 'load', init);
function init() {

    var mapElement = document.getElementById('map');
    var mapData = $('#map').data('map-options');

    console.log(map);

      // Basic options for a simple Google Map
      // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
      var mapOptions = {
          // How zoomed in you want the map to start at (always required)
          zoom: parseInt(mapData.zoomLevel),
          scrollwheel: false,

          // The latitude and longitude to center the map (always required)
          center: new google.maps.LatLng(mapData.latitude, mapData.longitude), // New York


          // How you would like to style the map.
          // This is where you would paste any style found on Snazzy Maps.
          styles:[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]
      };


    // Get the HTML DOM element that will contain your map
      // We are using a div with id="map" seen below in the <body>

      // Create the Google Map using our element and options defined above
      var map = new google.maps.Map(mapElement, mapOptions);


      // Let's also add a marker while we're at it
      var marker = new google.maps.Marker({
          position: map.getCenter(),
          icon: mapData.map_icon_mark,
          map: map,
      });

}
})(jQuery)
