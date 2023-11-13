<?php
include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

// Establish a database connection here (replace with your actual database connection code)

// Your SQL query to retrieve the data
$id_hpyxxth_2 = 3;
if (isset($_POST['id_hpyxxth_2']) && $_POST['id_hpyxxth_2'] > 0) {
	$id_hpyxxth_2 = $_POST['id_hpyxxth_2'];
}
$qs_payroll = $db
    ->raw()
	->bind(':id_hpyxxth_2', $id_hpyxxth_2)
    ->exec('SELECT
                a.id_hemxxmh,
                b.nama,
				concat(c.kode, " - ", c.nama) as peg,
                a.id_hpcxxmh,
                a.nominal,
                a.jenis
            FROM hpyemtd_2 AS a
            LEFT JOIN hpcxxmh AS b ON b.id = a.id_hpcxxmh
            LEFT JOIN hemxxmh AS c ON c.id = a.id_hemxxmh
            WHERE a.id_hpyxxth_2 = :id_hpyxxth_2
			ORDER BY a.id asc
    ');

$rs_payroll = $qs_payroll->fetchAll();

if (count($rs_payroll) > 0) {
    // Create an associative array to hold the pivoted data
    $pivotedData = [];

    foreach ($rs_payroll as $row) {
        $id_hemxxmh = $row['id_hemxxmh'];
        $peg = $row['peg'];
		// var_dump($peg);
        if (!isset($pivotedData[$id_hemxxmh])) {
            $pivotedData[$id_hemxxmh] = [
                'id_hemxxmh' => $id_hemxxmh,
                '9Nama' => $peg
            ];
        }
		
        $nama = $row['nama'];
        $nominal = $row['nominal'];
        $jenis = $row['jenis'];

        $pivotedData[$id_hemxxmh][$jenis . $nama] = $nominal;
    }

    // Convert the associative array into a numeric indexed array (optional)
    $pivotedData = array_values($pivotedData);

    // Create an array of column definitions
    $columns = [];
    foreach ($pivotedData[0] as $key => $value) {
        if ($key != 'jenis' && $key != 'id_hemxxmh') {
            $columns[] = ['data' => $key, 'name' => $key];
        }
    }

    // Prepare the results in the desired format
    $results = [
        'data' => $pivotedData,
        'columns' => $columns
    ];

    // Output the results as JSON
    echo json_encode($results);
} else {
    // If no results found, prepare an empty result
    $results = [
        'data' => [],
        'columns' => []
    ];

    echo json_encode($results);
}

// Close the database connection here (replace with your actual code)
?>
