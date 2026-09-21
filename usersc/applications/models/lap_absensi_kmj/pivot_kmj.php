<?php

include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;


/*
|--------------------------------------------------------------------------
| GET POST
|--------------------------------------------------------------------------
*/

$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];


/*
|--------------------------------------------------------------------------
| VALIDASI TANGGAL
|--------------------------------------------------------------------------
*/

if (empty($start_date) || empty($end_date)) {

    echo json_encode([
        'data' => [],
        'columns' => []
    ]);

    exit;
}


/*
|--------------------------------------------------------------------------
| GENERATE COLUMN TANGGAL
|--------------------------------------------------------------------------
|
| Contoh:
|
| 6-Sep-26
| 7-Sep-26
| 8-Sep-26
| ...
|
*/

$startDate = new DateTime($start_date);
$endDate   = new DateTime($end_date);

$dateColumns = [];

$currentDate = clone $startDate;

while ($currentDate <= $endDate) {

    $dateColumns[] = [
        'sql_date' => $currentDate->format('Y-m-d'),
        'label'    => $currentDate->format('j-M-y')
    ];

    $currentDate->modify('+1 day');
}


/*
|--------------------------------------------------------------------------
| BUILD SELECT DATE
|--------------------------------------------------------------------------
*/

$selectDate = '';

foreach ($dateColumns as $date) {

    $sqlDate = $date['sql_date'];
    $label   = $date['label'];

    $selectDate .= ",
    
    COUNT(
        CASE
            WHEN a.tanggal = '{$sqlDate}'
            THEN 1
        END
    ) AS `{$label}_Jml_Orang`,

    ROUND(
        SUM(
            CASE
                WHEN a.tanggal = '{$sqlDate}'
                THEN COALESCE(
                    TIMESTAMPDIFF(
                        MINUTE,
                        a.clock_in,
                        a.clock_out
                    ) / 60.0,
                    0
                )
                ELSE 0
            END
        ),
        2
    ) AS `{$label}_Durasi_Total`
    ";
}


/*
|--------------------------------------------------------------------------
| QUERY
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT

        CASE
            WHEN a.st_jadwal LIKE '%PAGI%'
                THEN 'Shift Pagi'

            WHEN a.st_jadwal LIKE '%SORE%'
                OR a.st_jadwal LIKE '%SIANG%'
                THEN 'Shift Siang'

            WHEN a.st_jadwal LIKE '%MALAM%'
                THEN 'Shift Malam'
        END AS Shift

        {$selectDate}

    FROM htsprrd a

    JOIN hemxxmh b
        ON b.id = a.id_hemxxmh

    JOIN hemjbmh c
        ON c.id_hemxxmh = a.id_hemxxmh

    WHERE a.tanggal BETWEEN :start_date AND :end_date

        AND c.id_heyxxmd = 4

        -- Hanya ceklok yang lengkap
        AND a.clock_in IS NOT NULL
        AND a.clock_out IS NOT NULL

        AND (
            a.st_jadwal LIKE '%PAGI%'
            OR a.st_jadwal LIKE '%SORE%'
            OR a.st_jadwal LIKE '%SIANG%'
            OR a.st_jadwal LIKE '%MALAM%'
        )

    GROUP BY
        CASE
            WHEN a.st_jadwal LIKE '%PAGI%'
                THEN 'Shift Pagi'

            WHEN a.st_jadwal LIKE '%SORE%'
                OR a.st_jadwal LIKE '%SIANG%'
                THEN 'Shift Siang'

            WHEN a.st_jadwal LIKE '%MALAM%'
                THEN 'Shift Malam'
        END

    ORDER BY
        FIELD(
            Shift,
            'Shift Pagi',
            'Shift Siang',
            'Shift Malam'
        )
";


/*
|--------------------------------------------------------------------------
| EXECUTE QUERY
|--------------------------------------------------------------------------
*/

$qs = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec($sql);


$rs = $qs->fetchAll();


/*
|--------------------------------------------------------------------------
| BUILD COLUMNS
|--------------------------------------------------------------------------
*/

$columns = [];


/*
|--------------------------------------------------------------------------
| SHIFT COLUMN
|--------------------------------------------------------------------------
*/

$columns[] = [
    'data' => 'Shift',
    'name' => 'Shift'
];


/*
|--------------------------------------------------------------------------
| DATE COLUMNS
|--------------------------------------------------------------------------
*/

foreach ($dateColumns as $date) {

    $label = $date['label'];

    $columns[] = [
        'data' => $label . '_Jml_Orang',
        'name' => $label . '_Jml_Orang'
    ];

    $columns[] = [
        'data' => $label . '_Durasi_Total',
        'name' => $label . '_Durasi_Total'
    ];
}


/*
|--------------------------------------------------------------------------
| RESULT
|--------------------------------------------------------------------------
*/

$results = [
    'data' => $rs,
    'columns' => $columns
];


/*
|--------------------------------------------------------------------------
| JSON
|--------------------------------------------------------------------------
*/

header('Content-Type: application/json');

echo json_encode(
    $results,
    JSON_UNESCAPED_UNICODE
);

?>