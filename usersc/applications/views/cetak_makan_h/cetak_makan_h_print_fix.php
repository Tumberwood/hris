
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
            CONCAT(
                CASE DAYOFWEEK(a.tanggal)
                    WHEN 1 THEN "Minggu"
                    WHEN 2 THEN "Senin"
                    WHEN 3 THEN "Selasa"
                    WHEN 4 THEN "Rabu"
                    WHEN 5 THEN "Kamis"
                    WHEN 6 THEN "Jumat"
                    WHEN 7 THEN "Sabtu"
                END,
                ", ",
                DATE_FORMAT(a.tanggal, "%d %M %Y")
            ) AS tanggal_lengkap,
            b.nama AS penerima,
            b.perusahaan,
            b.pic_tamu
        FROM cetak_makan_h a
        JOIN cetak_makan_d b
            ON b.id_cetak_makan_h = a.id
        WHERE a.id = :id_cetak_makan_h
          AND a.is_active = 1
          AND b.is_active = 1
        ORDER BY b.id ASC
    ');

$rs_cetak_makan_h = $qs_cetak_makan_h->fetchAll();

/*
|--------------------------------------------------------------------------
| KONFIGURASI MPDF
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
| WATERMARK SVG
|--------------------------------------------------------------------------
| Satu tulisan untuk setiap pola.
| Ukuran pola diperbesar agar tulisan tidak bertumpuk.
| Teks lengkap: PT PUTRA MANDIRI INTIPACK.
|--------------------------------------------------------------------------
*/

$watermarkSvgPath = sys_get_temp_dir()
    . DIRECTORY_SEPARATOR
    . 'watermark_kupon_makan_' . uniqid('', true) . '.svg';

$watermarkSvg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg"
     width="380" height="260"
     viewBox="0 0 380 260">

    <rect
        x="0"
        y="0"
        width="380"
        height="260"
        fill="none"/>

    <text
        x="40"
        y="145"
        transform="rotate(-35 190 130)"
        fill="#e9a0a0"
        fill-opacity="0.38"
        font-family="Arial, sans-serif"
        font-size="20"
        font-weight="normal"
        textLength="300"
        lengthAdjust="spacingAndGlyphs">PT PUTRA MANDIRI INTIPACK</text>

</svg>
SVG;

if (file_put_contents($watermarkSvgPath, $watermarkSvg) === false) {
    die('Gagal membuat aset watermark.');
}

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
        font-size: 12px;
    }

    .container {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    /* Area setiap kupon dan garis potong */
    .slot {
        width: 50%;
        height: 69mm;
        padding: 1mm;
        vertical-align: top;
        border: none;
    }

    .slot-kiri {
        border-right: 0.25mm dashed #999999;
    }

    .slot-baris {
        border-bottom: 0.25mm dashed #999999;
    }

    /* Watermark transparan dan berjarak */
    .kupon {
        width: 100%;
        height: 66mm;
        border: 0.3mm solid #000000;
        border-collapse: collapse;
        table-layout: fixed;

        background-image: url("' . $watermarkSvgPath . '");
        background-repeat: repeat;
        background-position: center center;
    }

    .judul {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        padding: 1.5mm 0 0.5mm 0;
    }

    .subjudul {
        text-align: center;
        font-size: 12px;
        padding: 0 0 1.5mm 0;
        border-bottom: 0.25mm solid #000000;
    }

    .data {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .data td {
        font-size: 12px;
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
        height: 8mm;
    }

    .ttd {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
        border-top: 0.25mm solid #000000;
    }

    .ttd td {
        height: 13mm;
        padding: 0;
        vertical-align: bottom;
        font-size: 12px;
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

    .slot-kosong {
        width: 50%;
        height: 69mm;
        padding: 1mm;
        vertical-align: top;
        border: none;
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
        $record['tanggal_lengkap'] ?? '',
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
                            <td class="label">Tanggal Berlaku</td>
                            <td class="separator">:</td>
                            <td class="value">' . $tanggal . '</td>
                        </tr>

                        <tr>
                            <td class="label">Penerima Makan</td>
                            <td class="separator">:</td>
                            <td class="value">' . $penerima . '</td>
                        </tr>

                        <tr>
                            <td class="label">Perusahaan</td>
                            <td class="separator">:</td>
                            <td class="value">' . $perusahaan . '</td>
                        </tr>

                        <tr>
                            <td class="label">PIC Tamu PMI</td>
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
                            <td class="ttd-kiri">TTD Bagian HRD</td>
                            <td class="ttd-kanan">TTD Bagian Kantin</td>
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
| 2 kolom x 4 baris.
| Kupon ke-9 dan seterusnya dimulai di halaman baru.
|--------------------------------------------------------------------------
*/

$total_data = count($rs_cetak_makan_h);

if ($total_data === 0) {
    $mpdf->WriteHTML(
        '<p style="text-align:center;">Data kupon makan tidak ditemukan.</p>',
        \Mpdf\HTMLParserMode::HTML_BODY
    );
} else {
    $kelompok_data = array_chunk($rs_cetak_makan_h, 8);

    foreach ($kelompok_data as $halaman => $records) {
        if ($halaman > 0) {
            $mpdf->AddPage('P');
        }

        $html = '
            <table class="container"
                   width="100%"
                   cellpadding="0"
                   cellspacing="0"
                   style="width:100%; table-layout:fixed; border-collapse:collapse;">
                <tbody>
        ';

        $jumlah_data = count($records);

        foreach ($records as $index => $record) {
            if ($index % 2 === 0) {
                $html .= '<tr>';
            }

            $kelas_slot = 'slot';

            if ($index % 2 === 0) {
                $kelas_slot .= ' slot-kiri';
            }

            if (floor($index / 2) < 3) {
                $kelas_slot .= ' slot-baris';
            }

            $html .= '
                <td class="' . $kelas_slot . '"
                    width="50%"
                    style="width:50%; height:69mm; padding:1mm; vertical-align:top;">
                    ' . buatKuponMakan($record) . '
                </td>
            ';

            if ($index % 2 === 1) {
                $html .= '</tr>';
            }
        }

        /* Sel kanan kosong jika jumlah kupon ganjil */
        if ($jumlah_data % 2 !== 0) {
            $kelas_kosong = 'slot-kosong';

            if (floor(($jumlah_data - 1) / 2) < 3) {
                $kelas_kosong .= ' slot-baris';
            }

            $html .= '
                <td class="' . $kelas_kosong . '"
                    width="50%"
                    style="width:50%; height:69mm; padding:1mm;">
                    &nbsp;
                </td>
                </tr>
            ';
        }

        $html .= '
                </tbody>
            </table>
        ';

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

if (is_file($watermarkSvgPath)) {
    @unlink($watermarkSvgPath);
}

?>
