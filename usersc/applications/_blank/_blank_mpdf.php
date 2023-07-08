<?php
	include_once( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	require '../../../../usersc/vendor/autoload.php';

	/**
	 * referensi: https://mpdf.github.io/reference/html-control-tags/overview.html
	 * htmlpageheader
	 * htmlpagefooter
	 */

	// get data
	// query untuk data-data yang akan ditampilkan

	// generate tampilan
	$html = '
		<html>
			<head>
				
			</head>
			<body>
	
			<!--mpdf
			<htmlpageheader name="myheader">
				<table style="border-collapse: collapse;" width="100%">
					<tr>
						<td></td>
					</tr>
				</table>
			</htmlpageheader>
			
			<htmlpagefooter name="myfooter">
				<table width="100%">
					<tr>
						<td></td>
					</tr>
				</table>
				
				<div class="hr-footer">
					Page {PAGENO} of {nb}
				</div>
			</htmlpagefooter>
			
			<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
			<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
	
		<table width="100%">
			<thead>
				<tr>
					<td></td>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN ITEMS HERE -->
				<!-- END ITEMS HERE -->
			</tbody>
			<tfoot>
				<tr>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</body>
</html>
';

	$stylesheet = file_get_contents('../../../templates/inspinia/assets/css/print_mpdf.min.css');

	// render ke mpdf
	$mpdf = new \Mpdf\Mpdf([
		'margin_left' => 5,
		'margin_right' => 5,
		'margin_top' => 5, // table ke header
		'margin_bottom' => 5,
		'margin_header' => 5,	// header
		'margin_footer' => 5,	// footer
		'format' => [100, 150], // dalam mm | 'A4' | 'A5' 
		
	]);
	$mpdf->AddPage('P');	// L: Landscape
	$mpdf->SetProtection(array('print'));
	$mpdf->SetTitle("");
	$mpdf->SetDisplayMode('fullpage');
	$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
	$mpdf->WriteHTML($html);
	$mpdf->Output();
?>