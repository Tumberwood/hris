<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'havxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhavxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pelanggaran</th>
                                <th>Saran Tindakan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/havxxmh/fn/havxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthavxxmh, tblhavxxmh, show_inactive_status_havxxmh = 0, id_havxxmh;
		// ------------- end of default variable

		var id_hadxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthavxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/havxxmh/havxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_havxxmh = show_inactive_status_havxxmh;
					}
				},
				table: "#tblhavxxmh",
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
						def: "havxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "havxxmh.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Pelanggaran <sup class='text-danger'>*<sup>",
						name: "havxxmh.nama"
					},
					{
						label: "Saran Tindakan <sup class='text-danger'>*<sup>",
						name: "havxxmh.id_hadxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hadxxmh/hadxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hadxxmh_old: id_hadxxmh_old,
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
						label: "Keterangan",
						name: "havxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthavxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthavxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhavxxmh.rows().deselect();
				}
			});

			edthavxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthavxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi havxxmh.nama
					if ( ! edthavxxmh.field('havxxmh.nama').isMultiValue() ) {
						nama = edthavxxmh.field('havxxmh.nama').val();
						if(!nama || nama == ''){
							edthavxxmh.field('havxxmh.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi havxxmh.nama

					// BEGIN of validasi havxxmh.id_hadxxmh
					if ( ! edthavxxmh.field('havxxmh.id_hadxxmh').isMultiValue() ) {
						id_hadxxmh = edthavxxmh.field('havxxmh.id_hadxxmh').val();
						if(!id_hadxxmh || id_hadxxmh == ''){
							edthavxxmh.field('havxxmh.id_hadxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi havxxmh.id_hadxxmh
				}
				
				if ( edthavxxmh.inError() ) {
					return false;
				}
			});
			
			edthavxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthavxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhavxxmh = $('#tblhavxxmh').DataTable( {
				ajax: {
					url: "../../models/havxxmh/havxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_havxxmh = show_inactive_status_havxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "havxxmh.id",visible:false },
					{ data: "havxxmh.nama" },
					{ data: "hadxxmh.nama" },
					{ data: "havxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_havxxmh';
						$table       = 'tblhavxxmh';
						$edt         = 'edthavxxmh';
						$show_status = '_havxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.havxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhavxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhavxxmh);
			} );
			
			tblhavxxmh.on( 'select', function( e, dt, type, indexes ) {
				havxxmh_data    = tblhavxxmh.row( { selected: true } ).data().havxxmh;
				id_havxxmh      = havxxmh_data.id;
				id_transaksi_h = id_havxxmh; // dipakai untuk general
				is_approve     = havxxmh_data.is_approve;
				is_nextprocess = havxxmh_data.is_nextprocess;
				is_jurnal      = havxxmh_data.is_jurnal;
				is_active      = havxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhavxxmh);
			} );

			tblhavxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_havxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhavxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
