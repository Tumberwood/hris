<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gctxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgctxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>City</th>
                                <th>Province</th>
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
		var edtgctxxmh, tblgctxxmh, show_inactive_status_gctxxmh = 0, id_gctxxmh;
		// ------------- end of default 
		
		var id_gpvxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtgctxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gctxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gctxxmh = show_inactive_status_gctxxmh;
					}
				},
				table: "#tblgctxxmh",
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
						def: "gctxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "_blank.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "City <sup class='text-danger'>*<sup>",
						name: "gctxxmh.nama"
					}, 	{
						label: "Province <sup class='text-danger'>*<sup>",
						name: "gctxxmh.id_gpvxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gpvxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gpvxxmh_old: id_gpvxxmh_old,
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
					}
				]
			} );

			edtgctxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgctxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgctxxmh.rows().deselect();
				}
			});

			edtgctxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgctxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

                    // BEGIN of validasi gctxxmh.nama
					if ( ! edtgctxxmh.field('gctxxmh.nama').isMultiValue() ) {
						gctxxmh_nama = edtgctxxmh.field('gctxxmh.nama').val();
						if(!gctxxmh_nama || gctxxmh_nama == ''){
							edtgctxxmh.field('gctxxmh.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gctxxmh.nama

                    // BEGIN of validasi gctxxmh.id_gpvxxmh
					if ( ! edtgctxxmh.field('gctxxmh.id_gpvxxmh').isMultiValue() ) {
						id_gpvxxmh = edtgctxxmh.field('gctxxmh.id_gpvxxmh').val();
						if(!id_gpvxxmh || id_gpvxxmh == ''){
							edtgctxxmh.field('gctxxmh.id_gpvxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gctxxmh.id_gpvxxmh

				}
				
				if ( edtgctxxmh.inError() ) {
					return false;
				}
			});

			edtgctxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgctxxmh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblgctxxmh = $('#tblgctxxmh').DataTable( {
				ajax: {
					url: "../../models/core/gctxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gctxxmh = show_inactive_status_gctxxmh;
					}
				},
				order: [[ 2, "asc" ],[ 1, "asc" ]],
				columns: [
					{ data: "gctxxmh.id",visible:false },
					{ data: "gctxxmh.nama" },
					{ data: "gpvxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gctxxmh';
						$table       = 'tblgctxxmh';
						$edt         = 'edtgctxxmh';
						$show_status = '_gctxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gctxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgctxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgctxxmh);
			} );
			
			tblgctxxmh.on( 'select', function( e, dt, type, indexes ) {
				gctxxmh_data    = tblgctxxmh.row( { selected: true } ).data().gctxxmh;
				id_gctxxmh      = gctxxmh_data.id;
				id_transaksi_h = id_gctxxmh; // dipakai untuk general
				is_approve     = gctxxmh_data.is_approve;
				is_nextprocess = gctxxmh_data.is_nextprocess;
				is_jurnal      = gctxxmh_data.is_jurnal;
				is_active      = gctxxmh_data.is_active;

				id_gpvxxmh_old = gctxxmh_data.id_gpvxxmh;

				// atur hak akses
				CekSelectHeaderH(tblgctxxmh);
			} );

			tblgctxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gctxxmh = 0;
				id_gpvxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblgctxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
