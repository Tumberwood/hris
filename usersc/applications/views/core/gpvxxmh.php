<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gpvxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgpvxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Province</th>
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
		var edtgpvxxmh, tblgpvxxmh, show_inactive_status_gpvxxmh = 0, id_gpvxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtgpvxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gpvxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gpvxxmh = show_inactive_status_gpvxxmh;
					}
				},
				table: "#tblgpvxxmh",
				fields: [ 
					{
						label: "start_on",
						name: "start_on",
						type: "hidden"
					},	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
						label: "nama_tabel",
						name: "nama_tabel",
						def: "gpvxxmh",
						type: "hidden"
					}, 	{
						label: "Province <sup class='text-danger'>*<sup>",
						name: "gpvxxmh.nama"
					}
				]
			} );

			edtgpvxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgpvxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgpvxxmh.rows().deselect();
				}
			});

			edtgpvxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgpvxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

                    // BEGIN of validasi gpvxxmh.nama
					if ( ! edtgpvxxmh.field('gpvxxmh.nama').isMultiValue() ) {
						gpvxxmh_nama = edtgpvxxmh.field('gpvxxmh.nama').val();
						if(!gpvxxmh_nama || gpvxxmh_nama == ''){
							edtgpvxxmh.field('gpvxxmh.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gpvxxmh.nama

				}
				
				if ( edtgpvxxmh.inError() ) {
					return false;
				}
			});

			edtgpvxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgpvxxmh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblgpvxxmh = $('#tblgpvxxmh').DataTable( {
				ajax: {
					url: "../../models/core/gpvxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gpvxxmh = show_inactive_status_gpvxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "gpvxxmh.id",visible:false },
					{ data: "gpvxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gpvxxmh';
						$table       = 'tblgpvxxmh';
						$edt         = 'edtgpvxxmh';
						$show_status = '_gpvxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gpvxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgpvxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgpvxxmh);
			} );
			
			tblgpvxxmh.on( 'select', function( e, dt, type, indexes ) {
				gpvxxmh_data    = tblgpvxxmh.row( { selected: true } ).data().gpvxxmh;
				id_gpvxxmh      = gpvxxmh_data.id;
				id_transaksi_h = id_gpvxxmh; // dipakai untuk general
				is_approve     = gpvxxmh_data.is_approve;
				is_nextprocess = gpvxxmh_data.is_nextprocess;
				is_jurnal      = gpvxxmh_data.is_jurnal;
				is_active      = gpvxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblgpvxxmh);
			} );

			tblgpvxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gpvxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblgpvxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
