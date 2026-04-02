<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'heyxxmd';
    $nama_tabels_d 	  = [];
    $nama_tabels_d[0] = 'htpr_heyxxmd';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblheyxxmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sub Tipe</th>
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
					<table id="tblhtpr_heyxxmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_heyxxmd</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_heyxxmd/fn/htpr_heyxxmd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtheyxxmd, tblheyxxmd, show_inactive_status_heyxxmd = 0, id_heyxxmd;
        var edthtpr_heyxxmd, tblhtpr_heyxxmd, show_inactive_status_htpr_heyxxmd = 0, id_htpr_heyxxmd;
		// ------------- end of default variable
	
		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			//start datatables
			tblheyxxmd = $('#tblheyxxmd').DataTable( {
				ajax: {
					url: "../../models/htpr_heyxxmd/htpr_heyxxmd_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_heyxxmd = show_inactive_status_heyxxmd;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "heyxxmd.id",visible:false },
					{ data: "heyxxmd.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_heyxxmd';
						$table       = 'tblheyxxmd';
						$edt         = 'edtheyxxmd';
						$show_status = '_heyxxmd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.heyxxmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblheyxxmd.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_heyxxmd];
				CekInitHeaderHD(tblheyxxmd, tbl_details);
			} );
			
			tblheyxxmd.on( 'select', function( e, dt, type, indexes ) {
				data_heyxxmd = tblheyxxmd.row( { selected: true } ).data().heyxxmd;
				id_heyxxmd  = data_heyxxmd.id;
				id_transaksi_h   = id_heyxxmd; // dipakai untuk general
				is_approve       = data_heyxxmd.is_approve;
				is_nextprocess   = data_heyxxmd.is_nextprocess;
				is_jurnal        = data_heyxxmd.is_jurnal;
				is_active        = data_heyxxmd.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_heyxxmd];
				CekSelectHeaderHD(tblheyxxmd, tbl_details);

			} );
			
			tblheyxxmd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_heyxxmd = '';

				// atur hak akses
				tbl_details = [tblhtpr_heyxxmd];
				CekDeselectHeaderHD(tblheyxxmd, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_heyxxmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_heyxxmd/htpr_heyxxmd_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_heyxxmd = show_inactive_status_htpr_heyxxmd;
						d.id_heyxxmd = id_heyxxmd;
					}
				},
				table: "#tblhtpr_heyxxmd",
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
						def: "htpr_heyxxmd",
						type: "hidden"
					},	{
						label: "id_heyxxmd",
						name: "htpr_heyxxmd.id_heyxxmd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_heyxxmd.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Komponen <sup class='text-danger'>*<sup>",
						name: "htpr_heyxxmd.id_hpcxxmh",
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
						name: "htpr_heyxxmd.tanggal_efektif",
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
						name: "htpr_heyxxmd.nominal"
					},
					{
						label: "Keterangan",
						name: "htpr_heyxxmd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_heyxxmd.on( 'preOpen', function( e, mode, action ) {
				edthtpr_heyxxmd.field('htpr_heyxxmd.id_heyxxmd').val(id_heyxxmd);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_heyxxmd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_heyxxmd.rows().deselect();
				}
			});

            edthtpr_heyxxmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_heyxxmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_heyxxmd.id_hpcxxmh 
					id_hpcxxmh = edthtpr_heyxxmd.field('htpr_heyxxmd.id_hpcxxmh').val();
					if(!id_hpcxxmh || id_hpcxxmh == ''){
						edthtpr_heyxxmd.field('htpr_heyxxmd.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_heyxxmd.id_hpcxxmh 

					// BEGIN of validasi htpr_heyxxmd.tanggal_efektif 
					tanggal_efektif = edthtpr_heyxxmd.field('htpr_heyxxmd.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_heyxxmd.field('htpr_heyxxmd.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_heyxxmd.tanggal_efektif 

					// BEGIN of validasi htpr_heyxxmd.nominal 
					nominal = edthtpr_heyxxmd.field('htpr_heyxxmd.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_heyxxmd.field('htpr_heyxxmd.nominal').error( 'Wajib diisi!' );
					}
					if(nominal <= 0 ){
						edthtpr_heyxxmd.field('htpr_heyxxmd.nominal').error( 'Inputan harus > 0' );
					}
					if(isNaN(nominal) ){
						edthtpr_heyxxmd.field('htpr_heyxxmd.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_heyxxmd.nominal 
				}
				
				if ( edthtpr_heyxxmd.inError() ) {
					return false;
				}
			});

			edthtpr_heyxxmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_heyxxmd.field('finish_on').val(finish_on);
			});

			
			edthtpr_heyxxmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_heyxxmd = $('#tblhtpr_heyxxmd').DataTable( {
				ajax: {
					url: "../../models/htpr_heyxxmd/htpr_heyxxmd_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_heyxxmd = show_inactive_status_htpr_heyxxmd;
						d.id_heyxxmd = id_heyxxmd;
					}
				},
				order: [[ 2, "desc" ]],
				rowGroup: {
					dataSrc: 'htpr_heyxxmd.tanggal_efektif',
				},
				columns: [
					{ data: "htpr_heyxxmd.id",visible:false },
					{ 
						data: "htpr_heyxxmd.id_heyxxmd",
						visible:false 
					},
					{ 
						data: "htpr_heyxxmd.tanggal_efektif"
					},
					{ data: "hpcxxmh.nama" },
					{ 
						data: "htpr_heyxxmd.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_heyxxmd';
						$table       = 'tblhtpr_heyxxmd';
						$edt         = 'edthtpr_heyxxmd';
						$show_status = '_htpr_heyxxmd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_heyxxmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_heyxxmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblheyxxmd, tblhtpr_heyxxmd, 'htpr_heyxxmd' );
				CekDrawDetailHDFinal(tblheyxxmd);
			} );

			tblhtpr_heyxxmd.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_heyxxmd = tblhtpr_heyxxmd.row( { selected: true } ).data().htpr_heyxxmd;
				id_htpr_heyxxmd   = data_htpr_heyxxmd.id;
				id_transaksi_d    = id_htpr_heyxxmd; // dipakai untuk general
				is_active_d       = data_htpr_heyxxmd.is_active;
				
				id_hpcxxmh_old       = data_htpr_heyxxmd.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblheyxxmd, tblhtpr_heyxxmd );
			} );

			tblhtpr_heyxxmd.on( 'deselect', function() {
				id_htpr_heyxxmd = '';
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblheyxxmd, tblhtpr_heyxxmd );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
