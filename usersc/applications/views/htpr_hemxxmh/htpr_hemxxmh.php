<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hemxxmh';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htpr_hemxxmh';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Level</th>
                                <th>Jabatan</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpr_hemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_hemxxmh</th>
                                <th>Tanggal Efektif</th>
                                <th>Komponen</th>
                                <th>Nominal</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
				</div>
			</div>
		</div>
	</div>

</div> <!-- end of row -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hemxxmh/fn/hemxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthemxxmh, tblhemxxmh, show_inactive_status_hemxxmh = 0, id_hemxxmh;
        var edthtpr_hemxxmh, tblhtpr_hemxxmh, show_inactive_status_htpr_hemxxmh = 0, id_htpr_hemxxmh;
		
		var edtgenerate_kbm;
		// ------------- end of default variable

		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			
			edtgenerate_kbm = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_hemxxmh/generate_kbm.php",
					type: 'POST',
					data: function (d){
					}
				},
				formOptions: {
					main: {
						focus: 3
					}
				},
				table: "#tblhtpr_hemxxmh",
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
						def: "htpr_hemxxmh",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "htpr_hemxxmh.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_hemxxmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.id_hpcxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hpcxxmh/hpcxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hpcxxmh_old: id_hpcxxmh_old,
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
						label: "Nominal <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.nominal"
					},
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.tanggal_efektif",
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
						label: "Keterangan",
						name: "htpr_hemxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edtgenerate_kbm.on( 'preOpen', function( e, mode, action ) {
				edtgenerate_kbm.field('htpr_hemxxmh.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgenerate_kbm.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_hemxxmh.rows().deselect();
				}
			});

            edtgenerate_kbm.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtgenerate_kbm.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_hemxxmh.id_hpcxxmh 
					id_hpcxxmh = edtgenerate_kbm.field('htpr_hemxxmh.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edtgenerate_kbm.field('htpr_hemxxmh.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.id_hpcxxmh 

					// BEGIN of validasi htpr_hemxxmh.nominal 
					nominal = edtgenerate_kbm.field('htpr_hemxxmh.nominal').val();
					if(!nominal || nominal == ''){
						edtgenerate_kbm.field('htpr_hemxxmh.nominal').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.nominal 

					// BEGIN of validasi htpr_hemxxmh.tanggal_efektif 
					tanggal_efektif = edtgenerate_kbm.field('htpr_hemxxmh.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edtgenerate_kbm.field('htpr_hemxxmh.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.tanggal_efektif 
				}
				
				if ( edtgenerate_kbm.inError() ) {
					return false;
				}
			});

			edtgenerate_kbm.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgenerate_kbm.field('finish_on').val(finish_on);
			});

			
			edtgenerate_kbm.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblhemxxmh.ajax.reload(null,false);
				tblhemxxmh.rows().deselect();
				tblhtpr_hemxxmh.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhemxxmh = $('#tblhemxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hemxxmh/htpr_hemxxmh_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemxxmh = show_inactive_status_hemxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hemxxmh.id",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hevxxmh.nama" },
					{ data: "hetxxmh.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemxxmh';
						$table       = 'tblhemxxmh';
						$edt         = 'edthemxxmh';
						$show_status = '_hemxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{ 
						extend: 'create',
						name: 'btngenerate_kbm', 
						editor: edtgenerate_kbm, 
						text: '<i class="fa fa-google"> KBM</i>', 
						className: 'btn btn-outline', 
						titleAttr: 'Generate Komponen KBM'
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhemxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_hemxxmh];
				CekInitHeaderHD(tblhemxxmh, tbl_details);
			} );
			
			tblhemxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_hemxxmh = tblhemxxmh.row( { selected: true } ).data().hemxxmh;
				id_hemxxmh  = data_hemxxmh.id;
				id_transaksi_h   = id_hemxxmh; // dipakai untuk general
				is_approve       = data_hemxxmh.is_approve;
				is_nextprocess   = data_hemxxmh.is_nextprocess;
				is_jurnal        = data_hemxxmh.is_jurnal;
				is_active        = data_hemxxmh.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_hemxxmh];
				CekSelectHeaderHD(tblhemxxmh, tbl_details);

			} );
			
			tblhemxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hemxxmh = '';

				// atur hak akses
				tbl_details = [tblhtpr_hemxxmh];
				CekDeselectHeaderHD(tblhemxxmh, tbl_details);
			} );

// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_hemxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_hemxxmh/htpr_hemxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hemxxmh = show_inactive_status_htpr_hemxxmh;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhtpr_hemxxmh",
				formOptions: {
					main: {
						focus: 3
					}
				},
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
						def: "htpr_hemxxmh",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "htpr_hemxxmh.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_hemxxmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.id_hpcxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hpcxxmh/hpcxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hpcxxmh_old: id_hpcxxmh_old,
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
						label: "Nominal <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.nominal"
					},
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "htpr_hemxxmh.tanggal_efektif",
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
						label: "Keterangan",
						name: "htpr_hemxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_hemxxmh.on( 'preOpen', function( e, mode, action ) {
				edthtpr_hemxxmh.field('htpr_hemxxmh.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hemxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_hemxxmh.rows().deselect();
				}
			});

            edthtpr_hemxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_hemxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_hemxxmh.id_hpcxxmh 
					id_hpcxxmh = edthtpr_hemxxmh.field('htpr_hemxxmh.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edthtpr_hemxxmh.field('htpr_hemxxmh.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.id_hpcxxmh 

					// BEGIN of validasi htpr_hemxxmh.nominal 
					nominal = edthtpr_hemxxmh.field('htpr_hemxxmh.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_hemxxmh.field('htpr_hemxxmh.nominal').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.nominal 

					// BEGIN of validasi htpr_hemxxmh.tanggal_efektif 
					tanggal_efektif = edthtpr_hemxxmh.field('htpr_hemxxmh.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_hemxxmh.field('htpr_hemxxmh.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hemxxmh.tanggal_efektif 
				}
				
				if ( edthtpr_hemxxmh.inError() ) {
					return false;
				}
			});

			edthtpr_hemxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hemxxmh.field('finish_on').val(finish_on);
			});

			
			edthtpr_hemxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_hemxxmh = $('#tblhtpr_hemxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hemxxmh/htpr_hemxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hemxxmh = show_inactive_status_htpr_hemxxmh;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "htpr_hemxxmh.id",visible:false },
					{ data: "htpr_hemxxmh.id_hemxxmh",visible:false },
					{ data: "htpr_hemxxmh.tanggal_efektif" },
					{ data: "hpcxxmh.nama" },
					{ 
						data: "htpr_hemxxmh.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htpr_hemxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_hemxxmh';
						$table       = 'tblhtpr_hemxxmh';
						$edt         = 'edthtpr_hemxxmh';
						$show_status = '_htpr_hemxxmh';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_hemxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_hemxxmh.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhtpr_hemxxmh, 'htpr_hemxxmh' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhtpr_hemxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_hemxxmh = tblhtpr_hemxxmh.row( { selected: true } ).data().htpr_hemxxmh;
				id_htpr_hemxxmh   = data_htpr_hemxxmh.id;
				id_transaksi_d    = id_htpr_hemxxmh; // dipakai untuk general
				is_active_d       = data_htpr_hemxxmh.is_active;
				id_hpcxxmh_old    = data_htpr_hemxxmh.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhtpr_hemxxmh );
			} );

			tblhtpr_hemxxmh.on( 'deselect', function() {
				id_htpr_hemxxmh = 0;
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhtpr_hemxxmh );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
