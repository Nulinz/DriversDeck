@extends('admin.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-2 mb-xl-3">
                <div class="col-auto d-none d-sm-block">
                    <h3><strong>Trip Profile</strong></h3>
                </div>
            </div>

            <div class="row">
                <div class="card p-0 rounded">
                    <div class="card-body pb-0">
                        <div class="row">
                            <input type="hidden" id="start_coords" value="{{ $trip->st_cord ?? '' }}">
                            <input type="hidden" id="end_coords" value="{{ $trip->end_cord ?? '' }}">
                            <input type="hidden" id="current_coords" value="{{ $trip->hiredApplication->crnt_loc ?? '' }}">
                            <input type="hidden" id="dest_coords" value="{{ $trip->dest_cord ?? '' }}">

                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Driver Name</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">
                                        {{ $trip->hiredApplication?->driver?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Client Name</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ $trip->corporate?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Start Location</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ $trip->st_loc }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Current Location</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">
                                        @if ($trip->hiredApplication?->crnt_loc)
                                            {{ $trip->hiredApplication->crnt_loc }}
                                        @else
                                            Not available
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Destination</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ $trip->st_dest }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Start Time</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ $trip->st_time }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">Start Date</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ date('d/m/Y', strtotime($trip->st_date)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex align-items-center justify-content-start">
                                    <h5 class="fs-6 text-muted mb-0 fw-bold">End Date</h5>
                                    <p class="ms-5 fs-6 text-dark mb-0">{{ date('d/m/Y', strtotime($trip->end_date)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="card p-0 border">
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
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBefgPjpir1KgS5-9A9T3OxvycO8q1FQCA&libraries=places&callback=initMap"
        async defer></script>



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
            // Safely get coordinates with proper null checks
            const startCoords = {
                lat: parseFloat(document.getElementById('start_coords').value) || 0,
                lng: parseFloat(document.getElementById('end_coords').value) || 0
            };

            const destCoords = parseCoordinateString(document.getElementById('dest_coords').value);
            const currentCoords = parseCoordinateString(document.getElementById('current_coords').value);

            // Validate coordinates
            if (isNaN(startCoords.lat) || isNaN(startCoords.lng) || !destCoords) {
                document.getElementById('map').innerHTML =
                    '<div class="p-3 text-danger">Map cannot be displayed - missing location data</div>';
                return;
            }

            // Initialize map centered on current location or midpoint
            const centerCoords = currentCoords || {
                lat: (startCoords.lat + destCoords.lat) / 2,
                lng: (startCoords.lng + destCoords.lng) / 2
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: centerCoords,
                zoom: 14
            });

            // Create markers
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

            // Only add current marker if coordinates exist
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
                    if (currentMarker) currentMarker.setAnimation(null);
                }, 2000);
            }

            // Draw route
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

            const waypoints = [];
            if (currentCoords) {
                waypoints.push({
                    location: currentCoords,
                    stopover: true
                });
            }

            const request = {
                origin: startCoords,
                destination: destCoords,
                waypoints: waypoints,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsRenderer.setDirections(result);
                } else {
                    console.error('Route drawing failed:', status);
                    // Fallback to simple polylines
                    const path = [startCoords];
                    if (currentCoords) path.push(currentCoords);
                    path.push(destCoords);

                    new google.maps.Polyline({
                        path: path,
                        geodesic: true,
                        strokeColor: '#4285F4',
                        strokeOpacity: 0.8,
                        strokeWeight: 5,
                        map: map
                    });
                }
            });

            // Fit map to show all markers
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(startMarker.getPosition());
            bounds.extend(destMarker.getPosition());
            if (currentMarker) bounds.extend(currentMarker.getPosition());
            map.fitBounds(bounds);
        }
    </script>
@endsection
