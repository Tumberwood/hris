<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hodxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhodxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hodxxmh/fn/hodxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthodxxmh, tblhodxxmh, show_inactive_status_hodxxmh = 0, id_hodxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthodxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hodxxmh/hodxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hodxxmh = show_inactive_status_hodxxmh;
					}
				},
				table: "#tblhodxxmh",
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
						def: "hodxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hodxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hodxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hodxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hodxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthodxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthodxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhodxxmh.rows().deselect();
				}
			});

			edthodxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthodxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hodxxmh.kode
					if ( ! edthodxxmh.field('hodxxmh.kode').isMultiValue() ) {
						kode = edthodxxmh.field('hodxxmh.kode').val();
						if(!kode || kode == ''){
							edthodxxmh.field('hodxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hodxxmh.kode
						if(action == 'create'){
							id_hodxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hodxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_hodxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthodxxmh.field('hodxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hodxxmh.kode
					}
					// END of validasi hodxxmh.kode

					// BEGIN of validasi hodxxmh.nama
					if ( ! edthodxxmh.field('hodxxmh.nama').isMultiValue() ) {
							nama = edthodxxmh.field('hodxxmh.nama').val();
							if(!nama || nama == ''){
								edthodxxmh.field('hodxxmh.nama').error( 'Wajib diisi!' );
							}
							
							// BEGIN of cek unik hodxxmh.nama
							if(action == 'create'){
								id_hodxxmh = 0;
							}
							
							
							$.ajax( {
								url: '../../../helpers/validate_fn_unique.php',
								dataType: 'json',
								type: 'POST',
								async: false,
								data: {
									table_name       : 'hodxxmh',
									nama_field       : 'nama',
									nama_field_value : '"' + nama + '"',
									id_transaksi     : id_hodxxmh
								},
								success: function ( json ) {
									if(json.data.count == 1){
										edthodxxmh.field('hodxxmh.nama').error( 'Data tidak boleh kembar!' );
									}
								}
							} );
							// END of cek unik hodxxmh.nama
						}
						// END of validasi hodxxmh.nama
					}
				

				if ( edthodxxmh.inError() ) {
					return false;
				}
			});
			
			edthodxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthodxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhodxxmh = $('#tblhodxxmh').DataTable( {
				ajax: {
					url: "../../models/hodxxmh/hodxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hodxxmh = show_inactive_status_hodxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hodxxmh.id",visible:false },
					{ data: "hodxxmh.kode" },
					{ data: "hodxxmh.nama" },
					{ data: "hodxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hodxxmh';
						$table       = 'tblhodxxmh';
						$edt         = 'edthodxxmh';
						$show_status = '_hodxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 

					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hodxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhodxxmh.buttons().containers().appendTo( '#fab-datatables' );

			tblhodxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhodxxmh);
			} );
			
			tblhodxxmh.on( 'select', function( e, dt, type, indexes ) {
				hodxxmh_data    = tblhodxxmh.row( { selected: true } ).data().hodxxmh;
				id_hodxxmh      = hodxxmh_data.id;
				id_transaksi_h = id_hodxxmh; // dipakai untuk general
				is_approve     = hodxxmh_data.is_approve;
				is_nextprocess = hodxxmh_data.is_nextprocess;
				is_jurnal      = hodxxmh_data.is_jurnal;
				is_active      = hodxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhodxxmh);
			} );

			tblhodxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hodxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhodxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
