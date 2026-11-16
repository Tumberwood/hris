
<?php
include_once("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");
include("../../../../usersc/helpers/datatables_fn_debug.php");
require '../../../../usersc/vendor/autoload.php';

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| FILTER DATA
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
| QUERY DATA SLIP GAJI
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
            h.nama AS status,

            -- GAJI DAN TUNJANGAN
            a.gp,
            a.t_jab,
            a.fix_cost,
            a.trm_jkkjkm,
            a.terima_lain,

            -- LEMBUR
            a.jam_lembur,
            a.lemburbersih,
            a.lembur15,
            a.lembur2,
            a.lembur3,

            -- POTONGAN
            a.pot_bpjs,
            a.pot_jht,
            a.pot_psiun,
            a.pot_pph21,
            a.pot_jkkjkm,
            a.pot_makan,
            a.pot_upah,

            (
                IFNULL(a.pot_pinjaman, 0)
                + IFNULL(a.pot_klaim, 0)
                + IFNULL(a.pot_denda_apd, 0)
            ) AS pot_lain,

            a.pot_upah AS tidak_masuk,

            -- TOTAL TUNJANGAN TIDAK TETAP
            (
                IFNULL(a.fix_cost, 0)
                + IFNULL(a.trm_jkkjkm, 0)
                + IFNULL(a.terima_lain, 0)
            ) AS total_tunjangan_tidak_tetap,

            -- TOTAL POTONGAN
            (
                IFNULL(a.pot_makan, 0)
                + IFNULL(a.pot_bpjs, 0)
                + IFNULL(a.pot_jht, 0)
                + IFNULL(a.pot_psiun, 0)
                + IFNULL(a.pot_pph21, 0)
                + IFNULL(a.pot_jkkjkm, 0)
                + IFNULL(a.pot_pinjaman, 0)
                + IFNULL(a.pot_klaim, 0)
                + IFNULL(a.pot_denda_apd, 0)
                + IFNULL(a.pot_upah, 0)
            ) AS total_pot,

            a.gaji_bersih,
            a.gaji_terima,

            DATE_FORMAT(CURDATE(), '%d-%m-%Y') AS tanggal,
            UPPER(DATE_FORMAT(g.tanggal_akhir, '%M %Y')) AS title

        FROM hpyemtd AS a

        LEFT JOIN hemxxmh AS b
            ON b.id = a.id_hemxxmh

        LEFT JOIN hemjbmh AS c
            ON c.id_hemxxmh = a.id_hemxxmh

        LEFT JOIN hodxxmh AS d
            ON d.id = c.id_hodxxmh

        LEFT JOIN hevxxmh AS e
            ON e.id = c.id_hevxxmh

        LEFT JOIN hevgrmh AS f
            ON f.id = e.id_hevgrmh

        LEFT JOIN hpyxxth AS g
            ON g.id = a.id_hpyxxth

        LEFT JOIN hosxxmh AS h
            ON h.id = c.id_hosxxmh

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
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
};

$rupiah = function ($value) {
    return number_format((float) ($value ?? 0), 0, ',', '.');
};

$decimal = function ($value) {
    return number_format((float) ($value ?? 0), 2, ',', '.');
};

/*
|--------------------------------------------------------------------------
| MPDF CONFIGURATION
|--------------------------------------------------------------------------
*/

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [95, 140],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 5,
    'margin_footer' => 5,
]);

$mpdf->SetProtection(['print']);
$mpdf->SetTitle('Slip Gaji');
$mpdf->SetDisplayMode('fullpage');

$stylesheet = file_get_contents(
    '../../../templates/inspinia-side/assets/css/print_mpdf.css'
);

/*
|--------------------------------------------------------------------------
| LOOP SLIP GAJI
|--------------------------------------------------------------------------
*/

foreach ($rs_hpyxxth as $index => $record) {

    /*
    |--------------------------------------------------------------------------
    | IDENTITAS
    |--------------------------------------------------------------------------
    */

    $identitas = '
        <table class="pn identitas" style="font-size:8px;">
            <tr>
                <td width="17%">No. Induk</td>
                <td width="3%">:</td>
                <td colspan="3">' . $esc($record['nik']) . '</td>

                <td width="17%">Kelompok</td>
                <td width="3%">:</td>
                <td colspan="3">' . $esc($record['kelompok']) . '</td>
            </tr>

            <tr>
                <td>Nama</td>
                <td>:</td>
                <td colspan="3">' . $esc($record['peg']) . '</td>

                <td>Grade</td>
                <td>:</td>
                <td colspan="3">' . $esc($record['grade']) . '</td>
            </tr>

            <tr>
                <td>Bagian</td>
                <td>:</td>
                <td colspan="3">' . $esc($record['bagian']) . '</td>

                <td>Status</td>
                <td>:</td>
                <td colspan="3">' . $esc($record['status']) . '</td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | GAJI POKOK
    |--------------------------------------------------------------------------
    */

    $tbl_gaji_pokok = '
        <tr>
            <td width="8%">
                <h4>I</h4>
            </td>
            <td>
                <h4>Gaji Pokok</h4>
            </td>
        </tr>

        <tr>
            <td></td>
            <td>
                <p>- Gaji Pokok</p>
            </td>
            <td class="text-right">
                ' . $rupiah($record['gp']) . '
            </td>
        </tr>
    ';

    /*
    |--------------------------------------------------------------------------
    | TUNJANGAN TETAP
    |--------------------------------------------------------------------------
    */

    $tbl_tunjangan_tetap = '
        <tr>
            <td width="8%">
                <h4>II</h4>
            </td>
            <td>
                <h4>Tunjangan Tetap</h4>
            </td>
        </tr>
    ';

    if ((float) $record['t_jab'] > 0) {
        $tbl_tunjangan_tetap .= '
            <tr>
                <td></td>
                <td>
                    <p>- Tj. Jabatan</p>
                </td>
                <td class="text-right">
                    ' . $rupiah($record['t_jab']) . '
                </td>
            </tr>
        ';
    }

    /*
    |--------------------------------------------------------------------------
    | TUNJANGAN TIDAK TETAP
    |--------------------------------------------------------------------------
    */

    $tbl_tunjangan_tidak_tetap = '';

    if ((float) $record['total_tunjangan_tidak_tetap'] > 0) {

        $tbl_tunjangan_tidak_tetap .= '
            <tr>
                <td width="8%">
                    <h4>III</h4>
                </td>
                <td>
                    <h4>Tunjangan Tidak Tetap</h4>
                </td>
            </tr>
        ';

        if ((float) $record['fix_cost'] > 0) {
            $tbl_tunjangan_tidak_tetap .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- Tj. Masa Kerja</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['fix_cost']) . '
                    </td>
                </tr>
            ';
        }

        if ((float) $record['trm_jkkjkm'] > 0) {
            $tbl_tunjangan_tidak_tetap .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- JKK JKM</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['trm_jkkjkm']) . '
                    </td>
                </tr>
            ';
        }

        if ((float) $record['terima_lain'] > 0) {
            $tbl_tunjangan_tidak_tetap .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- Lain-lain</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['terima_lain']) . '
                    </td>
                </tr>
            ';
        }

        $tbl_tunjangan_tidak_tetap .= '
            <tr>
                <td></td>
                <td></td>
                <td class="text-right">______________</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td class="text-right">
                    ' . $rupiah($record['total_tunjangan_tidak_tetap']) . '
                </td>
            </tr>
        ';
    }

    /*
    |--------------------------------------------------------------------------
    | POTONGAN
    |--------------------------------------------------------------------------
    */

    $tbl_potongan = '';

    if ((float) $record['total_pot'] > 0) {

        $tbl_potongan .= '
            <tr>
                <td width="8%">
                    <h4>V</h4>
                </td>
                <td>
                    <h4>Potongan</h4>
                </td>
            </tr>
        ';

        // BPJS KESEHATAN
        if ((float) $record['pot_bpjs'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- BPJS-K</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_bpjs']) . '
                    </td>
                </tr>
            ';
        }

        // BPJS TK JHT
        if ((float) $record['pot_jht'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- BPJS-TK (JHT)</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_jht']) . '
                    </td>
                </tr>
            ';
        }

        // BPJS TK JP
        if ((float) $record['pot_psiun'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- BPJS-TK (JP)</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_psiun']) . '
                    </td>
                </tr>
            ';
        }

        // PPH 21
        if ((float) $record['pot_pph21'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- PPh 21</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_pph21']) . '
                    </td>
                </tr>
            ';
        } else {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- PPh 21</p>
                    </td>
                    <td class="text-right">0</td>
                </tr>
            ';
        }

        // JKK JKM
        if ((float) $record['pot_jkkjkm'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- JKK JKM</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_jkkjkm']) . '
                    </td>
                </tr>
            ';
        }

        // TIDAK MASUK
        if ((float) $record['tidak_masuk'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- Tidak Masuk</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['tidak_masuk']) . '
                    </td>
                </tr>
            ';
        }

        // MAKAN
        if ((float) $record['pot_makan'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- Makan</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_makan']) . '
                    </td>
                </tr>
            ';
        }

        // POTONGAN LAIN-LAIN
        if ((float) $record['pot_lain'] > 0) {
            $tbl_potongan .= '
                <tr>
                    <td></td>
                    <td>
                        <p>- Lain-lain</p>
                    </td>
                    <td class="text-right">
                        ' . $rupiah($record['pot_lain']) . '
                    </td>
                </tr>
            ';
        }

        $tbl_potongan .= '
            <tr>
                <td></td>
                <td></td>
                <td class="text-right">______________</td>
            </tr>

            <tr>
                <td></td>
                <td></td>
                <td class="text-right">
                    ' . $rupiah($record['total_pot']) . '
                </td>
            </tr>
        ';
    }

    /*
    |--------------------------------------------------------------------------
    | LEMBUR
    |--------------------------------------------------------------------------
    */

    $tbl_lembur = '
        <table class="pn" style="font-size:8px;">
            <tr>
                <td width="5%">
                    <h4>IV</h4>
                </td>
                <td width="40%">
                    <h4>Lembur</h4>
                </td>
                <td colspan="2"></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <h4>
                        ' . $decimal($record['jam_lembur']) . ' Jam =
                        ' . $rupiah($record['lemburbersih']) . '
                    </h4>
                </td>
                <td colspan="2"></td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | RINCIAN LEMBUR
    |--------------------------------------------------------------------------
    */

    $tbl_rincian_lembur = '
        <table class="table-ba" style="font-size:8px;">
            <tr>
                <td colspan="2" class="text-center">
                    <h4>Rincian Jam Lembur</h4>
                </td>
            </tr>

            <tr>
                <td width="40%">
                    <h4>Pertama</h4>
                </td>
                <td class="text-right">
                    ' . $decimal($record['lembur15']) . '
                </td>
            </tr>

            <tr>
                <td>
                    <h4>Kedua</h4>
                </td>
                <td class="text-right">
                    ' . $decimal($record['lembur2']) . '
                </td>
            </tr>

            <tr>
                <td>
                    <h4>Ketiga</h4>
                </td>
                <td class="text-right">
                    ' . $decimal($record['lembur3']) . '
                </td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | TAKE HOME PAY DAN TANDA TERIMA
    |--------------------------------------------------------------------------
    */

    $tbl_take_home_pay = '
        <table class="table" style="font-size:8px;">
            <tr>
                <td width="60%">
                    <h4>Take Home Pay</h4>
                </td>
                <td class="text-right">
                    ' . $rupiah($record['gaji_bersih']) . '
                </td>
            </tr>

            <tr>
                <td>
                    <h4>Diterima</h4>
                </td>
                <td class="text-right">
                    ' . $rupiah($record['gaji_terima']) . '
                </td>
            </tr>

            <tr>
                <td></td>
                <td></td>
            </tr>

            <tr>
                <td>Sidoarjo, ' . $esc($record['tanggal']) . '</td>
                <td></td>
            </tr>

            <tr>
                <td>Diterima Oleh,</td>
                <td></td>
            </tr>

            <tr>
                <td>
                    <br><br><br><br><br>
                </td>
                <td></td>
            </tr>

            <tr>
                <td>' . $esc($record['peg']) . '</td>
                <td></td>
            </tr>
        </table>
    ';

    /*
    |--------------------------------------------------------------------------
    | HTML SLIP GAJI
    |--------------------------------------------------------------------------
    */

    $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: sans-serif;
                    font-size: 8px;
                }

                h4 {
                    font-size: 8px;
                    font-weight: bold;
                    margin: 2px 0;
                }

                p {
                    margin: 2px 0;
                }

                table {
                    border-collapse: collapse;
                    width: 100%;
                }

                .text-right {
                    text-align: right;
                }

                .text-center {
                    text-align: center;
                }

                .identitas td {
                    padding: 1px 0;
                }

                .pn td {
                    vertical-align: top;
                }
            </style>
        </head>

        <body>

            <h4 class="text-center">
                SLIP GAJI ' . $esc($record['title']) . '
            </h4>

            ' . $identitas . '

            <br>

            <table class="pn" style="font-size:8px;">
                <tr>
                    <td width="45%" style="vertical-align:top;">
                        <table class="pn" style="font-size:8px;">
                            ' . $tbl_gaji_pokok . '
                            ' . $tbl_tunjangan_tetap . '
                            ' . $tbl_tunjangan_tidak_tetap . '
                        </table>
                    </td>

                    <td width="5%"></td>

                    <td width="50%" style="vertical-align:top;">
                        <table class="pn" style="font-size:8px;">
                            ' . $tbl_potongan . '
                        </table>
                    </td>
                </tr>
            </table>

            <br>

            ' . $tbl_lembur . '

            <br>

            <table class="pn" style="font-size:8px;">
                <tr>
                    <td width="50%" style="vertical-align:top;">
                        ' . $tbl_rincian_lembur . '
                    </td>

                    <td width="50%" style="vertical-align:top;">
                        ' . $tbl_take_home_pay . '
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

    if (strip_tags($html) !== '') {

        if ($index > 0) {
            $mpdf->AddPage('P');
        }

        $mpdf->WriteHTML(
            $stylesheet,
            \Mpdf\HTMLParserMode::HEADER_CSS
        );

        $mpdf->WriteHTML($html);
    }
}

/*
|--------------------------------------------------------------------------
| OUTPUT PDF
|--------------------------------------------------------------------------
*/

$mpdf->Output();
