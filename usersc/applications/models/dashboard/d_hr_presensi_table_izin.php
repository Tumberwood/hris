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
        $where = ' AND id_heyxxmh =' . $_POST['id_heyxxmh']; 
    } 

    $qs_hemxxmh = $db
    ->raw()
    ->bind(':dept', $dept)
    ->bind(':izin', $izin)
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' WITH izin AS (
                SELECT
                    a.id id_report,
                    date_format(a.tanggal, "%d %b %Y") tanggal,
                    a.st_clock_in,
                    a.status_presensi_in,
                    a.status_presensi_out,
                    DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS clock_in,
                    DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS clock_out,
                    case
                        when a.st_clock_in = "LATE" AND  a.status_presensi_in = "Belum Ada Izin" then CONCAT(a.st_clock_in, " - ", a.status_presensi_in)
                        WHEN a.htlxxrh_kode LIKE "%[I/%" THEN TRIM(SUBSTRING_INDEX(a.htlxxrh_kode, "[I/", 1))
                        when a.status_presensi_in <> "HK" then a.status_presensi_in
                        when a.status_presensi_out <> "HK" then a.status_presensi_out
                        ELSE ""
                    END kondite,
                    a.id_hemxxmh,
                    a.htlxxrh_kode,
                    dep.nama departemen
                FROM htsprrd a
                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
            LEFT JOIN hodxxmh AS dep ON dep.id = job.id_hodxxmh
                WHERE a.tanggal BETWEEN :start_date AND :end_date
                AND (
                    a.status_presensi_in IN ( SELECT kode FROM htpxxmh) 
                    OR a.status_presensi_out IN ( SELECT kode FROM htpxxmh) 
                    OR a.st_clock_in IN ("LATE")
                    OR a.htlxxrh_kode LIKE "%[I/%"
                )
                '.$where.'
                HAVING kondite <> ""
            )
            SELECT
                id_report id,
                tanggal,
                htlxxrh_kode kode,
                h.nama,
                departemen,
                IFNULL(iz.nama, kondite) jenis,
                clock_in jam_awal,
                clock_out jam_akhir,
                "" keterangan,
                IFNULL(iz.id, -1) id_izin
            FROM izin
            LEFT JOIN htpxxmh iz ON iz.kode = izin.kondite
            LEFT JOIN hemxxmh h ON h.id = izin.id_hemxxmh
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

