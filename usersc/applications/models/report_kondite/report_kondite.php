<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$qs_data_sql = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					a.id_hemxxmh,
					TRIM(b.kode) AS kode,
					b.nama,
					SUM(
						CASE WHEN a.status_presensi_in = "AL" THEN 1
						ELSE 0 END
					) al,
					SUM(
						CASE WHEN a.status_presensi_in = "SK" THEN 1
						ELSE 0 END
					) s2,
					SUM(
						CASE WHEN a.status_presensi_in IN ("SDL", "S3") THEN 1 -- s3
						ELSE 0 END
					) s3,
					SUM(
						-- Pastikan bukan IP izin Baru (Izin Pulang),tapi IP Absen (Izin Pribadi)
						CASE WHEN (a.status_presensi_in = "IP" AND a.status_presensi_out = "IP") OR (a.status_presensi_in = "IT" AND a.status_presensi_out = "IT") THEN 1 -- it
						ELSE 0 END
					) it, -- Izin pribadi

					-- TL + PA + MK = ip_tdkpot  + ip_pot
					SUM(
						CASE WHEN a.status_presensi_in = "TL" AND st_clock_in = "LATE" AND pot_hk > 0 THEN 1
						-- WHEN a.st_clock_in = "LATE" AND status_presensi_in NOT LIKE "%DL%" THEN 1
						WHEN a.st_clock_in = "LATE" AND htlxxrh_kode = "" THEN 1
						-- WHEN a.htlxxrh_kode LIKE "TL%" AND a.st_clock_in = "OK" THEN 1
						ELSE 0 END
					) 
					+
					SUM(
						CASE WHEN a.status_presensi_out = "PA" OR (a.status_presensi_in <> "IP" AND a.status_presensi_out = "IP") THEN 1 -- IP
						-- WHEN a.st_clock_out = "EARLY" AND status_presensi_out NOT LIKE "%DL%" THEN 1
						WHEN a.st_clock_out = "EARLY" AND htlxxrh_kode = "" THEN 1
						-- WHEN a.htlxxrh_kode LIKE "PA%" AND a.st_clock_out = "OK" THEN 1
						ELSE 0 END
					) 
					+
					SUM(
						CASE 
							WHEN a.status_presensi_out = "MK" THEN 1
							WHEN a.status_presensi_in = "MK" THEN 1
							ELSE 0 
						END
					) 
					+
					SUM(
						CASE 
							WHEN a.pot_hk > 0 
								AND NOT (
									(a.status_presensi_in = "TL" AND st_clock_in = "LATE")
									-- OR (a.st_clock_in = "LATE" AND a.status_presensi_in NOT LIKE "%DL%")
									OR (a.st_clock_in = "LATE" AND htlxxrh_kode = "")
									OR (a.status_presensi_out = "PA" OR (a.status_presensi_in <> "IP" AND a.status_presensi_out = "IP"))
									-- OR (a.st_clock_out = "EARLY" AND a.status_presensi_out NOT LIKE "%DL%")
									OR (a.st_clock_out = "EARLY" AND htlxxrh_kode = "")
									OR (a.status_presensi_in = "MK" OR a.status_presensi_out = "MK")
								)
							THEN 1
							ELSE 0
						END
					) 
					AS ip_pot,
					SUM(
						CASE WHEN a.status_presensi_in = "lb" THEN 1
							WHEN a.keterangan LIKE "lb%" THEN 1
						ELSE 0 END
					) lb
				FROM htsprrd a
				LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date AND a.is_active = 1
				GROUP BY a.id_hemxxmh
	');
	$rs_data_sql = $qs_data_sql->fetchAll();
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"lembur" => $rs_data_sql  
	];

    // BEGIN results akhir
	$is_debug = true;
    if($is_debug == true){
        $results = array(
            "debug" => $debug,
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }else{
        $results = array(
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }
    // END results akhir

    echo json_encode($results);

?>