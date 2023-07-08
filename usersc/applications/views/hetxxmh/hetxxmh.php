<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hetxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhetxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Atasan Langsung</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hetxxmh/fn/hetxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthetxxmh, tblhetxxmh, show_inactive_status_hetxxmh = 0, id_hetxxmh;
		// ------------- end of default variable

		var id_hetxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthetxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hetxxmh/hetxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hetxxmh = show_inactive_status_hetxxmh;
					}
				},
				table: "#tblhetxxmh",
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
						def: "hetxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hetxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hetxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hetxxmh.nama"
					}, 	{
						label: "Atasan Langsung",
						name: "hetxxmh.id_hetxxmh_al",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hetxxmh/hetxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hetxxmh_old: id_hetxxmh_old,
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
						label: "Keterangan",
						name: "hetxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthetxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthetxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhetxxmh.rows().deselect();
				}
			});

			edthetxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthetxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hetxxmh.kode
					if ( ! edthetxxmh.field('hetxxmh.kode').isMultiValue() ) {
						kode = edthetxxmh.field('hetxxmh.kode').val();
						if(!kode || kode == ''){
							edthetxxmh.field('hetxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hetxxmh.kode
						if(action == 'create'){
							id_hetxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hetxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_hetxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthetxxmh.field('hetxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hetxxmh.kode
					}
					// END of validasi hetxxmh.kode
					
					// BEGIN of validasi hetxxmh.nama
					if ( ! edthetxxmh.field('hetxxmh.nama').isMultiValue() ) {
						nama = edthetxxmh.field('hetxxmh.nama').val();
						if(!nama || nama == ''){
							edthetxxmh.field('hetxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hetxxmh.nama
						if(action == 'create'){
							id_hetxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hetxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_hetxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthetxxmh.field('hetxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hetxxmh.nama
					}
					// END of validasi hetxxmh.nama
				}
				
				if ( edthetxxmh.inError() ) {
					return false;
				}
			});
			
			edthetxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthetxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhetxxmh = $('#tblhetxxmh').DataTable( {
				ajax: {
					url: "../../models/hetxxmh/hetxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hetxxmh = show_inactive_status_hetxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hetxxmh.id",visible:false },
					{ data: "hetxxmh.kode" },
					{ data: "hetxxmh.nama" },
					{ data: "hetxxmh_al.nama" },
					{ data: "hetxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hetxxmh';
						$table       = 'tblhetxxmh';
						$edt         = 'edthetxxmh';
						$show_status = '_hetxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hetxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhetxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhetxxmh);
			} );
			
			tblhetxxmh.on( 'select', function( e, dt, type, indexes ) {
				hetxxmh_data    = tblhetxxmh.row( { selected: true } ).data().hetxxmh;
				id_hetxxmh      = hetxxmh_data.id;
				id_transaksi_h = id_hetxxmh; // dipakai untuk general
				is_approve     = hetxxmh_data.is_approve;
				is_nextprocess = hetxxmh_data.is_nextprocess;
				is_jurnal      = hetxxmh_data.is_jurnal;
				is_active      = hetxxmh_data.is_active;

				id_hetxxmh_old = hetxxmh_data.id_hetxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhetxxmh);
			} );

			tblhetxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hetxxmh = 0;
				id_hetxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhetxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
