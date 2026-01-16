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
        $where = ' AND job.id_heyxxmh =' . $_POST['id_heyxxmh']; 
    } 
    
    $qs_hemxxmh = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec('SELECT
                IF( kondite = "PA", -2,
                    IFNULL(iz.id, -1)
                ) AS id_izin, 
                IFNULL(iz.nama, x.kondite) AS hemxxmh_izin,
                COUNT(*) AS c_izin
            FROM (
                SELECT
                    a.id_hemxxmh,
                    a.tanggal,

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
                    END AS kondite

                FROM htsprrd a
	            LEFT JOIN hemjbmh job ON job.id_hemxxmh = a.id_hemxxmh
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
            WHERE x.kondite IS NOT NULL
            GROUP BY x.kondite, iz.id, iz.nama
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
        ->exec('SELECT
                    IF( kondite = "PA", -2,
                        IFNULL(iz.id, -1)
                    ) AS id_izin, 
                    IFNULL(iz.nama, x.kondite) AS hemxxmh_izin,
                    department,
                    COUNT(*) AS c_izin
                FROM (
                    SELECT
                        a.id_hemxxmh,
                        a.tanggal,
                        dep.nama AS department,

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
                        END AS kondite

                    FROM htsprrd a
                    INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
                    LEFT JOIN hemjbmh job ON job.id_hemxxmh = b.id
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
                WHERE x.kondite IS NOT NULL
                GROUP BY department, x.kondite
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

