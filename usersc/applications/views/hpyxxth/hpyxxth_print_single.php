<?php
	include_once( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );
	require '../../../../usersc/vendor/autoload.php';

	use Carbon\Carbon;

	// BEGIN select header
	$qs_hpyxxth = $db
		->raw()
			->bind(':id_hpyemtd', $_GET['id_hpyemtd'])
			->exec(' SELECT
						b.kode as nik,
						b.nama as peg,
						d.nama as bagian,
						e.nama as grade,
						f.nama as kelompok,
						h.nama as status,
						a.gp,
						a.t_jab,
						a.fix_cost,
						a.trm_jkkjkm,
						a.jam_lembur,
						a.lemburbersih,
						a.lembur15,
						a.lembur2,
						a.lembur3,
						a.pot_bpjs,
						a.pot_jht,
						a.pot_psiun,
						a.pot_pph21,
						a.pot_jkkjkm,
						(a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd) AS pot_lain,
						a.pot_upah AS tidak_masuk,
						a.pot_makan,
						a.pot_bpjs + a.pot_jht + a.pot_psiun + a.pot_pph21 + a.pot_jkkjkm + (a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd) + a.pot_upah AS total_pot,
						a.fix_cost + a.trm_jkkjkm AS tunjangan,
						gaji_bersih,
						a.gaji_terima,
						DATE_FORMAT(CURDATE(), "%d %b %Y") AS tanggal,
						UPPER(DATE_FORMAT(g.tanggal_akhir, "%M %Y")) AS title
					FROM hpyemtd AS a
					LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
					LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
					LEFT JOIN hodxxmh AS d ON d.id = c.id_hodxxmh
					LEFT JOIN hevxxmh AS e ON e.id = c.id_hevxxmh
					LEFT JOIN hevgrmh AS f ON f.id = e.id_hevgrmh
					LEFT JOIN hpyxxth AS g ON g.id = a.id_hpyxxth
					LEFT JOIN hosxxmh AS h ON h.id = c.id_hosxxmh
					WHERE a.id = :id_hpyemtd
					ORDER BY CONCAT(b.kode, " - ", b.nama)
					'
					);
	$rs_hpyxxth = $qs_hpyxxth->fetch();
	
	// render ke mpdf
	$mpdf = new \Mpdf\Mpdf([
		'margin_left' => 5,
		'margin_right' => 5,
		'margin_top' => 5, // table ke header
		'margin_bottom' => 5,
		'margin_header' => 5,	// kertas ke header
		'margin_footer' => 5,	// kertas ke footer
		'format' => [95, 140], // mm
	]);
	$mpdf->AddPage('P');
    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("");
    $mpdf->SetDisplayMode('fullpage');

	$pph21 = '';
	if ($rs_hpyxxth['pot_pph21'] != 0) {
		$pph21 .= '<td width="5%"></td>';
		$pph21 .= '<td width="24%">- PPh21</td>';
		$pph21 .= '<td colspan="2" class="text-right">' . number_format($rs_hpyxxth['pot_pph21'], 2, ',', '.') . '</td>';
	}
		
	$pot_lain = '';
	if ($rs_hpyxxth['pot_lain'] != 0) {
		$pot_lain .= '<td width="5%"></td>';
		$pot_lain .= '<td width="24%">- Lain-lain</td>';
		$pot_lain .= '<td colspan="2" class="text-right">' . number_format($rs_hpyxxth['pot_lain'], 2, ',', '.') . '</td>';
	}
		
	$tidak_masuk = '';
	if ($rs_hpyxxth['tidak_masuk'] != 0) {
		$tidak_masuk .= '<td width="5%"></td>';
		$tidak_masuk .= '<td width="24%">- Tidak Masuk</td>';
		$tidak_masuk .= '<td colspan="2" class="text-right">' . number_format($rs_hpyxxth['tidak_masuk'], 2, ',', '.') . '</td>';
	}
		
	$pot_makan = '';
	if ($rs_hpyxxth['pot_makan'] != 0) {
		$pot_makan .= '<td width="5%"></td>';
		$pot_makan .= '<td width="24%">- Makan</td>';
		$pot_makan .= '<td colspan="2" class="text-right">' . number_format($rs_hpyxxth['pot_makan'], 2, ',', '.') . '</td>';
	}
	
	$html = '
    <html>
        <head></head>
        <body>
            <htmlpageheader name="myheader"></htmlpageheader>
            <htmlpagefooter name="myfooter"></htmlpagefooter>';

			// Start the loop for each record
			$html .= '
				<h4 class="text-center"> SLIP GAJI ' . $rs_hpyxxth['title'] . '</h4>
				<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
				<sethtmlpagefooter name="myfooter" value="on" />
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="17%">No Induk</td>
						<td width="3%">:</td>
						<td colspan="3">' . $rs_hpyxxth['nik'] . '</td>
						<td width="17%">Kelompok</td>
						<td width="3%">:</td>
						<td colspan="3">' . $rs_hpyxxth['kelompok'] . '</td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td colspan="3">' . $rs_hpyxxth['peg'] . '</td>
						<td>Grade</td>
						<td>:</td>
						<td colspan="3">' . $rs_hpyxxth['grade'] . '</td>
					</tr>
					<tr>
						<td>Bagian</td>
						<td>:</td>
						<td colspan="3">' . $rs_hpyxxth['bagian'] . '</td>
						<td>Status</td>
						<td>:</td>
						<td colspan="3">' . $rs_hpyxxth['status'] . '</td>
					</tr>
				</table>
				<br>
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="5%"><h4>I</h4></td>
						<td width="35%"><h4>Gaji Pokok</h4></td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['gp'],2,',','.'). '</td>
						<td colspan="3"></td>

						<td width="5%"><h4>V</h4></td>
						<td width="35%"><h4>Potongan</h4>
						<td colspan="2" class="text-right"></td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%">- BPJS-K</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['pot_bpjs'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"><h4>II</h4></td>
						<td width="35%"><h4>Tunjangan Tetap</h4></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%">- BPJS-TK (JHT)</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['pot_jht'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- Tj. Jabatan</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['t_jab'],2,',','.'). '</td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%">- BPJS-TK (JP)</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['pot_psiun'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						' .$pph21 . '
					</tr>
					<tr>
						<td width="5%" style="vertical-align: center"><h4>III</h4></td>
						<td width="35%"><h4>Tunjangan Tidak Tetap</h4></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%" style="vertical-align: center">- JKK JKM</td>
						<td colspan="2" class="text-right" style="vertical-align: center">' .number_format($rs_hpyxxth['pot_jkkjkm'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- Tj. Masa Kerja</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['fix_cost'],2,',','.'). '</td>
						<td colspan="3"></td>

						'.$pot_lain.'
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- JKK JKM</td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['trm_jkkjkm'],2,',','.'). '</td>
						<td colspan="3"></td>

						'.$tidak_masuk.'
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right">______________</td>
						<td colspan="3"></td>

						'.$pot_makan.'
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['tunjangan'],2,',','.'). '</td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right">______________</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%"></td>
						<td colspan="2" class="text-right">' .number_format($rs_hpyxxth['total_pot'],2,',','.'). '</td>
					</tr>
				</table>
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="5%"><h4>IV</h4></td>
						<td width="40%"><h4>Lembur</h4></td>
						<td colspan="2" class="text-right"></td>
						
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="40%"><h4>' .number_format($rs_hpyxxth['jam_lembur'],2,',','.'). ' Jam = ' .number_format($rs_hpyxxth['lemburbersih'],2,',','.'). '</h4></td>
						<td colspan="2" class="text-right"></td>
						
					</tr>
				</table>
				
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="50%">
							<table class="table-ba" style="font-size: 8px; margin-top:-55px;">
								<tr>
									<td colspan="2" class="text-center"><h4>Rincian Jam Lembur</h4></td>
								</tr>
								<tr>
									<td width="40%"><h4>Pertama</h4></td>
									<td class="text-right">' .number_format($rs_hpyxxth['lembur15'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="40%"><h4>Kedua</h4></td>
									<td class="text-right">' .number_format($rs_hpyxxth['lembur2'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="40%"><h4>Ketiga</h4></td>
									<td class="text-right">' .number_format($rs_hpyxxth['lembur3'],2,',','.'). '</td>
								</tr>
							</table>
						</td>
						<td width="">
							<table class="table" style="font-size: 8px;">
								<tr>
									<td width="60%"><h4>Take Home Pay</h4></td>
									<td class="text-right">' .number_format($rs_hpyxxth['gaji_bersih'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="60%"><h4>Diterima</h4></td>
									<td class="text-right">' .number_format($rs_hpyxxth['gaji_terima'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="60%"></td>
									<td class="text-right"></td>
								</tr>
								<tr>
									<td width="60%">Sidoarjo, '.$rs_hpyxxth['tanggal'].'</td>
									<td class="text-right"></td>
								</tr>
								<tr>
									<td width="60%">Diterima Oleh</td>
									<td class="text-right"></td>
								</tr>
								<tr>
									<td width="60%">
										<br>
										<br>
										<br>
										<br>
										<br>
									</td>
									<td class="text-right"></td>
								</tr>
								<tr>
									<td width="60%">'.$rs_hpyxxth['peg'].'</td>
									<td class="text-right"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
    // Close the HTML content for this record
    $html .= '</body></html>';

	if (strip_tags($html) != '') {
        $stylesheet = file_get_contents('../../../templates/inspinia-side/assets/css/print_mpdf.css');

        // Add a page for each non-empty record
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
    }
// Finally, output the PDF
$mpdf->Output();
	
?>