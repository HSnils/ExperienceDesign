var mazeMarker;

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
  }).setLngLat(lnglatPos).setAccuracy(15);

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
    setTimeout(function() {
      blueDot.addTo(map);
    }, 300);
  }

  getLocation();

  map.on('click', function(e) {
    onMapClick(e);
    // blueDot.setLngLat(e.lngLat);
  });
});

// define a global


function onMapClick(e) {
  // Clear existing, if any
  clearPoiMarker();

  var lngLat = e.lngLat;
  var zLevel = map.zLevel;

  // Fetching via Data API
  Mazemap.Data.getPoiAt(lngLat, zLevel).then(poi => {
    placePoiMarker(poi);
  }).catch(function() {return false;});


  posOverlap(lngLat, lnglatPos);
}

function clearPoiMarker(poi) {
  if (mazeMarker) {
    mazeMarker.remove();
  }

  map.highlighter.clear();
};

var result;
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

  if (d > 20) {
    console.log("Mer enn 20m fra rom");
  }
}


function placePoiMarker(poi) {
  // Remove marker if exists

  console.log(poi);
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

/*
$.get("https://api.mazemap.com/api/pois/?campusid=55&srid=4326").done(function(data) {
  var len = data.pois.length;
  for (i = 0; i < len; i++) {
    var poiId = data.pois[i].poiId;
    Mazemap.Data.getPoi(poiId).then( poi => {
      // Room id
      console.log(poi.properties.id);

      // Room building + name (ex. 502-K114)
      console.log(poi.properties.identifier);
    });
  }
});
*/


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
