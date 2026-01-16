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
    IF(kondite = "PA", -2, IFNULL(iz.id, -1)) AS id_izin,
    IFNULL(iz.nama, kondite) AS nama_izin,
    department,
    COUNT(*) AS total
FROM (
    SELECT
        a.id_hemxxmh,
        dep.nama AS department,
        CASE
            WHEN a.st_clock_in = "LATE"
                 AND a.status_presensi_in = "Belum Ada Izin"
                THEN CONCAT(a.st_clock_in, " - ", a.status_presensi_in)

            WHEN a.htlxxrh_kode LIKE "%[I/%"
                THEN TRIM(SUBSTRING_INDEX(a.htlxxrh_kode, "[I/", 1))

            WHEN pin.kode IS NOT NULL
                THEN a.status_presensi_in

            WHEN pout.kode IS NOT NULL
                THEN a.status_presensi_out

            ELSE NULL
        END AS kondite
    FROM htsprrd a
    LEFT JOIN hemjbmh job ON job.id_hemxxmh = a.id_hemxxmh
    LEFT JOIN hodxxmh dep ON dep.id = job.id_hodxxmh
    LEFT JOIN htpxxmh pin ON pin.kode = a.status_presensi_in
    LEFT JOIN htpxxmh pout ON pout.kode = a.status_presensi_out
    WHERE a.tanggal BETWEEN :start_date AND :end_date
      AND (
            pin.kode IS NOT NULL
         OR pout.kode IS NOT NULL
         OR a.st_clock_in = "LATE"
         OR a.htlxxrh_kode LIKE "%[I/%"
      )
      AND a.htlxxrh_kode NOT LIKE "%[KD%"
      '.$where.'
) x
LEFT JOIN htpxxmh iz ON iz.kode = x.kondite
WHERE kondite IS NOT NULL
GROUP BY nama_izin, department, id_izin
ORDER BY id_izin, department
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
