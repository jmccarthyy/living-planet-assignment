<?php
// This code contains the function for getting the weather for the user's chosen city

// Access the API key for weather data saved as an Azure environment variable
$api_key= getenv('WEATHER_API_KEY');

// Declared selected city as variable
$city = isset($_GET['city']) ? $_GET['city'] : '';

// If city is chosen
if (!empty($city)) {
    $api_url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=$api_key&units=metric";

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

        header('Content-Type: application/json');

        // Error Handling:

        if ($data['cod'] === 200) {
            echo json_encode($data);
        } else {
            echo json_encode([
                'cod' => $data['cod'],
                'message' => $data['message']
            ]);
        }
    } else {
        echo json_encode([
            'cod' => 500,
            'message' => 'Error fetching weather data.'
        ]);
    }
} else {
    echo json_encode([
        'cod' => 400,
        'message' => 'Please enter a city name.'
    ]);
}
?>
