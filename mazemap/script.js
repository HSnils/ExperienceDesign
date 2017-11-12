var mazeMarker;
var result;

var map = new Mazemap.Map({
  // container id specified in the HTML
  container: 'map',

  campuses: 55,

  // initial position in lngLat format
  // 60.789852, 10.682203
  center: {lng: 10.682203, lat: 60.789852},

  // initial zoom
  zoom: 17,

  zLevel: 1
});

var lnglatPos = {lng: 10.682424100019745, lat: 60.789850652563956};

map.on('load', function() {
  // Get url parameters and highlight room
  var urlParams = new URLSearchParams(window.location.search);
  var urlPath = window.location.pathname.split("/").pop();

  // Get room id from URL and highlight
  if (urlParams.get('id')) {
    var query = urlParams.get('id');
    getRoom(query);
  }

  // Initialize a Highlighter for POIs
  // Storing the object on the map just makes it easy to access for other things
  map.highlighter = new Mazemap.Highlighter(map, {
    showOutline: false,
    showFill: true,
    fillColor: Mazemap.Util.Colors.MazeColors.MazeGreen
  });

  // Location dot
  var blueDot = new Mazemap.BlueDot({
    zLevel: 1,
    accuracyCircle: true,
  }).setLngLat(lnglatPos).setAccuracy(10);

  // Get user location
  function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.watchPosition(getPos);
    } else {
      console.log('Geolocation is not supported by this browser.');
    }
  }

  function getPos(position) {
    lnglatPos = {lng: position.coords.longitude, lat: position.coords.latitude};

    blueDot.setLngLat(lnglatPos, {animate: true, duration: 300});
    blueDot.addTo(map);

    // Get available room list and populate roomDistance
    if (urlPath == 'findroom.php') {
      var deferred = $.Deferred();
      var roomListCnt = $('tbody tr').length;

      for (i = 0; i < roomListCnt; i++) {
        var thisDistance;
        var roomId = $('tbody tr .roomId')[i];
        roomId = $(roomId).text();
        var cnt = 0;

        searchRoom.search(roomId).then( response => {
          var poiId = response.results.features[0].properties.poiId;

          Mazemap.Data.getPoi(poiId).then( poi => {
            var roomLngLat = Mazemap.Util.getPoiLngLat(poi);
            var thisDistance = posOverlap(roomLngLat, lnglatPos);
            thisDistanceData = thisDistance.toFixed(4);
            thisDistance = thisDistance.toFixed(0);
            var roomDistance = $('tbody tr .roomDistance')[cnt];
            var roomContainer = $('tbody tr')[cnt];

            roomtempId = $('tbody tr .roomId')[cnt];
            roomtempId = $(roomtempId).text();

            $(roomDistance).html(thisDistance + 'm');
            $(roomContainer).attr('distance', thisDistanceData);

            cnt++;
            if (cnt == i) {
              deferred.resolve();
            }
          });
        });
      }
      deferred.done(function() {
        jQuery.fn.sortDivs = function sortDivs() {
          $("tr", this[0]).sort(dec_sort).appendTo(this[0]);
          function dec_sort(a, b){ return ($(b).attr("distance")) > ($(a).attr("distance")) ? -1 : ($(b).attr("distance")) < ($(a).attr("distance")) ? 1 : -1; }
        }
        $('tbody').sortDivs();
      });
    }
  }

  getLocation();
});

function clearPoiMarker(poi) {
  if (mazeMarker) {
    mazeMarker.remove();
  }

  map.highlighter.clear();
};


function posOverlap(a,b) {
  var x = (b.lat - a.lat);
  var y = (b.lng - a.lng);


  var R = 6371; // metres

  var aX = Math.sin(x/2) * Math.sin(x/2) +
          Math.cos(a.lat) * Math.cos(b.lat) *
          Math.sin(y/2) * Math.sin(y/2);
  var c = 2 * Math.atan2(Math.sqrt(aX), Math.sqrt(1-aX));

  var d = R * c;
  d = d*10;

  var dist = d;
  return dist;
}


function placePoiMarker(poi) {
  // Remove marker if exists
  clearPoiMarker();

  // Get a center point for the POI, because the data can return a polygon instead of just a point sometimes
  var lngLat = Mazemap.Util.getPoiLngLat(poi);

  // If we have a polygon, use the default 'highlight' function to draw a marked outline around the POI.
  if (poi.geometry.type === "Polygon") {
    map.highlighter.highlight(poi);
  }
  map.flyTo({center: lngLat, zoom: 19, speed: 0.5});

  setTimeout(function() {
    map.setZLevel(poi.properties.zLevel);
  }, 150);
}

// Highlight room on map on button click
var searchRoom = new Mazemap.Search.SearchController({
    campusid: 55,

    rows: 1,

    withpois: true,
    withbuilding: false,
    withtype: false,
    withcampus: false,

    resultsFormat: 'geojson'
});

function getRoom(query) {
  // Perform a search query using the Search object
  searchRoom.search(query).then( response => {
    var poiId = response.results.features[0].properties.poiId;

    Mazemap.Data.getPoi(poiId).then( poi => {
      placePoiMarker(poi);
    });
  });
}

// Add zoom and rotation controls to the map.
//map.addControl(new Mazemap.mapboxgl.NavigationControl());
