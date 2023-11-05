<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hesxxtd_resign';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhesxxtd_resign" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tanggal Akhir</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hesxxtd_resign/fn/hesxxtd_resign_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxtd_resign, tblhesxxtd_resign, show_inactive_status_hesxxtd_resign = 0, id_hesxxtd_resign;
		// ------------- end of default variable
		var is_need_approval = 1;
		var is_need_generate_kode = 1;
		var id_hemxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthesxxtd_resign = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hesxxtd_resign/hesxxtd_resign.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd_resign = show_inactive_status_hesxxtd_resign;
					}
				},
				table: "#tblhesxxtd_resign",
				fields: [ 
					{
						// untuk generate_kode
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						// untuk generate_kode
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						// untuk generate_kode
						label: "field_tanggal",
						name: "field_tanggal",
						type: "hidden"
					},
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
						def: "hesxxtd_resign",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hesxxtd_resign.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Nama<sup class='text-danger'>*<sup>",
						name: "hesxxtd_resign.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
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
					},
					{
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "hesxxtd_resign.tanggal_selesai",
						type: "datetime",
						format: 'DD MMM YYYY',
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Keterangan",
						name: "hesxxtd_resign.keterangan",
						type: "textarea"
					}
				]
			} );

			edthesxxtd_resign.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd_resign.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhesxxtd_resign.rows().deselect();
					edthesxxtd_resign.field('kategori_dokumen').val('');
					edthesxxtd_resign.field('kategori_dokumen_value').val('');
					edthesxxtd_resign.field('field_tanggal').val('created_on');
				}
			});

			edthesxxtd_resign.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthesxxtd_resign.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					id_hemxxmh = edthesxxtd_resign.field('hesxxtd_resign.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthesxxtd_resign.field('hesxxtd_resign.id_hemxxmh').error( 'Wajib diisi!' );
					}
					tanggal_selesai = edthesxxtd_resign.field('hesxxtd_resign.tanggal_selesai').val();
					if(!tanggal_selesai || tanggal_selesai == ''){
						edthesxxtd_resign.field('hesxxtd_resign.tanggal_selesai').error( 'Wajib diisi!' );
					}
				}
				
				if ( edthesxxtd_resign.inError() ) {
					return false;
				}
			});
			
			edthesxxtd_resign.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd_resign.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhesxxtd_resign = $('#tblhesxxtd_resign').DataTable( {
				ajax: {
					url: "../../models/hesxxtd_resign/hesxxtd_resign.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd_resign = show_inactive_status_hesxxtd_resign;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hesxxtd_resign.id",visible:false },
					{ data: "hesxxtd_resign.kode" },
					{ data: "hemxxmh_data" },
					{ data: "hesxxtd_resign.tanggal_selesai" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hesxxtd_resign';
						$table       = 'tblhesxxtd_resign';
						$edt         = 'edthesxxtd_resign';
						$show_status = '_hesxxtd_resign';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hesxxtd_resign.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhesxxtd_resign.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhesxxtd_resign);
			} );
			
			tblhesxxtd_resign.on( 'select', function( e, dt, type, indexes ) {
				hesxxtd_resign_data    = tblhesxxtd_resign.row( { selected: true } ).data().hesxxtd_resign;
				id_hesxxtd_resign      = hesxxtd_resign_data.id;
				id_transaksi_h = id_hesxxtd_resign; // dipakai untuk general
				is_approve     = hesxxtd_resign_data.is_approve;
				is_nextprocess = hesxxtd_resign_data.is_nextprocess;
				is_jurnal      = hesxxtd_resign_data.is_jurnal;
				is_active      = hesxxtd_resign_data.is_active;
				id_hemxxmh_old      = hesxxtd_resign_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhesxxtd_resign);
			} );

			tblhesxxtd_resign.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hesxxtd_resign = '';
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhesxxtd_resign);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
