<?php

include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| FILTER
|--------------------------------------------------------------------------
*/

$id_hpyxxth = isset($_GET['id_hpyxxth']) ? (int) $_GET['id_hpyxxth'] : 0;
$id_heyxxmd = isset($_GET['id_heyxxmd']) ? (int) $_GET['id_heyxxmd'] : 0;
$id_hesxxmh = isset($_GET['id_hesxxmh']) ? (int) $_GET['id_hesxxmh'] : 0;

$where = "
    WHERE a.id_hpyxxth = {$id_hpyxxth}
    AND c.id_heyxxmd = {$id_heyxxmd}
";

if ($id_hesxxmh > 0) {
    $where .= " AND c.id_hesxxmh = {$id_hesxxmh}";
}

/*
|--------------------------------------------------------------------------
| QUERY DATA
|--------------------------------------------------------------------------
*/

$qs_hpyxxth = $db
    ->raw()
    ->exec("
        SELECT
            b.kode AS nik,
            b.nama AS peg,

            d.nama AS bagian,
            e.nama AS grade,
            f.nama AS kelompok,
            ptkp.kode AS status,

            -- GAJI
            IFNULL(a.gp, 0) AS gp,
            IFNULL(a.t_jab, 0) AS t_jab,

            -- TUNJANGAN TIDAK TETAP
            IFNULL(a.fix_cost, 0) AS fix_cost,
            IFNULL(a.jkk, 0) + IFNULL(a.jkm, 0) AS trm_jkkjkm,
            IFNULL(a.terima_lain, 0) AS terima_lain,

            (
                IFNULL(a.fix_cost, 0)
                + IFNULL(a.jkk, 0) + IFNULL(a.jkm, 0)
                + IFNULL(a.terima_lain, 0)
            ) AS total_tunjangan,

            -- LEMBUR
            IFNULL(a.total_lembur_jam_final, 0) AS total_lembur_jam_final,
            IFNULL(a.total_rp_lembur, 0) AS total_rp_lembur,
            IFNULL(a.lembur15, 0) AS lembur15,
            IFNULL(a.lembur2, 0) AS lembur2,
            IFNULL(a.lembur3, 0) AS lembur3,

            -- POTONGAN
            IFNULL(a.bpjs_kes_karyawan, 0) AS pot_bpjs,
            IFNULL(a.pot_jht_karyawan, 0) AS pot_jht,
            IFNULL(a.pot_jp_karyawan, 0) AS pot_psiun,
            IFNULL(a.pot_pph21, 0) AS pot_pph21,
            IFNULL(a.jkk, 0) + IFNULL(a.jkm, 0) AS pot_jkkjkm,
            IFNULL(a.pot_upah, 0) + IFNULL(a.pot_jam, 0) AS pot_upah,
            IFNULL(a.pot_makan, 0) AS pot_makan,

            (
                IFNULL(a.bpjs_kes_karyawan, 0)
                + IFNULL(a.pot_jht_karyawan, 0)
                + IFNULL(a.pot_jp_karyawan, 0)
                + IFNULL(a.pot_pph21, 0)
                + IFNULL(a.jkk, 0) + IFNULL(a.jkm, 0)
                + IFNULL(a.pot_upah, 0) + + IFNULL(a.pot_jam, 0)
                + IFNULL(a.pot_makan, 0)
            ) AS total_pot,

            -- GAJI DITERIMA
            IFNULL(a.gaji_bersih, 0) AS gaji_bersih,
            IFNULL(a.gaji_terima, 0) AS gaji_terima,

            -- TANGGAL PERIODE GAJI
            DATE_FORMAT(curdate(), '%d-%m-%Y') AS tanggal,
            UPPER(DATE_FORMAT(g.tanggal_akhir, '%M %Y')) AS title

        FROM hpyemtd AS a
        LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
        LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
        LEFT JOIN hemdcmh AS dc ON dc.id_hemxxmh = a.id_hemxxmh

        LEFT JOIN hobxxmh AS d ON d.id = c.id_hobxxmh
        LEFT JOIN hevxxmh AS e ON e.id = c.id_hevxxmh
        LEFT JOIN hevgrmh AS f ON f.id = c.id_hevgrmh
        LEFT JOIN hpyxxth AS g ON g.id = a.id_hpyxxth
        LEFT JOIN hesxxmh AS h ON h.id = c.id_hesxxmh
        LEFT JOIN gtxpkmh AS ptkp ON ptkp.id = dc.id_gtxpkmh

        {$where}

        ORDER BY CONCAT(b.kode, ' - ', b.nama)
    ");

$rs_hpyxxth = $qs_hpyxxth->fetchAll();

/*
|--------------------------------------------------------------------------
| HELPER
|--------------------------------------------------------------------------
*/

$esc = function ($value) {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

// Format sesuai gambar: pemisah ribuan koma (,) dan desimal titik (.)
$rupiah = function ($value) {
    return number_format((float) ($value ?? 0), 0, '.', ',');
};

$decimal = function ($value) {
    return number_format((float) ($value ?? 0), 2, '.', ',');
};

// Format bulan Bahasa Indonesia untuk judul slip
$bulanIndo = function ($title) {
    $map = [
        'JANUARY'   => 'JANUARI',
        'FEBRUARY'  => 'FEBRUARI',
        'MARCH'     => 'MARET',
        'APRIL'     => 'APRIL',
        'MAY'       => 'MEI',
        'JUNE'      => 'JUNI',
        'JULY'      => 'JULI',
        'AUGUST'    => 'AGUSTUS',
        'SEPTEMBER' => 'SEPTEMBER',
        'OCTOBER'   => 'OKTOBER',
        'NOVEMBER'  => 'NOVEMBER',
        'DECEMBER'  => 'DESEMBER',
    ];
    return strtr((string) $title, $map);
};

/*
|--------------------------------------------------------------------------
| HELPER BARIS
|--------------------------------------------------------------------------
*/

$baris = function ($label, $nilai, $format = 'rupiah') use ($rupiah, $decimal) {
    $value = $format === 'decimal'
        ? $decimal($nilai)
        : $rupiah($nilai);

    return '
        <tr>
            <td class="col-num"></td>
            <td class="indent">- ' . $label . '</td>
            <td class="nominal">' . $value . '</td>
        </tr>
    ';
};

/*
|--------------------------------------------------------------------------
| MPDF
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [95, 140],

    'margin_left' => 4,
    'margin_right' => 4,
    'margin_top' => 4,
    'margin_bottom' => 4,

    'margin_header' => 0,
    'margin_footer' => 0,

    'default_font' => 'sans-serif',
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Slip Gaji');
$mpdf->SetDisplayMode('fullpage');

/*
|--------------------------------------------------------------------------
| CSS SLIP GAJI
|--------------------------------------------------------------------------
*/

$css = '
    body {
        font-family: sans-serif;
        font-size: 7.5px;
        color: #111;
    }

    h1 {
        font-size: 9.5px;
        font-weight: bold;
        text-align: center;
        margin: 0 0 3px 0;
        padding-bottom: 2px;
        border-bottom: 1.5px double #111;
        letter-spacing: 0.3px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    td {
        padding: 1.2px 0;
        vertical-align: top;
    }

    /* IDENTITAS */
    .identitas {
        font-size: 7.5px;
        margin-bottom: 6px;
    }

    .identitas td {
        padding: 1px 0;
    }

    .identitas .label-left {
        width: 14%;
        white-space: nowrap;
    }

    .identitas .sep {
        width: 2%;
        text-align: center;
    }

    .identitas .val-left {
        width: 39%;
    }

    .identitas .label-right {
        width: 14%;
        text-align: right;
        padding-right: 2px;
        white-space: nowrap;
    }

    .identitas .val-right {
        width: 29%;
        white-space: nowrap;
    }

    /* LAYOUT UTAMA 2 KOLOM */
    .content {
        width: 100%;
        table-layout: fixed;
    }

    .left-column {
        width: 49%;
        vertical-align: top;
    }

    .space-column {
        width: 3%;
    }

    .right-column {
        width: 48%;
        vertical-align: top;
    }

    .section {
        margin-bottom: 6px;
    }

    .col-num {
        width: 12%;
        font-weight: bold;
    }

    .section-title {
        font-weight: bold;
        width: 56%;
        white-space: nowrap;
    }

    .indent {
        padding-left: 3px;
        width: 56%;
        white-space: nowrap;
    }

    .nominal {
        text-align: right;
        width: 32%;
        white-space: nowrap;
    }

    .subtotal-line {
        border-top: 0.8px solid #111;
        text-align: right;
        padding-top: 2px;
    }

    .spacer-row td {
        height: 6px;
    }

    .spacer-tunjangan td {
        height: 16px;
    }

    /* BAGIAN BAWAH (RINCIAN LEMBUR & PENERIMAAN) */
    .bottom {
        margin-top: 4px;
        width: 100%;
        table-layout: fixed;
    }

    .rincian-col {
        width: 48%;
        vertical-align: top;
    }

    .penerimaan-col {
        width: 52%;
        vertical-align: top;
        padding-left: 6px;
    }

    .rincian-box {
        width: 78%;
    }

    .rincian-title {
        border-bottom: 0.8px solid #111;
        padding-bottom: 1.5px;
        margin-bottom: 2px;
    }

    .rincian-row td {
        padding: 1.5px 0;
    }

    .takehome {
        font-weight: bold;
        padding: 1.2px 0;
    }

    .tanggal {
        padding-top: 8px;
    }

    .ttd {
        height: 36px;
    }

    .nama-penerima {
        padding-top: 2px;
    }
';

/*
|--------------------------------------------------------------------------
| LOOP DATA KARYAWAN
|--------------------------------------------------------------------------
*/

foreach ($rs_hpyxxth as $index => $record) {

    /*
    |--------------------------------------------------------------------------
    | KOLOM KIRI: I. GAJI POKOK, II. TUNJANGAN TETAP, III. TUNJANGAN TIDAK TETAP, IV. LEMBUR
    |--------------------------------------------------------------------------
    */

    // I. Gaji Pokok (Nominal sejajar langsung dengan judul sesuai gambar)
    $html_gaji = '
        <table class="section">
            <tr>
                <td class="col-num">I</td>
                <td class="section-title">Gaji Pokok</td>
                <td class="nominal">' . $rupiah($record['gp']) . '</td>
            </tr>
        </table>
    ';

    // II. Tunjangan Tetap
    $html_tunjangan_tetap = '
        <table class="section">
            <tr>
                <td class="col-num">II</td>
                <td colspan="2" class="section-title">Tunjangan Tetap</td>
            </tr>
    ';

    if ($record['t_jab'] > 0) {
        $html_tunjangan_tetap .= $baris('Tj. Jabatan', $record['t_jab']);
    }

    $html_tunjangan_tetap .= '</table>';

    // III. Tunjangan Tidak Tetap
    $html_tunjangan_tidak_tetap = '
        <table class="section">
            <tr>
                <td class="col-num">III</td>
                <td colspan="2" class="section-title">Tunjangan Tidak Tetap</td>
            </tr>
            <tr class="spacer-tunjangan"><td colspan="3"></td></tr>
    ';

    if ($record['fix_cost'] > 0) {
        $html_tunjangan_tidak_tetap .= $baris('Tj. Masa Kerja', $record['fix_cost']);
    }

    if ($record['trm_jkkjkm'] > 0) {
        $html_tunjangan_tidak_tetap .= $baris('JKK JKM', $record['trm_jkkjkm']);
    }

    if ($record['terima_lain'] > 0) {
        $html_tunjangan_tidak_tetap .= $baris('Lain-lain', $record['terima_lain']);
    }

    if ($record['total_tunjangan'] > 0) {
        $html_tunjangan_tidak_tetap .= '
            <tr>
                <td></td>
                <td colspan="2" class="subtotal-line">' . $rupiah($record['total_tunjangan']) . '</td>
            </tr>
        ';
    }

    $html_tunjangan_tidak_tetap .= '</table>';

    // IV. Lembur (Berada di bawah Tunjangan Tidak Tetap pada kolom kiri)
    $html_lembur = '
        <table class="section" style="margin-bottom: 0;">
            <tr>
                <td class="col-num">IV</td>
                <td colspan="2" class="section-title">Lembur</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right; padding-right: 4px; white-space: nowrap;">
                    ' . $decimal($record['total_lembur_jam_final']) . ' Jam =
                </td>
                <td class="nominal">
                    ' . $rupiah($record['total_rp_lembur']) . '
                </td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | KOLOM KANAN: V. POTONGAN
    |--------------------------------------------------------------------------
    */

    $html_potongan = '
        <table class="section">
            <tr>
                <td class="col-num">V</td>
                <td colspan="2" class="section-title">Potongan</td>
            </tr>
    ';

    if ($record['pot_bpjs'] > 0) {
        $html_potongan .= $baris('BPJS - K', $record['pot_bpjs']);
    }

    if ($record['pot_jht'] > 0) {
        $html_potongan .= $baris('BPJS-TK (JHT)', $record['pot_jht']);
    }

    if ($record['pot_psiun'] > 0) {
        $html_potongan .= $baris('BPJS-TK (JP)', $record['pot_psiun']);
    }

    // Jeda baris sebelum PPh 21 sesuai gambar
    $html_potongan .= '<tr class="spacer-row"><td colspan="3"></td></tr>';

    // PPh 21 selalu ditampilkan, termasuk jika nilainya 0
    $html_potongan .= $baris('PPh 21', $record['pot_pph21']);

    if ($record['pot_jkkjkm'] > 0) {
        $html_potongan .= $baris('JKK JKM', $record['pot_jkkjkm']);
    }

    if ($record['pot_upah'] > 0) {
        $html_potongan .= '<tr class="spacer-row"><td colspan="3"></td></tr>';
        $html_potongan .= $baris('Tidak Masuk', $record['pot_upah']);
    }

    if ($record['pot_makan'] > 0) {
        $html_potongan .= $baris('Makan', $record['pot_makan']);
    }

    $html_potongan .= '
            <tr class="spacer-row"><td colspan="3"></td></tr>
            <tr>
                <td></td>
                <td></td>
                <td class="subtotal-line">' . $rupiah($record['total_pot']) . '</td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | RINCIAN JAM LEMBUR (KIRI BAWAH)
    |--------------------------------------------------------------------------
    */

    $html_rincian_lembur = '
        <table class="rincian-box">
            <tr>
                <td colspan="2" class="rincian-title">Rincian Jam Lembur</td>
            </tr>
    ';

    if ($record['lembur15'] > 0) {
        $html_rincian_lembur .= '
            <tr class="rincian-row">
                <td style="padding-left: 4px; width: 60%;">Pertama =</td>
                <td style="text-align: right; width: 40%;">' . $decimal($record['lembur15']) . '</td>
            </tr>
        ';
    }

    if ($record['lembur2'] > 0) {
        $html_rincian_lembur .= '
            <tr class="rincian-row">
                <td style="padding-left: 4px;">Kedua =</td>
                <td style="text-align: right;">' . $decimal($record['lembur2']) . '</td>
            </tr>
        ';
    }

    if ($record['lembur3'] > 0) {
        $html_rincian_lembur .= '
            <tr class="rincian-row">
                <td style="padding-left: 4px;">Ketiga =</td>
                <td style="text-align: right;">' . $decimal($record['lembur3']) . '</td>
            </tr>
        ';
    }

    $html_rincian_lembur .= '</table>';

    /*
    |--------------------------------------------------------------------------
    | TAKE HOME PAY DAN TANDA TERIMA (KANAN BAWAH)
    |--------------------------------------------------------------------------
    */

    $html_penerimaan = '
        <table>
            <tr>
                <td width="58%" class="takehome">Take Home Pay</td>
                <td class="nominal takehome">' . $rupiah($record['gaji_bersih']) . '</td>
            </tr>

            <tr>
                <td class="takehome">Diterima</td>
                <td class="nominal takehome">' . $rupiah($record['gaji_terima']) . '</td>
            </tr>

            <tr>
                <td colspan="2" class="tanggal">
                    Sidoarjo, &nbsp;&nbsp;' . $esc($record['tanggal']) . '
                </td>
            </tr>

            <tr>
                <td colspan="2">Diterima Oleh,</td>
            </tr>

            <tr>
                <td colspan="2" class="ttd"></td>
            </tr>

            <tr>
                <td colspan="2" class="nama-penerima">
                    ' . $esc($record['peg']) . '
                </td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | SUSUNAN AKHIR SLIP GAJI
    |--------------------------------------------------------------------------
    */

    $html = '
        <html>
        <head>
            <style>
                ' . $css . '
            </style>
        </head>

        <body>

            <h1>
                SLIP GAJI ' . $esc($bulanIndo($record['title'])) . '
            </h1>

            <!-- IDENTITAS -->
            <table class="identitas">
                <tr>
                    <td class="label-left">No. Induk</td>
                    <td class="sep">:</td>
                    <td class="val-left">' . $esc($record['nik']) . '</td>

                    <td class="label-right">Kelompok</td>
                    <td class="sep">:</td>
                    <td class="val-right">' . $esc($record['kelompok']) . '</td>
                </tr>

                <tr>
                    <td class="label-left" style="letter-spacing: 2.3px;">Nama</td>
                    <td class="sep">:</td>
                    <td class="val-left">' . $esc($record['peg']) . '</td>

                    <td class="label-right">Grade</td>
                    <td class="sep">:</td>
                    <td class="val-right">' . $esc($record['grade']) . '</td>
                </tr>

                <tr>
                    <td class="label-left">Bagian</td>
                    <td class="sep">:</td>
                    <td class="val-left">' . $esc($record['bagian']) . '</td>

                    <td class="label-right">Status</td>
                    <td class="sep">:</td>
                    <td class="val-right">' . $esc($record['status']) . '</td>
                </tr>
            </table>

            <!-- PENDAPATAN, LEMBUR, DAN POTONGAN -->
            <table class="content">
                <tr>
                    <td class="left-column">
                        ' . $html_gaji . '
                        ' . $html_tunjangan_tetap . '
                        ' . $html_tunjangan_tidak_tetap . '
                        ' . $html_lembur . '
                    </td>

                    <td class="space-column"></td>

                    <td class="right-column">
                        ' . $html_potongan . '
                    </td>
                </tr>
            </table>

            <!-- RINCIAN LEMBUR DAN PENERIMAAN -->
            <table class="bottom">
                <tr>
                    <td class="rincian-col">
                        ' . $html_rincian_lembur . '
                    </td>

                    <td class="penerimaan-col">
                        ' . $html_penerimaan . '
                    </td>
                </tr>
            </table>

        </body>
        </html>
    ';

    /*
    |--------------------------------------------------------------------------
    | WRITE PDF
    |--------------------------------------------------------------------------
    */

    if ($index > 0) {
        $mpdf->AddPage('P');
    }

    $mpdf->WriteHTML($html);
}

/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/

$mpdf->Output();

?>