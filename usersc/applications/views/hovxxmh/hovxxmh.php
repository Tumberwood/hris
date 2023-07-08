<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hovxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhovxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hovxxmh/fn/hovxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthovxxmh, tblhovxxmh, show_inactive_status_hovxxmh = 0, id_hovxxmh;
		// ------------- end of default variable

		$(document).ready(function() {
			//start datatables editor
			edthovxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hovxxmh/hovxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hovxxmh = show_inactive_status_hovxxmh;
					}
				},
				table: "#tblhovxxmh",
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
						def: "hovxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hovxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hovxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hovxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hovxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthovxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthovxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhovxxmh.rows().deselect();
				}
			});

			edthovxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthovxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hovxxmh.kode
					if ( ! edthovxxmh.field('hovxxmh.kode').isMultiValue() ) {
						kode = edthovxxmh.field('hovxxmh.kode').val();
						if(!kode || kode == ''){
							edthovxxmh.field('hovxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hovxxmh.kode
						if(action == 'create'){
							id_hovxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hovxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_hovxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthovxxmh.field('hovxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hovxxmh.kode
					}
					// END of validasi hovxxmh.kode

					// BEGIN of validasi hovxxmh.nama
					if ( ! edthovxxmh.field('hovxxmh.nama').isMultiValue() ) {
						nama = edthovxxmh.field('hovxxmh.nama').val();
						if(!nama || nama == ''){
							edthovxxmh.field('hovxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hovxxmh.nama
						if(action == 'create'){
							id_hovxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hovxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_hovxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthovxxmh.field('hovxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hovxxmh.nama
					}
					// END of validasi hovxxmh.nama
					
				}
				
				if ( edthovxxmh.inError() ) {
					return false;
				}
			});
			
			edthovxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthovxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhovxxmh = $('#tblhovxxmh').DataTable( {
				ajax: {
					url: "../../models/hovxxmh/hovxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hovxxmh = show_inactive_status_hovxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hovxxmh.id",visible:false },
					{ data: "hovxxmh.kode" },
					{ data: "hovxxmh.nama" },
					{ data: "hovxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hovxxmh';
						$table       = 'tblhovxxmh';
						$edt         = 'edthovxxmh';
						$show_status = '_hovxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hovxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhovxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhovxxmh);
			} );
			
			tblhovxxmh.on( 'select', function( e, dt, type, indexes ) {
				hovxxmh_data    = tblhovxxmh.row( { selected: true } ).data().hovxxmh;
				id_hovxxmh      = hovxxmh_data.id;
				id_transaksi_h = id_hovxxmh; // dipakai untuk general
				is_approve     = hovxxmh_data.is_approve;
				is_nextprocess = hovxxmh_data.is_nextprocess;
				is_jurnal      = hovxxmh_data.is_jurnal;
				is_active      = hovxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhovxxmh);
			} );

			tblhovxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hovxxmh = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhovxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
