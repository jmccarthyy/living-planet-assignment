<?php
// This code contains the function for getting the air quality data for the user's chosen city

// Access the API key for weather data saved as an Azure environment variable
$api_key= getenv('WEATHER_API_KEY');

// Declared langitude and longitude variables for chosen city
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';

header('Content-Type: application/json');

// If langitude and longitude data for chosen city is provided
if (!empty($lat) && !empty($lon)) {

    // Set Weather API as URL, passing the longitude and latitude variables as parameters
    $api_url = "https://api.openweathermap.org/data/2.5/air_pollution?lat=$lat&lon=$lon&appid=$api_key";
    
    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $api_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the request and get the response
    $response = curl_exec($curl);

    // Check if the request was successful - if it was:
    if ($response !== false) {
        // Close cURL session
        curl_close($curl);

        // Parse the JSON response
        $data = json_decode($response, true);

        print_r($response);

        // Error Handling:
        
        if (!empty($data) || $data['cod'] !== 200) {
            return $response;
        } else {
            echo "<p>Data not available for $lat and $lon.</p>";
        }
    } else {
        echo "<p>Error fetching air quality data for $lat and $lon.</p>";
    }
} else {
    echo "<p>Please enter provided lat and lon.</p>";
}
?>