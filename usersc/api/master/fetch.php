<?php
// API endpoint
$url = 'https://hrispmi.solusiprogram.top/usersc/api/employee/employee.php';
// $url = 'http://localhost/hrispmi2/usersc/api/employee/employee.php';

// API credentials
$username = 'pmierp';
$password = 'G:}*DA1]U1';

// Base64 encode the credentials
$auth_header = base64_encode("$username:$password");

/**
 * Make the API request
 */
// Set up cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Headers
$headers = array(
    "Accept: application/json",
    // Add more headers if needed
    "Auth: Basic $auth_header"
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Make the API request
$response = curl_exec($ch);
curl_close($ch);

// Output the API response
echo $response;
?>
