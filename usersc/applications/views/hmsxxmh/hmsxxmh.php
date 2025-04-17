<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hmsxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhmsxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hmsxxmh/fn/hmsxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthmsxxmh, tblhmsxxmh, show_inactive_status_hmsxxmh = 0, id_hmsxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthmsxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hmsxxmh/hmsxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hmsxxmh = show_inactive_status_hmsxxmh;
					}
				},
				table: "#tblhmsxxmh",
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
						def: "hmsxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hmsxxmh.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hmsxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hmsxxmh.keterangan",
						type: "textarea"
					},
				]
			} );

			edthmsxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthmsxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhmsxxmh.rows().deselect();
				}
			});

			edthmsxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthmsxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					nama = edthmsxxmh.field('hmsxxmh.nama').val();
					if(!nama || nama == ''){
						edthmsxxmh.field('hmsxxmh.nama').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik hmsxxmh.nama
					if(action == 'create'){
						id_hmsxxmh = 0;
					}
					
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name       : 'hmsxxmh',
							nama_field       : 'nama',
							nama_field_value : '"' + nama + '"',
							id_transaksi     : id_hmsxxmh
						},
						success: function ( json ) {
							if(json.data.count > 0){
								edthmsxxmh.field('hmsxxmh.nama').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik hmsxxmh.nama
				}
				
				if ( edthmsxxmh.inError() ) {
					return false;
				}
			});
			
			edthmsxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthmsxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhmsxxmh = $('#tblhmsxxmh').DataTable( {
				ajax: {
					url: "../../models/hmsxxmh/hmsxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hmsxxmh = show_inactive_status_hmsxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hmsxxmh.id",visible:false },
					{ data: "hmsxxmh.nama" },
					{ data: "hmsxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hmsxxmh';
						$table       = 'tblhmsxxmh';
						$edt         = 'edthmsxxmh';
						$show_status = '_hmsxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hmsxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhmsxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhmsxxmh);
			} );
			
			tblhmsxxmh.on( 'select', function( e, dt, type, indexes ) {
				hmsxxmh_data    = tblhmsxxmh.row( { selected: true } ).data().hmsxxmh;
				id_hmsxxmh      = hmsxxmh_data.id;
				id_transaksi_h = id_hmsxxmh; // dipakai untuk general
				is_approve     = hmsxxmh_data.is_approve;
				is_nextprocess = hmsxxmh_data.is_nextprocess;
				is_jurnal      = hmsxxmh_data.is_jurnal;
				is_active      = hmsxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhmsxxmh);
			} );

			tblhmsxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hmsxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhmsxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
