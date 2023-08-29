<?php
	include( "../../../../users/init.php" );

	// DataTables PHP library
	include( "../../../../usersc/lib/DataTables.php" );

	use Carbon\Carbon;
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$where = " AND htlxxrh.tanggal BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
	
	if (isset($_POST['id_hemxxmh'])) {
		$id_hemxxmh = $_POST['id_hemxxmh'];
		if ($id_hemxxmh > 0) {
			$where = $where . " AND htlxxrh.id_hemxxmh = " . $id_hemxxmh;
		}
	}
	
	// Your SQL query
	$sql = "SELECT
				htlxxrh.id,
				UPPER(htlxxrh.kode) AS kode,
				htlxxrh.nama AS nama,
				htlxxrh.keterangan,
				htlxxrh.is_active,
				IFNULL(htlxxrh.created_by, '') AS created_by,
				IFNULL(htlxxrh.created_on, '') AS created_on,
				IFNULL(htlxxrh.last_edited_by, '') AS last_edited_by,
				htlxxrh.is_approve,
				htlxxrh.is_defaultprogram,
				DATE_FORMAT(htlxxrh.tanggal, '%d %b %Y') AS tanggal,
				IFNULL(htlxxmh.nama, htpxxmh.nama)  AS htlxxmh_nama,
				hemxxmh.kode AS hemxxmh_kode,
				hemxxmh.nama AS hemxxmh_nama
			FROM
				htlxxrh
			LEFT JOIN htlxxmh ON htlxxrh.jenis = 1 AND htlxxmh.id = htlxxrh.id_htlxxmh
			LEFT JOIN htpxxmh ON htlxxrh.jenis = 2 AND htpxxmh.id = htlxxrh.id_htlxxmh
			LEFT JOIN hemxxmh ON hemxxmh.id = htlxxrh.id_hemxxmh
			WHERE htlxxrh.is_active = 1" . $where;
		;

		// echo $sql;
		$result = $db->sql($sql)->fetchAll();
		$count = $db->sql($sql)->count();


		if ($count > 0){
			$results = $result;
			echo json_encode( ['data' => $results]);
		}else{
			echo json_encode( [ "data" => [] ] );
		}
		// include( "htlxxrh_extra.php" );
		// include( "../../../helpers/edt_log.php" );
	exit();
?>