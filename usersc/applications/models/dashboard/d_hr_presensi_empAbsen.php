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
                absen.nama AS hemxxmh_absen,
                absen.id AS id_absen,
                COUNT(peg.id) AS c_absen
            FROM htlxxrh AS a
            LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
            LEFT JOIN htlxxmh AS absen ON absen.id = a.id_htlxxmh
            WHERE a.jenis = 1 
                AND a.is_active = 1
                AND a.tanggal BETWEEN :start_date AND :end_date
            GROUP BY absen.id
            ORDER BY absen.id ASC
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'absen';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	$series1['colorByPoint'] =  true;

    $drilldown = array();
    $data_dept = array();
	

	foreach ($rs_hemxxmh as $key => $row) {
		$category['data'][] = $row['hemxxmh_absen'];
        $series1['data'][] = array(
            'name' => $row['hemxxmh_absen'],
            'y' =>$row['c_absen'],
            'drilldown' => $row['hemxxmh_absen']
        );

        $drillDeptData = [
            'name' => $row['hemxxmh_absen'],
            'id' => $row['hemxxmh_absen']
        ];

		$absen = $row['id_absen'];

        $qs_department = $db
        ->raw()
        ->bind(':absen', $absen)
        ->exec('SELECT
                    absen.nama AS nama_absen,
                    COUNT(peg.id) AS c_absen,
                    dep.nama AS department
                FROM htlxxrh AS a
                LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                LEFT JOIN htlxxmh AS absen ON absen.id = a.id_htlxxmh
                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = peg.id
                LEFT JOIN hodxxmh AS dep ON dep.id = job.id_hodxxmh
                WHERE a.jenis = 1 
                    AND a.id_htlxxmh = :absen
                GROUP BY dep.id
                ORDER BY dep.id ASC;
                '
                );
        $rs_department = $qs_department->fetchAll();

        foreach ($rs_department as $key => $dept) {
            $data_dept = array($dept['department'], $dept['c_absen']);

            $data_dept = [$dept['department'], $dept['c_absen']];
            
            $drillDeptData['data'][] = $data_dept;
        }
        //add drillDeptData ke drilldown[series] agar mengkelompokkan berdasarkan absen sesuai dengan yang di example
        $drilldown['series'][] = $drillDeptData;
	}
	
	$results = array();
	array_push($results,$category);
	array_push($results,$series1);
	array_push($results,$drilldown);

    $data = array(
        'results_emp_absen' => $results,
        'start_date' => $start_date,
        'end_date' => $end_date
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

