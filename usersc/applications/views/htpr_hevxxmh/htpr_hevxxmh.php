<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hevxxmh';
    $nama_tabels_d 	  = [];
    $nama_tabels_d[0] = 'htpr_hevxxmh';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhevxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhtpr_hevxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_hevxxmh</th>
								<th>Tanggal</th>
								<th>Komponen</th>
								<th>Nominal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_hevxxmh/fn/htpr_hevxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthevxxmh, tblhevxxmh, show_inactive_status_hevxxmh = 0, id_hevxxmh;
        var edthtpr_hevxxmh, tblhtpr_hevxxmh, show_inactive_status_htpr_hevxxmh = 0, id_htpr_hevxxmh;
		// ------------- end of default variable
	
		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			//start datatables
			tblhevxxmh = $('#tblhevxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hevxxmh/htpr_hevxxmh_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevxxmh = show_inactive_status_hevxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hevxxmh.id",visible:false },
					{ data: "hevxxmh.kode" },
					{ data: "hevxxmh.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hevxxmh';
						$table       = 'tblhevxxmh';
						$edt         = 'edthevxxmh';
						$show_status = '_hevxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hevxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhevxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_hevxxmh];
				CekInitHeaderHD(tblhevxxmh, tbl_details);
			} );
			
			tblhevxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_hevxxmh = tblhevxxmh.row( { selected: true } ).data().hevxxmh;
				id_hevxxmh  = data_hevxxmh.id;
				id_transaksi_h   = id_hevxxmh; // dipakai untuk general
				is_approve       = data_hevxxmh.is_approve;
				is_nextprocess   = data_hevxxmh.is_nextprocess;
				is_jurnal        = data_hevxxmh.is_jurnal;
				is_active        = data_hevxxmh.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_hevxxmh];
				CekSelectHeaderHD(tblhevxxmh, tbl_details);

			} );
			
			tblhevxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hevxxmh = '';

				// atur hak akses
				tbl_details = [tblhtpr_hevxxmh];
				CekDeselectHeaderHD(tblhevxxmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_hevxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_hevxxmh/htpr_hevxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hevxxmh = show_inactive_status_htpr_hevxxmh;
						d.id_hevxxmh = id_hevxxmh;
					}
				},
				table: "#tblhtpr_hevxxmh",
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
						def: "htpr_hevxxmh",
						type: "hidden"
					},	{
						label: "id_hevxxmh",
						name: "htpr_hevxxmh.id_hevxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_hevxxmh.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_hevxxmh.id_hpcxxmh",
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
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "htpr_hevxxmh.tanggal_efektif",
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
						label: "Nominal <sup class='text-danger'>*<sup>",
						name: "htpr_hevxxmh.nominal"
					},
					{
						label: "Keterangan",
						name: "htpr_hevxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_hevxxmh.on( 'preOpen', function( e, mode, action ) {
				edthtpr_hevxxmh.field('htpr_hevxxmh.id_hevxxmh').val(id_hevxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hevxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_hevxxmh.rows().deselect();
				}
			});

            edthtpr_hevxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_hevxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_hevxxmh.id_hpcxxmh 
					id_hpcxxmh = edthtpr_hevxxmh.field('htpr_hevxxmh.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edthtpr_hevxxmh.field('htpr_hevxxmh.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hevxxmh.id_hpcxxmh 

					// BEGIN of validasi htpr_hevxxmh.tanggal_efektif 
					tanggal_efektif = edthtpr_hevxxmh.field('htpr_hevxxmh.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_hevxxmh.field('htpr_hevxxmh.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hevxxmh.tanggal_efektif 

					// BEGIN of validasi htpr_hevxxmh.nominal 
					nominal = edthtpr_hevxxmh.field('htpr_hevxxmh.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_hevxxmh.field('htpr_hevxxmh.nominal').error( 'Wajib diisi!' );
					}
					if(nominal <= 0 ){
						edthtpr_hevxxmh.field('htpr_hevxxmh.nominal').error( 'Inputan harus > 0' );
					}
					if(isNaN(nominal) ){
						edthtpr_hevxxmh.field('htpr_hevxxmh.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_hevxxmh.nominal 
				}
				
				if ( edthtpr_hevxxmh.inError() ) {
					return false;
				}
			});

			edthtpr_hevxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hevxxmh.field('finish_on').val(finish_on);
			});

			
			edthtpr_hevxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_hevxxmh = $('#tblhtpr_hevxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hevxxmh/htpr_hevxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hevxxmh = show_inactive_status_htpr_hevxxmh;
						d.id_hevxxmh = id_hevxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				rowGroup: {
					dataSrc: 'htpr_hevxxmh.tanggal_efektif',
				},
				columns: [
					{ data: "htpr_hevxxmh.id",visible:false },
					{ 
						data: "htpr_hevxxmh.id_hevxxmh",
						visible:false 
					},
					{ 
						data: "htpr_hevxxmh.tanggal_efektif" ,
						visible:false 
					},
					{ data: "hpcxxmh.nama" },
					{ 
						data: "htpr_hevxxmh.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_hevxxmh';
						$table       = 'tblhtpr_hevxxmh';
						$edt         = 'edthtpr_hevxxmh';
						$show_status = '_htpr_hevxxmh';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_hevxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_hevxxmh.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhevxxmh, tblhtpr_hevxxmh, 'htpr_hevxxmh' );
				CekDrawDetailHDFinal(tblhevxxmh);
			} );

			tblhtpr_hevxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_hevxxmh = tblhtpr_hevxxmh.row( { selected: true } ).data().htpr_hevxxmh;
				id_htpr_hevxxmh   = data_htpr_hevxxmh.id;
				id_transaksi_d    = id_htpr_hevxxmh; // dipakai untuk general
				is_active_d       = data_htpr_hevxxmh.is_active;
				
				id_hpcxxmh_old       = data_htpr_hevxxmh.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhevxxmh, tblhtpr_hevxxmh );
			} );

			tblhtpr_hevxxmh.on( 'deselect', function() {
				id_htpr_hevxxmh = '';
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhevxxmh, tblhtpr_hevxxmh );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
