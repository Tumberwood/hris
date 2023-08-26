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

    $qs_hemxxmh = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' SELECT
                izin.nama AS hemxxmh_izin,
                izin.id AS id_izin,
                COUNT(peg.id) AS c_izin
            FROM htlxxrh AS a
            LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
            LEFT JOIN htpxxmh AS izin ON izin.id = a.id_htlxxmh
            WHERE a.jenis = 2 
                AND a.is_active = 1 
                AND a.tanggal BETWEEN :start_date AND :end_date
            GROUP BY izin.id
            ORDER BY izin.id ASC
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
        ->exec('SELECT
                    izin.nama AS nama_izin,
                    COUNT(peg.id) AS c_izin,
                    dep.nama AS department
                FROM htlxxrh AS a
                LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                LEFT JOIN htpxxmh AS izin ON izin.id = a.id_htlxxmh
                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = peg.id
                LEFT JOIN hodxxmh AS dep ON dep.id = job.id_hodxxmh
                WHERE a.jenis = 2 
                    AND a.id_htlxxmh = :izin
                GROUP BY dep.id
                ORDER BY dep.id ASC;
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

