<?php 
    /**
     * Digunakan 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	$start_date = $_POST['start_date'];
	$end_date   = $_POST['end_date'];
	$dept   = $_POST['dept'];
	$izin   = $_POST['izin'];
    
    $where = ''; 
    if (isset($_POST['id_heyxxmh']) && ($_POST['id_heyxxmh'] > 0 ) ) {
        $where = ' AND job.id_heyxxmh =' . $_POST['id_heyxxmh']; 
    } 

    $qs_hemxxmh = $db
    ->raw()
    ->bind(':dept', $dept)
    ->bind(':izin', $izin)
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec('SELECT
                x.* ,
                IFNULL(iz.nama, kondite) jenis 
            FROM (
                SELECT
                    a.id,
                    date_format(a.tanggal, "%d %b %Y") tanggal,
                    a.htlxxrh_kode kode,
                    b.nama,
                    dep.nama AS departemen,
                    CASE
                        WHEN a.st_clock_in = "LATE"
                            AND a.status_presensi_in = "Belum Ada Izin"
                            THEN CONCAT(a.st_clock_in, " - ", a.status_presensi_in)

                        WHEN a.htlxxrh_kode LIKE "%[I/%"
                            THEN TRIM(SUBSTRING_INDEX(a.htlxxrh_kode, "[I/", 1))

                        WHEN pin.kode IS NOT NULL
                            THEN a.status_presensi_in

                        WHEN pout.kode IS NOT NULL
                            THEN a.status_presensi_out

                        ELSE NULL
                    END AS kondite,
                
                    DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS jam_awal,
                    DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS jam_akhir,

                    "" keterangan

                FROM htsprrd a
                INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
                LEFT JOIN hemjbmh job ON job.id_hemxxmh = a.id_hemxxmh
                LEFT JOIN hodxxmh AS dep ON dep.id = job.id_hodxxmh
                LEFT JOIN htpxxmh pin
                    ON pin.kode = a.status_presensi_in

                LEFT JOIN htpxxmh pout
                    ON pout.kode = a.status_presensi_out

                WHERE a.tanggal BETWEEN :start_date AND :end_date
                AND (
                        pin.kode IS NOT NULL
                    OR pout.kode IS NOT NULL
                    OR a.st_clock_in = "LATE"
                    OR a.htlxxrh_kode LIKE "%[I/%"
                )
                AND a.htlxxrh_kode NOT LIKE "%[KD%"
                '.$where.'
            ) x
            LEFT JOIN htpxxmh iz
                ON iz.kode = x.kondite
            HAVING departemen = :dept AND jenis = :izin
                    
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
        'start_date' => $start_date,
        'end_date' => $end_date
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

