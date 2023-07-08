<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'heyxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblheyxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/heyxxmh/fn/heyxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtheyxxmh, tblheyxxmh, show_inactive_status_heyxxmh = 0, id_heyxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtheyxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/heyxxmh/heyxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmh = show_inactive_status_heyxxmh;
					}
				},
				table: "#tblheyxxmh",
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
						def: "heyxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "heyxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "heyxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "heyxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "heyxxmh.keterangan",
						type: "textarea"
					},	{
						label: "Uang Makan",
						name: "heyxxmh.is_uangmakan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					}
				]
			} );

			edtheyxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtheyxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblheyxxmh.rows().deselect();
				}
			});

			edtheyxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtheyxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi heyxxmh.kode
					if ( ! edtheyxxmh.field('heyxxmh.kode').isMultiValue() ) {
						kode = edtheyxxmh.field('heyxxmh.kode').val();
						if(!kode || kode == ''){
							edtheyxxmh.field('heyxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik heyxxmh.kode
						if(action == 'create'){
							id_heyxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'heyxxmh',
								kode_field       : 'kode',
								kode_field_value : '"' + kode + '"',
								id_transaksi     : id_heyxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtheyxxmh.field('heyxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik heyxxmh.kode

						nama = edtheyxxmh.field('heyxxmh.nama').val();
						if(!nama || nama == ''){
							edtheyxxmh.field('heyxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik heyxxmh.nama
						if(action == 'create'){
							id_heyxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'heyxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_heyxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtheyxxmh.field('heyxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik heyxxmh.nama
					}
					// END of validasi heyxxmh.nama
					
				}
				
				if ( edtheyxxmh.inError() ) {
					return false;
				}
			});
			
			edtheyxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtheyxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblheyxxmh = $('#tblheyxxmh').DataTable( {
				ajax: {
					url: "../../models/heyxxmh/heyxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmh = show_inactive_status_heyxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "heyxxmh.id",visible:false },
					{ data: "heyxxmh.kode" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_heyxxmh';
						$table       = 'tblheyxxmh';
						$edt         = 'edtheyxxmh';
						$show_status = '_heyxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.heyxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblheyxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblheyxxmh);
			} );
			
			tblheyxxmh.on( 'select', function( e, dt, type, indexes ) {
				heyxxmh_data    = tblheyxxmh.row( { selected: true } ).data().heyxxmh;
				id_heyxxmh      = heyxxmh_data.id;
				id_transaksi_h = id_heyxxmh; // dipakai untuk general
				is_approve     = heyxxmh_data.is_approve;
				is_nextprocess = heyxxmh_data.is_nextprocess;
				is_jurnal      = heyxxmh_data.is_jurnal;
				is_active      = heyxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblheyxxmh);
			} );

			tblheyxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_heyxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblheyxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
