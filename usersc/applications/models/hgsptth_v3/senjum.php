<?php

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	if (isset($_POST['id_hgsptth_v3'])){
		$id_hgsptth_v3		= $_POST['id_hgsptth_v3'];
	}
	
	$qs_shift1 = $db
		->raw()
		->bind(':id_hgsptth_v3', $id_hgsptth_v3)
		->exec('SELECT
                    a.jam AS jam,
                    a.shift AS shift,
                    CONCAT(b.kode, " - ", b.nama) AS nama
                FROM hgsemtd_v3 AS a
                LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                WHERE a.id_hgsptth_v3 = :id_hgsptth_v3 AND a.nama = "senjum"  AND a.shift = 1 
				'
				);
	$rs_shift1 = $qs_shift1->fetchAll();
	
	$qs_shift2 = $db
		->raw()
		->bind(':id_hgsptth_v3', $id_hgsptth_v3)
		->exec('SELECT
                    a.jam AS jam,
                    a.shift AS shift,
                    CONCAT(b.kode, " - ", b.nama) AS nama
                FROM hgsemtd_v3 AS a
                LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                WHERE a.id_hgsptth_v3 = :id_hgsptth_v3 AND a.nama = "senjum"  AND a.shift = 2 
				'
				);
	$rs_shift2 = $qs_shift2->fetchAll();
	
	$qs_shift3 = $db
		->raw()
		->bind(':id_hgsptth_v3', $id_hgsptth_v3)
		->exec('SELECT
                    a.jam AS jam,
                    a.shift AS shift,
                    CONCAT(b.kode, " - ", b.nama) AS nama
                FROM hgsemtd_v3 AS a
                LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                WHERE a.id_hgsptth_v3 = :id_hgsptth_v3 AND a.nama = "senjum"  AND a.shift = 3 
				'
				);
	$rs_shift3 = $qs_shift3->fetchAll();

	$results = array();

	if (!empty($rs_shift1)) {
		$results['data'] = $rs_shift1;
		$results['data2'] = $rs_shift2;
		$results['data3'] = $rs_shift3;
		
		// harus urut sama tablenya
		$results['columns'] = [
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'nama', 'name' => 'nama']
		];
		$results['columns2'] = [
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'nama', 'name' => 'nama']
		];
		$results['columns3'] = [
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'nama', 'name' => 'nama']
		];
	} else {
		$results['data'] = [];
		$results['columns'] = [];
	}

	echo json_encode($results);
?>