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
				htsprrd.pot_jam,
				htsprrd.pot_ti,
				htsprrd.pot_overtime,
				htsprrd.pot_hk,
				htsprrd.durasi_lembur_final,
				htsprrd.is_makan,
				htsprrd.jam_awal_lembur_libur,
				htsprrd.jam_akhir_lembur_libur,
				htsprrd.durasi_lembur_libur,
				htsprrd.jam_awal_lembur_awal,
				htsprrd.jam_akhir_lembur_awal,
				htsprrd.durasi_lembur_awal,
				htsprrd.jam_awal_lembur_akhir,
				htsprrd.jam_akhir_lembur_akhir,
				htsprrd.durasi_lembur_akhir,
				htsprrd.jam_awal_lembur_istirahat1,
				htsprrd.jam_akhir_lembur_istirahat1,
				htsprrd.durasi_lembur_istirahat1,
				htsprrd.jam_awal_lembur_istirahat2,
				htsprrd.jam_akhir_lembur_istirahat2,
				htsprrd.durasi_lembur_istirahat2,
				htsprrd.jam_awal_lembur_istirahat3,
				htsprrd.jam_akhir_lembur_istirahat3,
				htsprrd.durasi_lembur_istirahat3,
				htsprrd.durasi_lembur_total_jam,
				htsprrd.lembur15,
				htsprrd.lembur2,
				htsprrd.lembur3,
				htsprrd.lembur4,
				htsprrd.lembur15_final,
				htsprrd.lembur2_final,
				htsprrd.lembur3_final,
				htsprrd.lembur4_final,
				htsprrd.rp_lembur15,
				htsprrd.rp_lembur2,
				htsprrd.rp_lembur3,
				htsprrd.rp_lembur4,
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
			'.$where.'
			group by htsprrd.id
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