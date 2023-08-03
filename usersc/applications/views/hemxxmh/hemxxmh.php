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
    $nama_tabels_d[0] = 'hemfmmd';
?>

<!-- begin content here -->
<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>Jabatan</th>
                                <th>Tanggal Join</th>
                                <th>Grup HK</th>
                                <th>Pola Shift</th>
                                <th>Aktif</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhemfmmd"> Family</a></li>
							<!-- <li><a class="nav-link" data-toggle="tab" href="#tab_blankdetail_2"> Tab 2</a></li> -->
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhemfmmd" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemfmmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Hubungan</th>
													<th>Nama</th>
													<th>Gender</th>
													<th>Tanggal Lahir</th>
													<th>Pendidikan Terakhir</th>
													<th>Pekerjaan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>
							<div role="tabpanel" id="tab_blankdetail_2" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tbl_blankdetail_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id__blankheader</th>
													<th>Kode</th>
													<th>Nama</th>
													<th>Keterangan</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hemxxmh/fn/hemxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthemxxmh, tblhemxxmh, show_inactive_status_hemxxmh = 0, id_hemxxmh;
        var edthemfmmd, tblhemfmmd, show_inactive_status_hemfmmd = 0, id_hemfmmd;
		// ------------- end of default variable

		var id_hovxxmh_old = 0, id_hodxxmh_old = 0, id_hosxxmh_old = 0, id_hetxxmh_old = 0, id_hevxxmh_old = 0;
		var id_hedlvmh_old = 0;

		$(document).ready(function() {

			//start datatables editor
			edthemxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemxxmh = show_inactive_status_hemxxmh;
					}
				},
				table: "#tblhemxxmh",
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
						def: "hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemxxmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hemxxmh.kode"
					}, 	
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hemxxmh.nama"
					}, 	
					{
						label: "Divisi <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hovxxmh",
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
										id_hovxxmh: 0,
										id_hovxxmh_old: id_hovxxmh_old,
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
						label: "Department <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hodxxmh",
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
										id_hodxxmh: 0,
										id_hodxxmh_old: id_hodxxmh_old,
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
						label: "Section <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hosxxmh",
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
										id_hosxxmh: 0,
										id_hosxxmh_old: id_hosxxmh_old,
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
						label: "Level <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hevxxmh",
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
										id_hevxxmh: 0,
										id_hevxxmh_old: id_hevxxmh_old,
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
						label: "Jabatan",
						name: "hemjbmh.id_hetxxmh",
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
										id_hetxxmh: 0,
										id_hetxxmh_old: id_hetxxmh_old,
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
						label: "Tanggal Join",
						name: "hemjbmh.tanggal_masuk",
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
						label: "Grup Hari Kerja <sup class='text-danger'>*<sup>",
						name: "hemxxmh.grup_hk",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "5 HK", "value": 1 },
							{ "label": "6 HK", "value": 2 }
						]
					},
					{
						label: "Keterangan",
						name: "hemxxmh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthemxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhemxxmh.rows().deselect();
				}
			});

            edthemxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemxxmh.kode 
					kode = edthemxxmh.field('hemxxmh.kode').val();
					if(!kode || kode == ''){
						edthemxxmh.field('hemxxmh.kode').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik hemxxmh.kode 
					if(action == 'create'){
						id_hemxxmh = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'hemxxmh',
							nama_field: 'kode',
							nama_field_value: '"'+kode+'"',
							id_transaksi: id_hemxxmh
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthemxxmh.field('hemxxmh.kode').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik hemxxmh.kode 
					// END of validasi hemxxmh.kode 

					// BEGIN of validasi hemxxmh.nama 
					nama = edthemxxmh.field('hemxxmh.nama').val();
					if(!nama || nama == ''){
						edthemxxmh.field('hemxxmh.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.nama 

					// BEGIN of validasi hemjbmh.id_hovxxmh 
					id_hovxxmh = edthemxxmh.field('hemjbmh.id_hovxxmh').val();
					if(!id_hovxxmh || id_hovxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hovxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hovxxmh 

					// BEGIN of validasi hemjbmh.id_hodxxmh 
					id_hodxxmh = edthemxxmh.field('hemjbmh.id_hodxxmh').val();
					if(!id_hodxxmh || id_hodxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hodxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hodxxmh 

					// BEGIN of validasi hemjbmh.id_hosxxmh 
					id_hosxxmh = edthemxxmh.field('hemjbmh.id_hosxxmh').val();
					if(!id_hosxxmh || id_hosxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hosxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hosxxmh 

					// BEGIN of validasi hemjbmh.id_hevxxmh 
					id_hevxxmh = edthemxxmh.field('hemjbmh.id_hevxxmh').val();
					if(!id_hevxxmh || id_hevxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hevxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hevxxmh 

					// BEGIN of validasi hemjbmh.id_hetxxmh 
					id_hetxxmh = edthemxxmh.field('hemjbmh.id_hetxxmh').val();
					if(!id_hetxxmh || id_hetxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hetxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hetxxmh 

					// BEGIN of validasi hemxxmh.grup_hk 
					grup_hk = edthemxxmh.field('hemxxmh.grup_hk').val();
					if(!grup_hk || grup_hk == ''){
						edthemxxmh.field('hemxxmh.grup_hk').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.grup_hk 
				}
				
				if ( edthemxxmh.inError() ) {
					return false;
				}
			});

			edthemxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemxxmh.field('finish_on').val(finish_on);
			});
			
			edthemxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemxxmh = $('#tblhemxxmh').DataTable( {
				dom: 
					"<P>"+
					"<lf>"+
					"<B>"+
					"<rt>"+
					"<'row'<'col-sm-4'i><'col-sm-8'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true,
						},
						targets: [2,3,4,5,8]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,1,6,7,9]
					}
				],
				ajax: {
					url: "../../models/hemxxmh/hemxxmh.php",
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
					{ data: "hosxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "hemjbmh.tanggal_masuk" },
					{ 
						data: "hemjbmh.grup_hk",
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '5HK';
							}else if(data == 2){
								return '6HK';
							}else{
								return '<span class="text-danger"> Data Invalid</span>';
							}
						}
					},
					{ data: "v_hemxxmh_htsptth.pola_shift" },
					{ 
						data: "hemxxmh.is_active",
						render: function (data){
							if (data == 0){
								return '<i class="fa fa-remove text-danger"></i>';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == -9){
								return '<span class="text-danger">Data Error</span>';
							}
						}
					}
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
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblhemxxmh.searchPanes.container().appendTo( '#searchPanes1' );

			tblhemxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhemfmmd];
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

				data_hemjbmh = tblhemxxmh.row( { selected: true } ).data().hemjbmh;
				id_hovxxmh_old   = data_hemjbmh.id_hovxxmh;
				id_hodxxmh_old   = data_hemjbmh.id_hodxxmh;
				id_hosxxmh_old   = data_hemjbmh.id_hosxxmh;
				id_hevxxmh_old   = data_hemjbmh.id_hevxxmh;
				id_hetxxmh_old   = data_hemjbmh.id_hetxxmh;
				
				// atur hak akses
				tbl_details = [tblhemfmmd];
				CekSelectHeaderHD(tblhemxxmh, tbl_details);

			} );
			
			tblhemxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hemxxmh = 0;
				
				id_hovxxmh_old   = 0;
				id_hodxxmh_old   = 0;
				id_hosxxmh_old   = 0;
				id_hevxxmh_old   = 0;
				id_hetxxmh_old   = 0;

				// atur hak akses
				tbl_details = [tblhemfmmd];
				CekDeselectHeaderHD(tblhemxxmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthemfmmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemfmmd = show_inactive_status_hemfmmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemfmmd",
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
						def: "hemfmmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemfmmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemfmmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hubungan <sup class='text-danger'>*<sup>",
						name: "hemfmmd.hubungan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ayah", "value": "Ayah" },
							{ "label": "Ibu", "value": "Ibu" },
							{ "label": "Kakak", "value": "Kakak" },
							{ "label": "Adik", "value": "Adik" },
							{ "label": "Suami", "value": "Suami" },
							{ "label": "Istri", "value": "Istri" },
							{ "label": "Anak", "value": "Anak" },
						]
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hemfmmd.nama"
					},	{
						label: "Gender <sup class='text-danger'>*<sup>",
						name: "hemfmmd.gender",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Laki-laki", "value": "Laki-laki" },
							{ "label": "Perempuan", "value": "Perempuan" }
						]
					}, 	{
						label: "Tanggal Lahir <sup class='text-danger'>*<sup>",
						name: "hemfmmd.tanggal_lahir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Pendidikan Terakhir",
						name: "hemfmmd.id_hedlvmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hedlvmh/hedlvmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hedlvmh_old: id_hedlvmh_old,
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
					},	{
						label: "Pekerjaan <sup class='text-danger'>*<sup>",
						name: "hemfmmd.pekerjaan"
					}
				]
			} );
			
			edthemfmmd.on( 'preOpen', function( e, mode, action ) {
				edthemfmmd.field('hemfmmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemfmmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemfmmd.rows().deselect();
				}
			});

            edthemfmmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemfmmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemfmmd.nama 
					nama = edthemfmmd.field('hemfmmd.nama').val();
					if(!nama || nama == ''){
						edthemfmmd.field('hemfmmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.nama

					// BEGIN of validasi hemfmmd.tanggal_lahir 
					tanggal_lahir = edthemfmmd.field('hemfmmd.tanggal_lahir').val();
					if(!tanggal_lahir || tanggal_lahir == ''){
						edthemfmmd.field('hemfmmd.tanggal_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.tanggal_lahir

					// BEGIN of validasi hemfmmd.gender 
					gender = edthemfmmd.field('hemfmmd.gender').val();
					if(!gender || gender == ''){
						edthemfmmd.field('hemfmmd.gender').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.gender

					// BEGIN of validasi hemfmmd.hubungan 
					hubungan = edthemfmmd.field('hemfmmd.hubungan').val();
					if(!hubungan || hubungan == ''){
						edthemfmmd.field('hemfmmd.hubungan').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.hubungan

					// BEGIN of validasi hemfmmd.pekerjaan 
					pekerjaan = edthemfmmd.field('hemfmmd.pekerjaan').val();
					if(!pekerjaan || pekerjaan == ''){
						edthemfmmd.field('hemfmmd.pekerjaan').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.pekerjaan
				
				}
				
				if ( edthemfmmd.inError() ) {
					return false;
				}
			});

			edthemfmmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemfmmd.field('finish_on').val(finish_on);
			});
			
			edthemfmmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemfmmd = $('#tblhemfmmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemfmmd = show_inactive_status_hemfmmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemfmmd.id",visible:false },
					{ data: "hemfmmd.id_hemxxmh",visible:false },
					{ data: "hemfmmd.hubungan" },
					{ data: "hemfmmd.nama" },
					{ data: "hemfmmd.gender" },
					{ data: "hemfmmd.tanggal_lahir" },
					{ data: "hedlvmh.nama" },
					{ data: "hemfmmd.pekerjaan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemfmmd';
						$table       = 'tblhemfmmd';
						$edt         = 'edthemfmmd';
						$show_status = '_hemfmmd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemfmmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemfmmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemfmmd, 'hemfmmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemfmmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemfmmd = tblhemfmmd.row( { selected: true } ).data().hemfmmd;
				id_hemfmmd   = data_hemfmmd.id;
				id_transaksi_d    = id_hemfmmd; // dipakai untuk general
				is_active_d       = data_hemfmmd.is_active;

				id_hedlvmh_old       = data_hemfmmd.id_hemfmmd;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemfmmd );
			} );

			tblhemfmmd.on( 'deselect', function() {
				id_hemfmmd = 0;
				is_active_d = 0;
				id_hedlvmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemfmmd );
			} );

// --------- end _detail --------------- //
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
