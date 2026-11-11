<?php
include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

// Establish a database connection here (replace with your actual database connection code)

// Your SQL query to retrieve the data
$start_date = $_POST['start_date'];
$id_hodxxmh = $_SESSION['id_hodxxmh'] ?? 0;

$qs_payroll = $db
    ->raw()
	->bind(':start_date', $start_date)
    ->exec('SELECT
                CONCAT(h.kode_finger, " - ", h.nama) AS peg,
                hob.nama bag,
                a.id_hemxxmh,
                b.kode,
                shift,
                day(a.tanggal) AS tanggal,
                asal_non_karyawan AS asal
            FROM htssctd AS a
            LEFT JOIN hemxxmh AS h ON h.id = a.id_hemxxmh
            LEFT JOIN hemjbmh AS j ON j.id_hemxxmh = a.id_hemxxmh
            LEFT JOIN hobxxmh AS hob ON hob.id = j.id_hobxxmh
            LEFT JOIN (
                SELECT 
                    sh.id,
                    sh.kode,
                    sh.kode AS shift
                FROM htsxxmh AS sh
            ) AS b ON b.id = a.id_htsxxmh

            WHERE YEAR(a.tanggal) = YEAR(:start_date) AND MONTH(a.tanggal) = MONTH(:start_date) AND a.is_active = 1 
            -- AND j.jumlah_grup = 2
            AND is_non_karyawan = 1
            AND h.is_active = 1

            AND (j.tanggal_keluar IS NULL OR j.tanggal_keluar > :start_date)
            GROUP BY a.id_hemxxmh, a.tanggal
            ORDER BY a.tanggal
            ;

    ');

$rs_payroll = $qs_payroll->fetchAll();

if (count($rs_payroll) > 0) {
    // Create an associative array to hold the pivoted data
    $pivotedData = [];

    foreach ($rs_payroll as $row) {
        $id_hemxxmh = $row['id_hemxxmh'];
        $peg = $row['peg'];
        $asal = $row['asal'];
        $bag = $row['bag'];
		// var_dump($peg);
        if (!isset($pivotedData[$id_hemxxmh])) {
            $pivotedData[$id_hemxxmh] = [
                'id_hemxxmh' => $id_hemxxmh,
                'Nama' => $peg,
                'Bagian' => $bag,
                'Asal' => $asal,
            ];
        }
		
        $shift = $row['shift'];
        $tanggal = $row['tanggal'];

        $pivotedData[$id_hemxxmh][$tanggal] = $shift;
    }

    // Convert the associative array into a numeric indexed array (optional)
    $pivotedData = array_values($pivotedData);

    // Create an array of column definitions
    $columns = [];
    foreach ($pivotedData[0] as $key => $value) {
        if ($key != 'id_hemxxmh') {
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
