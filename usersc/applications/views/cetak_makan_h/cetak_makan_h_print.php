<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

use Carbon\Carbon;

$id_cetak_makan_h = $_GET['id_cetak_makan_h'] ?? null;

if (!$id_cetak_makan_h) {
    die('ID cetak makan tidak ditemukan.');
}

/*
|--------------------------------------------------------------------------
| QUERY DATA
|--------------------------------------------------------------------------
*/

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
    'mode'          => 'utf-8',
    'format'        => 'A4',
    'orientation'   => 'P',
    'margin_left'   => 5,
    'margin_right'  => 5,
    'margin_top'    => 5,
    'margin_bottom' => 5,
    'margin_header' => 0,
    'margin_footer' => 0,
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
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 8px;
    }

    .container {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .container td {
        width: 50%;
        height: 68mm;
        padding: 1mm;
        vertical-align: top;
    }

    .kupon {
        width: 100%;
        height: 66mm;
        border: 0.3mm solid #000;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .judul {
        text-align: center;
        font-size: 11px;
        font-weight: bold;
        padding: 1.5mm 0 0.5mm 0;
    }

    .subjudul {
        text-align: center;
        font-size: 7px;
        padding: 0 0 1mm 0;
        border-bottom: 0.25mm solid #000;
    }

    .data {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .data td {
        height: auto;
        font-size: 7px;
        padding: 1mm 0.5mm;
        vertical-align: middle;
    }

    .label {
        width: 31%;
    }

    .separator {
        width: 4%;
        text-align: center;
    }

    .value {
        width: 65%;
        word-wrap: break-word;
    }

    .spacer {
        height: 12mm;
    }

    .ttd {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border-top: 0.25mm solid #000;
    }

    .ttd td {
        height: 12mm;
        padding: 0.5mm;
        vertical-align: bottom;
        font-size: 7px;
        font-weight: bold;
    }

    .ttd-kiri {
        width: 50%;
        text-align: left;
    }

    .ttd-kanan {
        width: 50%;
        text-align: right;
    }
</style>
';

$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);

/*
|--------------------------------------------------------------------------
| FUNGSI HTML KUPON
|--------------------------------------------------------------------------
*/

function buatKuponMakan($record)
{
    $tanggal = htmlspecialchars(
        $record['tanggal'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    $penerima = htmlspecialchars(
        $record['penerima'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    $perusahaan = htmlspecialchars(
        $record['perusahaan'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    $pic_tamu = htmlspecialchars(
        $record['pic_tamu'] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );

    return '
        <table class="kupon" cellpadding="0" cellspacing="0">

            <tr>
                <td class="judul">
                    Kupon Makan
                </td>
            </tr>

            <tr>
                <td class="subjudul">
                    Berlaku untuk 1 orang
                </td>
            </tr>

            <tr>
                <td style="padding: 1mm 1mm 0 1mm;">

                    <table class="data" cellpadding="0" cellspacing="0">

                        <tr>
                            <td class="label">
                                Tanggal Berlaku
                            </td>
                            <td class="separator">:</td>
                            <td class="value">' . $tanggal . '</td>
                        </tr>

                        <tr>
                            <td class="label">
                                Penerima Makan
                            </td>
                            <td class="separator">:</td>
                            <td class="value">' . $penerima . '</td>
                        </tr>

                        <tr>
                            <td class="label">
                                Perusahaan
                            </td>
                            <td class="separator">:</td>
                            <td class="value">' . $perusahaan . '</td>
                        </tr>

                        <tr>
                            <td class="label">
                                PIC Tamu PMI
                            </td>
                            <td class="separator">:</td>
                            <td class="value">' . $pic_tamu . '</td>
                        </tr>

                    </table>

                </td>
            </tr>

            <tr>
                <td class="spacer">&nbsp;</td>
            </tr>

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
}

/*
|--------------------------------------------------------------------------
| CETAK MAKSIMAL 8 KUPON PER HALAMAN
|--------------------------------------------------------------------------
*/

$total_data = count($rs_cetak_makan_h);

if ($total_data > 0) {

    // Setiap halaman menampung maksimal 8 orang.
    $kelompok_data = array_chunk($rs_cetak_makan_h, 8);

    foreach ($kelompok_data as $halaman => $records) {

        if ($halaman > 0) {
            $mpdf->AddPage('P');
        }

        $html = '
            <table class="container" cellpadding="0" cellspacing="0">
        ';

        foreach ($records as $index => $record) {

            // Awal baris baru setiap 2 kupon.
            if ($index % 2 === 0) {
                $html .= '<tr>';
            }

            $html .= '
                <td>
                    ' . buatKuponMakan($record) . '
                </td>
            ';

            // Tutup baris setelah 2 kupon atau jika data terakhir.
            if ($index % 2 === 1 || $index === count($records) - 1) {
                $html .= '</tr>';
            }
        }

        $html .= '</table>';

        $mpdf->WriteHTML(
            $html,
            \Mpdf\HTMLParserMode::HTML_BODY
        );
    }

} else {

    $mpdf->WriteHTML(
        '<p style="text-align:center;">Data kupon makan tidak ditemukan.</p>'
    );

}

/*
|--------------------------------------------------------------------------
| OUTPUT PDF
|--------------------------------------------------------------------------
*/

$mpdf->Output('Kupon_Makan.pdf', 'I');

?>