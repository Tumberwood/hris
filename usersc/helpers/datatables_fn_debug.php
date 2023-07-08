<?php
    // digunakan untuk melakukan debugging query datatables class, tanpa melalui editor
    // untuk menampilkan hasil debug, gunakan print_r di halaman terkait:
    // print_r($debug);
    
    $debug = [];
    $results = array();
    $db->debug( function ( $mess ) use ( &$debug ) {
        $debug[] = $mess;
    } );

    // foreach ( $debug as $row ) {
    //     $results['query'] = $row['query'];
    //     $results['bindings'] = $row['bindings'];
    // }

    // echo json_encode($results);
?>