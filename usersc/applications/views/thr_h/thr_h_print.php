
<?php
// =====================================================
// PRINT SLIP THR
// =====================================================

include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

require '../../../../usersc/vendor/autoload.php';

use Mpdf\Mpdf;

// =====================================================
// PARAMETER
// =====================================================

$id_thr_h = (int) ($_GET['id_thr_h'] ?? 0);

if ($id_thr_h <= 0) {
    exit('ID transaksi THR tidak valid.');
}

// =====================================================
// QUERY DATA THR
// =====================================================

$qs_thr = $db
    ->raw()
    ->bind(':id_thr_h', $id_thr_h)
    ->exec("
        SELECT
            d.id,
            d.id_thr_h,
            d.id_hemxxmh,

            d.kode,
            d.nama,
            d.keterangan,
            d.nominal,

            peg.nama AS nama_pegawai,
            peg.kode AS nik,

            h.kode AS kode_thr,
            h.nama AS nama_thr,
            h.tanggal_hari_raya

        FROM thr_d d

        JOIN thr_h h
            ON h.id = d.id_thr_h

        JOIN hemxxmh peg
            ON peg.id = d.id_hemxxmh

        WHERE
            d.id_thr_h = :id_thr_h
            AND d.is_active = 1

        ORDER BY
            d.nama ASC
    ");

$rs_thr = $qs_thr->fetchAll();

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
// VALIDASI DATA
// =====================================================

if (empty($rs_thr)) {
    exit('Data THR tidak ditemukan.');
}

// =====================================================
// TANGGAL CETAK
// =====================================================

$tanggal_cetak = date('d-m-Y');

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

$mpdf->SetTitle('Slip THR');
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

    .company {
        text-align: center;
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .header {
        text-align: center;
        font-size: 12px;
        font-weight: bold;
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
        padding-top: 8px;
        padding-bottom: 8px;
        vertical-align: top;
    }

    .label {
        width: 36%;
        white-space: nowrap;
        padding-left: 3px;
    }

    .separator {
        width: 5%;
        text-align: center;
    }

    .value {
        width: 59%;
    }

    .nominal {
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    .footer {
        width: 100%;
        margin-top: 38px;
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
// LOOP DATA THR
// =====================================================

foreach ($rs_thr as $index => $record) {

    $nik  = $esc($record['nik']);
    $nama = $esc($record['nama_pegawai']);

    $nominal = $rupiah($record['nominal']);

    // Bagian belum dipetakan karena relasi tabel bagian
    // belum tersedia pada struktur yang diberikan.
    $bagian = '-';

    // =================================================
    // HTML SLIP THR
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

                <div class="company">
                    PT. PUTRA MANDIRI INTIPACK
                </div>

                <div class="header">
                    SLIP THR ' . date(
                        'Y',
                        strtotime($record['tanggal_hari_raya'])
                    ) . '
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
                        <td class="label">Bagian</td>
                        <td class="separator">:</td>
                        <td class="value">' . $bagian . '</td>
                    </tr>

                    <tr>
                        <td class="label">THR Diterima Rp</td>
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
    'Slip_THR_' . $id_thr_h . '.pdf',
    'I'
);

?>
