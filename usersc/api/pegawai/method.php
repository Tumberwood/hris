<?php
	include( "../../../users/init.php" );
	include( "../../../usersc/lib/DataTables.php" );
	include( "../../../usersc/helpers/datatables_fn_debug.php" );
    require '../../../usersc/vendor/autoload.php';
	
	// Ambil konfigurasi dari init.php
	$mysqlConfig = $GLOBALS['config']['mysql'];

	// deklarasikan variable seperti host, username, password, dan database
	$host = $mysqlConfig['host'];
	$username = $mysqlConfig['username'];
	$password = $mysqlConfig['password'];
	$database = $mysqlConfig['db'];

	// deklarasi mysqli dengan variable tadi
	$mysqli = new mysqli($host, $username, $password, $database);


class Employee 
{

	public  function get_all_emp()
	{
		global $mysqli;
		$query="
			SELECT
				a.id AS id_pegawai,
				a.id_users AS id_user,
				a.kode_finger AS id_checkclock,
				b.id_gcpxxmh AS id_company,
				b.id_gbrxxmh AS id_cabang,
				b.id_hovxxmh AS id_divisi,
				c.nama AS divisi,
				b.id_hodxxmh AS id_department,
				d.nama AS department,
				b.id_hosxxmh AS id_section,
				e.nama AS section,
				b.id_hobxxmh AS id_bagian,
				f.nama AS bagian,
				b.id_hevxxmh AS id_level,
				g.nama AS LEVEL,
				b.id_hetxxmh AS id_jabatan,
				h.nama AS jabatan,
				b.id_heyxxmh AS id_tipe,
				i.nama AS tipe,
				b.id_heyxxmd AS id_sub_tipe,
				j.nama AS sub_tipe,
				b.id_hesxxmh AS id_status,
				k.nama AS STATUS,
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
			) resign ON resign.id_hemxxmh = a.id";
		$data=array();
		$result=$mysqli->query($query);
		while($row=mysqli_fetch_object($result))
		{
			$data[]=$row;
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
		global $mysqli;
		$query="
			SELECT
				a.id AS id_pegawai,
				a.id_users AS id_user,
				a.kode_finger AS id_checkclock,
				b.id_gcpxxmh AS id_company,
				b.id_gbrxxmh AS id_cabang,
				b.id_hovxxmh AS id_divisi,
				c.nama AS divisi,
				b.id_hodxxmh AS id_department,
				d.nama AS department,
				b.id_hosxxmh AS id_section,
				e.nama AS section,
				b.id_hobxxmh AS id_bagian,
				f.nama AS bagian,
				b.id_hevxxmh AS id_level,
				g.nama AS LEVEL,
				b.id_hetxxmh AS id_jabatan,
				h.nama AS jabatan,
				b.id_heyxxmh AS id_tipe,
				i.nama AS tipe,
				b.id_heyxxmd AS id_sub_tipe,
				j.nama AS sub_tipe,
				b.id_hesxxmh AS id_status,
				k.nama AS STATUS,
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
			) resign ON resign.id_hemxxmh = a.id";
		if($id != 0)
		{
			$query.=" WHERE a.id=".$id." LIMIT 1";
		}
		$data=array();
		$result=$mysqli->query($query);
		while($row=mysqli_fetch_object($result))
		{
			$data[]=$row;
		}
		$response=array(
							'status' => 1,
							'message' =>'Get Employee Successfully.',
							'data' => $data
						);
		header('Content-Type: application/json');
		echo json_encode($response);
		 
	}
}

 ?>