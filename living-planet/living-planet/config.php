<?php
header('Content-Type: application/json');

// Fetch environment variables:
$config = array(
    'MAP_API_KEY' => getenv('MAP_API_KEY'),
    'WEATHER_API_KEY' => getenv('WEATHER_API_KEY'),
    'CLIENT_ID' => getenv('CLIENT_ID'),
    'CLIENT_SECRET' => getenv('CLIENT_SECRET')
);

// Output the configuration as JSON:
echo json_encode($config);
?>
