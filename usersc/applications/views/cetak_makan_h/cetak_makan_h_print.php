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
        JOIN cetak_makan_d b 
            ON b.id_cetak_makan_h = a.id
        WHERE a.id = :id_cetak_makan_h
    ');

$rs_cetak_makan_h = $qs_cetak_makan_h->fetchAll();


/*
|--------------------------------------------------------------------------
| MPDF
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'margin_left'   => 5,
    'margin_right'  => 5,
    'margin_top'    => 5,
    'margin_bottom' => 5,
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

    $tanggal    = htmlspecialchars($record['tanggal'] ?? '');
    $penerima   = htmlspecialchars($record['penerima'] ?? '');
    $perusahaan = htmlspecialchars($record['perusahaan'] ?? '');
    $pic_tamu   = htmlspecialchars($record['pic_tamu'] ?? '');


    $html = '

    <style>

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 9px;
        }


        /* ==========================================
           KUPON
        ========================================== */

        .kupon {
            width: 100%;
            border: 0.3mm solid #000;
            border-collapse: collapse;
            table-layout: fixed;
        }


        /* ==========================================
           JUDUL
        ========================================== */

        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            padding: 1.5mm 0 0.5mm 0;
        }


        /* ==========================================
           SUB JUDUL
        ========================================== */

        .subjudul {
            text-align: center;
            font-size: 8px;
            padding: 0 0 1.5mm 0;
            border-bottom: 0.25mm solid #000;
        }


        /* ==========================================
           DATA
        ========================================== */

        .data {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .data td {
            font-size: 8px;
            padding: 1mm 1mm;
            vertical-align: middle;
        }

        .label {
            width: 25%;
        }

        .separator {
            width: 5%;
            text-align: center;
        }

        .value {
            width: 59%;
        }


        /* ==========================================
           AREA KOSONG
        ========================================== */

        .spacer {
            height: 18mm;
        }


        /* ==========================================
           TANDA TANGAN
        ========================================== */

        .ttd {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 0.25mm solid #000;
        }

        .ttd td {
            height: 18mm;
            padding: 0;
            vertical-align: bottom;
            font-size: 8px;
            font-weight: bold;
        }

        .ttd-kiri {
            width: 50%;
            text-align: left;
            padding-left: 1mm !important;
        }

        .ttd-kanan {
            width: 50%;
            text-align: right;
            padding-right: 1mm !important;
        }

    </style>


    <table class="kupon" cellpadding="0" cellspacing="0">

        <!-- ==========================================
             JUDUL
        ========================================== -->

        <tr>
            <td class="judul">
                Kupon Makan
            </td>
        </tr>


        <!-- ==========================================
             SUB JUDUL
        ========================================== -->

        <tr>
            <td class="subjudul">
                Berlaku untuk 1 orang
            </td>
        </tr>


        <!-- ==========================================
             DATA
        ========================================== -->

        <tr>
            <td style="padding: 1mm 1mm 0 1mm;">

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


        <!-- ==========================================
             JARAK TANDA TANGAN
        ========================================== -->

        <tr>
            <td class="spacer">
                &nbsp;
            </td>
        </tr>


        <!-- ==========================================
             TANDA TANGAN
        ========================================== -->

        <tr>
            <td style="padding: 0;">

                <table class="ttd" cellpadding="0" cellspacing="0">

                    <tr>

                        <td class="ttd-kiri">
                            TTD Bagian HRD
                        </td>

                        <td class="ttd-kanan">
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