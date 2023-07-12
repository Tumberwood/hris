<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hevgrmh';
    $nama_tabels_d 	  = [];
    $nama_tabels_d[0] = 'htpr_hevgrmh_mk';
?>

<!-- begin content here -->
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhevgrmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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
					<table id="tblhtpr_hevgrmh_mk" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_hevgrmh</th>
								<th>Tanggal</th>
								<th>Tahun > </th>
								<th>Tahun <=</th>
								<th>Nominal</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="tabs-container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a class="nav-link active" data-toggle="tab" href="#tabhtpr_hevgrmh_mk"> Masa Kerja</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" id="tabhtpr_hevgrmh_mk" class="tab-pane">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblhtpr_hevgrmh_mk" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_hevgrmh</th>
										<th>Tanggal</th>
										<th>Tahun > </th>
										<th>Tahun <=</th>
										<th>Nominal</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->

</div> <!-- end of row -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_hevgrmh/fn/htpr_hevgrmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthevgrmh, tblhevgrmh, show_inactive_status_hevgrmh = 0, id_hevgrmh;
        var edthtpr_hevgrmh_mk, tblhtpr_hevgrmh_mk, show_inactive_status_htpr_hevgrmh_mk = 0, id_htpr_hevgrmh;
		// ------------- end of default variable
	
		var id_hpcxxmh_old = 0;

		$(document).ready(function() {
			//start datatables
			tblhevgrmh = $('#tblhevgrmh').DataTable( {
				ajax: {
					url: "../../models/htpr_hevgrmh/htpr_hevgrmh_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevgrmh = show_inactive_status_hevgrmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hevgrmh.id",visible:false },
					{ data: "hevgrmh.kode" },
					{ data: "hevgrmh.nama" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hevgrmh';
						$table       = 'tblhevgrmh';
						$edt         = 'edthevgrmh';
						$show_status = '_hevgrmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hevgrmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhevgrmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtpr_hevgrmh_mk];
				CekInitHeaderHD(tblhevgrmh, tbl_details);
			} );
			
			tblhevgrmh.on( 'select', function( e, dt, type, indexes ) {
				data_hevgrmh = tblhevgrmh.row( { selected: true } ).data().hevgrmh;
				id_hevgrmh  = data_hevgrmh.id;
				id_transaksi_h   = id_hevgrmh; // dipakai untuk general
				is_approve       = data_hevgrmh.is_approve;
				is_nextprocess   = data_hevgrmh.is_nextprocess;
				is_jurnal        = data_hevgrmh.is_jurnal;
				is_active        = data_hevgrmh.is_active;
				
				// atur hak akses
				tbl_details = [tblhtpr_hevgrmh_mk];
				CekSelectHeaderHD(tblhevgrmh, tbl_details);

			} );
			
			tblhevgrmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hevgrmh = '';

				// atur hak akses
				tbl_details = [tblhtpr_hevgrmh_mk];
				CekDeselectHeaderHD(tblhevgrmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtpr_hevgrmh_mk = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_hevgrmh/htpr_hevgrmh_d_mk.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hevgrmh_mk = show_inactive_status_htpr_hevgrmh_mk;
						d.id_hevgrmh = id_hevgrmh;
					}
				},
				table: "#tblhtpr_hevgrmh_mk",
				formOptions: {
					main: {
						focus: 5
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
						def: "htpr_hevgrmh_mk",
						type: "hidden"
					},	{
						label: "id_hevgrmh",
						name: "htpr_hevgrmh_mk.id_hevgrmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_hevgrmh_mk.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tahun Min (>)<sup class='text-danger'>*<sup>",
						name: "htpr_hevgrmh_mk.tahun_min"
					},
					{
						label: "Tahun Max (<=) <sup class='text-danger'>*<sup>",
						name: "htpr_hevgrmh_mk.tahun_max"
					},
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "htpr_hevgrmh_mk.tanggal_efektif",
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
						name: "htpr_hevgrmh_mk.nominal"
					},
					{
						label: "Keterangan",
						name: "htpr_hevgrmh_mk.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpr_hevgrmh_mk.on( 'preOpen', function( e, mode, action ) {
				edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.id_hevgrmh').val(id_hevgrmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hevgrmh_mk.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_hevgrmh_mk.rows().deselect();
				}
			});

            edthtpr_hevgrmh_mk.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtpr_hevgrmh_mk.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htpr_hevgrmh_mk.tahun_min 
					tahun_min = edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_min').val();
					if(!tahun_min || tahun_min == ''){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_min').error( 'Wajib diisi!' );
					}
					if(tahun_min < 0 ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_min').error( 'Inputan Minimal 0' );
					}
					if(isNaN(tahun_min) ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_min').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_hevgrmh_mk.tahun_min 

					// BEGIN of validasi htpr_hevgrmh_mk.tahun_max 
					tahun_max = edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_max').val();
					if(!tahun_max || tahun_max == ''){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_max').error( 'Wajib diisi!' );
					}
					if(tahun_max < 0 ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_max').error( 'Inputan Minimal 0' );
					}
					if(isNaN(tahun_max) ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tahun_max').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_hevgrmh_mk.tahun_max 

					// BEGIN of validasi htpr_hevgrmh_mk.tanggal_efektif 
					tanggal_efektif = edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tanggal_efektif').val();
					if(!tanggal_efektif || tanggal_efektif == ''){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.tanggal_efektif').error( 'Wajib diisi!' );
					}
					// END of validasi htpr_hevgrmh_mk.tanggal_efektif 

					// BEGIN of validasi htpr_hevgrmh_mk.nominal 
					nominal = edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.nominal').val();
					if(!nominal || nominal == ''){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.nominal').error( 'Wajib diisi!' );
					}
					if(nominal <= 0 ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.nominal').error( 'Inputan harus > 0' );
					}
					if(isNaN(nominal) ){
						edthtpr_hevgrmh_mk.field('htpr_hevgrmh_mk.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htpr_hevgrmh_mk.nominal 
				}
				
				if ( edthtpr_hevgrmh_mk.inError() ) {
					return false;
				}
			});

			edthtpr_hevgrmh_mk.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_hevgrmh_mk.field('finish_on').val(finish_on);
			});

			
			edthtpr_hevgrmh_mk.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpr_hevgrmh_mk = $('#tblhtpr_hevgrmh_mk').DataTable( {
				ajax: {
					url: "../../models/htpr_hevgrmh/htpr_hevgrmh_d_mk.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_hevgrmh_mk = show_inactive_status_htpr_hevgrmh_mk;
						d.id_hevgrmh = id_hevgrmh;
					}
				},
				order: [[ 2, "desc" ]],
				rowGroup: {
					dataSrc: 'htpr_hevgrmh_mk.tanggal_efektif',
				},
				columns: [
					{ data: "htpr_hevgrmh_mk.id",visible:false },
					{ 
						data: "htpr_hevgrmh_mk.id_hevgrmh",
						visible:false 
					},
					{ 
						data: "htpr_hevgrmh_mk.tanggal_efektif" ,
						visible:false 
					},
					{ data: "htpr_hevgrmh_mk.tahun_min" },
					{ data: "htpr_hevgrmh_mk.tahun_max" },
					{ 
						data: "htpr_hevgrmh_mk.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_hevgrmh_mk';
						$table       = 'tblhtpr_hevgrmh_mk';
						$edt         = 'edthtpr_hevgrmh_mk';
						$show_status = '_htpr_hevgrmh_mk';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_hevgrmh_mk.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpr_hevgrmh_mk.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhevgrmh, tblhtpr_hevgrmh_mk, 'htpr_hevgrmh_mk' );
				CekDrawDetailHDFinal(tblhevgrmh);
			} );

			tblhtpr_hevgrmh_mk.on( 'select', function( e, dt, type, indexes ) {
				data_htpr_hevgrmh_mk = tblhtpr_hevgrmh_mk.row( { selected: true } ).data().htpr_hevgrmh_mk;
				id_htpr_hevgrmh_mk   = data_htpr_hevgrmh_mk.id;
				id_transaksi_d    = id_htpr_hevgrmh_mk; // dipakai untuk general
				is_active_d       = data_htpr_hevgrmh_mk.is_active;
				
				id_hpcxxmh_old       = data_htpr_hevgrmh_mk.id_hpcxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhevgrmh, tblhtpr_hevgrmh_mk );
			} );

			tblhtpr_hevgrmh_mk.on( 'deselect', function() {
				id_htpr_hevgrmh_mk = '';
				is_active_d = 0;
				id_hpcxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhevgrmh, tblhtpr_hevgrmh_mk );
			} );

// --------- end _detail --------------- //

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
