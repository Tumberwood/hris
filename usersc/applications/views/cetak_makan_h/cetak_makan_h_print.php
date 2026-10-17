<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

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
| Kertas A4 portrait
| Ukuran kupon A6
| 2 kolom x 2 baris
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'mode'          => 'utf-8',
    'format'        => 'A4',
    'orientation'   => 'P',
    'margin_left'   => 0,
    'margin_right'  => 0,
    'margin_top'    => 0,
    'margin_bottom' => 0,
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
    @page {
        margin: 0;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        font-size: 9px;
    }

    /* GRID A4: 2 KOLOM */
    .container {
        width: 210mm;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .slot {
        width: 105mm;
        height: 148.5mm;
        padding: 0;
        vertical-align: top;
    }

    /* KUPON SETARA A6 */
    .kupon {
        width: 105mm;
        height: 148.5mm;
        border: 0.3mm solid #000;
        border-collapse: collapse;
        table-layout: fixed;
    }

    /* JUDUL */
    .judul {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding: 5mm 2mm 2mm 2mm;
    }

    /* SUBJUDUL */
    .subjudul {
        text-align: center;
        font-size: 10px;
        padding: 0 2mm 3mm 2mm;
        border-bottom: 0.25mm solid #000;
    }

    /* DATA */
    .data {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .data td {
        font-size: 10px;
        padding: 2.5mm 1mm;
        vertical-align: top;
    }

    .label {
        width: 32%;
    }

    .separator {
        width: 4%;
        text-align: center;
    }

    .value {
        width: 64%;
        word-wrap: break-word;
    }

    /* AREA KOSONG */
    .spacer {
        height: 65mm;
    }

    /* TANDA TANGAN */
    .ttd {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border-top: 0.25mm solid #000;
    }

    .ttd td {
        height: 25mm;
        padding: 1.5mm;
        vertical-align: bottom;
        font-size: 9px;
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
                <td style="padding: 4mm 3mm 0 3mm;">

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
                <td class="spacer">
                    &nbsp;
                </td>
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
| CETAK MAKSIMAL 4 KUPON PER HALAMAN
|--------------------------------------------------------------------------
*/

$total_data = count($rs_cetak_makan_h);

if ($total_data === 0) {

    $mpdf->WriteHTML(
        '<p style="text-align:center;">Data kupon makan tidak ditemukan.</p>',
        \Mpdf\HTMLParserMode::HTML_BODY
    );

} else {

    // Maksimal 4 kupon untuk setiap halaman A4.
    $kelompok_data = array_chunk($rs_cetak_makan_h, 4);

    foreach ($kelompok_data as $halaman => $records) {

        if ($halaman > 0) {
            $mpdf->AddPage('P');
        }

        $html = '
            <table class="container" cellpadding="0" cellspacing="0">
        ';

        // Selalu gunakan grid 2 kolom x 2 baris.
        for ($baris = 0; $baris < 2; $baris++) {

            $html .= '<tr>';

            for ($kolom = 0; $kolom < 2; $kolom++) {

                $index = ($baris * 2) + $kolom;

                if (isset($records[$index])) {

                    $html .= '
                        <td class="slot">
                            ' . buatKuponMakan($records[$index]) . '
                        </td>
                    ';

                } else {

                    // Sel kosong tetap menjaga ukuran kupon lainnya.
                    $html .= '
                        <td class="slot">&nbsp;</td>
                    ';
                }
            }

            $html .= '</tr>';
        }

        $html .= '</table>';

        $mpdf->WriteHTML(
            $html,
            \Mpdf\HTMLParserMode::HTML_BODY
        );
    }
}

/*
|--------------------------------------------------------------------------
| OUTPUT PDF
|--------------------------------------------------------------------------
*/

$mpdf->Output('Kupon_Makan.pdf', 'I');

?>