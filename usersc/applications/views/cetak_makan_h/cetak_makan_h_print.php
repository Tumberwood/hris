<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

use Mpdf\Mpdf;

/*
|--------------------------------------------------------------------------
| AMBIL DATA
|--------------------------------------------------------------------------
*/

$id_cetak_makan_h = $_GET['id_cetak_makan_h'] ?? null;

if (!$id_cetak_makan_h) {
    die('ID cetak makan tidak ditemukan.');
}

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
| KONFIGURASI MPDF - A6 PORTRAIT
|--------------------------------------------------------------------------
*/

$mpdf = new Mpdf([
    'mode'              => 'utf-8',
    'format'            => 'A6',
    'orientation'       => 'P',
    'margin_left'       => 5,
    'margin_right'      => 5,
    'margin_top'        => 5,
    'margin_bottom'     => 5,
    'margin_header'     => 0,
    'margin_footer'     => 0,
    'default_font'      => 'sans-serif',
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Kupon Makan');
$mpdf->SetDisplayMode('fullpage');

/*
|--------------------------------------------------------------------------
| CSS
|--------------------------------------------------------------------------
*/

$css = '
    body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        font-size: 9pt;
        color: #000;
    }

    table {
        border-collapse: collapse;
    }

    .kupon {
        width: 100%;
        border: 0.35mm solid #000;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .judul {
        text-align: center;
        font-size: 14pt;
        font-weight: bold;
        padding: 3mm 2mm 1mm 2mm;
    }

    .subjudul {
        text-align: center;
        font-size: 8pt;
        padding: 0 2mm 2.5mm 2mm;
        border-bottom: 0.25mm solid #000;
    }

    .area-data {
        padding: 2mm 2mm 1mm 2mm;
    }

    .data {
        width: 100%;
        table-layout: fixed;
    }

    .data td {
        font-size: 8pt;
        line-height: 1.3;
        padding: 1.5mm 0;
        vertical-align: top;
        overflow-wrap: break-word;
    }

    .label {
        width: 36%;
    }

    .separator {
        width: 5%;
        text-align: center;
    }

    .value {
        width: 59%;
        font-weight: normal;
        overflow-wrap: break-word;
    }

    .area-ttd {
        padding: 0 2mm;
    }

    .spacer {
        height: 25mm;
    }

    .ttd {
        width: 100%;
        table-layout: fixed;
        border-top: 0.25mm solid #000;
    }

    .ttd td {
        height: 17mm;
        padding: 2mm 0 0 0;
        vertical-align: bottom;
        font-size: 7.5pt;
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
';

/*
|--------------------------------------------------------------------------
| CETAK KUPON
|--------------------------------------------------------------------------
*/

foreach ($rs_cetak_makan_h as $index => $record) {

    if ($index > 0) {
        $mpdf->AddPage('P');
    }

    $tanggal = htmlspecialchars(
        (string) ($record['tanggal'] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );

    $penerima = htmlspecialchars(
        (string) ($record['penerima'] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );

    $perusahaan = htmlspecialchars(
        (string) ($record['perusahaan'] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );

    $pic_tamu = htmlspecialchars(
        (string) ($record['pic_tamu'] ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );

    $html = '
    <style>
        ' . $css . '
    </style>

    <table class="kupon" cellpadding="0" cellspacing="0">

        <tr>
            <td class="judul">
                KUPON MAKAN
            </td>
        </tr>

        <tr>
            <td class="subjudul">
                Berlaku untuk 1 orang
            </td>
        </tr>

        <tr>
            <td class="area-data">

                <table class="data" cellpadding="0" cellspacing="0">

                    <tr>
                        <td class="label">
                            Tanggal Berlaku
                        </td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . $tanggal . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Penerima Makan
                        </td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . $penerima . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            Perusahaan
                        </td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . $perusahaan . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">
                            PIC Tamu PMI
                        </td>
                        <td class="separator">:</td>
                        <td class="value">
                            ' . $pic_tamu . '
                        </td>
                    </tr>

                </table>

            </td>
        </tr>

        <tr>
            <td class="spacer">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td class="area-ttd">

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
| OUTPUT PDF
|--------------------------------------------------------------------------
*/

if (empty($rs_cetak_makan_h)) {
    die('Data kupon makan tidak ditemukan.');
}

$mpdf->Output('Kupon_Makan_A6.pdf', 'I');

?>