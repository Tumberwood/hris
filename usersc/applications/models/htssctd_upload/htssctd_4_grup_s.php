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
	->bind(':id_hodxxmh', $id_hodxxmh)
    ->exec('SELECT
                CONCAT(h.kode, " - ", h.nama) AS peg,
                a.id_hemxxmh,
                b.kode,
                shift,
                day(a.tanggal) AS tanggal,
                c.h_bagian AS bagian,
                c.h_grup AS grup
            FROM htssctd AS a
            LEFT JOIN hemxxmh AS h ON h.id = a.id_hemxxmh
            LEFT JOIN hemjbmh AS j ON j.id_hemxxmh = a.id_hemxxmh
            LEFT JOIN (
                SELECT 
                    sh.id,
                    sh.kode,
                    CASE sh.id
                        WHEN 1 THEN "L"
                        WHEN 6 THEN "1"
                        WHEN 17 THEN "2"            
                        WHEN 23 THEN "3"
                        ELSE sh.kode
                    END AS shift
                FROM htsxxmh AS sh
            ) AS b ON b.id = a.id_htsxxmh
            LEFT JOIN (
                SELECT 
                    id_hemxxmh,
                    GROUP_CONCAT(DISTINCT sc.grup ORDER BY sc.grup SEPARATOR ",") AS h_grup,
                    GROUP_CONCAT(DISTINCT sc.bagian ORDER BY sc.bagian SEPARATOR ",") AS h_bagian
                FROM htssctd AS sc
                WHERE sc.is_active = 1 AND YEAR(sc.tanggal) = YEAR(:start_date) AND MONTH(sc.tanggal) = MONTH(:start_date)
                GROUP BY sc.id_hemxxmh
            ) AS c ON c.id_hemxxmh = a.id_hemxxmh

            WHERE YEAR(a.tanggal) = YEAR(:start_date) AND MONTH(a.tanggal) = MONTH(:start_date) AND a.is_active = 1 AND j.jumlah_grup = 2
            
            AND j.id_hodxxmh = :id_hodxxmh

            GROUP BY a.id_hemxxmh, a.tanggal
            ORDER BY a.tanggal;

    ');

$rs_payroll = $qs_payroll->fetchAll();

// Jumlah hari dalam bulan dari start_date
$daysInMonth = (int) date('t', strtotime($start_date));

if (count($rs_payroll) > 0) {
    $pivotedData = [];

    foreach ($rs_payroll as $row) {
        $id_hemxxmh = $row['id_hemxxmh'];

        if (!isset($pivotedData[$id_hemxxmh])) {
            $pivotedData[$id_hemxxmh] = [
                'id_hemxxmh' => $id_hemxxmh,
                'Nama'       => $row['peg'],
                'Bagian'     => $row['bagian'],
                'Grup'       => $row['grup'],
            ];

            // Isi default "-" untuk semua tanggal
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $pivotedData[$id_hemxxmh][$d] = '-';
            }
        }

        // Timpa dengan shift yang sebenarnya
        $pivotedData[$id_hemxxmh][(int)$row['tanggal']] = $row['shift'];
    }

    $pivotedData = array_values($pivotedData);

    // Kolom dibuat tetap: Nama, Bagian, Grup, 1..N
    $columns = [
        ['data' => 'Nama',   'name' => 'Nama'],
        ['data' => 'Bagian', 'name' => 'Bagian'],
        ['data' => 'Grup',   'name' => 'Grup'],
    ];
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $columns[] = ['data' => (string)$d, 'name' => (string)$d];
    }

    echo json_encode([
        'data'    => $pivotedData,
        'columns' => $columns,
    ]);
} else {
    echo json_encode([
        'data'    => [],
        'columns' => [],
    ]);
}

// Close the database connection here (replace with your actual code)
?>
