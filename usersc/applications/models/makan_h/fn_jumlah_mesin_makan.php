<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php
    $nama = $_POST['nama'];
    $tanggal = new Carbon($_POST['tanggal']);
    
    $qs_htpxxmh = $db
        ->raw()
        ->bind(':tanggal', $tanggal)
        ->bind(':nama', $nama)
        ->exec(' SELECT
                    SUM(CASE WHEN b.jam BETWEEN "11:00:00" AND "13:00:00" THEN ifnull(a.is_makan, 0) ELSE 0 END) AS shift1,
                    SUM(CASE WHEN b.jam BETWEEN "17:00:00" AND "19:00:00" THEN ifnull(a.is_makan, 0) ELSE 0 END) AS shift2,
                    SUM(CASE WHEN b.jam BETWEEN "00:00:00" AND "02:00:00" THEN ifnull(a.is_makan, 0) ELSE 0 END) AS shift3
                FROM
                    htsprrd AS a
                LEFT JOIN
                    hemxxmh AS hem ON hem.id = a.id_hemxxmh
                LEFT JOIN
                    htsprtd AS b ON b.kode = hem.kode_finger AND b.tanggal = a.tanggal
                WHERE
                    a.tanggal = :tanggal AND b.nama = :nama  AND b.is_active = 1;
                '
                );
    $rs_htpxxmh = $qs_htpxxmh->fetch();

    $data = array(
        'rs_htpxxmh' => $rs_htpxxmh
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>