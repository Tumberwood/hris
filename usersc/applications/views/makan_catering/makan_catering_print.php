<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

use Carbon\Carbon;

$id_makan_catering = $_GET['id_makan_catering'] ?? 0;


/*
|--------------------------------------------------------------------------
| HEADER / DATA CATERING
|--------------------------------------------------------------------------
*/

$qs_makan_catering = $db
    ->raw()
    ->bind(':id_makan_catering', $id_makan_catering)
    ->exec('
        SELECT
            a.tanggal_awal AS start_date,
            a.tanggal_akhir AS end_date,
            DATE_FORMAT(a.tanggal_awal, "%d %b %Y") AS tanggal_awal,
            DATE_FORMAT(a.tanggal_akhir, "%d %b %Y") AS tanggal_akhir,
            a.nama AS catering,
            a.keterangan
        FROM makan_catering a
        WHERE a.id = :id_makan_catering
    ');

$rs_makan_catering = $qs_makan_catering->fetch();

if (!$rs_makan_catering) {
    die('Data catering tidak ditemukan.');
}

$start_date = $rs_makan_catering['start_date'];
$end_date   = $rs_makan_catering['end_date'];


/*
|--------------------------------------------------------------------------
| DETAIL MAKAN
|--------------------------------------------------------------------------
*/

$qs_data_sql = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec('SELECT
                DATE_FORMAT(x.tanggal, "%d %b %Y") AS tanggal,

                /* =========================
                HARGA CATERING
                ========================= */
                5500 AS harga_catering_kary,

                5500 AS harga_catering_staff,
                5500 AS harga_catering_hl,


                /* =========================
                SHIFT 1
                ========================= */
                SUM(
                    x.shift = 1
                    AND x.sub <> "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift1_kary,

                SUM(
                    x.shift = 1
                    AND x.sub = "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift1_staff,

                IFNULL(hl.shift1_hl, 0) AS shift1_hl,


                /* =========================
                SHIFT 2
                ========================= */
                SUM(
                    x.shift = 2
                    AND x.sub <> "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift2_kary,

                SUM(
                    x.shift = 2
                    AND x.sub = "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift2_staff,

                IFNULL(hl.shift2_hl, 0) AS shift2_hl,


                /* =========================
                SHIFT 3
                ========================= */
                SUM(
                    x.shift = 3
                    AND x.sub <> "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift3_kary,

                SUM(
                    x.shift = 3
                    AND x.sub = "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS shift3_staff,

                IFNULL(hl.shift3_hl, 0) AS shift3_hl,


                /* =========================
                TOTAL KARYAWAN
                ========================= */
                SUM(
                    x.sub <> "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS total_kary,


                /* =========================
                TOTAL STAFF
                ========================= */
                SUM(
                    x.sub = "STAFF"
                    AND x.jam_makan IS NOT NULL
                ) AS total_staff,

                
                (
                    IFNULL(hl.shift1_hl, 0)
                    + IFNULL(hl.shift2_hl, 0)
                    + IFNULL(hl.shift3_hl, 0)
                ) AS total_hl,


                /* =========================
                GRAND TOTAL
                ========================= */
                SUM(
                    x.jam_makan IS NOT NULL
                ) 
                +
                
                (
                    IFNULL(hl.shift1_hl, 0)
                    + IFNULL(hl.shift2_hl, 0)
                    + IFNULL(hl.shift3_hl, 0)
                )

                AS grand_total


            FROM
            (
                SELECT
                    a.tanggal,
                    d.nama AS sub,
                    a.jam_makan,


                    /* =========================
                    HARGA CATERING
                    ========================= */
                    COALESCE(
                        (
                            SELECT
                                p.nominal
                            FROM htpr_hemxxmh p
                            WHERE p.id_hpcxxmh = 34
                                AND p.id_hemxxmh = b.id
                                AND p.tanggal_efektif <= a.tanggal
                                AND p.is_active = 1
                            ORDER BY
                                p.tanggal_efektif DESC
                            LIMIT 1
                        ),
                        0
                    ) AS harga_catering,


                    /* =========================
                    SHIFT
                    ========================= */
                    CASE
                        WHEN a.st_jadwal LIKE "PAGI%" 
                            OR a.jam_awal_lembur_libur BETWEEN "06:00:00" AND "08:00:00" 
                            THEN 1

                        WHEN a.st_jadwal LIKE "SIANG%"
                        OR a.st_jadwal LIKE "SORE%"
                        OR a.jam_awal_lembur_libur = "15:00:00"
                            THEN 2

                        WHEN a.st_jadwal LIKE "MALAM%"
                            THEN 3

                        ELSE NULL
                    END AS shift


                FROM htsprrd a

                INNER JOIN hemxxmh b
                    ON b.id = a.id_hemxxmh

                INNER JOIN hemjbmh c
                    ON c.id_hemxxmh = a.id_hemxxmh

                INNER JOIN heyxxmd d
                    ON d.id = c.id_heyxxmd

                WHERE a.tanggal BETWEEN :start_date AND :end_date
                GROUP BY a.id_hemxxmh, a.tanggal

                    -- AND d.id IN (2, 3)

            ) x

            LEFT JOIN (
                SELECT
                    tanggal,
                    SUM(CASE WHEN shift = 1 THEN IFNULL(jumlah, 0) ELSE 0 END) AS shift1_hl,
                    SUM(CASE WHEN shift = 2 THEN IFNULL(jumlah, 0) ELSE 0 END) AS shift2_hl,
                    SUM(CASE WHEN shift = 3 THEN IFNULL(jumlah, 0) ELSE 0 END) AS shift3_hl
                FROM makan_catering_hl_umum
                WHERE tanggal BETWEEN :start_date AND :end_date
                GROUP BY tanggal
            ) hl ON hl.tanggal = x.tanggal

            GROUP BY
                x.tanggal


            ORDER BY
                x.tanggal
    ');

$rs_data_sql = $qs_data_sql->fetchAll();


/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function n($value)
{
    return number_format((float)($value ?? 0), 0, ',', '.');
}


/*
|--------------------------------------------------------------------------
| TOTAL FOOTER
|--------------------------------------------------------------------------
*/

$total_kary       = 0;
$total_staff      = 0;
$total_hl         = 0;
$grand_total      = 0;
$harga_kary       = 0;
$harga_staff      = 0;
$harga_hl         = 0;
$total_biaya_kary = 0;
$total_biaya_staff = 0;
$total_biaya_hl   = 0;

foreach ($rs_data_sql as $row) {

    $kary  = (int)($row['total_kary'] ?? 0);
    $staff = (int)($row['total_staff'] ?? 0);
    $hl    = (int)($row['total_hl'] ?? 0);

    $total_kary  += $kary;
    $total_staff += $staff;
    $total_hl    += $hl;

    $grand_total += (int)($row['grand_total'] ?? 0);

    if ((float)($row['harga_catering_kary'] ?? 0) > 0) {
        $harga_kary = (float)$row['harga_catering_kary'];
    }

    if ((float)($row['harga_catering_staff'] ?? 0) > 0) {
        $harga_staff = (float)$row['harga_catering_staff'];
    }

    if ((float)($row['harga_catering_hl'] ?? 0) > 0) {
        $harga_hl = (float)$row['harga_catering_hl'];
    }
}


/*
|--------------------------------------------------------------------------
| HITUNG TOTAL BIAYA
|--------------------------------------------------------------------------
|
| Harga catering diambil dari harga terakhir yang tersedia pada periode.
| Jika harga tidak ditemukan, nilainya 0.
|
*/

$total_biaya_kary  = $total_kary * $harga_kary;
$total_biaya_staff = $total_staff * $harga_staff;
$total_biaya_hl    = $total_hl * $harga_hl;
$total_biaya       = $total_biaya_kary + $total_biaya_staff + $total_biaya_hl;


/*
|--------------------------------------------------------------------------
| MPDF
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'margin_left'   => 8,
    'margin_right'  => 8,
    'margin_top'    => 8,
    'margin_bottom' => 8,
    'margin_header' => 0,
    'margin_footer' => 0,
    'format'        => 'A4',
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Rekap Makan Catering');
$mpdf->SetDisplayMode('fullpage');


/*
|--------------------------------------------------------------------------
| PRINT
|--------------------------------------------------------------------------
*/

$html = '

<style>

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #000;
    }

    .report-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 0 0 5mm 0;
    }

    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 5mm;
    }

    .header-table td {
        padding: 1.1mm 0;
        vertical-align: top;
        font-size: 11px;
    }

    .header-label {
        width: 18mm;
        font-weight: normal;
    }

    .header-separator {
        width: 4mm;
        text-align: center;
    }

    .header-value {
        font-weight: normal;
    }

    .section-title {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 2.5mm;
    }


    /* =====================================================
       DETAIL TABLE
       ===================================================== */

    .detail {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .detail th,
    .detail td {
        border: 0.25mm solid #000;
        padding: 1.6mm 0.8mm;
        text-align: center;
        vertical-align: middle;
        line-height: 1.2;
        font-size: 10px;
    }

    .detail th {
        font-weight: bold;
    }

    .detail .tanggal {
        width: 16%;
    }

    .detail .shift-col {
        width: 9%;
    }

    .detail .total-col {
        width: 9%;
    }

    .detail .grand-col {
        width: 12%;
    }

    .detail .number {
        text-align: center;
    }


    /* =====================================================
       FOOTER
       ===================================================== */

    .footer-title {
        font-weight: bold;
        font-size: 12px;
        margin-top: 5mm;
        margin-bottom: 2.5mm;
    }

    .footer-table {
        border-collapse: collapse;
        width: 100%;
    }

    .footer-table td {
        padding: 1mm 0;
        vertical-align: middle;
        font-size: 11px;
    }

    .footer-label {
        width: 48mm;
    }

    .footer-separator {
        width: 4mm;
        text-align: center;
    }

    .footer-value {
        width: auto;
    }

    .footer-total {
        font-weight: bold;
    }

</style>


<!-- =====================================================
     JUDUL
     ===================================================== -->

<div class="report-title">
    Laporan Catering periode ' . e($rs_makan_catering['tanggal_awal']) . ' s/d ' . e($rs_makan_catering['tanggal_akhir']) . '
</div>


<!-- =====================================================
     HEADER
     ===================================================== -->

<table class="header-table" cellpadding="0" cellspacing="0">

    <tr>
        <td class="header-label">Header</td>
        <td class="header-separator"></td>
        <td class="header-value"></td>
    </tr>

    <tr>
        <td class="header-label">Catering</td>
        <td class="header-separator"></td>
        <td class="header-value">' . e($rs_makan_catering['catering']) . '</td>
    </tr>

    <tr>
        <td class="header-label">Periode</td>
        <td class="header-separator"></td>
        <td class="header-value">
            ' . e($rs_makan_catering['tanggal_awal']) . '
            s/d
            ' . e($rs_makan_catering['tanggal_akhir']) . '
        </td>
    </tr>

    <tr>
        <td class="header-label">Keterangan</td>
        <td class="header-separator"></td>
        <td class="header-value">' . e($rs_makan_catering['keterangan']) . '</td>
    </tr>

</table>


<!-- =====================================================
     DETAIL
     ===================================================== -->

<div class="section-title">Detail</div>

<table class="detail" cellpadding="0" cellspacing="0">

    <thead>

        <tr>
            <th class="tanggal" rowspan="2">Tanggal</th>

            <th colspan="3">Shift 1</th>
            <th colspan="3">Shift 2</th>
            <th colspan="3">Shift 3</th>

            <th colspan="3">Total</th>

            <th class="grand-col" rowspan="2">
                Grand Total
            </th>
        </tr>

        <tr>
            <th class="shift-col">Kary</th>
            <th class="shift-col">Staff</th>
            <th class="shift-col">HL</th>

            <th class="shift-col">Kary</th>
            <th class="shift-col">Staff</th>
            <th class="shift-col">HL</th>

            <th class="shift-col">Kary</th>
            <th class="shift-col">Staff</th>
            <th class="shift-col">HL</th>

            <th class="total-col">Kary</th>
            <th class="total-col">Staff</th>
            <th class="total-col">HL</th>
        </tr>


    </thead>

    <tbody>
';

if (!empty($rs_data_sql)) {

    foreach ($rs_data_sql as $row) {

        $shift1_kary  = (int)($row['shift1_kary'] ?? 0);
        $shift1_staff = (int)($row['shift1_staff'] ?? 0);
        $shift1_hl    = (int)($row['shift1_hl'] ?? 0);

        $shift2_kary  = (int)($row['shift2_kary'] ?? 0);
        $shift2_staff = (int)($row['shift2_staff'] ?? 0);
        $shift2_hl    = (int)($row['shift2_hl'] ?? 0);

        $shift3_kary  = (int)($row['shift3_kary'] ?? 0);
        $shift3_staff = (int)($row['shift3_staff'] ?? 0);
        $shift3_hl    = (int)($row['shift3_hl'] ?? 0);

        $total_kary_row  = (int)($row['total_kary'] ?? 0);
        $total_staff_row = (int)($row['total_staff'] ?? 0);
        $total_hl_row    = (int)($row['total_hl'] ?? 0);

        $grand_total_row = (int)($row['grand_total'] ?? 0);

        $html .= '
        <tr>

            <td>' . e($row['tanggal']) . '</td>

            <td class="number">' . n($shift1_kary) . '</td>
            <td class="number">' . n($shift1_staff) . '</td>
            <td class="number">' . n($shift1_hl) . '</td>

            <td class="number">' . n($shift2_kary) . '</td>
            <td class="number">' . n($shift2_staff) . '</td>
            <td class="number">' . n($shift2_hl) . '</td>

            <td class="number">' . n($shift3_kary) . '</td>
            <td class="number">' . n($shift3_staff) . '</td>
            <td class="number">' . n($shift3_hl) . '</td>

            <td class="number">' . n($total_kary_row) . '</td>
            <td class="number">' . n($total_staff_row) . '</td>
            <td class="number">' . n($total_hl_row) . '</td>

            <td class="number">' . n($grand_total_row) . '</td>

        </tr>
        ';
    }

} else {

    $html .= '
        <tr>
            <td colspan="14" style="height: 12mm;">
                Tidak ada data.
            </td>
        </tr>
    ';
}

$html .= '

    </tbody>

</table>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<div class="footer-title">Footer</div>

<table class="footer-table" cellpadding="0" cellspacing="0">

    <tr>
        <td class="footer-label">
            Jumlah makan Karyawan
        </td>

        <td class="footer-separator">:</td>

        <td class="footer-value">
            ' . n($total_kary) . '
            x
            ' . n($harga_kary) . '
            =
            ' . n($total_biaya_kary) . '
        </td>
    </tr>

    <tr>
        <td class="footer-label">
            Jumlah Makan Staff
        </td>

        <td class="footer-separator">:</td>

        <td class="footer-value">
            ' . n($total_staff) . '
            x
            ' . n($harga_staff) . '
            =
            ' . n($total_biaya_staff) . '
        </td>
    </tr>

    <tr>
        <td class="footer-label">
            Jumlah Makan HL
        </td>

        <td class="footer-separator">:</td>

        <td class="footer-value">
            ' . n($total_hl) . '
            x
            ' . n($harga_hl) . '
            =
            ' . n($total_biaya_hl) . '
        </td>
    </tr>

    <tr>
        <td class="footer-label footer-total">
            Total
        </td>

        <td class="footer-separator footer-total">:</td>

        <td class="footer-value footer-total">
            ' . n($total_biaya_kary) . '
            +
            ' . n($total_biaya_staff) . '
            +
            ' . n($total_biaya_hl) . '
            =
            ' . n($total_biaya) . '
        </td>
    </tr>

</table>
';


$mpdf->WriteHTML($html);


/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/

$mpdf->Output();

?>
