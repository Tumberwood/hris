<?php
if (isset($headers['Authorization'])) {
    $authorizationHeader = $headers['Authorization'];
}
echo "apache_request_headers: " . print_r(apache_request_headers(), true) . "<br><br>"; 
?>