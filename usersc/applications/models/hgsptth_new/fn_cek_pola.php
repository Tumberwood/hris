<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    
    $id_htsptth_new = $_POST['id_htsptth_new'];
    $tanggal_awal = $_POST['tanggal_awal'];

    $qs_pola = $db
        ->raw()
        ->bind(':id_htsptth_new', $id_htsptth_new)
        ->bind(':tanggal_awal', $tanggal_awal)
        ->exec('SELECT
                    MAX(urutan) AS urutan,
                    DATE_ADD(:tanggal_awal, INTERVAL MAX(urutan) DAY) AS tanggal_akhir,
                    a.jumlah_grup,
                    CASE
                        WHEN a.jumlah_grup = 3 THEN
                            CASE
                                WHEN DAYOFWEEK(:tanggal_awal) = 2 THEN "Valid" -- 2 == Senin di DAYOFWEEK
                                ELSE "Invalid"
                            END
                        ELSE "Valid"
                    END AS status
                
                FROM htsptth_new AS a
                LEFT JOIN htststd_new AS b ON b.id_htsptth_new = a.id
                WHERE a.id = :id_htsptth_new;
    
                '
                );
    $rs_pola = $qs_pola->fetch();

    $data = array(
        'rs_pola' => $rs_pola
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>