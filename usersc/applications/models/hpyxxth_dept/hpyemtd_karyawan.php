<?php
	// tes webhook
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	
	if(!isset($_POST['id_hpyxxth']) ){
		$id_hpyxxth = 0;
	} else {
		$id_hpyxxth = $_POST['id_hpyxxth'];
	}

	$qs_htsprrd = $db
		->raw()
		->bind(':id_hpyxxth', $id_hpyxxth)
		->exec('SELECT
					dep.id,
					dep.nama dept,
					SUM(gp) AS gp,
					SUM(t_jab) AS t_jab,
					SUM(terima_lain) AS terima_lain,
					SUM(var_cost) AS var_cost,
					SUM(tj_khusus) AS tj_khusus,
					SUM(fix_cost) AS fix_cost,
					SUM(premi_abs) AS premi_abs,
					SUM(lembur15) AS lembur15,
					SUM(rp_lembur15) AS rp_lembur15,
					SUM(lembur2) AS lembur2,
					SUM(rp_lembur2) AS rp_lembur2,
					SUM(lembur3) AS lembur3,
					SUM(rp_lembur3) AS rp_lembur3,
					SUM(total_lembur_jam_final) AS total_lembur_jam_final,
					SUM(total_rp_lembur) AS total_rp_lembur,
					SUM(komp_rekontrak) AS komp_rekontrak,
					SUM(cuti_tahunan) AS cuti_tahunan,
					SUM(cuti_bersama) AS cuti_bersama,
					SUM(sisa_cuti_hari) AS sisa_cuti_hari,
					SUM(komp_sisa_cuti) AS komp_sisa_cuti,
					SUM(thr) AS thr,
					SUM(pot_makan) AS pot_makan,
					SUM(c_pot_upah) AS c_pot_upah,
					SUM(pot_upah) AS pot_upah,
					SUM(c_pot_resign) AS c_pot_resign,
					SUM(pot_resign) AS pot_resign,
					SUM(c_pot_jam) AS c_pot_jam,
					SUM(pot_jam) AS pot_jam,
					SUM(pendapatan_lain_before_pph) AS pendapatan_lain_before_pph,
					SUM(pot_lain_before_pph) AS pot_lain_before_pph,
					SUM(bpjs_kes_perusahaan) AS bpjs_kes_perusahaan,
					SUM(jkk) AS jkk,
					SUM(jkm) AS jkm,
					SUM(bruto) AS bruto,
					SUM(persen_ter) AS persen_ter,
					SUM(pot_pph21) AS pot_pph21,
					SUM(after_pph21) AS after_pph21,
					SUM(jht_perusahaan) AS jht_perusahaan,
					SUM(jp_perusahaan) AS jp_perusahaan,
					SUM(pot_jht_karyawan) AS pot_jht_karyawan,
					SUM(pot_jp_karyawan) AS pot_jp_karyawan,
					SUM(bpjs_kes_karyawan) AS bpjs_kes_karyawan,
					SUM(bpjs_kes_perusahaan) AS bpjs_kes_perusahaan,
					SUM(jkk) AS jkk,
					SUM(jkm) AS jkm,
					SUM(pot_piutang) AS pot_piutang,
					SUM(denda_apd) AS denda_apd,
					SUM(iuran_spsi) AS iuran_spsi,
					SUM(pendapatan_lain_after_pph) AS pendapatan_lain_after_pph,
					SUM(pot_lain_after_pph) AS pot_lain_after_pph,
					SUM(gaji_bersih) AS gaji_bersih,
					SUM(bulat) AS bulat,
					SUM(gaji_terima) AS gaji_terima
				FROM hpyemtd a
				LEFT JOIN hemjbmh c on c.id_hemxxmh = a.id_hemxxmh
				LEFT JOIN hodxxmh dep on dep.id = c.id_hodxxmh
				WHERE a.id_hpyxxth = :id_hpyxxth
				GROUP BY id_hodxxmh
				'
		);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>