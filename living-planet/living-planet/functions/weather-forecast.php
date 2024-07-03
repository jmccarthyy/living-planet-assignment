<?php
// This code contains the function for getting the weather forecasts

// Access the API key for weather data saved as an Azure environment variable
$api_key= getenv('WEATHER_API_KEY');

// Declared langitude and longitude variables for chosen location
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';

// If langitude and longitude data for chosen location is provided
if (!empty($lat) && !empty($lon)) {

    // Set Weather API as URL, passing the longitude and latitude variables as parameters
    $api_url = "https://api.openweathermap.org/data/2.5/forecast?lat=$lat&lon=$lon&appid=$api_key&units=metric";

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the request and get the response
    $response = curl_exec($curl);

    // Check if the request was successful
    if ($response !== false) {
        // Close cURL session
        curl_close($curl);

        // Parse the JSON response
        $data = json_decode($response, true);

        // Error Handling:
        
        if (!empty($data) || $data['cod'] !== 200) {
            echo json_encode($data);
        } else {
            echo "<p>Data not available for $lat and $lon.</p>";
        }
    } else {
        echo "<p>Error fetching weather forecast for $lat and $lon.</p>";
    }
} else {
    echo "<p>Please enter provide lat and lon.</p>";
}
?>