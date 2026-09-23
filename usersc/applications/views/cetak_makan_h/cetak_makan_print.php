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


$mpdf = new \Mpdf\Mpdf([
    'margin_left'   => 4,
    'margin_right'  => 4,
    'margin_top'    => 4,
    'margin_bottom' => 4,
    'margin_header' => 0,
    'margin_footer' => 0,
    'format'        => [95, 140],
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Kupon Makan');
$mpdf->SetDisplayMode('fullpage');


foreach ($rs_cetak_makan_h as $index => $record) {

    if ($index > 0) {
        $mpdf->AddPage('P');
    }

    $html = '
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 9px;
        }

        .kupon {
            width: 100%;
            height: 130mm;
            border: 0.3mm solid #000;
            box-sizing: border-box;
        }

        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            padding-top: 2mm;
            padding-bottom: 1mm;
            border-bottom: 0.2mm solid #000;
        }

        .subjudul {
            text-align: center;
            font-size: 9px;
            padding-bottom: 2mm;
        }

        .info {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .info td {
            padding: 1mm 1mm;
            vertical-align: top;
        }

        .label {
            width: 25%;
        }

        .separator {
            width: 3%;
        }

        .value {
            width: 72%;
        }

        .spacer {
            height: 77mm;
        }

        .ttd {
            width: 100%;
            border-collapse: collapse;
            border-top: 0.2mm solid #000;
        }

        .ttd td {
            width: 50%;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            padding: 1.5mm 1mm;
        }
    </style>

    <table class="kupon" cellpadding="0" cellspacing="0">
        <tr>
            <td>

                <div class="judul">
                    Kupon Makan
                </div>

                <div class="subjudul">
                    Berlaku untuk 1 orang
                </div>

                <table class="info">
                    <tr>
                        <td class="label">Tanggal Berlaku</td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . htmlspecialchars($record['tanggal'] ?? '') . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Penerima Makan</td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . htmlspecialchars($record['penerima'] ?? '') . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Perusahaan</td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . htmlspecialchars($record['perusahaan'] ?? '') . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">PIC Tamu PMI</td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . htmlspecialchars($record['pic_tamu'] ?? '') . '
                        </td>
                    </tr>
                </table>

                <div class="spacer"></div>

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

$mpdf->Output();

?>