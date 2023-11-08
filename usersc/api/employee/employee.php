<?php
require_once "method.php";
$mhs = new Employee();
$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'GET':
        if (!empty($_GET["id"])) {
            $id = intval($_GET["id"]);


            $mhs->get_emp($id);
        } else {

            $mhs->get_all_emp();
        }
        break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
?>
