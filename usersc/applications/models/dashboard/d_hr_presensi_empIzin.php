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

    $where = ''; 
    if (isset($_POST['id_heyxxmh']) && ($_POST['id_heyxxmh'] > 0 ) ) {
        $where = ' AND id_heyxxmh =' . $_POST['id_heyxxmh']; 
    } 
    
    $qs_hemxxmh = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec('WITH izin AS (
                SELECT
                    a.st_clock_in,
                    a.status_presensi_in,
                    a.status_presensi_out,
                    case
                        when a.st_clock_in = "LATE" AND  a.status_presensi_in = "Belum Ada Izin" then CONCAT(a.st_clock_in, " - ", a.status_presensi_in)
                        WHEN a.htlxxrh_kode LIKE "%[I/%" THEN TRIM(SUBSTRING_INDEX(a.htlxxrh_kode, "[I/", 1))
                        when a.status_presensi_in <> "HK" then a.status_presensi_in
                        when a.status_presensi_out <> "HK" then a.status_presensi_out
                        ELSE ""
                    END kondite,
                    a.tanggal,
                    a.id_hemxxmh,
                    a.htlxxrh_kode
                FROM htsprrd a
                WHERE a.tanggal BETWEEN :start_date AND :end_date
                AND (
                    a.status_presensi_in IN ( SELECT kode FROM htpxxmh) 
                    OR a.status_presensi_out IN ( SELECT kode FROM htpxxmh) 
                    OR a.st_clock_in IN ("LATE")
                    OR a.htlxxrh_kode LIKE "%[I/%"
                )
                HAVING kondite <> ""
            )
            SELECT
                IFNULL(iz.id, -1) id_izin,
                IFNULL(iz.nama, kondite) hemxxmh_izin,
                COUNT(kondite) c_izin
            FROM izin
            LEFT JOIN htpxxmh iz ON iz.kode = izin.kondite
            GROUP BY kondite
            ORDER BY id_izin
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'izin';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	$series1['colorByPoint'] =  true;

    $drilldown = array();
    $data_dept = array();
	

	foreach ($rs_hemxxmh as $key => $row) {
		$category['data'][] = $row['hemxxmh_izin'];
        $series1['data'][] = array(
            'name' => $row['hemxxmh_izin'],
            'y' =>$row['c_izin'],
            'drilldown' => $row['hemxxmh_izin']
        );

        $drillDeptData = [
            'name' => $row['hemxxmh_izin'],
            'id' => $row['hemxxmh_izin']
        ];

		$izin = $row['id_izin'];

        $qs_department = $db
        ->raw()
        ->bind(':izin', $izin)
        ->bind(':start_date', $start_date)
        ->bind(':end_date', $end_date)
        ->exec('WITH izin AS (
                    SELECT
                        a.st_clock_in,
                        a.status_presensi_in,
                        a.status_presensi_out,
                        case
                            when a.st_clock_in = "LATE" AND  a.status_presensi_in = "Belum Ada Izin" then CONCAT(a.st_clock_in, " - ", a.status_presensi_in)
                            WHEN a.htlxxrh_kode LIKE "%[I/%" THEN TRIM(SUBSTRING_INDEX(a.htlxxrh_kode, "[I/", 1))
                            when a.status_presensi_in <> "HK" then a.status_presensi_in
                            when a.status_presensi_out <> "HK" then a.status_presensi_out
                            ELSE ""
                        END kondite,
                        a.tanggal,
                        a.id_hemxxmh,
                        a.htlxxrh_kode,
                        dep.nama department
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
                    HAVING kondite <> ""
                )
                SELECT
                    IFNULL(iz.id, -1) id_izin,
                    department,
                    IFNULL(iz.nama, kondite) hemxxmh_izin,
                    COUNT(kondite) c_izin
                FROM izin
                LEFT JOIN htpxxmh iz ON iz.kode = izin.kondite
                GROUP BY department, hemxxmh_izin
                HAVING id_izin = :izin
                ORDER BY department
                '
                );
        $rs_department = $qs_department->fetchAll();

        foreach ($rs_department as $key => $dept) {
            $data_dept = array($dept['department'], $dept['c_izin']);

            $data_dept = [$dept['department'], $dept['c_izin']];
            
            $drillDeptData['data'][] = $data_dept;
        }
        //add drillDeptData ke drilldown[series] agar mengkelompokkan berdasarkan izin sesuai dengan yang di example
        $drilldown['series'][] = $drillDeptData;
	}
	
	$results = array();
	array_push($results,$category);
	array_push($results,$series1);
	array_push($results,$drilldown);

    $data = array(
        'results_emp_izin' => $results,
        'start_date' => $start_date,
        'end_date' => $end_date
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

