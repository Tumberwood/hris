<?php
/**
 * Digunakan
 */
require_once("../../../../users/init.php");
require_once("../../../../usersc/lib/DataTables.php");
require_once("../../../../usersc/helpers/datatables_fn_debug.php");
require_once("../../../../usersc/vendor/autoload.php");

use Carbon\Carbon;

/* =========================================================
 * Init variable untuk fn_ajax_results.php
 * ========================================================= */
$data      = [];
$rs_opt    = [];
$c_rs_opt  = 0;
$morePages = 0;

/* =========================================================
 * Input
 * ========================================================= */
$start_date = $_POST['start_date'];
$end_date   = $_POST['end_date'];

$where = '';
if (!empty($_POST['id_heyxxmh']) && $_POST['id_heyxxmh'] > 0) {
    $where = ' AND job.id_heyxxmh = ' . (int)$_POST['id_heyxxmh'];
}

/* =========================================================
 * SINGLE QUERY (TIDAK ADA QUERY DALAM LOOP)
 * ========================================================= */
$sql = '
SELECT
    dep.nama AS department,
    IFNULL(ij.nama, "Late - Belum Ada Izin") AS nama_izin,
    COUNT(*) AS total
FROM htsprrd a
JOIN hemxxmh b ON b.id = a.id_hemxxmh
JOIN hemjbmh c ON c.id_hemxxmh = b.id
JOIN hodxxmh dep ON dep.id = c.id_hodxxmh

LEFT JOIN htpxxmh ij 
    ON (
        ij.kode = a.status_presensi_in
        OR ij.kode = a.status_presensi_out
        OR a.htlxxrh_kode LIKE CONCAT("%", ij.kode, "%")
    )

WHERE 
    a.tanggal BETWEEN :start_date AND :end_date

    AND (
        a.status_presensi_in <> "OFF"
        AND a.status_presensi_out <> "OFF"
    )

    AND (
        a.status_presensi_in = ij.kode
        OR a.status_presensi_out = ij.kode
        OR a.htlxxrh_kode LIKE CONCAT("%", ij.kode, "%")

        OR (
            ij.kode IS NULL
            AND a.st_clock_in = "LATE"
            AND a.status_presensi_in = "Belum Ada Izin"
        )
    )
'.$where.'

GROUP BY 
    department,
    nama_izin

ORDER BY 
    department,
    nama_izin
';

$rows = $db->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec($sql)
    ->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
 * OLAH DATA (PHP SAJA)
 * ========================================================= */
$category = [
    'name' => 'izin',
    'data' => []
];

$series1 = [
    'name' => 'Karyawan',
    'colorByPoint' => true,
    'data' => []
];

$drilldown = [
    'series' => []
];

$tmpIzin  = [];
$tmpDrill = [];

foreach ($rows as $r) {
    $izin  = $r['nama_izin'];
    $dept  = $r['department'];
    $total = (int)$r['total'];

    /* Level 1 */
    if (!isset($tmpIzin[$izin])) {
        $tmpIzin[$izin] = 0;
        $category['data'][] = $izin;
    }
    $tmpIzin[$izin] += $total;

    /* Level 2 */
    $tmpDrill[$izin][] = [$dept, $total];
}

/* Series utama */
foreach ($tmpIzin as $izin => $total) {
    $series1['data'][] = [
        'name' => $izin,
        'y' => $total,
        'drilldown' => $izin
    ];
}

/* Drilldown */
foreach ($tmpDrill as $izin => $deptData) {
    $drilldown['series'][] = [
        'name' => $izin,
        'id' => $izin,
        'data' => $deptData
    ];
}

/* =========================================================
 * OUTPUT
 * ========================================================= */
$data = [
    'results_emp_izin' => [
        $category,
        $series1,
        $drilldown
    ],
    'start_date' => $start_date,
    'end_date' => $end_date
];

require_once("../../../../usersc/helpers/fn_ajax_results.php");
