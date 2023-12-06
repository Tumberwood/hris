<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'notif_jadwal';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblnotif_jadwal" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
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
		var edtnotif_jadwal, tblnotif_jadwal, show_inactive_status_notif_jadwal = 0, id_notif_jadwal;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables
			tblnotif_jadwal = $('#tblnotif_jadwal').DataTable( {
				ajax: {
					url: "../../models/htssctd/notif_jadwal.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_notif_jadwal = show_inactive_status_notif_jadwal;
					}
				},
				order: [[ 0, "asc" ]],
				columns: [
					{ data: "tanggal" },
					{ data: "peg" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_notif_jadwal';
						$table       = 'tblnotif_jadwal';
						$edt         = 'edtnotif_jadwal';
						$show_status = '_notif_jadwal';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );
			
			tblnotif_jadwal.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblnotif_jadwal);
			} );
			
			tblnotif_jadwal.on( 'select', function( e, dt, type, indexes ) {
				notif_jadwal_data    = tblnotif_jadwal.row( { selected: true } ).data().notif_jadwal;
				id_notif_jadwal      = notif_jadwal_data.id;
				id_transaksi_h = id_notif_jadwal; // dipakai untuk general
				is_approve     = notif_jadwal_data.is_approve;
				is_nextprocess = notif_jadwal_data.is_nextprocess;
				is_jurnal      = notif_jadwal_data.is_jurnal;
				is_active      = notif_jadwal_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblnotif_jadwal);
			} );

			tblnotif_jadwal.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_notif_jadwal = '';

				// atur hak akses
				CekDeselectHeaderH(tblnotif_jadwal);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
