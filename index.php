<!DOCTYPE html>
<html>
<head>
	
	<title>Quick Start - Leaflet</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
	
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />
	<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>
        <script src="Permalink.js"></script>
        
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script>
            
            var api = 'http://api-adresse.data.gouv.fr/reverse/?';
            var marker;
            var mymap;
            
            function init() {
                
                mymap = L.map('mapid').setView([47.64524, -2.75355], 10);
                mymap.addControl(new L.Control.Permalink({text: 'Permalink'}));
                mymap.on('moveend', function (e) {
                    console.log(this.getCenter());
                    $("#field-lat").val(this.getCenter().lat);
                    $("#field-lon").val(this.getCenter().lng);
                    var lat = $("#field-lat").val();
                    var lon = $("#field-lon").val();
                    var reqDatas =  jQuery.param({"lat":lat, "lon":lon});
                    doReverseGeocode(reqDatas);
                    
                });
                
                L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                        maxZoom: 18,
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        id: 'mapbox.streets'
                }).addTo(mymap);

                $("#btn_reverse").click(function(event) {
                    var lat = $("#field-lat").val();
                    var lon = $("#field-lon").val();
                    var reqDatas =  jQuery.param({"lat":lat, "lon":lon});
                    doReverseGeocode(reqDatas);
                });             
            }
            
            function doReverseGeocode(reqDatas) {
                 $.ajax({
                    url : api,
                    data: decodeURIComponent(reqDatas),
                    dataType : 'json',
                    context: {'context':'context'}
                }).done(function(results) {
                    
                    console.log(results);
                    
                    if(results.features.length > 0) {
                        console.log(results.features[0]);
                        var result = results.features[0];
                        var context = this;
                        var lon = result.geometry.coordinates[0];
                        var lat = result.geometry.coordinates[1];
                                           
                        marker = L.marker([lat, lon]).addTo(mymap).bindPopup(
                                'Adresse : ' + result.properties.label + '<br />' +
                                'Alias : ' + result.properties.alias + '<br />' +
                                'Distance : ' + result.properties.distance + '<br />' +
                                'Type : ' + result.properties.type + '<br />'
                        ).togglePopup();
                
                    }else {
                       marker = L.marker(mymap.getCenter()).addTo(mymap).bindPopup(
                            'Aucun résultat :(('
                        ).togglePopup(); 
                    }
 
                    
                });
            }
        </script>        
        
</head>
<body onload="init();">

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Démo LeafLet / api-adresse.data.gouv.fr</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form class="navbar-form navbar-right">
            <div class="form-group">
                <!--47.64524,-2.75355-->
                <input type="text" id="field-lon" placeholder="longitude" class="form-control" value="-2.75355">
            </div>
            <div class="form-group">
              <input type="text" id="field-lat" placeholder="latitude" class="form-control" value="47.64524">
            </div>
            <button type="button" id="btn_reverse" class="btn btn-success">Reverse geocode</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

<div id="mapid" style="margin-top:140px; width: 100%; height: 600px;"></div>
</body>
</html>
