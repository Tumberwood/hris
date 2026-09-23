<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

use Carbon\Carbon;

$id_cetak_makan_h = $_GET['id_cetak_makan_h'];

$qs_cetak_makan_h = $db
    ->raw()
    ->bind(':id_cetak_makan_h', $id_cetak_makan_h)
    ->exec('
        SELECT
            DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
            b.nama AS penerima,
            b.perusahaan,
            b.pic_tamu
        FROM cetak_makan_h a
        JOIN cetak_makan_d b ON b.id_cetak_makan_h = a.id
        WHERE a.id = :id_cetak_makan_h
    ');

$rs_cetak_makan_h = $qs_cetak_makan_h->fetchAll();


/*
|--------------------------------------------------------------------------
| MPDF
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'margin_left'   => 3,
    'margin_right'  => 3,
    'margin_top'    => 3,
    'margin_bottom' => 3,
    'margin_header' => 0,
    'margin_footer' => 0,
    'format'        => [95, 140],
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Kupon Makan');
$mpdf->SetDisplayMode('fullpage');


/*
|--------------------------------------------------------------------------
| PRINT
|--------------------------------------------------------------------------
*/

foreach ($rs_cetak_makan_h as $index => $record) {

    if ($index > 0) {
        $mpdf->AddPage('P');
    }

    $tanggal   = htmlspecialchars($record['tanggal'] ?? '');
    $penerima  = htmlspecialchars($record['penerima'] ?? '');
    $perusahaan = htmlspecialchars($record['perusahaan'] ?? '');
    $pic_tamu  = htmlspecialchars($record['pic_tamu'] ?? '');


    $html = '

    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 9px;
        }

        .kupon {
            width: 100%;
            height: 133mm;
            border: 0.3mm solid #000;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* =========================
           HEADER
        ========================= */

        .judul {
            height: 6mm;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            vertical-align: middle;
            padding: 0;
        }

        .subjudul {
            height: 5mm;
            text-align: center;
            font-size: 9px;
            vertical-align: middle;
            padding: 0;
            border-bottom: 0.25mm solid #000;
        }


        /* =========================
           DATA
        ========================= */

        .data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data td {
            height: 6mm;
            vertical-align: middle;
            padding: 0 1mm;
            font-size: 9px;
        }

        .label {
            width: 38%;
        }

        .separator {
            width: 5%;
            text-align: center;
        }

        .value {
            width: 57%;
        }


        /* =========================
           AREA KOSONG
        ========================= */

        .empty {
            height: 82mm;
            vertical-align: top;
        }


        /* =========================
           TANDA TANGAN
        ========================= */

        .ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 0.25mm solid #000;
        }

        .ttd td {
            width: 50%;
            height: 7mm;
            padding: 0 1mm;
            vertical-align: middle;
            font-size: 9px;
            font-weight: bold;
        }

    </style>


    <table class="kupon" cellpadding="0" cellspacing="0">

        <!-- JUDUL -->
        <tr>
            <td class="judul">
                Kupon Makan
            </td>
        </tr>

        <!-- SUB JUDUL -->
        <tr>
            <td class="subjudul">
                Berlaku untuk 1 orang
            </td>
        </tr>


        <!-- DATA -->
        <tr>
            <td style="vertical-align: top; padding: 1mm 1mm 0 1mm;">

                <table class="data" cellpadding="0" cellspacing="0">

                    <tr>
                        <td class="label">
                            Tanggal Berlaku
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            ' . $tanggal . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Penerima Makan
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            ' . $penerima . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Perusahaan
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            ' . $perusahaan . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            PIC Tamu PMI
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            ' . $pic_tamu . '
                        </td>
                    </tr>

                </table>

            </td>
        </tr>


        <!-- AREA KOSONG -->
        <tr>
            <td class="empty">
                &nbsp;
            </td>
        </tr>


        <!-- TANDA TANGAN -->
        <tr>
            <td style="padding: 0;">

                <table class="ttd" cellpadding="0" cellspacing="0">

                    <tr>
                        <td>
                            TTD Bagian HRD
                        </td>

                        <td>
                            TTD Bagian Kantin
                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>
    ';


    $mpdf->WriteHTML($html);
}


/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/

$mpdf->Output();

?>