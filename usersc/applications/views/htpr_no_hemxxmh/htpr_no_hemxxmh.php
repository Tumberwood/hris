<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpr_no_hemxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpr_no_hemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_hemxxmh</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Jabatan</th>
                                <th>Tanggal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_no_hemxxmh/fn/htpr_no_hemxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpr_no_hemxxmh, tblhtpr_no_hemxxmh, show_inactive_status_htpr_no_hemxxmh = 0, id_htpr_no_hemxxmh;
		// ------------- end of default variable

		var id_hemxxmh_old = 0;
		var id_hadxxmh_saran = 0;
		var is_need_approval = 1;
		
		$(document).ready(function() {
			//start datatables editor
			edthtpr_no_hemxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_no_hemxxmh/htpr_no_hemxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_no_hemxxmh = show_inactive_status_htpr_no_hemxxmh;
					}
				},
				table: "#tblhtpr_no_hemxxmh",
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
						def: "htpr_no_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_no_hemxxmh.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Nama<sup class='text-danger'>*<sup>",
						name: "htpr_no_hemxxmh.id_hemxxmh",
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
						label: "id_hetxxmh",
						name: "htpr_no_hemxxmh.id_hetxxmh",
                        type: "hidden",
					}, 
					{
						label: "id_hodxxmh",
						name: "htpr_no_hemxxmh.id_hodxxmh",
                        type: "hidden",
					}, 
					{
						label: "Department",
						name: "id_hodxxmh",
                        type: "readonly",
					}, 
					{
						label: "Jabatan",
						name: "id_hetxxmh",
                        type: "readonly",
					}, 
					{
						label: "Tanggal",
						name: "htpr_no_hemxxmh.tanggal",
						type: "datetime",
						format: 'DD MMM YYYY',
						type: "datetime",
						def: function () { 
							var currentDate = new Date();
							return currentDate;
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}
				]
			} );

			edthtpr_no_hemxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_no_hemxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_no_hemxxmh.rows().deselect();
				}

			});

			edthtpr_no_hemxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtpr_no_hemxxmh.dependent( 'htpr_no_hemxxmh.id_hemxxmh', function ( val, data, callback ) {
				loadHemjb();
				return {}
			}, {event: 'keyup change'});

            edthtpr_no_hemxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_no_hemxxmh.id_hemxxmh
					id_hemxxmh = edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hemxxmh').val();
					if ( ! edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hemxxmh').isMultiValue() ) {
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpr_no_hemxxmh.id_hemxxmh

				}
				
				if ( edthtpr_no_hemxxmh.inError() ) {
					return false;
				}
			});
			
			edthtpr_no_hemxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_no_hemxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtpr_no_hemxxmh = $('#tblhtpr_no_hemxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_no_hemxxmh/htpr_no_hemxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_no_hemxxmh = show_inactive_status_htpr_no_hemxxmh;
					}
				},
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "htpr_no_hemxxmh.id",visible:false },
					{ data: "htpr_no_hemxxmh.id_hemxxmh",visible:false },
					{ data: "hemxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "htpr_no_hemxxmh.tanggal" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_no_hemxxmh';
						$table       = 'tblhtpr_no_hemxxmh';
						$edt         = 'edthtpr_no_hemxxmh';
						$show_status = '_htpr_no_hemxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_no_hemxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtpr_no_hemxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpr_no_hemxxmh);
			} );
			
			tblhtpr_no_hemxxmh.on( 'select', function( e, dt, type, indexes ) {
				htpr_no_hemxxmh_data    = tblhtpr_no_hemxxmh.row( { selected: true } ).data().htpr_no_hemxxmh;
				id_htpr_no_hemxxmh      = htpr_no_hemxxmh_data.id;
				id_transaksi_h = id_htpr_no_hemxxmh; // dipakai untuk general
				is_approve     = htpr_no_hemxxmh_data.is_approve;
				is_nextprocess = htpr_no_hemxxmh_data.is_nextprocess;
				is_jurnal      = htpr_no_hemxxmh_data.is_jurnal;
				is_active      = htpr_no_hemxxmh_data.is_active;

				id_hemxxmh_old      = htpr_no_hemxxmh_data.id_hemxxmh;
				find_status();
				// atur hak akses
				CekSelectHeaderH(tblhtpr_no_hemxxmh);
			} );

			tblhtpr_no_hemxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpr_no_hemxxmh = 0;
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtpr_no_hemxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
