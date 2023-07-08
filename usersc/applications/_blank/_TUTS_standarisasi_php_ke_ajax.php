standar struktur results php untuk ajax
- debug         : menampilkan debug untuk membantu cek query (harus include usersc/helpers/datatables_fn_debug.php)
- data          : data yang di fetch. Berupa array

ini hanya digunakan untuk options select2
- options       : data options
- total_data    : total jumlah data (untuk membantu paginasi)
- pagination    : format paginasi select2


$results = array(
    "debug" => $debug,
    "data" => array(
        "value1" => $value1
        "_blank" => array(
            "field1" => $value_field1
            "field2" => $value_field2
        )
    )
    "options" => $rs_options,
    "total_data" => $c_results__blank,
    "pagination" => array(
        "more" => $morePages
    )
);

echo json_encode($results,JSON_NUMERIC_CHECK);