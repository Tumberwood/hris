<?php
	include_once( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );
	require '../../../../usersc/vendor/autoload.php';

	use Carbon\Carbon;

	// BEGIN select header
	$where = 'WHERE a.id_hpyxxth = '.$_GET['id_hpyxxth'].' AND c.id_heyxxmd = '.$_GET['id_heyxxmd'];
	if (isset($_GET['id_hesxxmh']) && !empty($_GET['id_hesxxmh'])) {
		$hes = $_GET['id_hesxxmh'];
		$where .= ' AND c.id_hesxxmh = '.$hes;
	}
	$qs_hpyxxth = $db
		->raw()
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
					'.$where.'
					ORDER BY CONCAT(b.kode, " - ", b.nama)
					'
					);
	$rs_hpyxxth = $qs_hpyxxth->fetchAll();
	
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

	foreach ($rs_hpyxxth as $record) {
		
	$pph21 = '';
	if ($record['pot_pph21'] != 0) {
		$pph21 .= '<td width="5%"></td>';
		$pph21 .= '<td width="35%">- PPh21</td>';
		$pph21 .= '<td colspan="2" class="text-right">' . number_format($record['pot_pph21'], 2, ',', '.') . '</td>';
	}
		
	$pot_lain = '';
	if ($record['pot_lain'] != 0) {
		$pot_lain .= '<td width="5%"></td>';
		$pot_lain .= '<td width="35%">- Lain-lain</td>';
		$pot_lain .= '<td colspan="2" class="text-right">' . number_format($record['pot_lain'], 2, ',', '.') . '</td>';
	}
		
	$tidak_masuk = '';
	if ($record['tidak_masuk'] != 0) {
		$tidak_masuk .= '<td width="5%"></td>';
		$tidak_masuk .= '<td width="35%">- Tidak Masuk</td>';
		$tidak_masuk .= '<td colspan="2" class="text-right">' . number_format($record['tidak_masuk'], 2, ',', '.') . '</td>';
	}
		
	$pot_makan = '';
	if ($record['pot_makan'] != 0) {
		$pot_makan .= '<td width="5%"></td>';
		$pot_makan .= '<td width="35%">- Makan</td>';
		$pot_makan .= '<td colspan="2" class="text-right">' . number_format($record['pot_makan'], 2, ',', '.') . '</td>';
	}

	$html = '
    <html>
        <head></head>
        <body>
            <htmlpageheader name="myheader"></htmlpageheader>
            <htmlpagefooter name="myfooter"></htmlpagefooter>';

			// Start the loop for each record
			$html .= '
				<h4 class="text-center"> SLIP GAJI ' . $record['title'] . '</h4>
				<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
				<sethtmlpagefooter name="myfooter" value="on" />
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="17%">No Induk</td>
						<td width="3%">:</td>
						<td colspan="3">' . $record['nik'] . '</td>
						<td width="17%">Kelompok</td>
						<td width="3%">:</td>
						<td colspan="3">' . $record['kelompok'] . '</td>
					</tr>
					<tr>
						<td>Nama</td>
						<td>:</td>
						<td colspan="3">' . $record['peg'] . '</td>
						<td>Grade</td>
						<td>:</td>
						<td colspan="3">' . $record['grade'] . '</td>
					</tr>
					<tr>
						<td>Bagian</td>
						<td>:</td>
						<td colspan="3">' . $record['bagian'] . '</td>
						<td>Status</td>
						<td>:</td>
						<td colspan="3">' . $record['status'] . '</td>
					</tr>
				</table>
				<br>
				<table class="pn" style="font-size: 8px;">
					<tr>
						<td width="5%"><h4>I</h4></td>
						<td width="35%"><h4>Gaji Pokok</h4></td>
						<td colspan="2" class="text-right">' .number_format($record['gp'],2,',','.'). '</td>
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
						<td colspan="2" class="text-right">' .number_format($record['pot_bpjs'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"><h4>II</h4></td>
						<td width="35%"><h4>Tunjangan Tetap</h4></td>
						<td colspan="2" class="text-right"></td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%">- BPJS-TK (JHT)</td>
						<td colspan="2" class="text-right">' .number_format($record['pot_jht'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- Tj. Jabatan</td>
						<td colspan="2" class="text-right">' .number_format($record['t_jab'],2,',','.'). '</td>
						<td colspan="3"></td>

						<td width="5%"></td>
						<td width="35%">- BPJS-TK (JP)</td>
						<td colspan="2" class="text-right">' .number_format($record['pot_psiun'],2,',','.'). '</td>
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
						<td colspan="2" class="text-right" style="vertical-align: center">' .number_format($record['pot_jkkjkm'],2,',','.'). '</td>
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- Tj. Masa Kerja</td>
						<td colspan="2" class="text-right">' .number_format($record['fix_cost'],2,',','.'). '</td>
						<td colspan="3"></td>

						'.$pot_lain.'
					</tr>
					<tr>
						<td width="5%"></td>
						<td width="35%">- JKK JKM</td>
						<td colspan="2" class="text-right">' .number_format($record['trm_jkkjkm'],2,',','.'). '</td>
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
						<td colspan="2" class="text-right">' .number_format($record['tunjangan'],2,',','.'). '</td>
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
						<td colspan="2" class="text-right">' .number_format($record['total_pot'],2,',','.'). '</td>
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
						<td width="40%"><h4>' .number_format($record['jam_lembur'],2,',','.'). ' Jam = ' .number_format($record['lemburbersih'],2,',','.'). '</h4></td>
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
									<td class="text-right">' .number_format($record['lembur15'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="40%"><h4>Kedua</h4></td>
									<td class="text-right">' .number_format($record['lembur2'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="40%"><h4>Ketiga</h4></td>
									<td class="text-right">' .number_format($record['lembur3'],2,',','.'). '</td>
								</tr>
							</table>
						</td>
						<td width="">
							<table class="table" style="font-size: 8px;">
								<tr>
									<td width="60%"><h4>Take Home Pay</h4></td>
									<td class="text-right">' .number_format($record['gaji_bersih'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="60%"><h4>Diterima</h4></td>
									<td class="text-right">' .number_format($record['gaji_terima'],2,',','.'). '</td>
								</tr>
								<tr>
									<td width="60%"></td>
									<td class="text-right"></td>
								</tr>
								<tr>
									<td width="60%">Sidoarjo, '.$record['tanggal'].'</td>
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
									<td width="60%">'.$record['peg'].'</td>
									<td class="text-right"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
    // Close the HTML content for this record
    $html .= '
	<page_break>
	</body>
	</html>';

	if (strip_tags($html) != '') {
        $stylesheet = file_get_contents('../../../templates/inspinia-side/assets/css/print_mpdf.css');

        // Add a page for each non-empty record
        $mpdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html);
    }
}

// Finally, output the PDF
$mpdf->Output();
	
?>