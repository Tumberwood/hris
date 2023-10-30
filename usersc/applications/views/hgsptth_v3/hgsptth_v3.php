<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hgsptth_v3';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hgsemtd_v3';
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
				<form class="form-horizontal" id="frmhgsptth_v3">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary" type="submit" id="go">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhgsptth_v3" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Dari Tanggal</th>
                            </tr>
                        </thead>
                    </table>
					
					<div class="row">
						<div class="col">
							<div class="ibox collapsed" id="iboxfilter">
								<div class="ibox-title">
									<h5 class="text-navy">Filter</h5>&nbsp
									<button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
								</div>
								<div class="ibox-content">
									<form class="form-horizontal" id="frmhgsemtd_v3">
										<div class="form-group row">
											<label class="col-lg-3 col-form-label">Bagian</label>
											<div class="col-lg-5">
												<select class="form-control" id="select_bagian" name="select_bagian"></select>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-lg-4">
												<button class="btn btn-primary" type="submit" id="go">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>

					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tab_sabtu"> Sabtu</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tab_minggu"> Minggu</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#sen-jum"> Sen-Jum</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tab_sabtu" class="tab-pane active">	
								<div class="panel-body">
									<div class="row">
										<div class="col-md-4">
											<h3>Shift 1</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_sabtu_s1" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class="col-md-4">
											<h3>Shift 2</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_sabtu_s2" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
										</div>
										<div class="col-md-4">
											<h3>Shift 3</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_sabtu_s3" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tab_minggu" class="tab-pane">	
								<div class="panel-body">
									<div class="row">
										<div class="col-md-4">
											<h3>Shift 1</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_minggu_s1" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class="col-md-4">
											<h3>Shift 2</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_minggu_s2" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
										</div>
										<div class="col-md-4">
											<h3>Shift 3</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_minggu_s3" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="sen-jum" class="tab-pane">
								<div class="panel-body">
									<div class="row">
										<div class="col-md-4">
											<h3>Shift 1</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_senjum_s1" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
										<div class="col-md-4">
											<h3>Shift 2</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_senjum_s2" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
										</div>
										<div class="col-md-4">
											<h3>Shift 3</h3>
											<div class="table-responsive">
												<table id="tblhgsemtd_v3_senjum_s3" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id_hgsptth_v3</th>
															<th>Jam</th>
															<th>Nama</th>
														</tr>
													</thead>
		
												</table>
											</div> <!-- end of table -->
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
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgsptth_v3/fn/hgsptth_v3_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgsptth_v3, tblhgsptth_v3, show_inactive_status_hgsptth_v3 = 0, id_hgsptth_v3;
        var edthgsemtd_v3_senjum_s1, tblhgsemtd_v3_senjum_s1, show_inactive_status_hgsemtd_v3 = 0, id_hgsemtd_v3;
		// ------------- end of default variable
		var id_htsptth_old = 0;
		var id_htsxxmh_old = 0;
		var id_hemxxmh_old = 0;
		var id_htsptth_v3_old = 0;
		var id_holxxmd_old = 0;
		var select_bagian = 0;
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});

        // BEGIN select2 init
        $("#select_bagian").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/holxxmd/holxxmd_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_holxxmd_old: id_holxxmd_old,
						search: params.term || '',
						page: params.page || 1
					}
						return query;
				},
				processResults: function (data, params) {
					data.results.push({
						id: '999',  
						text: 'NON SHIFT'
					}, {
						id: '998',  
						text: 'MANAGEMENT TRAINEE'
					});

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
			}
			
		});
        // END select2 init

		$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		$(document).ready(function() {
			
			//start datatables editor
			edthgsptth_v3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsptth_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth_v3 = show_inactive_status_hgsptth_v3;
						d.start_date = start_date;
					}
				},
				table: "#tblhgsptth_v3",
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
						def: "hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsptth_v3.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hgsptth_v3.tanggal_awal",
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
						label: "Tanggal Akhir ",
						name: "hgsptth_v3.tanggal_akhir",
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
						label: "Copy Dari Tanggal",
						name: "hgsptth_v3.dari_tanggal",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	
				]
			} );
			
			edthgsptth_v3.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth_v3.field('start_on').val(start_on);

				if(action == 'create'){
					tblhgsptth_v3.rows().deselect();
				}
			});

            edthgsptth_v3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthgsptth_v3.dependent( 'hgsptth_v3.tanggal_awal', function ( val, data, callback ) {
				if (val != null) {
					var startDate = moment(val);
					startDate.add(6, 'days');

					var tanggal_akhir = startDate.format('DD MMM YYYY');
					edthgsptth_v3.field('hgsptth_v3.tanggal_akhir').val(tanggal_akhir);
				}
				return {}
			}, {event: 'keyup change'});
			
			edthgsptth_v3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasvalidasiSubmit(edt)i hgsptth_v3.tanggal_awal
					if ( ! edthgsptth_v3.field('hgsptth_v3.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthgsptth_v3.field('hgsptth_v3.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthgsptth_v3.field('hgsptth_v3.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hgsptth_v3.tanggal_awal
				}
				
				if ( edthgsptth_v3.inError() ) {
					return false;
				}
			});

			edthgsptth_v3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth_v3.field('finish_on').val(finish_on);
			});
			
			edthgsptth_v3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsptth_v3.ajax.reload(null,false);
			} );
			
			//start datatables
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			tblhgsptth_v3 = $('#tblhgsptth_v3').DataTable( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsptth_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth_v3 = show_inactive_status_hgsptth_v3;
						d.start_date = start_date;
					}
				},
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hgsptth_v3.id",visible:false },
					{ data: "hgsptth_v3.tanggal_awal"},
					{ data: "hgsptth_v3.tanggal_akhir"},
					{ data: "hgsptth_v3.dari_tanggal"}
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsptth_v3';
						$table       = 'tblhgsptth_v3';
						$edt         = 'edthgsptth_v3';
						$show_status = '_hgsptth_v3';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGenJadwal',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');
							// console.log(dari_tanggal_select);
							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hgsptth_v3/hgsptth_v3_fn_gen_jadwal_ferry.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hgsptth_v3		: id_hgsptth_v3,
									tanggal_awal		: tanggal_awal_select,
									tanggal_akhir		: tanggal_akhir_select,
									dari_tanggal		: dari_tanggal_select,
									timestamp			: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhgsptth_v3.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgsptth_v3.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhgsptth_v3.on( 'init', function () {
				// atur hak akses
				tbl_details = [	
								tblhgsemtd_v3_senjum_s1, 
								tblhgsemtd_v3_senjum_s2, 
								tblhgsemtd_v3_senjum_s3,
								tblhgsemtd_v3_sabtu_s1, 
								tblhgsemtd_v3_sabtu_s2, 
								tblhgsemtd_v3_sabtu_s3,
								tblhgsemtd_v3_minggu_s1, 
								tblhgsemtd_v3_minggu_s2, 
								tblhgsemtd_v3_minggu_s3
								];
				CekInitHeaderHD(tblhgsptth_v3, tbl_details);
				tblhgsptth_v3.button( 'btnGenJadwal:name' ).disable();
			} );
			
			tblhgsptth_v3.on( 'select', function( e, dt, type, indexes ) {
				data_hgsptth_v3 = tblhgsptth_v3.row( { selected: true } ).data().hgsptth_v3;
				id_hgsptth_v3  = data_hgsptth_v3.id;
				id_transaksi_h   = id_hgsptth_v3; // dipakai untuk general
				is_approve       = data_hgsptth_v3.is_approve;
				is_nextprocess   = data_hgsptth_v3.is_nextprocess;
				is_jurnal        = data_hgsptth_v3.is_jurnal;
				is_active        = data_hgsptth_v3.is_active;
				tanggal_awal_select        = data_hgsptth_v3.tanggal_awal;
				dari_tanggal_select        = data_hgsptth_v3.dari_tanggal;
				tipe        = data_hgsptth_v3.tipe;
				tanggal_akhir_select        = data_hgsptth_v3.tanggal_akhir;
				
				// atur hak akses
				tbl_details = [	
								tblhgsemtd_v3_senjum_s1, 
								tblhgsemtd_v3_senjum_s2, 
								tblhgsemtd_v3_senjum_s3,
								tblhgsemtd_v3_sabtu_s1, 
								tblhgsemtd_v3_sabtu_s2, 
								tblhgsemtd_v3_sabtu_s3,
								tblhgsemtd_v3_minggu_s1, 
								tblhgsemtd_v3_minggu_s2, 
								tblhgsemtd_v3_minggu_s3
								];
				CekSelectHeaderHD(tblhgsptth_v3, tbl_details);
				if (dari_tanggal_select != null) {
					tblhgsptth_v3.button( 'btnGenJadwal:name' ).enable();
				} else {
					tblhgsptth_v3.button( 'btnGenJadwal:name' ).disable();
				}
			} );
			
			tblhgsptth_v3.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgsptth_v3 = 0;
				id_htsptth_v3 = 0;
				id_htsptth_old = 0;
				id_htsptth = 0
				tipe = '';

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				dari_tanggal_select = null;
				id_htsptth_select = 0;

				// atur hak akses
				
				tbl_details = [	
								tblhgsemtd_v3_senjum_s1, 
								tblhgsemtd_v3_senjum_s2, 
								tblhgsemtd_v3_senjum_s3,
								tblhgsemtd_v3_sabtu_s1, 
								tblhgsemtd_v3_sabtu_s2, 
								tblhgsemtd_v3_sabtu_s3,
								tblhgsemtd_v3_minggu_s1, 
								tblhgsemtd_v3_minggu_s2, 
								tblhgsemtd_v3_minggu_s3
								];
				CekDeselectHeaderHD(tblhgsptth_v3, tbl_details);
				tblhgsptth_v3.button( 'btnGenJadwal:name' ).disable();
			} );

			$("#frmhgsptth_v3").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhgsptth_v3) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhgsptth_v3.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});

			///sabtu
// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_sabtu_s1 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_sabtu_s1",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "sabtu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:1
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_sabtu_s1.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_sabtu_s1.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s1.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_sabtu_s1.rows().deselect();
				}
			});

            edthgsemtd_v3_sabtu_s1.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_sabtu_s1.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s1.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s1.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_sabtu_s1);
				}
				
				if ( edthgsemtd_v3_sabtu_s1.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_sabtu_s1.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s1.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_sabtu_s1.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_sabtu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_sabtu_s1 = $('#tblhgsemtd_v3_sabtu_s1').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_sabtu_s1';
						$edt         = 'edthgsemtd_v3_sabtu_s1';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_sabtu_s1.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s1, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_sabtu_s1.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_sabtu_s1.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s1 );
			} );

			tblhgsemtd_v3_sabtu_s1.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s1 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_sabtu_s2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_sabtu_s2",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "sabtu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:2
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_sabtu_s2.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_sabtu_s2.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_sabtu_s2.rows().deselect();
				}
			});

            edthgsemtd_v3_sabtu_s2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_sabtu_s2.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s2.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_sabtu_s2);
				}
				
				if ( edthgsemtd_v3_sabtu_s2.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_sabtu_s2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s2.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_sabtu_s2.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_sabtu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_sabtu_s2 = $('#tblhgsemtd_v3_sabtu_s2').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_sabtu_s2';
						$edt         = 'edthgsemtd_v3_sabtu_s2';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_sabtu_s2.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s2, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_sabtu_s2.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_sabtu_s2.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s2 );
			} );

			tblhgsemtd_v3_sabtu_s2.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s2 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_sabtu_s3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_sabtu_s3",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "sabtu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:3
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_sabtu_s3.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_sabtu_s3.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s3.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_sabtu_s3.rows().deselect();
				}
			});

            edthgsemtd_v3_sabtu_s3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_sabtu_s3.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s3.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_sabtu_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_sabtu_s3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_sabtu_s3);
				}
				
				if ( edthgsemtd_v3_sabtu_s3.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_sabtu_s3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_sabtu_s3.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_sabtu_s3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_sabtu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_sabtu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_sabtu_s3 = $('#tblhgsemtd_v3_sabtu_s3').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_sabtu_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_sabtu_s3';
						$edt         = 'edthgsemtd_v3_sabtu_s3';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_sabtu_s3.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s3, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_sabtu_s3.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_sabtu_s3.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s3 );
			} );

			tblhgsemtd_v3_sabtu_s3.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_sabtu_s3 );
			} );

// --------- end _detail --------------- //		

			///minggu
// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_minggu_s1 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_minggu_s1",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "minggu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:1
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_minggu_s1.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_minggu_s1.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s1.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_minggu_s1.rows().deselect();
				}
			});

            edthgsemtd_v3_minggu_s1.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_minggu_s1.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s1.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s1.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_minggu_s1);
				}
				
				if ( edthgsemtd_v3_minggu_s1.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_minggu_s1.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s1.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_minggu_s1.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_minggu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_minggu_s1 = $('#tblhgsemtd_v3_minggu_s1').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_minggu_s1';
						$edt         = 'edthgsemtd_v3_minggu_s1';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_minggu_s1.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s1, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_minggu_s1.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_minggu_s1.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s1 );
			} );

			tblhgsemtd_v3_minggu_s1.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s1 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_minggu_s2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_minggu_s2",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "minggu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:2
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_minggu_s2.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_minggu_s2.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_minggu_s2.rows().deselect();
				}
			});

            edthgsemtd_v3_minggu_s2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_minggu_s2.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s2.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_minggu_s2);
				}
				
				if ( edthgsemtd_v3_minggu_s2.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_minggu_s2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s2.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_minggu_s2.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_minggu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_minggu_s2 = $('#tblhgsemtd_v3_minggu_s2').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_minggu_s2';
						$edt         = 'edthgsemtd_v3_minggu_s2';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_minggu_s2.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s2, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_minggu_s2.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_minggu_s2.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s2 );
			} );

			tblhgsemtd_v3_minggu_s2.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s2 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_minggu_s3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_minggu_s3",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "minggu"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:3
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_minggu_s3.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_minggu_s3.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s3.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_minggu_s3.rows().deselect();
				}
			});

            edthgsemtd_v3_minggu_s3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_minggu_s3.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s3.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_minggu_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_minggu_s3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_minggu_s3);
				}
				
				if ( edthgsemtd_v3_minggu_s3.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_minggu_s3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_minggu_s3.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_minggu_s3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_minggu_s1.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s2.ajax.reload(null,false);
				tblhgsemtd_v3_minggu_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_minggu_s3 = $('#tblhgsemtd_v3_minggu_s3').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_minggu_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_minggu_s3';
						$edt         = 'edthgsemtd_v3_minggu_s3';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_minggu_s3.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s3, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_minggu_s3.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_minggu_s3.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s3 );
			} );

			tblhgsemtd_v3_minggu_s3.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_minggu_s3 );
			} );

// --------- end _detail --------------- //		

			///Senjum
// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_senjum_s1 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_senjum_s1",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "senjum"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:1
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_senjum_s1.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_senjum_s1.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s1.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_senjum_s1.rows().deselect();
				}
			});

            edthgsemtd_v3_senjum_s1.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_senjum_s1.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s1.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s1);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s1.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_senjum_s1);
				}
				
				if ( edthgsemtd_v3_senjum_s1.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_senjum_s1.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s1.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_senjum_s1.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_senjum_s1.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s2.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_senjum_s1 = $('#tblhgsemtd_v3_senjum_s1').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s1.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_senjum_s1';
						$edt         = 'edthgsemtd_v3_senjum_s1';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_senjum_s1.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s1, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_senjum_s1.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_senjum_s1.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s1 );
			} );

			tblhgsemtd_v3_senjum_s1.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s1 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_senjum_s2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_senjum_s2",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "senjum"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:2
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_senjum_s2.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_senjum_s2.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_senjum_s2.rows().deselect();
				}
			});

            edthgsemtd_v3_senjum_s2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_senjum_s2.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s2.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s2);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_senjum_s2);
				}
				
				if ( edthgsemtd_v3_senjum_s2.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_senjum_s2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s2.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_senjum_s2.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_senjum_s1.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s2.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_senjum_s2 = $('#tblhgsemtd_v3_senjum_s2').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s2.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_senjum_s2';
						$edt         = 'edthgsemtd_v3_senjum_s2';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_senjum_s2.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s2, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_senjum_s2.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_senjum_s2.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s2 );
			} );

			tblhgsemtd_v3_senjum_s2.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s2 );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_v3_senjum_s3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				table: "#tblhgsemtd_v3_senjum_s3",
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
						def: "hgsemtd_v3",
						type: "hidden"
					},	{
						label: "id_hgsptth_v3",
						name: "hgsemtd_v3.id_hgsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hari",
						name: "hgsemtd_v3.nama",
                        type: "hidden",
						def: "senjum"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_hemxxmh",
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
						label: "Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": 1 },
							{ "label": "2", "value": 2 },
							{ "label": "3", "value": 3 },
							{ "label": "OFF", "value": 4 },
							{ "label": "NS", "value": 5 },
						],
						def:3
					},
					{
						label: "Grup Shift <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_htsptth_v3",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_v3/htsptth_v3_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_v3_old: id_htsptth_v3_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hgsemtd_v3.id_holxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd/holxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_old: id_holxxmd_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									data.results.push({
										id: '999',  
										text: 'NON SHIFT'
									}, {
										id: '998',  
										text: 'MANAGEMENT TRAINEE'
									});

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
						label: "Jam",  
						name: "hgsemtd_v3.jam",
						type: "hidden"
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",    
						name: "hgsemtd_v3.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					}
				]
			} );
			
			edthgsemtd_v3_senjum_s3.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_v3_senjum_s3.field('hgsemtd_v3.id_hgsptth_v3').val(id_hgsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s3.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_v3_senjum_s3.rows().deselect();
				}
			});

            edthgsemtd_v3_senjum_s3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_v3_senjum_s3.dependent( 'hgsemtd_v3.shift', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s3.dependent( 'hgsemtd_v3.id_htsptth_v3', function ( val, data, callback ) {
				if (val > 0) {
					jamKerja(edthgsemtd_v3_senjum_s3);
				}
				return {}
			}, {event: 'keyup change'});

			edthgsemtd_v3_senjum_s3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					validasiSubmit(edthgsemtd_v3_senjum_s3);
				}
				
				if ( edthgsemtd_v3_senjum_s3.inError() ) {
					return false;
				}
			});

			edthgsemtd_v3_senjum_s3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_v3_senjum_s3.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_v3_senjum_s3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhgsemtd_v3_senjum_s1.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s2.ajax.reload(null,false);
				tblhgsemtd_v3_senjum_s3.ajax.reload(null,false);
			} );
			
			//start datatables
			tblhgsemtd_v3_senjum_s3 = $('#tblhgsemtd_v3_senjum_s3').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_v3/hgsemtd_v3_senjum_s3.php",
					type: 'POST',
					data: function (d){
						d.select_bagian = select_bagian;
						d.show_inactive_status_hgsemtd_v3 = show_inactive_status_hgsemtd_v3;
						d.id_hgsptth_v3 = id_hgsptth_v3;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_v3.id",visible:false },
					{ data: "hgsemtd_v3.id_hgsptth_v3",visible:false },
					{ data: "htsxxmh_data" },
					{ data: "hemxxmh_data" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_v3';
						$table       = 'tblhgsemtd_v3_senjum_s3';
						$edt         = 'edthgsemtd_v3_senjum_s3';
						$show_status = '_hgsemtd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_v3_senjum_s3.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s3, 'hgsemtd_v3' );
				CekDrawDetailHDFinal(tblhgsptth_v3);
			} );

			tblhgsemtd_v3_senjum_s3.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_v3 = tblhgsemtd_v3_senjum_s3.row( { selected: true } ).data().hgsemtd_v3;
				id_hgsemtd_v3   = data_hgsemtd_v3.id;
				id_transaksi_d    = id_hgsemtd_v3; // dipakai untuk general
				is_active_d       = data_hgsemtd_v3.is_active;
				id_hemxxmh_old       = data_hgsemtd_v3.id_hemxxmh;
				id_htsptth_v3_old       = data_hgsemtd_v3.id_htsptth_v3;
				id_holxxmd_old       = data_hgsemtd_v3.id_holxxmd;
				id_htsxxmh_old       = data_hgsemtd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s3 );
			} );

			tblhgsemtd_v3_senjum_s3.on( 'deselect', function() {
				id_hgsemtd_v3 = '';
				is_active_d = 0;
				id_holxxmd_old = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;
				id_htsptth_v3_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_v3, tblhgsemtd_v3_senjum_s3 );
			} );

// --------- end _detail --------------- //		

			$("#frmhgsemtd_v3").submit(function(e) {
				e.preventDefault(); // Prevent the default form submission
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhgsemtd_v3) {
					select_bagian 		= $('#select_bagian').val();
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhgsemtd_v3_sabtu_s1.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_sabtu_s2.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_sabtu_s3.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);

					tblhgsemtd_v3_senjum_s1.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_senjum_s2.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_senjum_s3.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);

					tblhgsemtd_v3_minggu_s1.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_minggu_s2.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					tblhgsemtd_v3_minggu_s3.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
