<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'urutan_simbol_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblurutan_simbol_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode Presensi</th>
                                <th>Nama Presensi</th>
                                <th>Urutan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_modal_log.php'; ?>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/urutan_simbol_m/fn/urutan_simbol_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edturutan_simbol_m, tblurutan_simbol_m, show_inactive_status_urutan_simbol_m = 0, id_urutan_simbol_m;
		// ------------- end of default variable

		$(document).ready(function() {
			//start datatables editor
			edturutan_simbol_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/urutan_simbol_m/urutan_simbol_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_urutan_simbol_m = show_inactive_status_urutan_simbol_m;
					}
				},
				table: "#tblurutan_simbol_m",
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
						def: "urutan_simbol_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "urutan_simbol_m.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode Presensi <sup class='text-danger'>*<sup>",
						name: "urutan_simbol_m.kode"
					}, 	
					{
						label: "Nama Presensi <sup class='text-danger'>*<sup>",
						name: "urutan_simbol_m.nama"
					}, 	
					{
						label: "Urutan <sup class='text-danger'>*<sup>",
						name: "urutan_simbol_m.urutan",
						attr: {
							type: "number",
							min: 1,
							step: 1
						}

					}, 	
					{
						label: "Keterangan",
						name: "urutan_simbol_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edturutan_simbol_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edturutan_simbol_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblurutan_simbol_m.rows().deselect();
				}
			});

			edturutan_simbol_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edturutan_simbol_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi urutan_simbol_m.kode
					urutan = edturutan_simbol_m.field('urutan_simbol_m.urutan').val();
					if(!urutan || urutan == ''){
						edturutan_simbol_m.field('urutan_simbol_m.urutan').error( 'Wajib diisi!' );
					}
					if ( ! edturutan_simbol_m.field('urutan_simbol_m.kode').isMultiValue() ) {
						kode = edturutan_simbol_m.field('urutan_simbol_m.kode').val();
						if(!kode || kode == ''){
							edturutan_simbol_m.field('urutan_simbol_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik urutan_simbol_m.kode
						if(action == 'create'){
							id_urutan_simbol_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'urutan_simbol_m',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_urutan_simbol_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edturutan_simbol_m.field('urutan_simbol_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik urutan_simbol_m.kode
					}
					// END of validasi urutan_simbol_m.kode

					// BEGIN of validasi urutan_simbol_m.nama
					if ( ! edturutan_simbol_m.field('urutan_simbol_m.nama').isMultiValue() ) {
						nama = edturutan_simbol_m.field('urutan_simbol_m.nama').val();
						if(!nama || nama == ''){
							edturutan_simbol_m.field('urutan_simbol_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik urutan_simbol_m.nama
						if(action == 'create'){
							id_urutan_simbol_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'urutan_simbol_m',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_urutan_simbol_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edturutan_simbol_m.field('urutan_simbol_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik urutan_simbol_m.nama
					}
					// END of validasi urutan_simbol_m.nama
					
				}
				
				if ( edturutan_simbol_m.inError() ) {
					return false;
				}
			});
			
			edturutan_simbol_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edturutan_simbol_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblurutan_simbol_m = $('#tblurutan_simbol_m').DataTable( {
				ajax: {
					url: "../../models/urutan_simbol_m/urutan_simbol_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_urutan_simbol_m = show_inactive_status_urutan_simbol_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "urutan_simbol_m.id",visible:false },
					{ data: "urutan_simbol_m.kode" },
					{ data: "urutan_simbol_m.nama" },
					{ data: "urutan_simbol_m.urutan" },
					{ data: "urutan_simbol_m.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_urutan_simbol_m';
						$table       = 'tblurutan_simbol_m';
						$edt         = 'edturutan_simbol_m';
						$show_status = '_urutan_simbol_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h', 'log'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.urutan_simbol_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblurutan_simbol_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblurutan_simbol_m);
			} );
			
			tblurutan_simbol_m.on( 'select', function( e, dt, type, indexes ) {
				urutan_simbol_m_data    = tblurutan_simbol_m.row( { selected: true } ).data().urutan_simbol_m;
				id_urutan_simbol_m      = urutan_simbol_m_data.id;
				id_transaksi_h = id_urutan_simbol_m; // dipakai untuk general
				is_approve     = urutan_simbol_m_data.is_approve;
				is_nextprocess = urutan_simbol_m_data.is_nextprocess;
				is_jurnal      = urutan_simbol_m_data.is_jurnal;
				is_active      = urutan_simbol_m_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblurutan_simbol_m);
			} );

			tblurutan_simbol_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_urutan_simbol_m = 0;

				// atur hak akses
				CekDeselectHeaderH(tblurutan_simbol_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
