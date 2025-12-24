@extends('organization.layouts.app')
@section('content')
    <main class="content">
        <div class="container-fluid p-0">
            <div class="row mb-xl-3 mb-2">
                <div class="d-none d-sm-block col-auto">
                    <h3><strong>Add Vacancy</strong></h3>
                </div>
            </div>

            <div class="row">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5 class="card-title mb-0">Job Details</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('organization.add_vacancy') }}" method="POST" id="form_input">
                            @csrf
                            <div class="row">
                                <!-- Job Type Selector -->
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Job Type</label>
                                    <select class="form-select form-control-lg border-2" name="job_type" id="jobTypeSelect">
                                        <option value="" selected disabled>Select Option</option>
                                        <option value="Full Time">Full Time</option>
                                        <option value="Acting">Acting</option>
                                    </select>
                                </div>


                                <!-- Common Fields (always visible) -->
                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Vehicle Type</label>
                                    <select class="form-select form-control-lg border-2" name="veh_type">
                                        <option value="" selected disabled>Select Option</option>
                                        <option value="Car">Car</option>
                                        <option value="Van">Van</option>
                                        <option value="Tempo">Tempo</option>
                                        <option value="Mini Truck">Mini Truck</option>
                                        <option value="Lorry">Lorry</option>
                                        <option value="Trailer">Trailer</option>
                                        <option value="Tanker">Tanker</option>
                                        <option value="Bus">Bus</option>
                                        <option value="Tractor">Tractor</option>
                                        <option value="JCB">JCB</option>
                                        <option value="Pickup">Pickup</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-3">
                                    <label class="form-label fw-bold">Vehicle Name</label>
                                    <input type="text" name="veh_name" class="form-control form-control-lg border-2"
                                        placeholder="Vehicle Name - Maruthi 4 seater">
                                </div>

                                <!-- Full Time Fields -->
                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Minimum Experience</label>
                                    <input type="number" name="min_exp" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Maximum Experience</label>
                                    <input type="number" name="max_exp" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Job Location</label>
                                    <input type="text" name="job_location" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Join Date</label>
                                    <input type="date" name="join_date" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Minimum Salary</label>
                                    <input type="number" name="min_salary" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="form-label fw-bold">Maximum Salary</label>
                                    <input type="number" name="max_salary" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <!-- Acting Fields (hidden by default) -->
                                <input type="hidden" name="start_coordinates" id="start_coordinates">
                                <input type="hidden" name="st_dist" id="st_dist">
                                <input type="hidden" name="end_coordinates" id="end_coordinates">
                                <input type="hidden" name="end_dist" id="end_dist">
                                <input type="hidden" name="dest_coordinates" id="dest_coordinates">
                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Vehicle Number</label>
                                    <input type="text" name="veh_number" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>
                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Alternate Number</label>
                                    <input type="text" name="alternate_number" 
                                        class="form-control form-control-lg border-2" 
                                        placeholder=""
                                        maxlength="10" 
                                        minlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">End Date</label>
                                    <input type="date" name="end_date" class="form-control form-control-lg border-2"
                                        placeholder="">
                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Start Time</label>
                                    <input type="time" name="start_time" class="form-control form-control-lg border-2"
                                        placeholder="">

                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">From Address</label>
                                    {{-- <input type="text" name="from_address"
                                        class="form-control form-control-lg border-2" placeholder=""> --}}

                                    <input type="text" id="from_address" name="from_address"
                                        class="form-control form-control-lg border-2" placeholder="">
                                </div>


                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">To Address</label>
                                    {{-- <input type="text" name="to_address" class="form-control form-control-lg border-2"
                                        placeholder=""> --}}
                                    <input type="text" id="to_address" name="to_address"
                                        class="form-control form-control-lg border-2" placeholder="">
                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Contact Number</label>
                                    <input type="text" name="contact_number"
                                        class="form-control form-control-lg border-2" 
                                        placeholder=""
                                        maxlength="10" 
                                        minlength="10"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Number of Days</label>
                                    <input type="number" name="no_of_days" class="form-control form-control-lg border-2"
                                        value="0" placeholder="">
                                </div>

                                <div class="acting-field mb-3 col-md-3" style="display:none;">
                                    <label class="form-label fw-bold">Driver Type</label>
                                    <select class="form-select form-control-lg border-2" name="d_type">
                                        <option value="" selected disabled>Select Option</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>

                                <!-- Radio buttons (full time only) -->
                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="d-block form-label fw-bold">Accommodation</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="accommodation"
                                            id="accommodation_yes" value="Yes">
                                        <label class="form-check-label fs-5" for="accommodation_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="accommodation"
                                            id="accommodation_no" value="No">
                                        <label class="form-check-label fs-5" for="accommodation_no">No</label>
                                    </div>
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="d-block form-label fw-bold">Food</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="food"
                                            id="food_yes" value="Yes">
                                        <label class="form-check-label fs-5" for="food_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="food"
                                            id="food_no" value="No">
                                        <label class="form-check-label fs-5" for="food_no">No</label>
                                    </div>
                                </div>

                                <div class="full-time-field mb-3 col-md-3">
                                    <label class="d-block form-label fw-bold">Agreement</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="aggrement"
                                            id="aggrement_yes" value="Yes">
                                        <label class="form-check-label fs-5" for="aggrement_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fs-3" type="radio" name="aggrement"
                                            id="aggrement_no" value="No">
                                        <label class="form-check-label fs-5" for="aggrement_no">No</label>
                                    </div>
                                </div>

                                <!-- Description (full time only) -->
                                <div class="full-time-field mb-3 col-md-12">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control form-control-lg border-2" placeholder=""></textarea>
                                </div>

                                <!-- Map (acting only) -->
                                <div class="acting-field mb-3 col-md-12" style="display:none;">
                                    <label class="form-label fw-bold">Map</label>
                                    <div id="map" style="width: 100%; height: 300px;"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mt-2 col-md-2">
                                    <input type="submit" class="btn btn-primary w-100 form_btn" value="Save">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqbFCGZVfe1hYOdsPZt838fx1pc_4tF3I&callback=initMap" async
        defer></script> --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBefgPjpir1KgS5-9A9T3OxvycO8q1FQCA&libraries=places">
    </script>

    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script>
        const form = document.querySelector('#form_input');

        form.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {

                alert('Please use the submit button to save your changes.');
                event.preventDefault(); // Stop the form from submitting
            }
        });


        let map;
        let geocoder;
        let markers = [];
        let nextMarkerLabel = "A"; // Switch between A and B
        let directionsService;
        let directionsRenderer;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                center: {
                    lat: 11.1271,
                    lng: 78.6569
                },
                zoom: 8
            });

            geocoder = new google.maps.Geocoder();
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true
            });
            directionsRenderer.setMap(map);

            // Map click to place marker manually
            map.addListener("click", function(event) {
                placeMarker(event.latLng, nextMarkerLabel);
                geocodeLatLng(event.latLng, nextMarkerLabel);
                nextMarkerLabel = nextMarkerLabel === "A" ? "B" : "A";

                // If both markers are placed, draw route
                if (markers[0] && markers[1]) {
                    drawRoute(markers[0].getPosition(), markers[1].getPosition());
                }
            });

            const tnBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(8.076, 76.6),
                new google.maps.LatLng(13.5, 80.5)
            );

            // FROM Autocomplete
            const fromInput = document.getElementById('from_address');
            const fromAutocomplete = new google.maps.places.Autocomplete(fromInput, {
                componentRestrictions: {
                    country: "in"
                },
                bounds: tnBounds,
                strictBounds: true
            });

            fromAutocomplete.addListener('place_changed', function() {
                const place = fromAutocomplete.getPlace();
                if (!place.geometry) return;

                const location = place.geometry.location;
                $('#start_coordinates').val(location.lat() + ',' + location.lng());
                placeMarker(location, "A");

                // Extract district (sub-administrative area)
                let district = '';

                if (place.address_components) {
                    for (const component of place.address_components) {
                        const types = component.types;

                        if (!district && types.includes('sublocality')) {
                            district = component.long_name;
                        } else if (!district && types.includes('locality')) {
                            district = component.long_name;
                        } else if (!district && types.includes('administrative_area_level_2')) {
                            district = component.long_name;
                        }

                        if (district) break; // Once found, exit early
                    }
                }

                console.log(district);

                $('#st_dist').val(district);

                if (markers[0] && markers[1]) {
                    drawRoute(markers[0].getPosition(), markers[1].getPosition());
                }
            });

            // TO Autocomplete
            const toInput = document.getElementById('to_address');
            const toAutocomplete = new google.maps.places.Autocomplete(toInput, {
                componentRestrictions: {
                    country: "in"
                },
                bounds: tnBounds,
                strictBounds: true
            });

            toAutocomplete.addListener('place_changed', function() {
                const place = toAutocomplete.getPlace();
                if (!place.geometry) return;

                const location = place.geometry.location;
                $('#end_coordinates').val(location.lat() + ',' + location.lng());
                $('#dest_coordinates').val(location.lat() + ',' + location.lng());
                placeMarker(location, "B");

                // console.log(place);

                let end_district = '';
                if (place.address_components) {
                    for (const component of place.address_components) {
                        const types = component.types;

                        if (!end_district && types.includes('sublocality')) {
                            end_district = component.long_name;
                        } else if (!end_district && types.includes('locality')) {
                            end_district = component.long_name;
                        } else if (!end_district && types.includes('administrative_area_level_2')) {
                            end_district = component.long_name;
                        }

                        if (end_district) break; // Once found, exit early
                    }
                }


                console.log(end_district);
                $('#end_dist').val(end_district);

                if (markers[0] && markers[1]) {
                    drawRoute(markers[0].getPosition(), markers[1].getPosition());
                }
            });
        }

        // Place marker with given label (A or B)
        function placeMarker(location, label) {
            const index = label === "A" ? 0 : 1;

            // Clear existing marker
            if (markers[index]) {
                markers[index].setMap(null);
            }

            // Create new marker
            markers[index] = new google.maps.Marker({
                position: location,
                map: map,
                label: label,
                draggable: true
            });

            map.setCenter(location);

            const coords = location.lat() + ',' + location.lng();
            if (label === "A") {
                $('#start_coordinates').val(coords);
            } else {
                $('#end_coordinates').val(coords);
                $('#dest_coordinates').val(coords);
            }

            // On drag end, update address & coordinates + redraw route
            markers[index].addListener("dragend", function() {
                const newPos = markers[index].getPosition();
                geocodeLatLng(newPos, label);
                const newCoords = newPos.lat() + ',' + newPos.lng();

                if (label === "A") {
                    $('#start_coordinates').val(newCoords);
                } else {
                    $('#end_coordinates').val(newCoords);
                    $('#dest_coordinates').val(newCoords);
                }

                if (markers[0] && markers[1]) {
                    drawRoute(markers[0].getPosition(), markers[1].getPosition());
                }
            });
        }

        // Convert LatLng to Address and fill input
        // function geocodeLatLng(latlng, label) {
        //     geocoder.geocode({
        //         location: latlng
        //     }, function(results, status) {
        //         if (status === "OK" && results[0]) {
        //             const address = results[0].formatted_address;
        //             if (label === "A") {
        //                 $('#from_address').val(address);
        //             } else {
        //                 $('#to_address').val(address);
        //             }
        //         }
        //     });
        // }

        function geocodeLatLng(latlng, label) {
            geocoder.geocode({
                location: latlng
            }, function(results, status) {
                if (status === "OK" && results[0]) {
                    const address = results[0].formatted_address;

                    // Extract district from address components
                    let district = '';
                    const components = results[0].address_components;

                    for (const component of components) {
                        const types = component.types;

                        if (!district && types.includes('sublocality')) {
                            district = component.long_name;
                        } else if (!district && types.includes('locality')) {
                            district = component.long_name;
                        } else if (!district && types.includes('administrative_area_level_2')) {
                            district = component.long_name;
                        }

                        if (district) break;
                    }

                    if (label === "A") {
                        $('#from_address').val(address);
                        $('#st_dist').val(district);
                        $('#start_coordinates').val(latlng.lat() + ',' + latlng.lng());
                    } else {
                        $('#to_address').val(address);
                        $('#end_dist').val(district);
                        $('#end_coordinates').val(latlng.lat() + ',' + latlng.lng());
                        $('#dest_coordinates').val(latlng.lat() + ',' + latlng.lng());
                    }

                    console.log(`Marker ${label} district:`, district);

                    // Optionally, redraw route if both markers placed
                    if (markers[0] && markers[1]) {
                        drawRoute(markers[0].getPosition(), markers[1].getPosition());
                    }
                } else {
                    console.error('Geocoder failed due to:', status);
                }
            });
        }


        // Draw shortest route between two points
        function drawRoute(origin, destination) {
            const request = {
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING
            };

            directionsService.route(request, function(result, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(result);
                } else {
                    alert("Could not display route due to: " + status);
                }
            });
        }

        // Show/hide map section based on job type
        $(document).ready(function() {
            let mapInitialized = false;

            $('#jobTypeSelect').change(function() {
                const selectedJobType = $(this).val();

                if (selectedJobType === 'Acting') {
                    $('.full-time-field').hide();
                    $('.acting-field').show();

                    if (!mapInitialized) {
                        initMap();
                        mapInitialized = true;
                    }
                } else {
                    $('.full-time-field').show();
                    $('.acting-field').hide();
                }
            });
        });
    </script>
@endsection
