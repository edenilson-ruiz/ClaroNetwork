@extends('layouts.app')

@section('styles')
    <style>
        /*  <span class="metadata-marker" style="display: none;" data-region_tag="css"></span>       Set the size of the div element that contains the map */
        #map {
            height: 600px;
            /* The height is 400 pixels */
            width: 100%;
            /* The width is the width of the web page */
        }

         #floating-panel {
            position: absolute;
            top: 10px;
            left: 25%;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
            text-align: center;
            font-family: 'Roboto','sans-serif';
            line-height: 30px;
            padding-left: 10px;
          }
          #floating-panel {
            position: absolute;
            top: 5px;
            left: 50%;
            margin-left: -180px;
            width: 350px;
            z-index: 5;
            background-color: #fff;
            padding: 5px;
            border: 1px solid #999;
          }
          #latlng {
            width: 225px;
          }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Monitoreando la red |
                  @if ($tipoRed == "2G")
                    <img src="{{ url('img/icon_2g.jpeg')}}"/>
                  @elseif ($tipoRed == "3G")
                    <img src="{{ url('img/icon_3g.jpeg')}}"/>
                  @elseif ($tipoRed == "LTE")
                    <img src="{{ url('img/icon_lte.jpeg')}}"/>
                  @elseif ($tipoRed == "WiFi")
                    <img src="{{ url('img/icon_wifi.jpeg')}}"/>
                  @endif
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="map"></div>
                   <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Departamento</th>
                          <th scope="col">Municipio</th>
                          <th scope="col">Tipo de Red</th>
                          <th scope="col">Observaciones</th>
                          <th scope="col">Prom. Barras</th>
                          <th scope="col">Prom. Señal (dBM)</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($stats as $stat)
                        <tr>
                          <th>{{ $stat->departamento }}</th>
                          <th>{{ $stat->municipio }}</th>
                          <th>{{ $stat->mobile_data_network_type }}</th>
                          <th>{{ $stat->count }}</th>
                          <th>{{ $stat->avg_bars }}</th>
                          <th>{{ $stat->avg_signal }}</th>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    <div>
                        <h5>Operaciones Comerciales | Claro El Salvador</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
  <script>
          var map;
          var InforObj = [];
          var centerCords = {
              lat: 13.709361,
              lng: -89.250306
          };
          var markersOnMap = @json($data);

          window.onload = function () {
              initMap();
          };

          function addMarkerInfo() {
              for (var i = 0; i < markersOnMap.length; i++) {

                  iconShape = "";
                  signalLevel = "";

                  if (markersOnMap[i].cell_signal_strength_dbm >= -75 ) {
                      iconShape = "/marker-green.png";
                      signalLevel = "Excelente";
                  } else if (markersOnMap[i].cell_signal_strength_dbm >= -90) {
                      iconShape = "/marker-blue.png";
                      signalLevel = "Buena";
                  } else if (markersOnMap[i].cell_signal_strength_dbm >= -100) {
                      iconShape = "/marker-orange.png";
                      signalLevel = "Regular";
                  }
                  else {
                      iconShape = "/marker-pink.png";
                      signalLevel = "Mala";
                  }

                  var contentString = '<div id="content">' +
                          '<table width="auto">' +
                              '<tr>' +
                              '<th>Id</th>' +
                              '<td>'+ i +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Operador</th>' +
                              '<td>'+ markersOnMap[i].network_operator +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Cell Asu Level</th>' +
                              '<td>'+ markersOnMap[i].cell_asu_level +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Nivel de Señal</th>' +
                              '<td>'+ markersOnMap[i].cell_signal_strength +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Nivel de Señal DBM</th>' +
                              '<td>'+ markersOnMap[i].cell_signal_strength_dbm +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Nivel de Señal Desc</th>' +
                              '<td>'+ signalLevel +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Mobile Data Network Type</th>' +
                              '<td>'+ markersOnMap[i].mobile_data_network_type +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Network Type</th>' +
                              '<td>'+ markersOnMap[i].network_type +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Velocidad de bajada</th>' +
                              '<td>'+ markersOnMap[i].download_speed +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Velocidad de subida</th>' +
                              '<td>'+ markersOnMap[i].upload_speed +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Latitud</th>' +
                              '<td>'+ markersOnMap[i].device_latitude +'</td>' +
                            '</tr>' +
                            '<tr>' +
                              '<th>Longitud</th>' +
                              '<td>'+ markersOnMap[i].device_longitude +'</td>' +
                            '</tr>' +
                          '</table></div>';

                  const marker = new google.maps.Marker({
                      //position: markersOnMap[i].LatLng[0],
                      position: new google.maps.LatLng(markersOnMap[i].device_latitude,markersOnMap[i].device_longitude),
                      /*icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 10
                      },*/
                      icon : '{{ url('img/') }}'+iconShape,
                      map: map
                  });

                  const infowindow = new google.maps.InfoWindow({
                      content: contentString,
                      maxWidth: 400
                  });

                  marker.addListener('click', function () {
                    closeOtherInfo();
                    infowindow.open(marker.get('map'), marker);                    
                    InforObj[0] = infowindow;
                  });
                  marker.addListener('mouseover', function () {
                       closeOtherInfo();
                       infowindow.open(marker.get('map'), marker);
                       InforObj[0] = infowindow;
                  });
                  marker.addListener('mouseout', function () {
                       closeOtherInfo();
                       infowindow.close();
                       InforObj[0] = infowindow;
                  });
              }
          }

          function closeOtherInfo() {
              if (InforObj.length > 0) {
                  /* detach the info-window from the marker ... undocumented in the API docs */
                  InforObj[0].set("marker", null);
                  /* and close it */
                  InforObj[0].close();
                  /* blank the array */
                  InforObj.length = 0;
              }
          }

          function initMap() {
              map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 8,
                  center: centerCords
              });
              addMarkerInfo();
              var geocoder = new google.maps.Geocoder;
              var infowindow = new google.maps.InfoWindow;            
          }

          function geocodeLatLng(geocoder, map, infowindow) {
            var input = document.getElementById('latlng').value;
            var latlngStr = input.split(',', 2);
            var latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
            geocoder.geocode({'location': latlng}, function(results, status) {
              if (status === 'OK') {
                if (results[0]) {
                  map.setZoom(11);
                  var marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                  });
                  infowindow.setContent("<table><tr><td>"+results[0].address_components[3].long_name+", </td><td>"+results[0].address_components[2].long_name+"</td></tr></table>");
                  infowindow.open(map, marker);
                } else {
                  window.alert('No results found');
                }
              } else {
                window.alert('Geocoder failed due to: ' + status);
              }
            });
          }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCEACLgCogxJS3XzgQ4JqiOGIyqzKJ2Ybw"></script>
@endsection
