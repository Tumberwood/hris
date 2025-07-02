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

	if (isset($_POST['id_htsprrd_htlxxmh_h']) && $_POST['id_htsprrd_htlxxmh_h'] > 0) {
		$id_htsprrd_htlxxmh_h = $_POST['id_htsprrd_htlxxmh_h'];
	
		$qs_htsprrd_htlxxmh_h = $db
			->query('select', 'htsprrd_htlxxmh_h' )
			->get([
				'tanggal_awal as start_date',
				'tanggal_akhir as end_date'
			] )
			->where('id', $id_htsprrd_htlxxmh_h )
			->exec();
		$rs_htsprrd_htlxxmh_h = $qs_htsprrd_htlxxmh_h->fetch();
	
		$start_date = $rs_htsprrd_htlxxmh_h['start_date'];
		$end_date = $rs_htsprrd_htlxxmh_h['end_date'];

		$user = $_SESSION['user'];
        if ($user > 100) {
            $w_id_heyxxmh_session = ' AND b.id_heyxxmh IN (' . $_SESSION['str_arr_ha_heyxxmh'] . ')';
        } else {
            $w_id_heyxxmh_session = ' AND b.id_heyxxmh NOT IN (-1)';
        }
		
		$qs_detail_upload = $db
			->raw()
			->bind(':id_htsprrd_htlxxmh_h', $id_htsprrd_htlxxmh_h)
			->exec('SELECT
						a.id,
						b.id_heyxxmh,
						d.nama status,
						a.id_hemxxmh,
						id_htsprrd_htlxxmh_h,
						a.kode,
						a.nama,
						al,
						s2,
						s3,
						it,
						IFNULL(ip_tdk_pot,0) + IFNULL(ip_pot,0) AS ip_pot,
						lb
					FROM htsprrd_htlxxmh_d a
					LEFT JOIN hemjbmh b ON b.id_hemxxmh = a.id_hemxxmh
					LEFT JOIN heyxxmh c ON c.id = b.id_heyxxmh
					LEFT JOIN heyxxmd d ON d.id = b.id_heyxxmd
					WHERE a.id_htsprrd_htlxxmh_h = :id_htsprrd_htlxxmh_h
		'.$w_id_heyxxmh_session);
		$dataRows = $qs_detail_upload->fetchAll();

		// echo $w_id_heyxxmh_session;
		
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
                            CASE WHEN a.status_presensi_in = "sdl" THEN 1
                            ELSE 0 END
						) s3,
						SUM(
                            CASE WHEN a.status_presensi_in = "IP" THEN 1
                            ELSE 0 END
						) it, -- Izin pribadi

						-- TL + PA + MK = ip_tdkpot  + ip_pot
						SUM(
                            CASE WHEN a.status_presensi_in = "TL" AND st_clock_in = "LATE" THEN 1
                            -- WHEN a.st_clock_in = "LATE" AND status_presensi_in NOT LIKE "%DL%" THEN 1
                            -- WHEN a.htlxxrh_kode LIKE "TL%" AND a.st_clock_in = "OK" THEN 1
                            ELSE 0 END
						) 
						+
						SUM(
                            CASE WHEN a.status_presensi_out = "PA" THEN 1
                            -- WHEN a.st_clock_out = "EARLY" AND status_presensi_out NOT LIKE "%DL%" THEN 1
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
								WHEN 
									a.status_presensi_out <> "MK" AND
                            	 	a.status_presensi_in <> "MK" AND
									a.status_presensi_in <> "TL" AND st_clock_in <> "LATE" AND
									a.st_clock_in <> "LATE" AND
									a.status_presensi_out <> "PA" AND
									a.st_clock_out <> "EARLY" AND
									a.pot_hk > 0 THEN 1
                            	ELSE 0 
							END
						) 
						AS ip_pot,
						SUM(
                            CASE WHEN a.status_presensi_in = "lb" THEN 1
                            ELSE 0 END
						) lb
					FROM htsprrd a
					LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
					WHERE a.tanggal BETWEEN :start_date AND :end_date AND a.is_active = 1
					GROUP BY a.id_hemxxmh
		');
		$rs_data_sql = $qs_data_sql->fetchAll();
	
		$sqlData = [];	
		foreach ($rs_data_sql as $row) {
			$sqlData[$row['kode']] = $row;
		}
	
		$pivotData = array_map(function ($row) use ($sqlData, $start_date, $end_date) {
			$kode = $row['kode'];
			$sqlRow = $sqlData[$kode] ?? ["al" => 0, "s2" => 0, "s3" => 0, "it" => 0, "ip_pot" => 0, "lb" => 0];
			$total_xl = $row['al'] + $row['s2'] + $row['s3'] + $row['it'] + $row['ip_pot'] + $row['lb'];
			$total_db = $sqlRow['al'] + $sqlRow['s2'] + $sqlRow['s3'] + $sqlRow['it'] + $sqlRow['ip_pot'] + $sqlRow['lb'];
	
			if ($total_xl == $total_db) {
				$is_tidak_sesuai = 0;
			} else {
				$is_tidak_sesuai = 1;
			}
			
			return [
				"id"					=> $row['id'],
				"id_heyxxmh"			=> $row['id_heyxxmh'],
				"id_hemxxmh"			=> $row['id_hemxxmh'],
				"start_date"			=> $start_date,
				"end_date"				=> $end_date,
				"status"				=> $row['status'],
				"id_htsprrd_htlxxmh_h"	=> $row['id_htsprrd_htlxxmh_h'],
				"kode"        			=> $kode,
				"nama"        			=> $row['nama'],
				"al_xl" 				=> $row['al'],
				"al_db" 				=> $sqlRow['al'],  
				"s2_xl"  				=> $row['s2'],
				"s2_db"  				=> $sqlRow['s2'],   
				"s3_xl"  				=> $row['s3'],
				"s3_db"  				=> $sqlRow['s3'],   
				"it_xl"  				=> $row['it'],
				"it_db"  				=> $sqlRow['it'],   
				"ip_pot_xl"  				=> $row['ip_pot'],
				"ip_pot_db"  				=> $sqlRow['ip_pot'],   
				"lb_xl"    				=> $row['lb'],
				"lb_db"    				=> $sqlRow['lb'],     

				"total_xl"    			=> $total_xl,
				"total_db"    			=> $total_db,
				"is_tidak_sesuai"    	=> $is_tidak_sesuai
			];
			
		}, $dataRows);
	} else {
		$pivotData = [];
	}
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"lembur" => $pivotData  
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