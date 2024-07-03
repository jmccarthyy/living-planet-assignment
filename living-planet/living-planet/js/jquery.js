// This code contains the application's jQuery code:

// The companyPosition variable is declared, which is where Living Planet's HQ is located
const companyPosition = { lat: 54.976, lng: -1.607 };

$(function() {
    // Initialize the map with the company's position:
    initMap(companyPosition.lat, companyPosition.lng);
    // Get the weather forecast for the company's position:
    $.getWeatherForecast(companyPosition.lat, companyPosition.lng, 'default-weather');
});

$.getWeatherForecast = async function(lat, lng, containerId) {
    $.ajax({
        // Link to the weather forecast PHP file
        url: './functions/weather-forecast.php',
        // http method to use
        method: 'GET',
        // Data to send with the request
        data: { lat: lat, lon: lng },
        success: function(response) {
            // Parse the JSON response
            let weatherForecastData = JSON.parse(response);
            //Checks if the forecast was not found:
            if(weatherForecastData.cod === "404") {
                // If forecast could not be retrieved, display error message:
                $('#' + containerId).html(`<h2 class="info-weather">Forecast not found</h2>`);
                return;
            }

            // Format the weather forecast data:
            let formattedWeatherForecastData = $.formatWeatherForecastData(weatherForecastData);

            //Display the formatted weather forecast data:
            $('#' + containerId).html(formattedWeatherForecastData);
        },

        // Error Handling:

        error: function(xhr, status, error) {
            // Log the error to the console so issue can be located:
            console.error('Error fetching weather forecast:', error);
        }
    });
}

$.formatWeatherForecastData = function(weatherForecastData) {
    let weatherForecast = weatherForecastData.list;
    let groupedForecast = {};

    // Group forecasts by date and collect weather descriptions and icons:
    for (let i = 0; i < weatherForecast.length; i++) {
        let weather = weatherForecast[i];
        let date = new Date(weather.dt * 1000).toDateString();
        if (!groupedForecast[date]) {
            groupedForecast[date] = {
                temperatureSum: 0,
                weatherDescriptions: [],
                icons: []
            };
        }
        groupedForecast[date].temperatureSum += weather.main.temp;
        groupedForecast[date].weatherDescriptions.push(weather.weather[0].description);
        groupedForecast[date].icons.push(`http://openweathermap.org/img/w/${weather.weather[0].icon}.png`);
    }

    let formattedWeatherForecastData = '';

    // Create a weather data column for each day:
    Object.keys(groupedForecast).forEach(date => {
        let averageTemperature = Math.round(groupedForecast[date].temperatureSum / groupedForecast[date].weatherDescriptions.length);
        // Use the first weather description:
        let weatherDescription = groupedForecast[date].weatherDescriptions[0];
        // Use the icon corresponding to the first weather description:
        let icon = groupedForecast[date].icons[0];

        // HTML code displaying the formatted weather forecast data:
        formattedWeatherForecastData += `
            <div class="weather-card">
                <h3>${date}</h3>
                <div class="weather">
                    <p>Average Temperature: ${averageTemperature}°C</p>
                    <p>${weatherDescription}</p>
                    <img src="${icon}" alt="${weatherDescription}">
                </div>
            </div>
        `;
    });

    // Return the formatted weather forecast data:
    return formattedWeatherForecastData;
}

// Declare marker variable, currently null as not assigned to user's choice:
let marker = null;

// Initialize the map:
async function initMap(lat, lng) {
    const position = { lat: lat, lng: lng };

    const { Map } = await google.maps.importLibrary("maps");
    const { Marker } = await google.maps.importLibrary("marker");
    
    let map = new Map(document.getElementById("map"), {
        zoom: 7,
        center: position,
        mapId: "DEMO_MAP_ID",
    });

    // Add a marker for the Living Planet's position
    addMarker(companyPosition, map);

    // Add click event listener to the map
    map.addListener("click", (event) => {
        // Add a marker at the clicked position (The user's chosen location):
        addMarker(event.latLng, map);
    });
}

function addMarker(location, map) {
    // Remove previously placed marker if it exists:
    if (marker) {
        marker.setMap(null);
    }

    // Create new marker:
    marker = new google.maps.Marker({
        position: location,
        map: map,
    });

    // Get latitude and longitude from the marker's position:
    const lat = marker.getPosition().lat();
    const lng = marker.getPosition().lng();

    // If the marker is not Living Planet's position, get the weather forecast
    if (lat !== companyPosition.lat && lng !== companyPosition.lng) {
        // Get the weather forecast for the new position:
        $.getWeatherForecast(lat, lng, 'map-weather');
    }
}
