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
	//NON AKTIFKAN PEGAWAI TANGGAL KELUAR 2 BULAN Yang Lalu
	
	//db raw template db raw template raw
	$qs_peg = $db
		->raw()
		->exec(' SELECT
					COUNT(a.id) AS c_peg,
					DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), "%d %b %Y") AS tanggal
				FROM hemxxmh AS a
				LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
				WHERE b.tanggal_keluar < DATE_SUB(CURDATE(), INTERVAL 2 MONTH) AND a.is_active = 1
				'
				);
	$rs_peg = $qs_peg->fetch();

	if ($rs_peg['c_peg'] > 0) {
		$pesan = 'Terdapat <b>'.$rs_peg['c_peg'].'</b> karyawan yang keluar <b>2 Bulan yang lalu </b>atau dibawah tanggal <b>'.$rs_peg['tanggal'].'</b> telah otomatis di non aktifkan!';
		$full_message = '<p>' . $pesan . '</p>';
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
					NOW() AS date_created
				FROM gntxxsh AS a
				LEFT JOIN gntussd AS b ON b.id_gntxxsh = a.id 
				WHERE a.nama = "Nonaktif Pegawai Keluar 2 Bulan Yang Lalu";
				;
				'
				);
	
		$qs_non_aktif = $db
			->raw()
			->exec('UPDATE hemxxmh AS a
					LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
					SET a.is_active = 0
					WHERE b.tanggal_keluar < DATE_SUB(CURDATE(), INTERVAL 2 MONTH) AND a.is_active = 1 AND id_hesxxmh <> 4;
					;
					'
					);
		//your code ends here.
		
		$today = date('Y-m-d H:i:s');
	
		$qi_cronjob = $db
			->query('insert', 'crons_logs_si')
			->set('nama', "nonaktif_pegawai_2_bulan")
			->set('created_by',1)
			->set('created_on',$today)
			->exec();
	}

?>