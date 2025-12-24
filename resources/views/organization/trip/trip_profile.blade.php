@extends('organization.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Trip Profile</strong></h3>
                </div>
            </div>

            <div class="row">
                <div class="card rounded p-0">
                    <div class="card-body pb-0">
                        <div class="row">
                            <input type="hidden" name="start_coordinates" id="start_coordinates">
                            <input type="hidden" name="end_coordinates" id="end_coordinates">
                            <input type="hidden" name="dest_coordinates" id="dest_coordinates">

                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Driver Name</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->hiredApplication?->driver?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Start Location</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->st_loc }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Destination</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->st_dest }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Start Date</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ date('d/m/Y', strtotime($trip->st_date)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">End Date</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ date('d/m/Y', strtotime($trip->end_date)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Start Time</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->st_time }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">No of days</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->no_days }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Salary (Per Day)</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->hiredApplication->salary_perday ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Food</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->hiredApplication->food ?? 'N/A'  }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Vehicle Type</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->veh_type }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Contact Number</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->con_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Alternate Number</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->alter_number }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="">
                                    <h5 class="fs-6 text-muted fw-bold mb-0">Status</h5>
                                    <p class="fs-6 text-dark mb-0 mt-2">{{ $trip->status }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card border p-0">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Trip Route</h5>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>

 

      <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBefgPjpir1KgS5-9A9T3OxvycO8q1FQCA&libraries=places&callback=initMap" async defer></script>

  <script>
    function parseCoordinateString(coordString) {
        if (!coordString) return null;
        const parts = coordString.split(',');
        if (parts.length !== 2) return null;
        return {
            lat: parseFloat(parts[0].trim()),
            lng: parseFloat(parts[1].trim())
        };
    }

    function initMap() {
        // Keep startCoords and destCoords as-is
        const startCoords = {
            lat: {{ (float)$trip->st_cord }},
            lng: {{ (float)$trip->end_cord }}
        };

        const destCoords = parseCoordinateString("{{ $trip->dest_cord }}");
        const currentCoords = parseCoordinateString("{{ $trip->hiredApplication->crnt_loc ?? '' }}");

        // ✅ Validate only start and dest
        if (!startCoords.lat || !startCoords.lng || !destCoords) {
            console.error('Missing required start/destination coordinates');
            document.getElementById('map').innerHTML = '<div class="p-3 text-danger">Map cannot be displayed - missing start or destination location</div>';
            return;
        }

        // ✅ If current location is available, center on it. Otherwise, center on start.
        const centerCoords = currentCoords || startCoords;

        const map = new google.maps.Map(document.getElementById("map"), {
            center: centerCoords,
            zoom: 14
        });

        const startMarker = new google.maps.Marker({
            position: startCoords,
            map: map,
            title: "Start Location",
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
                scaledSize: new google.maps.Size(50, 50)
            }
        });

        const destMarker = new google.maps.Marker({
            position: destCoords,
            map: map,
            title: "Destination",
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(50, 50)
            }
        });

        let currentMarker = null;
        if (currentCoords) {
            currentMarker = new google.maps.Marker({
                position: currentCoords,
                map: map,
                title: "Current Location",
                icon: {
                    url: 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png',
                    scaledSize: new google.maps.Size(60, 60)
                },
                animation: google.maps.Animation.BOUNCE
            });

            setTimeout(() => {
                currentMarker.setAnimation(null);
            }, 2000);
        }

        const directionsService = new google.maps.DirectionsService();
        const directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: '#4285F4',
                strokeWeight: 5,
                strokeOpacity: 0.8
            }
        });
        directionsRenderer.setMap(map);

        const request = {
            origin: startCoords,
            destination: destCoords,
            travelMode: google.maps.TravelMode.DRIVING
        };

        // ✅ Add currentCoords as waypoint only if available
        if (currentCoords) {
            request.waypoints = [{ location: currentCoords, stopover: true }];
        }

        directionsService.route(request, function(result, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(result);
            } else {
                console.error('Route drawing failed:', status);
                const path = currentCoords
                    ? [startCoords, currentCoords, destCoords]
                    : [startCoords, destCoords];

                const fallbackRoute = new google.maps.Polyline({
                    path: path,
                    geodesic: true,
                    strokeColor: '#4285F4',
                    strokeOpacity: 0.8,
                    strokeWeight: 5
                });
                fallbackRoute.setMap(map);
            }
        });

        // Fit map bounds
        const bounds = new google.maps.LatLngBounds();
        bounds.extend(startMarker.getPosition());
        bounds.extend(destMarker.getPosition());
        if (currentMarker) bounds.extend(currentMarker.getPosition());
        map.fitBounds(bounds);
    }
</script>

@endsection