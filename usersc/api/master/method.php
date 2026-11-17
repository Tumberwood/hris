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

	public  function get_departemen()
	{
		global $db;
		
		$qs_emp = $db
			->raw()
			->exec('SELECT
						a.id,
						a.kode,
						a.nama,
						a.keterangan
					FROM hodxxmh a
					WHERE a.is_active = 1
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

	public  function get_unit_kerja()
	{
		global $db;
		
		$qs_emp = $db
			->raw()
			->exec('SELECT
						a.id,
						a.kode,
						a.nama,
						a.keterangan
					FROM hosxxmh a
					WHERE a.is_active = 1
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
			'message' =>'Get List Master Successfully.',
			'data' => $data
		);
		header('Content-Type: application/json');
		echo json_encode($response);
	}
}

 ?>