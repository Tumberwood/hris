<?php

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	if (isset($_POST['start_date'])){
		$awal		= new Carbon($_POST['start_date']);
	}
	$start_date = $awal->format('Y-m-d');

	$qs_cuti = $db
		->raw()
		->bind(':id_hemxxmh', $_SESSION['id_hemxxmh'])
		->bind(':start_date', $start_date)
		->exec('WITH base AS (
					SELECT
					a.id_hemxxmh,
					a.tanggal,
					b.kode AS nrp,
					b.nama,
					a.kode,
					a.saldo
					FROM htlxxrh a
					LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
					WHERE YEAR(a.tanggal) = YEAR(:start_date)
					AND b.id = :id_hemxxmh
					AND a.saldo IS NOT NULL AND a.saldo <> 0
				),
				ordered AS (
					SELECT
					id_hemxxmh,
					tanggal,
					nrp,
					nama,
					kode,
					saldo,
					SUM(saldo) OVER (
						PARTITION BY id_hemxxmh
						ORDER BY tanggal,
								(CASE WHEN kode IS NULL OR kode = "\\N" THEN 0 ELSE 1 END),
								kode
					) AS running_total
					FROM base
				)
				SELECT
					id_hemxxmh,
					DATE_FORMAT(tanggal, "%d %b %Y") AS tanggal,
					nrp,
					nama,
					IF(kode IS NULL, "SALDO AWAL", kode) kode,
					COALESCE(
					LAG(running_total) OVER (
						PARTITION BY id_hemxxmh
						ORDER BY tanggal,
								(CASE WHEN kode IS NULL OR kode = "\\N" THEN 0 ELSE 1 END),
								kode
					),
					running_total
					) AS saldo_awal,
					0 plus,
					CASE WHEN saldo < 0 THEN saldo ELSE 0 END AS minus,
					running_total AS sisa_saldo
				FROM ordered
		  
	');
	
	$rs_cuti = $qs_cuti->fetchAll();

	if (empty($rs_cuti)) {
		$rs_cuti = [];
	}

	echo json_encode([
		"data" => $rs_cuti,
		"debug" => $qs_cuti,
	]);	
?>