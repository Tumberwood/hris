<?php
	ini_set('max_execution_time', 1356);
	ini_set('memory_limit','1024M');
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	$filename = currentPage();
	// $db = DB::getInstance(); //bawaan userspice tidak perlu dipakai
	
	// Import PHPMailer classes into the global namespace
	// These must be at the top of your script, not inside a function
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\SMTP;
	// use PHPMailer\PHPMailer\Exception;

	// Load Composer's autoloader
    require '../../../../usersc/vendor/autoload.php';

	// Instantiation and passing `true` enables exceptions
	// $mail = new PHPMailer(true);

	// ------------- end of loading phpmailer ------------- //
	
	
	// membatasi untuk run cronjob hanya dari server 		
	// $ip = ipCheck();
	// logger(1,"CronRequest","Cron request from $ip.");
	// $settings = $db->query("SELECT cron_ip FROM settings")->first();
	
	// if($settings->cron_ip != ''){
	// 	if($ip != $settings->cron_ip && $ip != '127.0.0.1'){
	// 	logger(1,"CronRequest","Cron request DENIED from $ip.");
	// 	die;
	// 	}
	// }
	// $errors = $successes = [];
	
	//tidak perlu
	//your code goes here...
	//do whatever you want to do and it will be run automatically when the cron job is triggered.
	// $user_id = 1; //just for testing purposes. Most cron jobs won't have a logged in user.
	
	$nama = array();
	//cari yang sudah perpanjang status 30 hari sebelum today
	$qs_no_jadwal = $db
		->raw()
		->exec('WITH RECURSIVE date_range AS (
					SELECT DATE(DATE_FORMAT(CURDATE(), "%Y-%m-01")) AS senjum
					UNION ALL
					SELECT DATE_ADD(senjum, INTERVAL 1 DAY)
					FROM date_range
					WHERE senjum < LAST_DAY(CURDATE())
				)
				SELECT
					CONCAT(hem.kode, " - ", hem.nama) AS peg,
					CONCAT(DATE_FORMAT(CURDATE(), "01 %b %Y"), " s/d ", DATE_FORMAT(LAST_DAY(CURDATE()), "%d %b %Y")) AS periode,
					a.id_hemxxmh,
					senjum AS tanggal,
					jb.tanggal_masuk,
					if(jb.tanggal_masuk >= senjum, "Tercatat", 
						CASE
							WHEN htssctd.tanggal = senjum THEN "Tercatat"
							ELSE "No Jadwal"
						END
					) AS status
				FROM (
					SELECT DISTINCT id_hemxxmh
					FROM htssctd
					WHERE tanggal BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) AND is_active = 1
				) AS a
				CROSS JOIN date_range
				LEFT JOIN htssctd ON a.id_hemxxmh = htssctd.id_hemxxmh AND htssctd.tanggal = senjum
				LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
				LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = hem.id
				WHERE a.id_hemxxmh > 0
				GROUP BY a.id_hemxxmh, status
				HAVING STATUS = "No Jadwal"
				ORDER BY id_hemxxmh, senjum
				;
		
				'
				);
	$rs_no_jadwal = $qs_no_jadwal->fetchAll();
	$c_no_jadwal = count($rs_no_jadwal);

	if ($c_no_jadwal > 0) {
		$pesan = 'Terdapat <b>'.$c_no_jadwal.'</b> karyawan pada periode <b>'.$rs_no_jadwal[0]['periode'].'</b> yang jadwalnya belum lengkap!!!';
		$full_message = '<a class="text-danger" href="'.$us_url_root.'usersc/applications/views/htssctd/notif_jadwal.php" target="_blank">' . $pesan . '</a>';
		echo $full_message;

		$qi_notif = $db
		->raw()
		->bind(':full_message', $full_message)
		->exec('INSERT INTO notifications (
					user_id,
					message,
					date_created
				)
				SELECT
					b.id_users_penerima,
					:full_message,
					CURDATE() AS date_created
				FROM gntxxsh AS a
				LEFT JOIN gntussd AS b ON b.id_gntxxsh = a.id 
				WHERE a.nama = "Jadwal Belum Dibuat";
				;
				'
				);
	}
	//your code ends here.
	
	//diganti datatables biasa di table cronjob
	$from = Input::get('from');
	if($from != NULL && $currentPage == $filename) {
		$qi_ = $db
			->query('insert', 'crons_logs_si')
			->set('name', "emailserver")
			->set('created_by',1)
			->set('created_on',$today)
			->exec();
	}

?>