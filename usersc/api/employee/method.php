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
					if (isset($headers['username'])) {
						$username_header = $headers['username'];
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
					if (isset($headers['password'])) {
						$password_header = $headers['password'];
					}
				}
				// Debugging information
				//  echo "apache_request_headers: " . print_r(apache_request_headers(), true) . "<br><br>";

				return $password_header;
			}
			
			if (!empty($_SERVER['PHP_AUTH_USER'])) {
				$username_auth = $_SERVER['PHP_AUTH_USER'];
				$password_auth = $_SERVER['PHP_AUTH_PW'];
			} else if (function_exists('apache_request_headers')) {
				$username_auth = getusername_header();
				$password_auth = getpassword_header();
			} else {
				$username_auth = '';
				$password_auth = '';
			}

			echo $username_auth;
			echo $password_auth;

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
						a.id AS id_pegawai,
						a.id_users AS id_user,
						a.kode_finger AS id_checkclock,
						b.id_hovxxmh AS id_divisi,
						c.nama AS divisi,
						b.id_hodxxmh AS id_department,
						d.nama AS department,
						b.id_hosxxmh AS id_section,
						e.nama AS section,
						b.id_hobxxmh AS id_bagian,
						f.nama AS bagian,
						b.id_hevxxmh AS id_level,
						g.nama AS level,
						b.id_hetxxmh AS id_jabatan,
						h.nama AS jabatan,
						a.kode AS nik,
						a.nama AS nama_pegawai,
						a.gender AS jenis_kelamin,
						a.is_active AS is_active_pegawai,
						b.tanggal_masuk AS tanggal_join,
						b.tanggal_keluar AS tanggal_keluar,
						ifnull(is_terminasi, 0) AS is_resign
					FROM hemxxmh as a
					left JOIN hemjbmh as b ON b.id_hemxxmh = a.id
					LEFT JOIN hovxxmh AS c ON c.id = b.id_hovxxmh
					LEFT JOIN hodxxmh AS d ON d.id = b.id_hodxxmh
					LEFT JOIN hosxxmh AS e ON e.id = b.id_hosxxmh
					LEFT JOIN hobxxmh AS f ON f.id = b.id_hobxxmh
					LEFT JOIN hevxxmh AS g ON g.id = b.id_hevxxmh
					LEFT JOIN hetxxmh AS h ON h.id = b.id_hetxxmh
					LEFT JOIN heyxxmh AS i ON i.id = b.id_heyxxmh
					LEFT JOIN heyxxmd AS j ON j.id = b.id_heyxxmd
					LEFT JOIN hesxxmh AS k ON k.id = b.id_hesxxmh
					LEFT JOIN (
					SELECT
						id_hemxxmh,
						IFNULL(is_terminasi, 0) AS is_terminasi
					FROM (
						SELECT
							id_hemxxmh,
							COUNT(id) AS is_terminasi
						FROM hemjbrd
						WHERE id_harxxmh IN (3, 4)
						GROUP BY id_hemxxmh
					) AS subquery
					) resign ON resign.id_hemxxmh = a.id
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
						a.id AS id_pegawai,
						a.id_users AS id_user,
						a.kode_finger AS id_checkclock,
						b.id_hovxxmh AS id_divisi,
						c.nama AS divisi,
						b.id_hodxxmh AS id_department,
						d.nama AS department,
						b.id_hosxxmh AS id_section,
						e.nama AS section,
						b.id_hobxxmh AS id_bagian,
						f.nama AS bagian,
						b.id_hevxxmh AS id_level,
						g.nama AS level,
						b.id_hetxxmh AS id_jabatan,
						h.nama AS jabatan,
						a.kode AS nik,
						a.nama AS nama_pegawai,
						a.gender AS jenis_kelamin,
						a.is_active AS is_active_pegawai,
						b.tanggal_masuk AS tanggal_join,
						b.tanggal_keluar AS tanggal_keluar,
						ifnull(is_terminasi, 0) AS is_resign
					FROM hemxxmh as a
					left JOIN hemjbmh as b ON b.id_hemxxmh = a.id
					LEFT JOIN hovxxmh AS c ON c.id = b.id_hovxxmh
					LEFT JOIN hodxxmh AS d ON d.id = b.id_hodxxmh
					LEFT JOIN hosxxmh AS e ON e.id = b.id_hosxxmh
					LEFT JOIN hobxxmh AS f ON f.id = b.id_hobxxmh
					LEFT JOIN hevxxmh AS g ON g.id = b.id_hevxxmh
					LEFT JOIN hetxxmh AS h ON h.id = b.id_hetxxmh
					LEFT JOIN heyxxmh AS i ON i.id = b.id_heyxxmh
					LEFT JOIN heyxxmd AS j ON j.id = b.id_heyxxmd
					LEFT JOIN hesxxmh AS k ON k.id = b.id_hesxxmh
					LEFT JOIN (
					SELECT
						id_hemxxmh,
						IFNULL(is_terminasi, 0) AS is_terminasi
					FROM (
						SELECT
							id_hemxxmh,
							COUNT(id) AS is_terminasi
						FROM hemjbrd
						WHERE id_harxxmh IN (3, 4)
						GROUP BY id_hemxxmh
					) AS subquery
					) resign ON resign.id_hemxxmh = a.id
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