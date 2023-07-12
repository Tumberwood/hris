<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'heyxxmh';
    $nama_tabels_d 	  = [];
    $nama_tabels_d[0] = 'htpr_heyxxmh';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblheyxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
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
					<table id="tblhtpr_heyxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_heyxxmh</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_heyxxmh/fn/htpr_heyxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtheyxxmh, tblheyxxmh, show_inactive_status_heyxxmh = 0, id_heyxxmh;
        var edthtpr_heyxxmh, tblhtpr_heyxxmh, show_inactive_status_htpr_heyxxmh = 0, id_htpr_heyxxmh;
		// ------------- end of default variable
	
		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			//start datatables
			tblheyxxmh = $('#tblheyxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_heyxxmh/htpr_heyxxmh_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmh = show_inactive_status_heyxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "heyxxmh.id",visible:false },
					{ data: "heyxxmh.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_heyxxmh';
						$table       = 'tblheyxxmh';
						$edt         = 'edtheyxxmh';
						$show_status = '_heyxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.heyxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblheyxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_heyxxmh];
				CekInitHeaderHD(tblheyxxmh, tbl_details);
			} );
			
			tblheyxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_heyxxmh = tblheyxxmh.row( { selected: true } ).data().heyxxmh;
				id_heyxxmh  = data_heyxxmh.id;
				id_transaksi_h   = id_heyxxmh; // dipakai untuk general
				is_approve       = data_heyxxmh.is_approve;
				is_nextprocess   = data_heyxxmh.is_nextprocess;
				is_jurnal        = data_heyxxmh.is_jurnal;
				is_active        = data_heyxxmh.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_heyxxmh];
				CekSelectHeaderHD(tblheyxxmh, tbl_details);

			} );
			
			tblheyxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_heyxxmh = '';

				// atur hak akses
				tbl_details = [tblhtpr_heyxxmh];
				CekDeselectHeaderHD(tblheyxxmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_heyxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_heyxxmh/htpr_heyxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_heyxxmh = show_inactive_status_htpr_heyxxmh;
						d.id_heyxxmh = id_heyxxmh;
					}
				},
				table: "#tblhtpr_heyxxmh",
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
						def: "htpr_heyxxmh",
						type: "hidden"
					},	{
						label: "id_heyxxmh",
						name: "htpr_heyxxmh.id_heyxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_heyxxmh.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_heyxxmh.id_hpcxxmh",
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
						name: "htpr_heyxxmh.tanggal_efektif",
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
						name: "htpr_heyxxmh.nominal"
					},
					{
						label: "Keterangan",
						name: "htpr_heyxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_heyxxmh.on( 'preOpen', function( e, mode, action ) {
				edthtpr_heyxxmh.field('htpr_heyxxmh.id_heyxxmh').val(id_heyxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_heyxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_heyxxmh.rows().deselect();
				}
			});

            edthtpr_heyxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_heyxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_heyxxmh.id_hpcxxmh 
					id_hpcxxmh = edthtpr_heyxxmh.field('htpr_heyxxmh.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edthtpr_heyxxmh.field('htpr_heyxxmh.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_heyxxmh.id_hpcxxmh 

					// BEGIN of validasi htpr_heyxxmh.tanggal_efektif 
					tanggal_efektif = edthtpr_heyxxmh.field('htpr_heyxxmh.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_heyxxmh.field('htpr_heyxxmh.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_heyxxmh.tanggal_efektif 

					// BEGIN of validasi htpr_heyxxmh.nominal 
					nominal = edthtpr_heyxxmh.field('htpr_heyxxmh.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_heyxxmh.field('htpr_heyxxmh.nominal').error( 'Wajib diisi!' );
					}
					if(nominal <= 0 ){
						edthtpr_heyxxmh.field('htpr_heyxxmh.nominal').error( 'Inputan harus > 0' );
					}
					if(isNaN(nominal) ){
						edthtpr_heyxxmh.field('htpr_heyxxmh.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_heyxxmh.nominal 
				}
				
				if ( edthtpr_heyxxmh.inError() ) {
					return false;
				}
			});

			edthtpr_heyxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_heyxxmh.field('finish_on').val(finish_on);
			});

			
			edthtpr_heyxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_heyxxmh = $('#tblhtpr_heyxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_heyxxmh/htpr_heyxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_heyxxmh = show_inactive_status_htpr_heyxxmh;
						d.id_heyxxmh = id_heyxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				rowGroup: {
					dataSrc: 'htpr_heyxxmh.tanggal_efektif',
				},
				columns: [
					{ data: "htpr_heyxxmh.id",visible:false },
					{ 
						data: "htpr_heyxxmh.id_heyxxmh",
						visible:false 
					},
					{ 
						data: "htpr_heyxxmh.tanggal_efektif" ,
						visible:false 
					},
					{ data: "hpcxxmh.nama" },
					{ 
						data: "htpr_heyxxmh.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_heyxxmh';
						$table       = 'tblhtpr_heyxxmh';
						$edt         = 'edthtpr_heyxxmh';
						$show_status = '_htpr_heyxxmh';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_heyxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_heyxxmh.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblheyxxmh, tblhtpr_heyxxmh, 'htpr_heyxxmh' );
				CekDrawDetailHDFinal(tblheyxxmh);
			} );

			tblhtpr_heyxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_heyxxmh = tblhtpr_heyxxmh.row( { selected: true } ).data().htpr_heyxxmh;
				id_htpr_heyxxmh   = data_htpr_heyxxmh.id;
				id_transaksi_d    = id_htpr_heyxxmh; // dipakai untuk general
				is_active_d       = data_htpr_heyxxmh.is_active;
				
				id_hpcxxmh_old       = data_htpr_heyxxmh.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblheyxxmh, tblhtpr_heyxxmh );
			} );

			tblhtpr_heyxxmh.on( 'deselect', function() {
				id_htpr_heyxxmh = '';
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblheyxxmh, tblhtpr_heyxxmh );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
