<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'kelengkapan_data';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblkelengkapan_data" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Data Tidak Lengkap</th>
                            </tr>
                        </thead>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtkelengkapan_data, tblkelengkapan_data, show_inactive_status_kelengkapan_data = 0, id_kelengkapan_data;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables
			tblkelengkapan_data = $('#tblkelengkapan_data').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/kelengkapan_data.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_kelengkapan_data = show_inactive_status_kelengkapan_data;
					}
				},
				order: [[ 0, "asc" ]],
				columns: [
					{ data: "kode" },
					{ data: "nama" },
					{ data: "keterangan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_kelengkapan_data';
						$table       = 'tblkelengkapan_data';
						$edt         = 'edtkelengkapan_data';
						$show_status = '_kelengkapan_data';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );
			
			tblkelengkapan_data.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblkelengkapan_data);
			} );
			
			tblkelengkapan_data.on( 'select', function( e, dt, type, indexes ) {
				kelengkapan_data_data    = tblkelengkapan_data.row( { selected: true } ).data().kelengkapan_data;
				id_kelengkapan_data      = kelengkapan_data_data.id;
				id_transaksi_h = id_kelengkapan_data; // dipakai untuk general
				is_approve     = kelengkapan_data_data.is_approve;
				is_nextprocess = kelengkapan_data_data.is_nextprocess;
				is_jurnal      = kelengkapan_data_data.is_jurnal;
				is_active      = kelengkapan_data_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblkelengkapan_data);
			} );

			tblkelengkapan_data.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_kelengkapan_data = '';

				// atur hak akses
				CekDeselectHeaderH(tblkelengkapan_data);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
