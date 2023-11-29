<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );

require '../../../../usersc/vendor/autoload.php';
use Carbon\Carbon;

use
	DataTables\Editor,
	DataTables\Editor\Query,
	DataTables\Editor\Result;
	
	$show_inactive_status = $_POST['show_inactive_status_htsprrd'];
	// -----------
	
	$where = 'WHERE htsprrd.durasi_lembur_final > 0';

	if(isset($_POST['id_hemxxmh']) && !empty($_POST['id_hemxxmh'])){
		$where .= ' AND htsprrd.id_hemxxmh = ' . $_POST['id_hemxxmh'];
	}

	if(isset($_POST['start_date']) && isset($_POST['end_date'])){
		$where .= ' AND htsprrd.tanggal BETWEEN "' . $_POST['start_date'] . '" AND "'. $_POST['end_date'] . '"';
		// print_r($_POST['start_date']);
		// print_r($_POST['end_date']);
	}

	// print_r($where);

$qs_outstanding_pr = $db
	->raw()
	->exec('SELECT
				htsprrd.id,
				UPPER(htsprrd.kode) AS kode,
				UCASE(htsprrd.nama) AS nama,
				htsprrd.keterangan,
				htsprrd.is_active,
				htsprrd.id_hemxxmh,
				htsprrd.is_approve,
				htsprrd.is_defaultprogram,
				htsprrd.kode_finger,
				DATE_FORMAT(htsprrd.tanggal, "%d %b %Y") AS tanggal,
				htsprrd.st_jadwal,
				htsprrd.shift_in,
				htsprrd.shift_out,
				DATE_FORMAT(htsprrd.clock_in, "%d %b %Y %H:%i:%s") AS clock_in,
				DATE_FORMAT(htsprrd.clock_out, "%d %b %Y %H:%i:%s") AS clock_out,
				htsprrd.st_clock_in,
				htsprrd.st_clock_out,
				htsprrd.status_presensi_in,
				htsprrd.status_presensi_out,
				htsprrd.htlxxrh_kode,
				htsprrd.cek,
				htsprrd.pot_hk,
				htsprrd.is_makan,
				if(htoxxrd.id_htotpmh = 4, htsprrd.jam_awal_lembur_libur, null) AS jam_awal_lembur_libur,
				if(htoxxrd.id_htotpmh = 4, htsprrd.jam_akhir_lembur_libur, null) AS jam_akhir_lembur_libur,
				if(htoxxrd.id_htotpmh = 4, htsprrd.durasi_lembur_libur, 0) AS durasi_lembur_libur,

				if(htoxxrd.id_htotpmh = 1, htsprrd.jam_awal_lembur_awal, null) AS jam_awal_lembur_awal,
				if(htoxxrd.id_htotpmh = 1, htsprrd.jam_akhir_lembur_awal, null) AS jam_akhir_lembur_awal,
				if(htoxxrd.id_htotpmh = 1, htsprrd.durasi_lembur_awal, 0) AS durasi_lembur_awal,

				if(htoxxrd.id_htotpmh = 2, htsprrd.jam_awal_lembur_akhir, null) AS jam_awal_lembur_akhir,
				if(htoxxrd.id_htotpmh = 2, htsprrd.jam_akhir_lembur_akhir, null) AS jam_akhir_lembur_akhir,
				if(htoxxrd.id_htotpmh = 2, htsprrd.durasi_lembur_akhir, 0) AS durasi_lembur_akhir,

				if(htoxxrd.id_htotpmh = 5, htsprrd.jam_awal_lembur_istirahat1, null) AS jam_awal_lembur_istirahat1,
				if(htoxxrd.id_htotpmh = 5, htsprrd.jam_akhir_lembur_istirahat1, null) AS jam_akhir_lembur_istirahat1,
				if(htoxxrd.id_htotpmh = 5, htsprrd.durasi_lembur_istirahat1, 0) AS durasi_lembur_istirahat1,

				if(htoxxrd.id_htotpmh = 6, htsprrd.jam_awal_lembur_istirahat2, null) AS jam_awal_lembur_istirahat2,
				if(htoxxrd.id_htotpmh = 6, htsprrd.jam_akhir_lembur_istirahat2, null) AS jam_akhir_lembur_istirahat2,
				if(htoxxrd.id_htotpmh = 6, htsprrd.durasi_lembur_istirahat2, 0) AS durasi_lembur_istirahat2,

				if(htoxxrd.id_htotpmh = 7, htsprrd.jam_awal_lembur_istirahat3, null) AS jam_awal_lembur_istirahat3,
				if(htoxxrd.id_htotpmh = 7, htsprrd.jam_akhir_lembur_istirahat3, null) AS jam_akhir_lembur_istirahat3,
				if(htoxxrd.id_htotpmh = 7, htsprrd.durasi_lembur_istirahat3, 0) AS durasi_lembur_istirahat3,

				ROUND(if(c.c_id = 1, htsprrd.durasi_lembur_total_jam, htsprrd.durasi_lembur_total_jam / c.c_id),1)  AS durasi_lembur_total_jam,
				ROUND(if(c.c_id = 1, htsprrd.lembur15, htsprrd.lembur15 / c.c_id),1)  AS lembur15,
				ROUND(if(c.c_id = 1, htsprrd.lembur2, htsprrd.lembur2 / c.c_id),1)  AS lembur2,
				ROUND(if(c.c_id = 1, htsprrd.lembur3, htsprrd.lembur3 / c.c_id),1)  AS lembur3,
				ROUND(if(c.c_id = 1, htsprrd.lembur4, htsprrd.lembur4 / c.c_id),1)  AS lembur4,
				ROUND(if(c.c_id = 1, htsprrd.pot_jam, htsprrd.pot_jam / c.c_id),1)  AS pot_jam,
				ROUND(if(c.c_id = 1, htsprrd.pot_ti, htsprrd.pot_ti / c.c_id),1)  AS pot_ti,
				ROUND(if(c.c_id = 1, htsprrd.pot_overtime, htsprrd.pot_overtime / c.c_id),1)  AS pot_overtime,
				ROUND(if(c.c_id = 1, htsprrd.durasi_lembur_final, htsprrd.durasi_lembur_final / c.c_id),1)  AS durasi_lembur_final,

				htsprrd.nominal_lembur_jam,
				hodxxmh.nama AS hodxxmh_nama,
				hetxxmh.nama AS hetxxmh_nama,
				heyxxmh.nama AS heyxxmh_nama,
				htoxxrd.kode AS kode_spkl,
				CONCAT(hemxxmh.kode, " - ", hemxxmh.nama) AS hemxxmh_data
			FROM htsprrd
			LEFT JOIN hemxxmh ON hemxxmh.id = htsprrd.id_hemxxmh
			LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id
			LEFT JOIN hodxxmh ON hodxxmh.id = hemjbmh.id_hodxxmh
			LEFT JOIN heyxxmh ON heyxxmh.id = hemjbmh.id_heyxxmh
			LEFT JOIN hetxxmh ON hetxxmh.id = hemjbmh.id_hetxxmh
			LEFT JOIN htoxxrd ON htoxxrd.id_hemxxmh = htsprrd.id_hemxxmh AND htoxxrd.tanggal = htsprrd.tanggal
			LEFT JOIN (
				SELECT
					count(id) AS c_id,
					id_hemxxmh,
					tanggal
				FROM htoxxrd
				GROUP BY id_hemxxmh, tanggal
			) AS c ON c.id_hemxxmh = htsprrd.id_hemxxmh AND c.tanggal = htsprrd.tanggal

			'.$where.'
			;

			'
			);
$rs_outstanding_pr = $qs_outstanding_pr->fetchAll();

$results = array();

if (!empty($rs_outstanding_pr)) {
	$results['data'] = $rs_outstanding_pr;
} else {
	$results['data'] = [];
}

echo json_encode($results);
?>