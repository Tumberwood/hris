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
        $where .= ' AND job.id_heyxxmh =' . $_POST['id_heyxxmh']; 
    }   
    
    if ($dept !== 0) {
        $where .= ' AND dep.nama = "' . $dept . '"';
    }

    $qs_hemxxmh = $db
    ->raw()
    ->bind(':izin', $izin)
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' SELECT
                a.id,
                a.id_hemxxmh,
                CONCAT(b.kode, " - ", b.nama) nama,
                DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
                dep.nama departemen,
                a.htlxxrh_kode AS kode,
                a.status_presensi_in,
                a.status_presensi_out,
                a.pot_jam,
                
                IFNULL(ij.nama, :izin) jenis,
                DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i")  AS jam_awal,
                DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS jam_akhir,
                "" AS keterangan
            FROM htsprrd a
            JOIN hemxxmh b ON b.id = a.id_hemxxmh
            JOIN hemjbmh job ON job.id_hemxxmh = b.id
            JOIN hodxxmh dep ON dep.id = job.id_hodxxmh
            LEFT JOIN htpxxmh ij ON ij.nama = :izin
            WHERE 
                a.tanggal BETWEEN :start_date AND :end_date
                AND (a.status_presensi_in <> "OFF" AND a.status_presensi_out <> "OFF")
                AND (
                a.status_presensi_in = ij.kode
                OR a.status_presensi_out = ij.kode
                OR a.htlxxrh_kode LIKE CONCAT("%", ij.kode, "%")
                
                -- fallback kalau ij tidak ketemu
                OR (
                    ij.kode IS NULL
                    AND :izin = "Late - Belum Ada Izin"
                    AND a.st_clock_in = "LATE"
                    AND a.status_presensi_in = "Belum Ada Izin"
                )
                '.$where.'
            )
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

