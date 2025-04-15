<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'holxxmd_2';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblholxxmd_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/holxxmd_2/fn/holxxmd_2_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtholxxmd_2, tblholxxmd_2, show_inactive_status_holxxmd_2 = 0, id_holxxmd_2;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtholxxmd_2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/holxxmd_2/holxxmd_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_holxxmd_2 = show_inactive_status_holxxmd_2;
					}
				},
				table: "#tblholxxmd_2",
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
						def: "holxxmd_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "holxxmd_2.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "holxxmd_2.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "holxxmd_2.nama"
					}, 	{
						label: "Keterangan",
						name: "holxxmd_2.keterangan",
						type: "textarea"
					}
				]
			} );

			edtholxxmd_2.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtholxxmd_2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblholxxmd_2.rows().deselect();
				}
			});

			edtholxxmd_2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtholxxmd_2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi holxxmd_2.kode
					if ( ! edtholxxmd_2.field('holxxmd_2.kode').isMultiValue() ) {
						kode = edtholxxmd_2.field('holxxmd_2.kode').val();
						if(!kode || kode == ''){
							edtholxxmd_2.field('holxxmd_2.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik holxxmd_2.kode
						if(action == 'create'){
							id_holxxmd_2 = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'holxxmd_2',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_holxxmd_2
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtholxxmd_2.field('holxxmd_2.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik holxxmd_2.kode
					}
					// END of validasi holxxmd_2.kode
					
					// BEGIN of validasi holxxmd_2.nama
					if ( ! edtholxxmd_2.field('holxxmd_2.nama').isMultiValue() ) {
						nama = edtholxxmd_2.field('holxxmd_2.nama').val();
						if(!nama || nama == ''){
							edtholxxmd_2.field('holxxmd_2.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik holxxmd_2.nama
						if(action == 'create'){
							id_holxxmd_2 = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'holxxmd_2',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_holxxmd_2
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtholxxmd_2.field('holxxmd_2.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik holxxmd_2.nama
					}
					// END of validasi holxxmd_2.nama
				}
				
				if ( edtholxxmd_2.inError() ) {
					return false;
				}
			});
			
			edtholxxmd_2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtholxxmd_2.field('finish_on').val(finish_on);
			});

			//start datatables
			tblholxxmd_2 = $('#tblholxxmd_2').DataTable( {
				ajax: {
					url: "../../models/holxxmd_2/holxxmd_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_holxxmd_2 = show_inactive_status_holxxmd_2;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "holxxmd_2.id",visible:false },
					{ data: "holxxmd_2.kode" },
					{ data: "holxxmd_2.nama" },
					{ data: "holxxmd_2.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_holxxmd_2';
						$table       = 'tblholxxmd_2';
						$edt         = 'edtholxxmd_2';
						$show_status = '_holxxmd_2';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.holxxmd_2.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblholxxmd_2.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblholxxmd_2);
			} );
			
			tblholxxmd_2.on( 'select', function( e, dt, type, indexes ) {
				holxxmd_2_data    = tblholxxmd_2.row( { selected: true } ).data().holxxmd_2;
				id_holxxmd_2      = holxxmd_2_data.id;
				id_transaksi_h = id_holxxmd_2; // dipakai untuk general
				is_approve     = holxxmd_2_data.is_approve;
				is_nextprocess = holxxmd_2_data.is_nextprocess;
				is_jurnal      = holxxmd_2_data.is_jurnal;
				is_active      = holxxmd_2_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblholxxmd_2);
			} );

			tblholxxmd_2.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_holxxmd_2 = '';

				// atur hak akses
				CekDeselectHeaderH(tblholxxmd_2);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
