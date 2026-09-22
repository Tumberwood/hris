<?php
	include( "../../../users/init.php" );
	include( "../../../usersc/lib/DataTables.php" );
	include( "../../../usersc/helpers/datatables_fn_debug.php" );
    require '../../../usersc/vendor/autoload.php';
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

		// Your secret key for JWT encoding and decoding
		// $secret_key = 'ferry123';
		// $pass = 'Bearer '.$secret_key;
		
		// Function to validate the JWT token from the request
		function validateToken() {
			global $secret_key;
			global $db;
		
			function getusername_header() {
				$username_header = null;
			
				// Check for the existence of indices before accessing them
				if (function_exists('apache_request_headers')) {
					$headers = apache_request_headers();
					if (isset($headers['Username'])) {
						$username_header = $headers['Username'];
					}
				}
				// Debugging information
				//  echo "apache_request_headers: " . print_r(apache_request_headers(), true) . "<br><br>";

				return $username_header;
			}
			
			function getpassword_header() {
				$password_header = null;
			
				// Check for the existence of indices before accessing them
				if (function_exists('apache_request_headers')) {
					$headers = apache_request_headers();
					if (isset($headers['Password'])) {
						$password_header = $headers['Password'];
					}
				}
				// Debugging information
				//  echo "apache_request_headers: " . print_r(apache_request_headers(), true) . "<br><br>";

				return $password_header;
			}
			
			$username_auth = getusername_header();
			$password_auth = getpassword_header();
			
			$remember = false;

			try {
				$user = new User();
    			$login = $user->loginEmail($username_auth, $password_auth, $remember);
				if ($login) {
					$decoded = array('HS256');
					return $decoded;
				} else {
					http_response_code(401);
					echo json_encode(array("message" => "Invalid token"));
					exit();
				}
				
				echo $token . '<br>';
				echo $credentials;
			} catch (Exception $e) {
				http_response_code(401);
				echo json_encode(array("message" => "Invalid token"));
				exit();
			}
		}
		
		// Validate the token before accessing any API endpoints
		validateToken();

class Employee 
{

	public  function get_all_emp()
	{
		global $db;
		
		$qs_emp = $db
			->raw()
			->exec(' SELECT
						a.id id_pegawai,
						a.id_users id_users,
						
						job.id_hovxxmh id_divisi,
						divisi.nama divisi,

						job.id_hodxxmh id_department,
						dep.nama department,

						job.id_hosxxmh id_section,
						hos.nama section,

						job.id_hobxxmh id_bagian,
						hob.nama bagian,

						job.id_hevxxmh id_level,
						hev.nama level,

						job.id_hetxxmh id_jabatan,
						het.nama jabatan,

						a.kode nik,
						a.nama nama_pegawai,
						a.gender jenis_kelamin,
						CASE
							WHEN (tanggal_keluar IS NULL OR tanggal_keluar >= CURDATE() ) then 1
							ELSE 0
						END is_active_pegawai,
						job.tanggal_masuk AS tanggal_join,
						job.tanggal_keluar AS tanggal_keluar
					FROM hemxxmh a
					JOIN hemjbmh job ON job.id_hemxxmh = a.id
					LEFT JOIN hodxxmh dep ON dep.id = job.id_hodxxmh
					LEFT JOIN hovxxmh divisi ON divisi.id = job.id_hovxxmh
					LEFT JOIN hetxxmh jab ON jab.id = job.id_hetxxmh
					LEFT JOIN hosxxmh hos ON hos.id = job.id_hosxxmh
					LEFT JOIN hobxxmh hob ON hob.id = job.id_hobxxmh
					LEFT JOIN hevxxmh hev ON hev.id = job.id_hevxxmh
					LEFT JOIN hetxxmh het ON het.id = job.id_hetxxmh
					WHERE 1
					AND is_harian_lepas = 0
            AND is_non_karyawan = 0
					ORDER BY a.is_active DESC, a.kode
					'
					);
		$result = $qs_emp->fetchAll();

		$data=array();
		$count = count($result);
	
		if ($count > 0){
			$data = $result;
		}
		
		$response=array(
			'status' => 1,
			'message' =>'Get List Employee Successfully.',
			'data' => $data
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	}

	public function get_emp($id=0)
	{
		global $db;
		$query= '';

		if($id != 0)
		{
			$query.=" WHERE a.id=".$id;
		}

		$qs_emp = $db
			->raw()
			->exec(' SELECT
						a.id id_pegawai,
						a.id_users id_users,
						
						job.id_hovxxmh id_divisi,
						divisi.nama divisi,

						job.id_hodxxmh id_department,
						dep.nama department,

						job.id_hosxxmh id_section,
						hos.nama section,

						job.id_hobxxmh id_bagian,
						hob.nama bagian,

						job.id_hevxxmh id_level,
						hev.nama level,

						job.id_hetxxmh id_jabatan,
						het.nama jabatan,

						a.kode nik,
						a.nama nama_pegawai,
						a.gender jenis_kelamin,
						CASE
							WHEN (tanggal_keluar IS NULL OR tanggal_keluar >= CURDATE() ) then 1
							ELSE 0
						END is_active_pegawai,
						job.tanggal_masuk AS tanggal_join,
						job.tanggal_keluar AS tanggal_keluar
					FROM hemxxmh a
					JOIN hemjbmh job ON job.id_hemxxmh = a.id
					LEFT JOIN hodxxmh dep ON dep.id = job.id_hodxxmh
					LEFT JOIN hovxxmh divisi ON divisi.id = job.id_hovxxmh
					LEFT JOIN hetxxmh jab ON jab.id = job.id_hetxxmh
					LEFT JOIN hosxxmh hos ON hos.id = job.id_hosxxmh
					LEFT JOIN hobxxmh hob ON hob.id = job.id_hobxxmh
					LEFT JOIN hevxxmh hev ON hev.id = job.id_hevxxmh
					LEFT JOIN hetxxmh het ON het.id = job.id_hetxxmh
					WHERE 1
					AND is_harian_lepas = 0
            AND is_non_karyawan = 0
					ORDER BY a.is_active DESC, a.kode
					'.$query.'
					'
					);
		$result = $qs_emp->fetchAll();
		
		$data=array();
		
		$count = count($result);
	
		if ($count > 0){
			$data = $result;
		}
		
		$response=array(
			'status' => 1,
			'message' =>'Get List Employee Successfully.',
			'data' => $data
		);
		header('Content-Type: application/json');
		echo json_encode($response);
		 
	}
}

 ?>