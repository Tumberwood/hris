<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'pekerjaan_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblpekerjaan_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Grup</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/pekerjaan_m/fn/pekerjaan_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtpekerjaan_m, tblpekerjaan_m, show_inactive_status_pekerjaan_m = 0, id_pekerjaan_m;
		// ------------- end of default variable
		var id_grup_pekerjaan_m_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtpekerjaan_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/pekerjaan_m/pekerjaan_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pekerjaan_m = show_inactive_status_pekerjaan_m;
					}
				},
				table: "#tblpekerjaan_m",
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
						def: "pekerjaan_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "pekerjaan_m.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "pekerjaan_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "pekerjaan_m.nama"
					}, 	
					{
						label: "Grup <sup class='text-danger'>*<sup>",
						name: "pekerjaan_m.id_grup_pekerjaan_m",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/grup_pekerjaan_m/grup_pekerjaan_m_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_grup_pekerjaan_m_old: id_grup_pekerjaan_m_old,
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
								minimumResultsForSearch: -1
							}
						}
					},
					{
						label: "Keterangan",
						name: "pekerjaan_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtpekerjaan_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpekerjaan_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblpekerjaan_m.rows().deselect();
				}
			});

			edtpekerjaan_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtpekerjaan_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi pekerjaan_m.kode
					if ( ! edtpekerjaan_m.field('pekerjaan_m.kode').isMultiValue() ) {
						kode = edtpekerjaan_m.field('pekerjaan_m.kode').val();
						if(!kode || kode == ''){
							edtpekerjaan_m.field('pekerjaan_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik pekerjaan_m.kode
						if(action == 'create'){
							id_pekerjaan_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'pekerjaan_m',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_pekerjaan_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtpekerjaan_m.field('pekerjaan_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik pekerjaan_m.kode
					}
					// END of validasi pekerjaan_m.kode
					
					// BEGIN of validasi pekerjaan_m.nama
					if ( ! edtpekerjaan_m.field('pekerjaan_m.nama').isMultiValue() ) {
						nama = edtpekerjaan_m.field('pekerjaan_m.nama').val();
						if(!nama || nama == ''){
							edtpekerjaan_m.field('pekerjaan_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik pekerjaan_m.nama
						if(action == 'create'){
							id_pekerjaan_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'pekerjaan_m',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_pekerjaan_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtpekerjaan_m.field('pekerjaan_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik pekerjaan_m.nama
					}
					// END of validasi pekerjaan_m.nama
					
					id_grup_pekerjaan_m = edtpekerjaan_m.field('pekerjaan_m.id_grup_pekerjaan_m').val();
					if(!id_grup_pekerjaan_m || id_grup_pekerjaan_m == ''){
						edtpekerjaan_m.field('pekerjaan_m.id_grup_pekerjaan_m').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtpekerjaan_m.inError() ) {
					return false;
				}
			});
			
			edtpekerjaan_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpekerjaan_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblpekerjaan_m = $('#tblpekerjaan_m').DataTable( {
				ajax: {
					url: "../../models/pekerjaan_m/pekerjaan_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pekerjaan_m = show_inactive_status_pekerjaan_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "pekerjaan_m.id",visible:false },
					{ data: "pekerjaan_m.kode" },
					{ data: "pekerjaan_m.nama" },
					{ data: "grup_pekerjaan_m.nama" },
					{ data: "pekerjaan_m.keterangan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_pekerjaan_m';
						$table       = 'tblpekerjaan_m';
						$edt         = 'edtpekerjaan_m';
						$show_status = '_pekerjaan_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.pekerjaan_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblpekerjaan_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblpekerjaan_m);
			} );
			
			tblpekerjaan_m.on( 'select', function( e, dt, type, indexes ) {
				pekerjaan_m_data    = tblpekerjaan_m.row( { selected: true } ).data().pekerjaan_m;
				id_pekerjaan_m      = pekerjaan_m_data.id;
				id_transaksi_h = id_pekerjaan_m; // dipakai untuk general
				is_approve     = pekerjaan_m_data.is_approve;
				is_nextprocess = pekerjaan_m_data.is_nextprocess;
				is_jurnal      = pekerjaan_m_data.is_jurnal;
				is_active      = pekerjaan_m_data.is_active;
				id_grup_pekerjaan_m_old      = pekerjaan_m_data.id_grup_pekerjaan_m;

				// atur hak akses
				CekSelectHeaderH(tblpekerjaan_m);
			} );

			tblpekerjaan_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_pekerjaan_m = '';
				id_grup_pekerjaan_m_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblpekerjaan_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
