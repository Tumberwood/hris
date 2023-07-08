<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gbrxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgbrxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cabang</th>
                                <th>Alamat</th>
                                <th>Kota</th>
                                <th>Perusahaan</th>
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

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtgbrxxmh, tblgbrxxmh, show_inactive_status_gbrxxmh = 0, id_gbrxxmh;
		// ------------- end of default variable

		var id_gctxxmh_old = 0, id_gcpxxmh_old = 0;

		$(document).ready(function() {

			//start datatables editor
			edtgbrxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gbrxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gbrxxmh = show_inactive_status_gbrxxmh;
					}
				},
				table: "#tblgbrxxmh",
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
						def: "gbrxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "gbrxxmh.is_active",
						type: "hidden",
						def: 1
					}, 	{
						label: "Cabang <sup class='text-danger'>*<sup>",
						name: "gbrxxmh.nama"
					}, 	{
						label: "Alamat <sup class='text-danger'>*<sup>",
						name: "gbrxxmh.alamat"
					},	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "gbrxxmh.id_gctxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_old,
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
					}, 	{
						label: "Perusahaan <sup class='text-danger'>*<sup>",
						name: "gbrxxmh.id_gcpxxmh",
                        type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gcpxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gcpxxmh_old: id_gcpxxmh_old,
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
					}, 	{
						label: "Latitude",
						name: "gbrxxmh.lat",
                        type: "readonly"
					}, 	{
						label: "Longitude",
						name: "gbrxxmh.lng",
                        type: "readonly"
					}, {
						label: "Maps",
						name: "tes_maps"
					}
				]
			} );

			edtgbrxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgbrxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgbrxxmh.rows().deselect();
				}
			});

			edtgbrxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgbrxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

                    // BEGIN of validasi gbrxxmh.nama
					if ( ! edtgbrxxmh.field('gbrxxmh.nama').isMultiValue() ) {
						gbrxxmh_nama = edtgbrxxmh.field('gbrxxmh.nama').val();
						if(!gbrxxmh_nama || gbrxxmh_nama == ''){
							edtgbrxxmh.field('gbrxxmh.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gbrxxmh.nama

                    // BEGIN of validasi gbrxxmh.alamat
					if ( ! edtgbrxxmh.field('gbrxxmh.alamat').isMultiValue() ) {
						alamat = edtgbrxxmh.field('gbrxxmh.alamat').val();
						if(!alamat || alamat == ''){
							edtgbrxxmh.field('gbrxxmh.alamat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gbrxxmh.alamat
					
					// BEGIN of validasi gbrxxmh.id_gctxxmh
					if ( ! edtgbrxxmh.field('gbrxxmh.id_gctxxmh').isMultiValue() ) {
						id_gctxxmh = edtgbrxxmh.field('gbrxxmh.id_gctxxmh').val();
						if(!id_gctxxmh || id_gctxxmh == ''){
							edtgbrxxmh.field('gbrxxmh.id_gctxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gbrxxmh.id_gctxxmh
				}
				
				if ( edtgbrxxmh.inError() ) {
					return false;
				}
			});

			edtgbrxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgbrxxmh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblgbrxxmh = $('#tblgbrxxmh').DataTable( {
				ajax: {
					url: "../../models/core/gbrxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gbrxxmh = show_inactive_status_gbrxxmh;
					}
				},
				order: [[ 2, "asc" ],[1,"asc"]],
				columns: [
					{ data: "gbrxxmh.id",visible:false },
					{ data: "gbrxxmh.nama" },
					{ data: "gbrxxmh.alamat" },
					{ data: "gctxxmh.nama" },
					{ data: "gcpxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gbrxxmh';
						$table       = 'tblgbrxxmh';
						$edt         = 'edtgbrxxmh';
						$show_status = '_gbrxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gbrxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgbrxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgbrxxmh);
			} );
			
			tblgbrxxmh.on( 'select', function( e, dt, type, indexes ) {
				gbrxxmh_data    = tblgbrxxmh.row( { selected: true } ).data().gbrxxmh;
				id_gbrxxmh      = gbrxxmh_data.id;
				id_transaksi_h = id_gbrxxmh; // dipakai untuk general
				is_approve     = gbrxxmh_data.is_approve;
				is_nextprocess = gbrxxmh_data.is_nextprocess;
				is_jurnal      = gbrxxmh_data.is_jurnal;
				is_active      = gbrxxmh_data.is_active;

				id_gctxxmh_old = gbrxxmh_data.id_gctxxmh;
				id_gcpxxmh_old = gbrxxmh_data.id_gcpxxmh;

				// atur hak akses
				CekSelectHeaderH(tblgbrxxmh);
			} );

			tblgbrxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gbrxxmh = 0;
				id_gctxxmh_old = 0;
				id_gcpxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblgbrxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
