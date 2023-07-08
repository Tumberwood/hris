<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'holxxmd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblholxxmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/holxxmd/fn/holxxmd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtholxxmd, tblholxxmd, show_inactive_status_holxxmd = 0, id_holxxmd;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtholxxmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/holxxmd/holxxmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_holxxmd = show_inactive_status_holxxmd;
					}
				},
				table: "#tblholxxmd",
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
						def: "holxxmd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "holxxmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "holxxmd.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "holxxmd.nama"
					}, 	{
						label: "Keterangan",
						name: "holxxmd.keterangan",
						type: "textarea"
					}
				]
			} );

			edtholxxmd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtholxxmd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblholxxmd.rows().deselect();
				}
			});

			edtholxxmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtholxxmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi holxxmd.kode
					if ( ! edtholxxmd.field('holxxmd.kode').isMultiValue() ) {
						kode = edtholxxmd.field('holxxmd.kode').val();
						if(!kode || kode == ''){
							edtholxxmd.field('holxxmd.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik holxxmd.kode
						if(action == 'create'){
							id_holxxmd = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'holxxmd',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_holxxmd
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtholxxmd.field('holxxmd.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik holxxmd.kode
					}
					// END of validasi holxxmd.kode
					
					// BEGIN of validasi holxxmd.nama
					if ( ! edtholxxmd.field('holxxmd.nama').isMultiValue() ) {
						nama = edtholxxmd.field('holxxmd.nama').val();
						if(!nama || nama == ''){
							edtholxxmd.field('holxxmd.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik holxxmd.nama
						if(action == 'create'){
							id_holxxmd = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'holxxmd',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_holxxmd
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtholxxmd.field('holxxmd.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik holxxmd.nama
					}
					// END of validasi holxxmd.nama
				}
				
				if ( edtholxxmd.inError() ) {
					return false;
				}
			});
			
			edtholxxmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtholxxmd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblholxxmd = $('#tblholxxmd').DataTable( {
				ajax: {
					url: "../../models/holxxmd/holxxmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_holxxmd = show_inactive_status_holxxmd;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "holxxmd.id",visible:false },
					{ data: "holxxmd.kode" },
					{ data: "holxxmd.nama" },
					{ data: "holxxmd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_holxxmd';
						$table       = 'tblholxxmd';
						$edt         = 'edtholxxmd';
						$show_status = '_holxxmd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.holxxmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblholxxmd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblholxxmd);
			} );
			
			tblholxxmd.on( 'select', function( e, dt, type, indexes ) {
				holxxmd_data    = tblholxxmd.row( { selected: true } ).data().holxxmd;
				id_holxxmd      = holxxmd_data.id;
				id_transaksi_h = id_holxxmd; // dipakai untuk general
				is_approve     = holxxmd_data.is_approve;
				is_nextprocess = holxxmd_data.is_nextprocess;
				is_jurnal      = holxxmd_data.is_jurnal;
				is_active      = holxxmd_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblholxxmd);
			} );

			tblholxxmd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_holxxmd = '';

				// atur hak akses
				CekDeselectHeaderH(tblholxxmd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
