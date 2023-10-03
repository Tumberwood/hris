<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'heyxxmd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblheyxxmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Tipe</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/heyxxmd/fn/heyxxmd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtheyxxmd, tblheyxxmd, show_inactive_status_heyxxmd = 0, id_heyxxmd;
		// ------------- end of default variable
		id_heyxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtheyxxmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/heyxxmd/heyxxmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmd = show_inactive_status_heyxxmd;
					}
				},
				table: "#tblheyxxmd",
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
						def: "heyxxmd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "heyxxmd.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tipe <sup class='text-danger'>*<sup>",
						name: "heyxxmd.id_heyxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh: 0,
										id_heyxxmh_old: id_heyxxmh_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									return {
										results: data.results,
										pagination: {
											more: true
										}
									};
								},
								cache: true,
								minimumInputLength: 1,
								maximum: 10,
								delay: 500,
								maximumSelectionLength: 5,
								minimumResultsForSearch: -1,
							},
						}
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "heyxxmd.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "heyxxmd.nama"
					}, 	{
						label: "Keterangan",
						name: "heyxxmd.keterangan",
						type: "textarea"
					}
				]
			} );

			edtheyxxmd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtheyxxmd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblheyxxmd.rows().deselect();
				}
			});

			edtheyxxmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtheyxxmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi heyxxmd.kode
					if ( ! edtheyxxmd.field('heyxxmd.kode').isMultiValue() ) {
						kode = edtheyxxmd.field('heyxxmd.kode').val();
						if(!kode || kode == ''){
							edtheyxxmd.field('heyxxmd.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik heyxxmd.kode
						if(action == 'create'){
							id_heyxxmd = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'heyxxmd',
								kode_field       : 'kode',
								kode_field_value : '"' + kode + '"',
								id_transaksi     : id_heyxxmd
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtheyxxmd.field('heyxxmd.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik heyxxmd.kode

						nama = edtheyxxmd.field('heyxxmd.nama').val();
						if(!nama || nama == ''){
							edtheyxxmd.field('heyxxmd.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik heyxxmd.nama
						if(action == 'create'){
							id_heyxxmd = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'heyxxmd',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_heyxxmd
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtheyxxmd.field('heyxxmd.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik heyxxmd.nama

					}
					// END of validasi heyxxmd.nama

					
					id_heyxxmh = edtheyxxmd.field('heyxxmd.id_heyxxmh').val();
					if(!id_heyxxmh || id_heyxxmh == ''){
						edtheyxxmd.field('heyxxmd.id_heyxxmh').error( 'Wajib diisi!' );
					}
					
				}
				
				if ( edtheyxxmd.inError() ) {
					return false;
				}
			});
			
			edtheyxxmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtheyxxmd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblheyxxmd = $('#tblheyxxmd').DataTable( {
				ajax: {
					url: "../../models/heyxxmd/heyxxmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmd = show_inactive_status_heyxxmd;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "heyxxmd.id",visible:false },
					{ data: "heyxxmd.kode" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "heyxxmd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_heyxxmd';
						$table       = 'tblheyxxmd';
						$edt         = 'edtheyxxmd';
						$show_status = '_heyxxmd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.heyxxmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblheyxxmd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblheyxxmd);
			} );
			
			tblheyxxmd.on( 'select', function( e, dt, type, indexes ) {
				heyxxmd_data    = tblheyxxmd.row( { selected: true } ).data().heyxxmd;
				id_heyxxmd      = heyxxmd_data.id;
				id_transaksi_h = id_heyxxmd; // dipakai untuk general
				is_approve     = heyxxmd_data.is_approve;
				is_nextprocess = heyxxmd_data.is_nextprocess;
				is_jurnal      = heyxxmd_data.is_jurnal;
				is_active      = heyxxmd_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblheyxxmd);
			} );

			tblheyxxmd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_heyxxmd = '';

				// atur hak akses
				CekDeselectHeaderH(tblheyxxmd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
