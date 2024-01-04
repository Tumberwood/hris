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
		
	//Cronjob Saldo Cuti (Dibuat per bulan)
	// Yang diinsertkan di detail absensi adalah yang belum pernah diinsert di tahun ini
	// Jadi pegawai baru akan dapat perhitungan sisa cuti dan pegawai yang sudah dihitung saldo cuti nya tidak akan diinsert ulang di tahun ini
	$qi_saldo = $db
		->raw()
		->exec('INSERT INTO htlxxrh (
					id_hemxxmh,
					saldo,
					tanggal,
					jumlah,
					nama
				)
				SELECT
					b.id_hemxxmh,
					IF( YEAR(b.tanggal_masuk) <> YEAR(CURDATE()) AND DATE_ADD(b.tanggal_masuk, INTERVAL 1 YEAR) < CURDATE(), 
						IF(
							TIMESTAMPDIFF(MONTH, b.tanggal_masuk, DATE_FORMAT(CURDATE(), "%Y-01-01")) >= 12,
							12,
							12 - MONTH(b.tanggal_masuk)
						),
						0
					) 
					AS saldo,
					CURDATE() AS tanggal,
					ifnull(c_id,0) AS c_id,
					"saldo"
				FROM
					hemxxmh AS a
				LEFT JOIN
					hemjbmh AS b ON b.id_hemxxmh = a.id

				LEFT JOIN (
					SELECT
						rh.id_hemxxmh,
						COUNT(rh.id) AS c_id
					FROM htlxxrh AS rh
					WHERE year(rh.tanggal) = YEAR(CURDATE()) AND rh.nama = "saldo"
					GROUP BY id_hemxxmh
				) AS c ON c.id_hemxxmh = a.id
				WHERE
					b.id_hesxxmh IN (1,5) AND b.id_heyxxmh = 1 AND a.is_active = 1
				GROUP BY id_hemxxmh
				HAVING c_id = 0
				;				
				'
		);

?>