function getRandomMarkerColor() {
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += Math.floor(Math.random() * 10);
    }
    return color;
}


function drawDirections(map, workDetails, color = '#FF0000') {
    // const directionsService = new google.maps.DirectionsService();
    //
    //
    // const directionsRenderer = new google.maps.DirectionsRenderer({
    //     map: map,
    //     polylineOptions: {
    //         strokeColor: color, // Set path color (e.g., red)
    //         strokeOpacity: 0.7, // Set path opacity
    //         strokeWeight: 6 // Set path thickness
    //     }
    // });
    // const start = {lat: workDetails.start_lat, lng: workDetails.start_lng};
    //
    // let end = {lat: workDetails.end_lat, lng: workDetails.end_lng};
    //
    // if (!workDetails.end_at) {
    //     if (workDetails.locations.length) {
    //         const {lat, lng} = workDetails.locations[workDetails.locations.length - 1];
    //         end = {lat, lng};
    //     }
    // }
    //
    // const getWayPoints = function () {
    //     return workDetails.locations.map(({lat, lng}) => {
    //         return {location: {lat, lng}, stopover: true};
    //     });
    // }
    //
    //
    // const request = {
    //     origin: start,
    //     destination: end,
    //     waypoints: getWayPoints(),
    //     optimizeWaypoints: false,
    //     travelMode: google.maps.TravelMode.DRIVING
    // };

    // directionsService.route(request, (result, status) => {
    //     if (status === google.maps.DirectionsStatus.OK) {
    //         directionsRenderer.setDirections(result);
    //     } else {

    const merge = [
        {lat: workDetails.start_lat, lng: workDetails.start_lng},
    ];
    if (workDetails.end_at) {
        merge.push({lat: workDetails.end_lat, lng: workDetails.end_lng},);
    }

    const path = Array.from([...workDetails.locations, ...merge,])
        .filter((value, index, self) => {
            return index === self.findIndex((t) => t.lat === value.lat && t.lng === value.lng);
        }).map(({lat, lng}) => ({lat, lng}));

    const pathPolyline = new google.maps.Polyline({
        path,
        geodesic: true,
        strokeColor: color,
        strokeOpacity: 1.0,
        strokeWeight: 4
    });
    pathPolyline.setMap(map);
    // console.error('Directions request failed due to ' + status);
    // }
    // });
}

function calculateCenter(coords) {
    let latSum = 0;
    let lngSum = 0;
    coords.forEach(coord => {
        latSum += coord.lat;
        lngSum += coord.lng;
    });
    return {
        lat: latSum / coords.length,
        lng: lngSum / coords.length
    };
}


function mostVisitedMarker(map, work, iconConfig =undefined) {
    work.most_visited.forEach(function (item) {
        new google.maps.Marker({
            position: {lat: item.lat, lng: item.lng},
            map: map,
            title: `${parseInt(item.duration / 60)} minutes`,
            label: "Most Visited",
            icon: iconConfig
        });
    })
}

function initMarker(map, work, iconConfig = undefined) {
    new google.maps.Marker({
        position: {
            lat: work.start_lat,
            lng: work.start_lng
        },
        map: map,
        title: "Start Point",
        label: "Start",
        icon: iconConfig
    });


    if (!work.end_lat) {
        return;
    }


    new google.maps.Marker({
        position: {
            lat: work.end_lat,
            lng: work.end_lng
        },
        map: map,
        title: "End Point",
        label: "End",
        icon: iconConfig
    });
}


function setLatLngBounds(map, locations) {
    const bounds = new google.maps.LatLngBounds();
    locations.forEach(crd => bounds.extend({lng: crd.lng, lat: crd.lat}));
    map.fitBounds(bounds);
}
