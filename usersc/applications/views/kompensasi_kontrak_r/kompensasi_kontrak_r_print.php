
<?php
// =====================================================
// PRINT KOMPENSASI KONTRAK
// =====================================================

include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

require '../../../../usersc/vendor/autoload.php';

use Mpdf\Mpdf;

// =====================================================
// FILTER TANGGAL
// =====================================================

$start_date = $_GET['start_date'] ?? '';
$end_date   = $_GET['end_date'] ?? '';

// Format tanggal dari URL: 23 Aug 2026
$start_timestamp = strtotime($start_date);
$end_timestamp   = strtotime($end_date);

if (!$start_timestamp || !$end_timestamp) {
    exit('Tanggal awal atau tanggal akhir tidak valid.');
}

$start_date = date('Y-m-d', $start_timestamp);
$end_date   = date('Y-m-d', $end_timestamp);

// =====================================================
// QUERY DATA
// =====================================================

$qs_htsprrd = $db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec("
        SELECT
            peg.id,
            peg.kode,
            peg.nama,

            DATE_FORMAT(a.tanggal, '%d %b %Y') AS tanggal,

            a.nominal,

            DATE_FORMAT(jb.tanggal_masuk, '%d-%m-%Y') AS tanggal_masuk,

            DATE_FORMAT(jb.tanggal_akhir_kontrak, '%d-%m-%Y') AS tanggal_akhir_kontrak

        FROM hpy_piutang_d a

        JOIN hemxxmh b
            ON b.id = a.id_hemxxmh

        JOIN hesxxtd c
            ON c.nik_baru = b.kode

        JOIN hemxxmh peg
            ON peg.id = c.id_hemxxmh

        JOIN hemjbmh jb
            ON jb.id_hemxxmh = peg.id

        WHERE 1
            AND a.id_hpcxxmh = 108
            AND a.tanggal BETWEEN :start_date AND :end_date

        ORDER BY a.id DESC
    ");

$rs_htsprrd = $qs_htsprrd->fetchAll();

// =====================================================
// HELPER
// =====================================================

$esc = function ($value) {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

$rupiah = function ($value) {
    return number_format(
        (float) ($value ?? 0),
        0,
        ',',
        '.'
    );
};

// =====================================================
// TANGGAL CETAK
// =====================================================

$tanggal_cetak = date('d-m-Y');

// =====================================================
// VALIDASI DATA
// =====================================================

if (empty($rs_htsprrd)) {
    exit('Data kompensasi tidak ditemukan pada periode tersebut.');
}

// =====================================================
// KONFIGURASI MPDF
// =====================================================

$mpdf = new Mpdf([
    'mode' => 'utf-8',

    // Ukuran kertas 9 x 13,5 cm
    'format' => [90, 135],

    'margin_left' => 8,
    'margin_right' => 8,
    'margin_top' => 7,
    'margin_bottom' => 7,

    'margin_header' => 0,
    'margin_footer' => 0,

    'default_font' => 'sans-serif',
]);

$mpdf->SetTitle('Kompensasi Kontrak');
$mpdf->SetAuthor('HRIS');

// =====================================================
// CSS
// =====================================================

$css = '
    body {
        font-family: sans-serif;
        font-size: 10px;
        color: #222;
    }

    .container {
        width: 100%;
    }

    .header {
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 5px;
        padding-bottom: 4px;
        border-bottom: 2px double #333;
        letter-spacing: 1px;
    }

    .content {
        width: 100%;
        border-collapse: collapse;
        margin-top: 35px;
    }

    .content td {
        padding-top: 9px;
        padding-bottom: 9px;
        vertical-align: top;
    }

    .label {
        width: 37%;
        font-weight: bold;
        white-space: nowrap;
        padding-left: 5px;
    }

    .separator {
        width: 5%;
        text-align: center;
    }

    .value {
        width: 58%;
    }

    .periode {
        font-size: 9px;
        line-height: 1.5;
        text-align: left;
    }

    .nominal {
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    .footer {
        width: 100%;
        margin-top: 35px;
        border-collapse: collapse;
    }

    .footer td {
        padding: 4px 0;
    }

    .tanggal {
        text-align: right;
        padding-right: 5px;
        font-size: 9px;
    }

    .diterima {
        text-align: right;
        padding-right: 5px;
        font-size: 9px;
    }

    .ttd {
        height: 65px;
    }

    .nama {
        text-align: right;
        padding-right: 5px;
        font-size: 9px;
        font-weight: bold;
        text-decoration: underline;
    }
';

// =====================================================
// LOOP DATA
// =====================================================

foreach ($rs_htsprrd as $index => $record) {

    $nik  = $esc($record['kode']);
    $nama = $esc($record['nama']);

    $tanggal_awal = $esc($record['tanggal_masuk']);
    $tanggal_akhir = $esc($record['tanggal_akhir_kontrak']);

    $nominal = $rupiah($record['nominal']);

    // =================================================
    // HTML PRINT
    // =================================================

    $html = '
        <html>
        <head>
            <style>
                ' . $css . '
            </style>
        </head>

        <body>

            <div class="container">

                <div class="header">
                    KOMPENSASI
                </div>

                <table class="content">

                    <tr>
                        <td class="label">No. Induk</td>
                        <td class="separator">:</td>
                        <td class="value">' . $nik . '</td>
                    </tr>

                    <tr>
                        <td class="label">N a m a</td>
                        <td class="separator">:</td>
                        <td class="value">' . $nama . '</td>
                    </tr>

                    <tr>
                        <td class="label">Kompensasi Kontrak</td>
                        <td class="separator">:</td>
                        <td class="value periode">
                            PERIODE ' . $tanggal_awal . '
                            S.D<br>
                            ' . $tanggal_akhir . '
                        </td>
                    </tr>

                    <tr>
                        <td class="label">Jumlah ( Rp )</td>
                        <td class="separator">:</td>
                        <td class="value nominal">' . $nominal . '</td>
                    </tr>

                </table>

                <table class="footer">

                    <tr>
                        <td class="tanggal">
                            Sidoarjo, ' . $tanggal_cetak . '
                        </td>
                    </tr>

                    <tr>
                        <td class="diterima">
                            Diterima Oleh
                        </td>
                    </tr>

                    <tr>
                        <td class="ttd">&nbsp;</td>
                    </tr>

                    <tr>
                        <td class="nama">
                            ' . $nama . '
                        </td>
                    </tr>

                </table>

            </div>

        </body>
        </html>
    ';

    if ($index > 0) {
        $mpdf->AddPage();
    }

    $mpdf->WriteHTML($html);
}

// =====================================================
// OUTPUT PDF
// =====================================================

$mpdf->Output(
    'Kompensasi_Kontrak.pdf',
    'I'
);

?>
