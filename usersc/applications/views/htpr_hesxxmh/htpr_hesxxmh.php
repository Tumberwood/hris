<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hesxxmh';
    $nama_tabels_d 	  = [];
    $nama_tabels_d[0] = 'htpr_hesxxmh';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhesxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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
					<table id="tblhtpr_hesxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_hesxxmh</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_hesxxmh/fn/htpr_hesxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxmh, tblhesxxmh, show_inactive_status_hesxxmh = 0, id_hesxxmh;
        var edthtpr_hesxxmh, tblhtpr_hesxxmh, show_inactive_status_htpr_hesxxmh = 0, id_htpr_hesxxmh;
		// ------------- end of default variable
	
		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			//start datatables
			tblhesxxmh = $('#tblhesxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hesxxmh/htpr_hesxxmh_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxmh = show_inactive_status_hesxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hesxxmh.id",visible:false },
					{ data: "hesxxmh.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hesxxmh';
						$table       = 'tblhesxxmh';
						$edt         = 'edthesxxmh';
						$show_status = '_hesxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hesxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhesxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_hesxxmh];
				CekInitHeaderHD(tblhesxxmh, tbl_details);
			} );
			
			tblhesxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_hesxxmh = tblhesxxmh.row( { selected: true } ).data().hesxxmh;
				id_hesxxmh  = data_hesxxmh.id;
				id_transaksi_h   = id_hesxxmh; // dipakai untuk general
				is_approve       = data_hesxxmh.is_approve;
				is_nextprocess   = data_hesxxmh.is_nextprocess;
				is_jurnal        = data_hesxxmh.is_jurnal;
				is_active        = data_hesxxmh.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_hesxxmh];
				CekSelectHeaderHD(tblhesxxmh, tbl_details);

			} );
			
			tblhesxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hesxxmh = '';

				// atur hak akses
				tbl_details = [tblhtpr_hesxxmh];
				CekDeselectHeaderHD(tblhesxxmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_hesxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_hesxxmh/htpr_hesxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hesxxmh = show_inactive_status_htpr_hesxxmh;
						d.id_hesxxmh = id_hesxxmh;
					}
				},
				table: "#tblhtpr_hesxxmh",
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
						def: "htpr_hesxxmh",
						type: "hidden"
					},	{
						label: "id_hesxxmh",
						name: "htpr_hesxxmh.id_hesxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_hesxxmh.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_hesxxmh.id_hpcxxmh",
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
						name: "htpr_hesxxmh.tanggal_efektif",
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
						name: "htpr_hesxxmh.nominal"
					},
					{
						label: "Keterangan",
						name: "htpr_hesxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_hesxxmh.on( 'preOpen', function( e, mode, action ) {
				edthtpr_hesxxmh.field('htpr_hesxxmh.id_hesxxmh').val(id_hesxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hesxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_hesxxmh.rows().deselect();
				}
			});

            edthtpr_hesxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_hesxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_hesxxmh.id_hpcxxmh 
					id_hpcxxmh = edthtpr_hesxxmh.field('htpr_hesxxmh.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edthtpr_hesxxmh.field('htpr_hesxxmh.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hesxxmh.id_hpcxxmh 

					// BEGIN of validasi htpr_hesxxmh.tanggal_efektif 
					tanggal_efektif = edthtpr_hesxxmh.field('htpr_hesxxmh.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_hesxxmh.field('htpr_hesxxmh.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hesxxmh.tanggal_efektif 

					// BEGIN of validasi htpr_hesxxmh.nominal 
					nominal = edthtpr_hesxxmh.field('htpr_hesxxmh.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_hesxxmh.field('htpr_hesxxmh.nominal').error( 'Wajib diisi!' );
					}
					if(nominal <= 0 ){
						edthtpr_hesxxmh.field('htpr_hesxxmh.nominal').error( 'Inputan harus > 0' );
					}
					if(isNaN(nominal) ){
						edthtpr_hesxxmh.field('htpr_hesxxmh.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_hesxxmh.nominal 
				}
				
				if ( edthtpr_hesxxmh.inError() ) {
					return false;
				}
			});

			edthtpr_hesxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hesxxmh.field('finish_on').val(finish_on);
			});

			
			edthtpr_hesxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_hesxxmh = $('#tblhtpr_hesxxmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hesxxmh/htpr_hesxxmh_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hesxxmh = show_inactive_status_htpr_hesxxmh;
						d.id_hesxxmh = id_hesxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				rowGroup: {
					dataSrc: 'htpr_hesxxmh.tanggal_efektif',
				},
				columns: [
					{ data: "htpr_hesxxmh.id",visible:false },
					{ 
						data: "htpr_hesxxmh.id_hesxxmh",
						visible:false 
					},
					{ 
						data: "htpr_hesxxmh.tanggal_efektif" ,
						visible:false 
					},
					{ data: "hpcxxmh.nama" },
					{ 
						data: "htpr_hesxxmh.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_hesxxmh';
						$table       = 'tblhtpr_hesxxmh';
						$edt         = 'edthtpr_hesxxmh';
						$show_status = '_htpr_hesxxmh';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_hesxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_hesxxmh.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhesxxmh, tblhtpr_hesxxmh, 'htpr_hesxxmh' );
				CekDrawDetailHDFinal(tblhesxxmh);
			} );

			tblhtpr_hesxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_hesxxmh = tblhtpr_hesxxmh.row( { selected: true } ).data().htpr_hesxxmh;
				id_htpr_hesxxmh   = data_htpr_hesxxmh.id;
				id_transaksi_d    = id_htpr_hesxxmh; // dipakai untuk general
				is_active_d       = data_htpr_hesxxmh.is_active;
				
				id_hpcxxmh_old       = data_htpr_hesxxmh.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhesxxmh, tblhtpr_hesxxmh );
			} );

			tblhtpr_hesxxmh.on( 'deselect', function() {
				id_htpr_hesxxmh = '';
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhesxxmh, tblhtpr_hesxxmh );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
