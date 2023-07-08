<?php
    /**
     * Digunakan untuk menampilkan hasil model _fn 
     * Ini digunakan secara global, di require_once di masing-masing halaman model _fn
     * Results :
     *  - debug         : menampilkan debug query. Didapatkan dari helpers/datatables_fn_debug.php
     *  - data          : output general data (array)
     * 
     * Untuk select2
     *  - results       : options untuk select2
     *  - total_data    : total_data options select2
     *  - pagination    : untuk keperluan paginasi select2
     */

    // BEGIN results akhir
    $is_debug = true;
    if($is_debug == true){
        $results = array(
            "debug" => $debug,
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }else{
        $results = array(
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }
    
    // END results akhir

    echo json_encode($results,JSON_NUMERIC_CHECK);
?>