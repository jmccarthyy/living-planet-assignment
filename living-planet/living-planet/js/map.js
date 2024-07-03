// This code contains the application's code for map interaction related data:
$(function() {
     // Attach a submit event handler to the city search form:
    $('#city-search-form').on('submit', function(e) {
        e.preventDefault();

        // Get the city input value and trim any whitespace
        let city = $('#city-input').val().trim();
        // If the city input box is not empty:
        if (city !== '') {
            // Hide the city info text and show a loading spinner:
            $('.city-info-text').hide();
            $('.city-info-content').show().html('<i class="fas fa-spinner fa-spin fa-3x"></i>');

            // Make AJAX request to fetch the weather data for the user chosen city:
            $.ajax({
                url: './functions/weather.php',
                method: 'GET',
                data: { city: city },
                success: function(response) {
                    let cityData = response;

                    // If the searched city is not found, display error message:
                    if(cityData.cod === "404") {
                        $('.city-info-content').html('<h2 class="info-city">City not found</h2>');
                        return;
                    }

                    // Declare formattedCityData variable as formatted data and display it:
                    let formattedCityData = $.formatCityData(cityData);
                    $('.city-info-content').html(formattedCityData);

                    // Make AJAX request to fetch the air quality data for the chosen city's longitude and latitude coordinates:
                    $.ajax({
                        url: './functions/air-quality.php',
                        method: 'GET',
                        data: { lat: cityData.coord.lat, lon: cityData.coord.lon },
                        success: function(response) {
                            let airQualityData = response;

                            // If air quality data cannot not be found, display error message:
                            if(airQualityData.cod === "404") {
                                $('.city-info-content').append('<h2 class="info-city">Air quality data not found</h2>');
                                return;
                            }

                            // Declare formattedAirQualityData variable as formatted data and display it:
                            let formattedAirQualityData = $.formatAirQualityData(airQualityData);
                            initMap(cityData.coord.lat, cityData.coord.lon, cityData, formattedAirQualityData, airQualityData.list[0].main.aqi);
                        },

                        // Error Handling:
                        error: function(xhr, status, error) {
                            console.error('Error fetching air quality data:', error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching city weather data:', error);
                    $('.city-info-content').html('<h2 class="info-city">Error fetching city weather data</h2>');
                }
            });
        }
    });

     // Attach a click event handler to the 'get directions' button:
    $('#get-directions-button').on('click', function() {
        // Calculate distance and display directions between the city marker and current location marker:
        $.calculateDistance(cityMarker, currentLocationMarker);
        $.displayDirections(currentLocationMarker, cityMarker, map);
    });
});

async function initMap(latitude, longitude, cityData, formattedAirQualityData, airQualityIndex) {
    const position = { lat: latitude, lng: longitude };
    const companyPosition = { lat: 54.976, lng: -1.607 };

    // Import Google Maps libraries for data:
    const { Map } = await google.maps.importLibrary("maps");
    const { Marker } = await google.maps.importLibrary("marker");
    const { directions } = await google.maps.importLibrary("routes");
    
    // Set map to the chosen city's position:
    let map = new Map(document.getElementById("map"), {
        zoom: 7,
        center: position,
        mapId: "DEMO_MAP_ID",
    });

    // Display air quality information:
    const infowindow = new google.maps.InfoWindow({
        content: formattedAirQualityData,
        ariaLabel: "Air Quality Information",
        pixelOffset: new google.maps.Size(0, -10)
    });

    // Get air quality data for setting marker icon:
    const airQualityIndexData = $.airQualityIndexData(airQualityIndex);

    // Create a marker for the city's position:
    const marker = new Marker({
        map: map,
        position: position,
        title: cityData.name,
        icon: airQualityIndexData.icon
    });

    // Create a marker for Living Planet HQ's position:
    const currentLocationMarker = new Marker({
        map: map,
        position: companyPosition,
        title: "Living Planet's Location",
    });

    // Adjust the CSS styles for the map:
    $('.google-container').css('display', 'flex');
    $('#map').css('width', '70%');
    $('#map').css('height', '80vh');

    // Add event listeners to open / close the info window on marker hover:
    marker.addListener("mouseover", () => {
        infowindow.open({
            anchor: marker,
            map,
        });
    });

    marker.addListener("mouseout", () => {
        infowindow.close();
    });

    // Display the 'Get Directions' button:
    const directionsButton = document.getElementById('directions-button');
    directionsButton.style.display = 'block';

    // Attach an event listener to calculate and display directions to Living Planet HQ when button is clicked:
    directionsButton.addEventListener("click", () => {
        $.calculateDistance(marker, currentLocationMarker);

        $.displayDirections(marker, currentLocationMarker, map);
        directionsButton.style.display = 'none';
    });

};

// Format the city weather data into HTML so it can be displayed on the frontend:
$.formatCityData = function(cityData) {
    if(cityData.cod === "404") {
        return `<h2 class="info-city">City not found</h2>`;
    }

    let feelsLike = Math.round(cityData?.main?.feels_like);
    let humidity = cityData?.main?.humidity;
    let pressure = cityData?.main?.pressure;

    let temp = Math.round(cityData?.main?.temp);
    let tempMax = Math.round(cityData?.main?.temp_max);
    let tempMin = Math.round(cityData.main.temp_min);

    let windSpeed = cityData.wind.speed;
    let windDeg = cityData.wind.deg;
    
    let weather = cityData.weather[0].description;
    weather = weather.charAt(0).toUpperCase() + weather.slice(1);

    let icon = cityData.weather[0].icon;
    let cityName = cityData.name;

    let sunrise = new Date(cityData.sys.sunrise * 1000).toLocaleTimeString();
    let sunset = new Date(cityData.sys.sunset * 1000).toLocaleTimeString();

    // Return the formatted data as HTML:
    return `
        <h2 class="info-city">${cityName}</h2>
        <p class="info-temp">${temp}&deg;C</p>
        <img src="http://openweathermap.org/img/w/${icon}.png" alt="${weather}">
        <span class="info-weather">${weather}</span>
        <p class="info-temps"><span>H:${tempMax}&deg;</span><span>L:${tempMin}&deg;</span></p>
        <p class="info-stat">Feels like: ${feelsLike}&deg;</p>
        <p class="info-stat">Humidity: ${humidity}%</p>
        <p class="info-stat">Pressure: ${pressure} hPa</p>
        <p class="info-stat">Wind: ${windSpeed} m/s, ${windDeg}&deg;</p>
        <p class="info-stat">Sunrise: ${sunrise}</p>
        <p class="info-stat">Sunset: ${sunset}</p>
    `;
};

// Format the air quality data into HTML so it can be displayed on the frontend:
$.formatAirQualityData = function(airQualityData) {
    const airQualityIndex = airQualityData.list[0].main.aqi;
    const airQualityIndexData = $.airQualityIndexData(airQualityIndex);
    const airQuality = airQualityIndexData.airQuality;

    const CO = airQualityData.list[0].components.co;
    const NO = airQualityData.list[0].components.no;
    const NO2 = airQualityData.list[0].components.no2;
    const O3 = airQualityData.list[0].components.o3;
    const SO2 = airQualityData.list[0].components.so2;
    const PM2_5 = airQualityData.list[0].components.pm2_5;
    const PM10 = airQualityData.list[0].components.pm10;
    const NH3 = airQualityData.list[0].components.nh3;

    // Return the formatted data as HTML:
    return `
        <h2 class="info-stat">Air Quality: ${airQuality}</h2>
        <p class="info-stat">CO: ${CO} µg/m³</p>
        <p class="info-stat">NO: ${NO} µg/m³</p>
        <p class="info-stat">NO2: ${NO2} µg/m³</p>
        <p class="info-stat">O3: ${O3} µg/m³</p>
        <p class="info-stat">SO2: ${SO2} µg/m³</p>
        <p class="info-stat">PM2.5: ${PM2_5} µg/m³</p>
        <p class="info-stat">PM10: ${PM10} µg/m³</p>
        <p class="info-stat">NH3: ${NH3} µg/m³</p>
    `;
};

// Get air quality index data and relevant icon based on the index's value:
$.airQualityIndexData = function(airQualityIndex) {
    let airQualityIndexData = {
        airQuality: '',
        icon: ''
    }

    // Set air quality description and icon based on the index's value:
    switch (airQualityIndex) {
        case 1:
            airQualityIndexData.airQuality = 'Good';
            airQualityIndexData.icon = 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
            break;
        case 2:
            airQualityIndexData.airQuality = 'Fair';
            airQualityIndexData.icon = 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            break;
        case 3:
            airQualityIndexData.airQuality = 'Moderate';
            airQualityIndexData.icon = 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png';
            break;
        case 4:
            airQualityIndexData.airQuality = 'Poor';
            airQualityIndexData.icon = 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
            break;
        case 5:
            airQualityIndexData.airQuality = 'Very Poor';
            airQualityIndexData.icon = 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png';
            break;
        default:
            airQualityIndexData.airQuality = 'Unknown';
            break;
    }

    // Return the relevant description:
    return airQualityIndexData;
};

// Calculate the distance between the city marker and the Living Planet HQ marker:
$.calculateDistance = function(cityMarker, companyMarker) {
    const distanceMatrixService = new google.maps.DistanceMatrixService();

    const origin = cityMarker.getPosition();
    const destination = companyMarker.getPosition();

    const request = {
        origins: [origin],
        destinations: [destination],
        travelMode: google.maps.TravelMode.DRIVING
    };

    distanceMatrixService.getDistanceMatrix(request, function(response, status) {
        if (status === google.maps.DistanceMatrixStatus.OK) {
            const distanceText = response.rows[0].elements[0].distance.text;
            const durationText = response.rows[0].elements[0].duration.text;
            
            // Already does the distance when getting directions:
            console.log('Distance:', distanceText);
            console.log('Duration:', durationText);
        } else {
            console.error('Error calculating distance:', status);
        }
    });
}

// Display driving directions between the city marker and the Living Planet HQ marker:
$.displayDirections = function(cityMarker, companyMarker, map) {
    const directionsPanel = document.getElementById('directions-panel');

    const directionsService = new google.maps.DirectionsService();
    const directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        panel: directionsPanel
    });

    const request = {
        origin: cityMarker.getPosition(),
        destination: companyMarker.getPosition(),
        travelMode: google.maps.TravelMode.DRIVING
    };

    directionsService.route(request, function(result, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            directionsRenderer.setDirections(result);
        } else {
            console.error('Error calculating directions:', status);
        }
    });
}
