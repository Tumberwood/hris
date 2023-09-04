<?php
	ini_set('max_execution_time', 1356);
	ini_set('memory_limit','1024M');
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	$filename = currentPage();
	// $db = DB::getInstance(); //bawaan userspice tidak perlu dipakai
	
	// Import PHPMailer classes into the global namespace
	// These must be at the top of your script, not inside a function
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	// Load Composer's autoloader
    require '../../../../usersc/vendor/autoload.php';

	// Instantiation and passing `true` enables exceptions
	$mail = new PHPMailer(true);

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
	$user_id = 1; //just for testing purposes. Most cron jobs won't have a logged in user.
	
	$nama = array();
	//cari yang sudah perpanjang status 30 hari sebelum today
	$qs_sudah_status = $db
		->raw()
		->exec('SELECT
					hem.nama
				FROM hesxxtd AS hes
				LEFT JOIN hemxxmh AS hem ON hem.id = hes.id_hemxxmh
				WHERE hes.tanggal_mulai >= DATE_SUB(NOW(), INTERVAL 30 DAY)
				AND hes.is_approve = 1
				'
				);
	$rs_sudah_status = $qs_sudah_status->fetchAll();

	foreach ($rs_sudah_status as $key => $sudah_status) {
		$nama[] = $sudah_status['nama'];
	}
	$sudah_perpanjang = '"' . implode('", "', $nama) . '"';
	// echo $sudah_perpanjang;
		
	$sql_select_kontrakexpired = $db
		->raw()
		// ->bind(':sudah_perpanjang', $sudah_perpanjang)
		->exec('SELECT
					a.id,
					b.kode AS nik,
					b.nama AS nama_lengkap,
					c.nama AS namamhpegawaistatus,
					d.nama AS jabatan,
					e.nama AS department,
					DATE_FORMAT(a.tanggal_awal, "%d %b %Y") as start_date,
					DATE_FORMAT(
						COALESCE(
							a.tanggal_akhir,
							DATE_ADD(a.tanggal_awal, INTERVAL 6 MONTH)
						),
						"%d %b %Y"
					) as end_date,
					TIMESTAMPDIFF(
						DAY,
						NOW(),
						COALESCE(
							a.tanggal_akhir,
							DATE_ADD(a.tanggal_awal, INTERVAL 6 MONTH)
						)
					) + 1 AS selisih_hari
				FROM hemjbrd AS a
				LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
				LEFT JOIN hesxxmh AS c ON c.id = a.id_hesxxmh
				LEFT JOIN hetxxmh AS d ON d.id = a.id_hetxxmh_awal
				LEFT JOIN hodxxmh AS e ON e.id = a.id_hodxxmh_awal
				WHERE (
					COALESCE(
						a.tanggal_akhir,
						DATE_ADD(a.tanggal_awal, INTERVAL 6 MONTH)
					) > NOW()
				)
				AND TIMESTAMPDIFF(
					DAY,
					NOW(),
					COALESCE(
						a.tanggal_akhir,
						DATE_ADD(a.tanggal_awal, INTERVAL 6 MONTH)
					)
				) <= 30
				AND a.id_harxxmh <> 3
				AND a.id_hesxxmh NOT IN (1,5)
				AND b.is_active = 1
				AND a.is_email_status = 1
				AND b.nama NOT IN (' . $sudah_perpanjang . ')
				ORDER BY selisih_hari ASC;
				'
				);
	$result_select_kontrakexpired = $sql_select_kontrakexpired->fetchAll();
	$c_select_kontrakexpired      = count($result_select_kontrakexpired);

	// echo $c_select_kontrakexpired;

	if($c_select_kontrakexpired > 0){
		$hariini = date('d M Y');
		$today = date('Y-m-d H:i:s');
		
		try{
			// generate body email as table 
			
			// build table
			$body = 'Berikut ini adalah nama-nama Karyawan yang akan segera habis masa kontraknya. </br>';
			$body = $body . '<table style="border-collapse: collapse; width:100%">';
				$body = $body .  '<thead>';
					$body = $body .  '<tr>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">NIK</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Nama Karyawan</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Status</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Department</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Jabatan</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Awal</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Akhir</th>';
						$body = $body .  '<th style="border: 1px solid #000; padding:5px">Sisa Hari</th>';
					$body = $body .  '</tr>';
				$body = $body .  '</thead>';
				$body = $body .  '<tbody>';
					//for ($x = 0; $x < $c_select_kontrakexpired; $x++) {
					foreach ($result_select_kontrakexpired as $key => $value) {
						$textColor = ''; // Default text color
						
						// Check the value of selisih_hari and set the text color accordingly
						if ($value['selisih_hari'] >= 1 && $value['selisih_hari'] <= 7) {
							$textColor = 'color: red;';
						} else if ($value['selisih_hari'] >= 8 && $value['selisih_hari'] <= 15) {
							$textColor = 'color: orange;';
						} else {
							$textColor = 'color: black;';
						}
						
						$body .= '<tr>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['nik'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['nama_lengkap'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['namamhpegawaistatus'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['department'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['jabatan'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['start_date'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['end_date'] . '</td>';
						$body .= '<td style="border: 1px solid #000; padding:5px; vertical-align:top; ' . $textColor . '">' . $value['selisih_hari'] . '</td>';
						$body .= '</tr>';
					}
						
				$body = $body .  '</tbody>';
			$body = $body .  '</table>';
			$body = $body .  '<br> <p>Terkirim otomatis dari HRIS </p>';
			// echo $body;
			
			include( "../../../../usersc/helpers/fn_mailserver.php" );		
			// generate email
			
			// $mail->setFrom('noreply@solusi-indonesia.com', 'HRIS PMI');
			// $mail->addAddress('recruitment@putramandiri-intipack.com');
			// $mail->addCC('hrd@putramandiri-intipack.com');
			// $mail->addBCC('danny.harjono@solusi-indonesia.com');

			//Testing by Ferry 01 Sep 23
			$mail->setFrom('noreply@solusi-indonesia.com', 'HRIS PMI');
			$mail->addAddress('ferryibnu86@gmail.com');
			$mail->addBCC('danny.harjono@solusi-indonesia.com');

					
			$mail->isHTML(true);
			$mail->Subject = 'Reminder Kontrak Kerja Karyawan ' . $hariini;
			$mail->Body    = $body;
			
			// sending email
			$mail->send();	
			
			
			// echo json_encode("OK");
		} catch (Exception $e) {
			// echo json_encode($errors);
		}
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