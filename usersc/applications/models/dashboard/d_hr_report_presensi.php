<?php

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

		
	$awal 			= new Carbon($_POST['start_date']);
	$id_hemxxmh		= $_POST['id_hemxxmh'];
	$counter		= $_POST['counter'];

	if ($counter == null) {
		$counter = 0;
	}

	$start_date = $awal->format('Y-m-d');

	$qs_cek_satu_all = $db
	->raw()
	->bind(':start_date', $start_date)
	->exec('SELECT
				a.id_hemxxmh
			FROM htsprrd AS a
			WHERE a.cek = 1 AND tanggal = :start_date
			ORDER BY a.status_presensi_in
			;
			'
			);
	$rs_cek_satu_all = $qs_cek_satu_all->fetchAll();
	$c_cek_satu = count($rs_cek_satu_all) - 1;

	$peg_cek = array();
	foreach ($rs_cek_satu_all as $key => $cek_satu) {
		$peg_cek[] = $cek_satu['id_hemxxmh'];
	}

	if ($id_hemxxmh == null) {
		if (!empty($rs_cek_satu_all)) {
			$id_hemxxmh = $peg_cek[$counter];
		} else {
			$id_hemxxmh = 0;
		}
		
	}
	// print_r($id_hemxxmh);

	$qs_report_presensi = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->exec('WITH grup AS (
					SELECT 
						kar.id_hemxxmh AS id_hemxxmh,
					shift.jumlah_grup
					from htsemtd as kar 
					left join htsptth shift ON shift.id = kar.id_htsptth 
					WHERE kar.is_active = 1 AND shift.is_active = 1
					group by kar.id
				)
				SELECT DISTINCT
					a.id_hemxxmh,
					a.st_jadwal,
					jumlah_grup,
					a.pot_jam AS potong,
					ifnull(a.pot_ti,0) AS ti,
					a.is_makan AS makan,
					a.durasi_lembur_total_jam as lembur,
					a.lembur15,
					a.lembur2,
					a.lembur3,
					a.lembur4,
					if(a.st_jadwal = "OFF", 0,(IF(jumlah_grup = 4, TIMESTAMPDIFF(HOUR, CONCAT(b.tanggal, " ", b.jam_awal), CONCAT(IF(a.st_jadwal LIKE "%malam%" AND b.jam_akhir < "12:00", DATE_ADD(b.tanggal, INTERVAL 1 DAY), b.tanggal), " ", b.jam_akhir)), TIMESTAMPDIFF(HOUR, CONCAT(b.tanggal, " ", b.jam_awal), CONCAT(IF(a.st_jadwal LIKE "%malam%" AND b.jam_akhir < "12:00", DATE_ADD(b.tanggal, INTERVAL 1 DAY), b.tanggal), " ", b.jam_akhir)) - 1))) AS jam_wajib,
					if(a.st_jadwal = "OFF", 0,(IF(jumlah_grup = 4, TIMESTAMPDIFF(HOUR, CONCAT(b.tanggal, " ", b.jam_awal), CONCAT(IF(a.st_jadwal LIKE "%malam%" AND b.jam_akhir < "12:00", DATE_ADD(b.tanggal, INTERVAL 1 DAY), b.tanggal), " ", b.jam_akhir)), TIMESTAMPDIFF(HOUR, CONCAT(b.tanggal, " ", b.jam_awal), CONCAT(IF(a.st_jadwal LIKE "%malam%" AND b.jam_akhir < "12:00", DATE_ADD(b.tanggal, INTERVAL 1 DAY), b.tanggal), " ", b.jam_akhir)) - 1)) - a.pot_hk) AS jam_kerja,
					IFNULL(b.keterangan, "") AS keterangan,
					a.cek,
					DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i:%s") as clock_in,
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i:%s") as clock_out,
					a.st_clock_in,
					a.st_clock_out,
					IFNULL(a.htlxxrh_kode, "-") as kondite,
					IFNULL(c.kode, "-") AS kode_spkl,
					c.jam_awal,
					c.jam_akhir
				FROM htsprrd AS a
				LEFT JOIN htssctd AS b ON b.id_hemxxmh = a.id_hemxxmh AND b.tanggal = a.tanggal
				LEFT JOIN htoxxrd AS c ON c.id_hemxxmh = a.id_hemxxmh AND c.tanggal = a.tanggal
				LEFT JOIN grup AS d ON d.id_hemxxmh = a.id_hemxxmh
				WHERE a.tanggal = :start_date AND a.id_hemxxmh = :id_hemxxmh
				'
				);
	$rs_report_presensi = $qs_report_presensi->fetchAll();

	$qs_riwayat_ceklok = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->exec('SELECT
					concat(b.kode, " - ", b.nama, " - ", d.nama) as nama,
					a.id_hemxxmh,
					a.jam,
					DATE_FORMAT(a.tanggal, "%d %b %Y") as tanggal,
					a.nama as mesin
				FROM htsprtd AS a
				LEFT JOIN hemxxmh AS b ON b.kode_finger = a.kode
				LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = b.id
				LEFT JOIN hetxxmh AS d ON d.id = c.id_hetxxmh
				WHERE a.tanggal BETWEEN DATE_SUB(:start_date, INTERVAL 1 DAY) AND DATE_ADD(:start_date, INTERVAL 2 DAY) AND b.id = :id_hemxxmh AND a.nama NOT IN ("makan", "istirahat", "makan manual");
				'
				);
	$rs_riwayat_ceklok = $qs_riwayat_ceklok->fetchAll();

	$qs_makan = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->exec('SELECT
					concat(b.kode, " - ", b.nama) as nama,
					a.id_hemxxmh,
					a.jam,
					DATE_FORMAT(a.tanggal, "%d %b %Y") as tanggal,
					a.nama as mesin
				FROM htsprtd AS a
				LEFT JOIN hemxxmh AS b ON b.kode_finger = a.kode
				WHERE a.tanggal BETWEEN :start_date AND CURDATE() AND b.id = :id_hemxxmh AND a.nama IN ("makan", "makan manual")
				LIMIT 3;
				'
				);
	$rs_makan = $qs_makan->fetchAll();

	$qs_istirahat = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->exec('SELECT
				concat(b.kode, " - ", b.nama) as nama,
					a.id_hemxxmh,
					a.jam,
					DATE_FORMAT(a.tanggal, "%d %b %Y") as tanggal,
					a.nama as mesin
				FROM htsprtd AS a
				LEFT JOIN hemxxmh AS b ON b.kode_finger = a.kode
				WHERE a.tanggal BETWEEN :start_date AND CURDATE() AND b.id = :id_hemxxmh AND a.nama = "istirahat"
				LIMIT 5;
				'
				);
	$rs_istirahat = $qs_istirahat->fetchAll();

	$results = array();

	if (!empty($rs_report_presensi)) {
		$results['data'] = $rs_report_presensi;
		$results['data2'] = $rs_riwayat_ceklok;
		$results['data3'] = $rs_makan;
		$results['data4'] = $rs_istirahat;
		$results['data5'] = $c_cek_satu;
		
		// harus urut sama tablenya
		$results['columns'] = [
			['data' => 'clock_in', 'name' => 'clock_in'],
			['data' => 'clock_out', 'name' => 'clock_out'],
			['data' => 'st_clock_in', 'name' => 'st_clock_in'],
			['data' => 'st_clock_out', 'name' => 'st_clock_out'],
			['data' => 'kondite', 'name' => 'kondite'],
			['data' => 'jam_awal', 'name' => 'jam_awal'],
			['data' => 'jam_akhir', 'name' => 'jam_akhir']
		];

		$results['columns2'] = [
			['data' => 'tanggal', 'name' => 'tanggal'],
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'mesin', 'name' => 'mesin']
		];

		$results['columns3'] = [
			['data' => 'tanggal', 'name' => 'tanggal'],
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'mesin', 'name' => 'mesin']
		];

		$results['columns4'] = [
			['data' => 'tanggal', 'name' => 'tanggal'],
			['data' => 'jam', 'name' => 'jam'],
			['data' => 'mesin', 'name' => 'mesin']
		];
		
		$results['columns5'] = [
			['data' => 'jam_wajib', 'name' => 'jam_wajib'],
			['data' => 'jam_kerja', 'name' => 'jam_kerja'],
			['data' => 'potong', 'name' => 'potong'],
			['data' => 'ti', 'name' => 'ti'],
			['data' => 'makan', 'name' => 'makan'],
			['data' => 'lembur', 'name' => 'lembur'],
			['data' => 'lembur15', 'name' => 'lembur15'],
			['data' => 'lembur2', 'name' => 'lembur2'],
			['data' => 'lembur3', 'name' => 'lembur3'],
			['data' => 'lembur4', 'name' => 'lembur4']
		];
		
	} else {
		$results['data'] = [];
		$results['columns'] = [];
	}

	echo json_encode($results);
?>