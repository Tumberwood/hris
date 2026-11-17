<?php
require_once "method.php";
$mhs = new Employee();
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        $mhs->get_departemen();
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
