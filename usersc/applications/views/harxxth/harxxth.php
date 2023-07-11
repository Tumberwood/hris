<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'harxxth';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<div id="frmharxxth">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-6">
									<editor-field name="harxxth.tanggal_efektif"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hemxxmh"></editor-field>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6">
									<editor-field name="hovxxmh_awal_nama"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hovxxmh_akhir"></editor-field>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<editor-field name="hodxxmh_awal_nama"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hodxxmh_akhir"></editor-field>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<editor-field name="hosxxmh_awal_nama"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hosxxmh_akhir"></editor-field>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<editor-field name="hevxxmh_awal_nama"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hevxxmh_akhir"></editor-field>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-6">
									<editor-field name="hetxxmh_awal_nama"></editor-field>
								</div>
								<div class="col-lg-6">
									<editor-field name="harxxth.id_hetxxmh_akhir"></editor-field>
								</div>
							</div>

						</div>
					</div>
                    <table id="tblharxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tanggal Efektif</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/harxxth/fn/harxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtharxxth, tblharxxth, show_inactive_status_harxxth = 0, id_harxxth;
		// ------------- end of default variable

		var id_hemxxmh_old = 0, id_hovxxmh_awal_old = 0, id_hovxxmh_akhir_old = 0, id_hodxxmh_awal_old = 0, id_hodxxmh_akhir_old = 0, id_hosxxmh_awal_old = 0, id_hosxxmh_akhir_old = 0, id_hevxxmh_awal_old = 0, id_hevxxmh_akhir_old = 0, id_hetxxmh_awal_old = 0, id_hetxxmh_akhir_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtharxxth = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/harxxth/harxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_harxxth = show_inactive_status_harxxth;
					}
				},
				table: "#tblharxxth",
				template: "#frmharxxth",
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
						def: "harxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "harxxth.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "harxxth.tanggal_efektif",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "harxxth.id_hemxxmh",
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
						label: "harxxth.id_hovxxmh_awal",
						name: "harxxth.id_hovxxmh_awal",
						// type: "hidden"
					},
					{
						label: "Divisi Awal",
						name: "hovxxmh_awal_nama",
						type: "readonly"
					},
					{
						label: "Divisi Akhir",
						name: "harxxth.id_hovxxmh_akhir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hovxxmh/hovxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hovxxmh: id_hovxxmh,
										id_hovxxmh_old: id_hovxxmh_akhir_old,
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
						label: "harxxth.id_hodxxmh_awal",
						name: "harxxth.id_hodxxmh_awal",
						// type: "hidden"
					},
					{
						label: "Department Awal",
						name: "hodxxmh_awal_nama",
						type: "readonly"
					},
					{
						label: "Department Akhir",
						name: "harxxth.id_hodxxmh_akhir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hodxxmh/hodxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hodxxmh: id_hodxxmh,
										id_hodxxmh_old: id_hodxxmh_akhir_old,
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
						label: "harxxth.id_hosxxmh_awal",
						name: "harxxth.id_hosxxmh_awal",
						// type: "hidden"
					},
					{
						label: "Section Awal",
						name: "hosxxmh_awal_nama",
						type: "readonly"
					},
					{
						label: "Section Akhir",
						name: "harxxth.id_hosxxmh_akhir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hosxxmh/hosxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hosxxmh: id_hosxxmh,
										id_hosxxmh_old: id_hosxxmh_akhir_old,
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
						label: "harxxth.id_hevxxmh_awal",
						name: "harxxth.id_hevxxmh_awal",
						// type: "hidden"
					},
					{
						label: "Level Awal",
						name: "hevxxmh_awal_nama",
						type: "readonly"
					},
					{
						label: "Level Akhir",
						name: "harxxth.id_hevxxmh_akhir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hevxxmh/hevxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hevxxmh: id_hevxxmh,
										id_hevxxmh_old: id_hevxxmh_akhir_old,
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
						label: "harxxth.id_hetxxmh_awal",
						name: "harxxth.id_hetxxmh_awal",
						// type: "hidden"
					},
					{
						label: "Jabatan Awal",
						name: "hetxxmh_awal_nama",
						type: "readonly"
					},
					{
						label: "Jabatan Akhir",
						name: "harxxth.id_hetxxmh_akhir",
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
										id_hetxxmh: id_hetxxmh,
										id_hetxxmh_old: id_hetxxmh_akhir_old,
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
						name: "harxxth.keterangan",
						type: "textarea"
					}
				]
			} );

			edtharxxth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtharxxth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblharxxth.rows().deselect();
				}
			});

			edtharxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-xl");
			});

			edtharxxth.dependent( 'harxxth.id_hemxxmh', function ( val, data, callback ) {
				if(val > 0){
					harxxth_load_hemxxmh();
				}
				return {}
			}, {event: 'keyup change'});

            edtharxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi harxxth.id_hemxxmh
					if ( ! edtharxxth.field('harxxth.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edtharxxth.field('harxxth.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edtharxxth.field('harxxth.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi harxxth.id_hemxxmh
					
					// BEGIN of validasi harxxth.tanggal_efektif
					if ( ! edtharxxth.field('harxxth.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edtharxxth.field('harxxth.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edtharxxth.field('harxxth.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}
					// END of validasi harxxth.tanggal_efektif
				}
				
				if ( edtharxxth.inError() ) {
					return false;
				}
			});
			
			edtharxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtharxxth.field('finish_on').val(finish_on);
			});

			//start datatables
			tblharxxth = $('#tblharxxth').DataTable( {
				ajax: {
					url: "../../models/harxxth/harxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_harxxth = show_inactive_status_harxxth;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "harxxth.id",visible:false },
					{ data: "harxxth.kode" },
					{ data: "hemxxmh_data" },
					{ data: "harxxth.tanggal_efektif" },
					{ data: "harxxth.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_harxxth';
						$table       = 'tblharxxth';
						$edt         = 'edtharxxth';
						$show_status = '_harxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.harxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblharxxth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblharxxth);
			} );
			
			tblharxxth.on( 'select', function( e, dt, type, indexes ) {
				harxxth_data    = tblharxxth.row( { selected: true } ).data().harxxth;
				id_harxxth      = harxxth_data.id;
				id_transaksi_h = id_harxxth; // dipakai untuk general
				is_approve     = harxxth_data.is_approve;
				is_nextprocess = harxxth_data.is_nextprocess;
				is_jurnal      = harxxth_data.is_jurnal;
				is_active      = harxxth_data.is_active;

				id_hemxxmh_old = harxxth_data.id_hemxxmh;
				
				id_hovxxmh_awal_old = harxxth_data.id_hovxxmh_awal;
				id_hovxxmh_akhir_old = harxxth_data.id_hovxxmh_akhir;

				id_hodxxmh_awal_old = harxxth_data.id_hodxxmh_awal;
				id_hodxxmh_akhir_old = harxxth_data.id_hodxxmh_akhir;

				id_hosxxmh_awal_old = harxxth_data.id_hosxxmh_awal;
				id_hosxxmh_akhir_old = harxxth_data.id_hosxxmh_akhir;

				id_hevxxmh_awal_old = harxxth_data.id_hevxxmh_awal;
				id_hevxxmh_akhir_old = harxxth_data.id_hevxxmh_akhir;

				id_hetxxmh_awal_old = harxxth_data.id_hetxxmh_awal;
				id_hetxxmh_akhir_old = harxxth_data.id_hetxxmh_akhir;

				// atur hak akses
				CekSelectHeaderH(tblharxxth);
			} );

			tblharxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_harxxth = 0;

				id_hemxxmh_old = 0, id_hovxxmh_awal_old = 0, id_hovxxmh_akhir_old = 0, id_hodxxmh_awal_old = 0, id_hodxxmh_akhir_old = 0, id_hosxxmh_awal_old = 0, id_hosxxmh_akhir_old = 0, id_hevxxmh_awal_old = 0, id_hevxxmh_akhir_old = 0, id_hetxxmh_awal_old = 0, id_hetxxmh_akhir_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblharxxth);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
