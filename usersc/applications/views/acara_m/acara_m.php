<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'acara_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblacara_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/acara_m/fn/acara_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtacara_m, tblacara_m, show_inactive_status_acara_m = 0, id_acara_m;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtacara_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/acara_m/acara_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_acara_m = show_inactive_status_acara_m;
					}
				},
				table: "#tblacara_m",
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
						def: "acara_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "acara_m.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "acara_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "acara_m.nama"
					}, 	{
						label: "Keterangan",
						name: "acara_m.keterangan",
						type: "textarea"
					},
				]
			} );

			edtacara_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtacara_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblacara_m.rows().deselect();
				}
			});

			edtacara_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtacara_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi acara_m.kode
					if ( ! edtacara_m.field('acara_m.kode').isMultiValue() ) {
						kode = edtacara_m.field('acara_m.kode').val();
						if(!kode || kode == ''){
							edtacara_m.field('acara_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik acara_m.kode
						if(action == 'create'){
							id_acara_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'acara_m',
								kode_field       : 'kode',
								kode_field_value : '"' + kode + '"',
								id_transaksi     : id_acara_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtacara_m.field('acara_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik acara_m.kode

						nama = edtacara_m.field('acara_m.nama').val();
						if(!nama || nama == ''){
							edtacara_m.field('acara_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik acara_m.nama
						if(action == 'create'){
							id_acara_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'acara_m',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_acara_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtacara_m.field('acara_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik acara_m.nama
					}
					// END of validasi acara_m.nama
					
				}
				
				if ( edtacara_m.inError() ) {
					return false;
				}
			});
			
			edtacara_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtacara_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblacara_m = $('#tblacara_m').DataTable( {
				ajax: {
					url: "../../models/acara_m/acara_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_acara_m = show_inactive_status_acara_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "acara_m.id",visible:false },
					{ data: "acara_m.kode" },
					{ data: "acara_m.nama" },
					{ data: "acara_m.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_acara_m';
						$table       = 'tblacara_m';
						$edt         = 'edtacara_m';
						$show_status = '_acara_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.acara_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblacara_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblacara_m);
			} );
			
			tblacara_m.on( 'select', function( e, dt, type, indexes ) {
				acara_m_data    = tblacara_m.row( { selected: true } ).data().acara_m;
				id_acara_m      = acara_m_data.id;
				id_transaksi_h = id_acara_m; // dipakai untuk general
				is_approve     = acara_m_data.is_approve;
				is_nextprocess = acara_m_data.is_nextprocess;
				is_jurnal      = acara_m_data.is_jurnal;
				is_active      = acara_m_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblacara_m);
			} );

			tblacara_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_acara_m = '';

				// atur hak akses
				CekDeselectHeaderH(tblacara_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
