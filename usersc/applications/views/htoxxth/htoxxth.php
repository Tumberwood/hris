<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htoxxth';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htoemtd';
	
	if (isset($_GET['kode_hto'])){
		$kode_hto		= ($_GET['kode_hto']);
	} else {
		$kode_hto = null;
	}
	
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
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
                <form class="form-horizontal" id="frmFilter">
                    <div class="form-group row">
						<label class="col-lg-2 col-form-label">Periode</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
                                <span class="input-group-addon">to</span>
                                <input type="text" id="end_date" class="form-control">
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
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtoxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
							<tr>
								<th>ID</th>
								<th data-priority="1">Nomor</th>
								<th data-priority="2">Tanggal</th>
								<th data-priority="3">Area Kerja</th>
								<th>Keterangan</th>
								<th>Approval</th>
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
                    <table id="tblhtoemtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th rowspan=2 class="text-center">ID</th>
								<th rowspan=2 class="text-center">id_htoxxth</th>
								<th data-priority="1" rowspan=2 class="text-center">Tipe</th>
								<th data-priority="2" rowspan=2 class="text-center">Karyawan</th>
								<th colspan=2 class="text-center">Jam Lembur</th>
								<th rowspan=2 class="text-center">Istirahat</th>	
								<th data-priority="3" rowspan=2 class="text-center">Durasi (Jam)</th>	
								<th rowspan=2 class="text-center">Keterangan</th>
								<th data-priority="4" rowspan=2 class="text-center">Valid</th>
							</tr>
							<tr>
								<th class = "text-center">Mulai</th>
								<th class = "text-center">Selesai</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th id="sum_durasi_lembur_jam"></th>
								<th></th>
								<th></th>
							</tr>
						</tfoot>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htoxxth/fn/htoxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtoxxth, tblhtoxxth, show_inactive_status_htoxxth = 0, id_htoxxth;
        var edthtoemtd, tblhtoemtd, show_inactive_status_htoemtd = 0, id_htoemtd;
		// ------------- end of default variable

		is_need_approval = 1;
		var kode_hto = "<?php echo $kode_hto ?>";

		console.log(kode_hto);
		// sementara di 0 kan
		// is_need_generate_kode = 1;

		var id_holxxmd_old = 0, id_heyxxmh_old = 0;
		var id_heyxxmh = 0, id_htotpmh_old  = 0, id_hemxxmh_old = 0;
		var tanggal, is_valid_checkclock;
		var tanggal_get = "<?php echo $awal ?>";
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		// $('#start_date').datepicker('setDate', awal_bulan_dmy);
		// $('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		
		if (tanggal_get === '') {
			$('#start_date').datepicker('setDate', awal_bulan_dmy);
			$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_get));
			$('#end_date').datepicker('setDate', new Date(tanggal_get));
		}
        // END datepicker init

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			//start datatables editor
			edthtoxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htoxxth/htoxxth.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.kode_hto = kode_hto;
						d.show_inactive_status_htoxxth = show_inactive_status_htoxxth;
					}
				},
				table: "#tblhtoxxth",
				fields: [ 
					{
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						label: "field_tanggal",
						name: "field_tanggal",
						type: "hidden"
					},	{
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
						def: "htoxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htoxxth.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htoxxth.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						format: 'DD MMM YYYY'
					},	
					{
						label: "Area Kerja <sup class='text-danger'>*<sup>",
						name: "htoxxth.id_holxxmd",
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
						label: "Tipe Karyawan",
						name: "htoxxth.id_heyxxmh",
						id: "select2_tipe_karyawan", //tambahkan id di select2nya
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh_old: id_heyxxmh_old,
										is_validate_session: 1,
										search: params.term || '',
										page: params.page || 1
									}
									return query;
								},
								processResults: function (data, params) {
									var options = data.results.map(function (result) {
										return {
											id: result.id,
											text: result.text
										};
									});

									//add by ferry agar auto select 07 sep 23
									if (params.page && params.page === 1) {
										$('#select2_tipe_karyawan').empty().select2({ data: options });
									} else {
										$('#select2_tipe_karyawan').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
									}

									return {
										results: options,
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
						name: "htoxxth.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtoxxth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoxxth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtoxxth.rows().deselect();	
					edthtoxxth.field('field_tanggal').val('tanggal');
				}
			});

            edthtoxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				//add by ferry agar auto select 07 sep 23
				//perlu dibuka dulu, set 5 detik terus close select2 nya. setelah itu bisa auto select value
				$('#select2_tipe_karyawan').select2('open');

				setTimeout(function() {
					$('#select2_tipe_karyawan').select2('close');
				}, 5);
			});
			
			edthtoxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htoxxth.tanggal 
					tanggal = edthtoxxth.field('htoxxth.tanggal').val();
					if(!tanggal || tanggal == ''){
						edthtoxxth.field('htoxxth.tanggal').error( 'Wajib diisi!' );
					}
					// END of validasi htoxxth.tanggal

					// BEGIN of validasi htoxxth.id_holxxmd 
					id_holxxmd = edthtoxxth.field('htoxxth.id_holxxmd').val();
					if(!id_holxxmd || id_holxxmd == ''){
						edthtoxxth.field('htoxxth.id_holxxmd').error( 'Wajib diisi!' );
					}
					// END of validasi htoxxth.id_holxxmd

					// BEGIN of cek unik kombinasi htoxxth.id_holxxmd dan htoxxth.tanggal 
					tanggal_ymd = moment(edthtoxxth.field('htoxxth.tanggal').val()).format('YYYY-MM-DD');
					if(action == 'create'){
						id_htoxxth = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'htoxxth',
							nama_field: 'tanggal_ymd, id_holxxmd',
							nama_field_value: '"'+tanggal_ymd+'",'+ id_holxxmd,
							id_transaksi: id_htoxxth
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtoxxth.field('htoxxth.tanggal').error( 'Data Area Kerja pada Tanggal tersebut sudah ada!' );
								edthtoxxth.field('htoxxth.id_holxxmd').error( 'Data Area Kerja pada Tanggal tersebut sudah ada!' );
							}
						}
					} );
					// BEGIN of cek unik kombinasi htoxxth.id_holxxmd dan htoxxth.tanggal 
				}
				
				if ( edthtoxxth.inError() ) {
					return false;
				}
			});

			edthtoxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoxxth.field('finish_on').val(finish_on);
				id_heyxxmh = edthtoxxth.field('htoxxth.id_heyxxmh').val();
				tanggal = edthtoxxth.field('htoxxth.tanggal').val();

				edthtoxxth.field('kategori_dokumen').val('id_heyxxmh');
				edthtoxxth.field('kategori_dokumen_value').val(id_heyxxmh);
			});

			edthtoxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhtoxxth.rows().deselect();
				tblhtoxxth.ajax.reload(null, false);
			} );
			
			//start datatables
			tblhtoxxth = $('#tblhtoxxth').DataTable( {
				ajax: {
					url: "../../models/htoxxth/htoxxth.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.kode_hto = kode_hto;
						d.show_inactive_status_htoxxth = show_inactive_status_htoxxth;
					}
				},
				order: [[ 2, "desc" ],[1, "desc"]],
				columns: [
					{ data: "htoxxth.id",visible:false },
					{ data: "htoxxth.kode" },
					{ data: "htoxxth.tanggal" },
					{ data: "holxxmd.nama" },
					{ data: "htoxxth.keterangan" },
					{ 
						data: "htoxxth.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							}
						}
					}
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htoxxth';
						$table       = 'tblhtoxxth';
						$edt         = 'edthtoxxth';
						$show_status = '_htoxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{ 
						text: '<span>Approve Per Tanggal</span>', 
						name: 'btnApproveTanggal',
						className: 'btn btn-primary',
						action: function ( e, dt, node, config ) {
							$.ajax( {
								url: '../../models/htoxxth/fn_approve_per_tanggal.php',
								dataType: 'json',
								type: 'POST',
								data: {
									end_date: end_date,
									status: "approve"
								},
								success: function ( json ) {
									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});
									tblhtoxxth.ajax.reload(null,false);
									cekApproveTanggal();
								}
							});
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.htoxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
					if ( data.htoxxth.is_approve == 1 ) {
						$('td', row).addClass('text-approved');
					}
				}
				
			} );

			cekApproveTanggal();

			tblhtoxxth.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtoemtd];
				CekInitHeaderHD(tblhtoxxth, tbl_details);
			} );
			
			tblhtoxxth.on( 'select', function( e, dt, type, indexes ) {
				data_htoxxth = tblhtoxxth.row( { selected: true } ).data().htoxxth;
				id_htoxxth  = data_htoxxth.id;
				id_transaksi_h   = id_htoxxth; // dipakai untuk general
				is_approve       = data_htoxxth.is_approve;
				is_nextprocess   = data_htoxxth.is_nextprocess;
				is_jurnal        = data_htoxxth.is_jurnal;
				is_active        = data_htoxxth.is_active;

				/**
				 * untuk generate kode saat approve
				 */
				kategori_dokumen = 'id_heyxxmh';
				kategori_dokumen_value = data_htoxxth.id_heyxxmh;

				id_holxxmd_old   = data_htoxxth.id_holxxmd;
				id_heyxxmh_old   = data_htoxxth.id_heyxxmh;
				id_heyxxmh   	 = data_htoxxth.id_heyxxmh; // untuk options id_hemxxmh di detail
				tanggal   	 	 = data_htoxxth.tanggal;
				
				// atur hak akses
				tbl_details = [tblhtoemtd];
				CekSelectHeaderHD(tblhtoxxth, tbl_details);

			} );
			
			tblhtoxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htoxxth = 0;
				id_holxxmd_old   = 0;
				id_heyxxmh_old   = 0;
				tanggal = '';

				// atur hak akses
				tbl_details = [tblhtoemtd];
				CekDeselectHeaderHD(tblhtoxxth, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtoemtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htoxxth/htoemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htoemtd = show_inactive_status_htoemtd;
						d.id_htoxxth = id_htoxxth;
					}
				},
				table: "#tblhtoemtd",
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
						def: "htoemtd",
						type: "hidden"
					},	{
						label: "id_htoxxth",
						name: "htoemtd.id_htoxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htoemtd.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tipe <sup class='text-danger'>*</sup>",
						name: "htoemtd.id_htotpmh",
						id: "select2_tipe", //tambahkan id di select2nya
						type: "select2",
						opts: {
							placeholder: "Select",
							allowClear: true,
							multiple: false,
							async: false,
							ajax: {
								url: "../../models/htotpmh/htotpmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htotpmh_old: id_htotpmh_old,
										search: params.term || '',
										page: params.page || 1
									};
									return query;
								},
								processResults: function (data, params) {
									var options = data.results.map(function (result) {
										return {
											id: result.id,
											text: result.text
										};
									});

									//add by ferry agar auto select 07 sep 23
									if (params.page && params.page === 1) {
										$('#select2_tipe').empty().select2({ data: options });
									} else {
										$('#select2_tipe').append(new Option(options[1].text, options[1].id, false, false)).trigger('change');
									}

									return {
										results: options,
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
						}
					},
	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "htoemtd.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htoxxth/htoemtd_fn_opt_hemxxmh.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh: id_heyxxmh,
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
						label: "Jadwal",
						name: "jadwal",
						type: "readonly"
					},	
					{
						label: "Jam Mulai <sup class='text-danger'>*<sup>",
						name: "htoemtd.jam_awal",
						type: "datetime",
						format: 'HH:mm'
					},	
					{
						label: "Jam Selesai <sup class='text-danger'>*<sup>",
						name: "htoemtd.jam_akhir",
						type: "datetime",
						format: 'HH:mm'
					},	
					{
						label: "Istirahat",
						name: "htoemtd.is_istirahat",
						type: "select",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "TI", "value":2 },
							{ "label": "Tidak", "value": 0 }
						]
					}, 	
					{
						label: "Checkclock Valid",
						name: "htoemtd.is_valid_checkclock",
						type: "readonly"
					},
					{
						label: "Keterangan",
						name: "htoemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtoemtd.on( 'preOpen', function( e, mode, action ) {
				edthtoemtd.field('htoemtd.id_htoxxth').val(id_htoxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoemtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtoemtd.rows().deselect();
				}
			});

            edthtoemtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				//add by ferry agar auto select 07 sep 23
				//perlu dibuka dulu, set 5 detik terus close select2 nya. setelah itu bisa auto select value
				$('#select2_tipe').select2('open');

				setTimeout(function() {
					$('#select2_tipe').select2('close');
				}, 5);
				
			});

			edthtoemtd.dependent( 'htoemtd.id_htotpmh', function ( val, data, callback ) {
				get_htsxxmh();
				return {}
			}, {event: 'keyup change'});

			edthtoemtd.dependent( 'htoemtd.id_hemxxmh', function ( val, data, callback ) {
				get_htsxxmh();
				return {}
			}, {event: 'keyup change'});

			edthtoemtd.dependent( 'htoemtd.id_htotpmh', function ( val, data, callback ) {
				id_htotpmh = edthtoemtd.field('htoemtd.id_htotpmh').val();
				if (id_htotpmh == 5 || id_htotpmh == 6 || id_htotpmh == 7){
					// jika tipe overtime = Istirahat1, Istirahat2, atau Istirahat3
					edthtoemtd.field('htoemtd.jam_awal').hide();
					edthtoemtd.field('htoemtd.jam_awal').val('');
					edthtoemtd.field('htoemtd.jam_akhir').hide();
					edthtoemtd.field('htoemtd.jam_akhir').val('');
					edthtoemtd.field('htoemtd.is_istirahat').hide();
					edthtoemtd.field('htoemtd.is_istirahat').val(2);
				}else{
					edthtoemtd.field('htoemtd.jam_awal').show();
					edthtoemtd.field('htoemtd.jam_akhir').show();
					edthtoemtd.field('htoemtd.is_istirahat').show();
				}
				return {}
			}, {event: 'keyup change'});
			
			edthtoemtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htoemtd.id_htotpmh 
					id_htotpmh = edthtoemtd.field('htoemtd.id_htotpmh').val();
					if(!id_htotpmh || id_htotpmh == ''){
						edthtoemtd.field('htoemtd.id_htotpmh').error( 'Wajib diisi!' );
					}else{
						if(id_htotpmh == 1 || id_htotpmh == 2 || id_htotpmh == 4){
							if ( edthtoemtd.field('htoemtd.jam_awal').val() == '') {
								edthtoemtd.field('htoemtd.jam_awal').error( 'Jam Awal Lembur Harus Diisi!' );
							}
							
							if ( edthtoemtd.field('htoemtd.jam_akhir').val() == '' ) {
								edthtoemtd.field('htoemtd.jam_akhir').error( 'Jam Akhir Lembur Harus Diisi!' );
							}
						}
					}
					// END of validasi htoemtd.id_htotpmh 

					// BEGIN of validasi htoemtd.id_hemxxmh 
					id_hemxxmh = edthtoemtd.field('htoemtd.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthtoemtd.field('htoemtd.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htoemtd.id_hemxxmh 

					// BEGIN of cek unik kombinasi htoemtd.id_htotpmh dan htoemtd.id_hemxxmh 
					if(action == 'create'){
						id_htoemtd = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'htoemtd',
							nama_field: 'id_htoxxth,id_htotpmh,id_hemxxmh',
							nama_field_value: id_htoxxth+','+id_htotpmh+','+id_hemxxmh,
							id_transaksi: id_htoemtd
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtoemtd.field('htoemtd.id_htotpmh').error( 'Kombinasi Nama Karyawan dan Tipe Lembur sudah ada!' );
								edthtoemtd.field('htoemtd.id_htotpmh').error( 'Kombinasi Nama Karyawan dan Tipe Lembur sudah ada!' );
							}
						}
					} );
					// END of cek unik kombinasi htoemtd.id_htotpmh dan htoemtd.id_hemxxmh 

					// BEGIN validasi jadwal
					jadwal = edthtoemtd.field('jadwal').val();
					if(jadwal == ''){
						edthtoemtd.field('jadwal').error( 'Jadwal belum ada!' );
					}
					// END validasi jadwal

					// BEGIN validasi checkclock
					check_valid_checkclock();
					if(is_valid_checkclock == 0){
						edthtoemtd.field('htoemtd.is_valid_checkclock').error( 'Checkclock belum valid!' );
					}
					// END validasi checkclock
					
					lemburLibur(id_hemxxmh, tanggal, id_htotpmh);
				}
				
				if ( edthtoemtd.inError() ) {
					return false;
				}
			});

			edthtoemtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoemtd.field('finish_on').val(finish_on);
				
				// BEGIN update terkait validasi checkclock
				check_valid_checkclock();
				if(is_valid_checkclock > 0){
					edthtoemtd.field('htoemtd.is_valid_checkclock').val(1);
				}
				// END update terkait validasi checkclock
			});

			
			edthtoemtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// tblhtoemtd.rows().deselect();
				tblhtoemtd.ajax.reload(null, false);
				tblhtoxxth.ajax.reload(null, false);
			} );
			
			//start datatables
			tblhtoemtd = $('#tblhtoemtd').DataTable( {
				ajax: {
					url: "../../models/htoxxth/htoemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htoemtd = show_inactive_status_htoemtd;
						d.id_htoxxth = id_htoxxth;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				columns: [
					{ data: "htoemtd.id",visible:false },
					{ data: "htoemtd.id_htoxxth",visible:false },
					{ data: "htotpmh.nama"},
					{ data: "hemxxmh_data"},
					{ data: "htoemtd.jam_awal"},
					{ data: "htoemtd.jam_akhir"},
					{ 
						data: "htoemtd.is_istirahat",
						render: function (data){
							if(data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else if(data == 2){
								return 'TI';
							}else{
								return '';
							}
						}
					},
					{ 
						data: "htoemtd.durasi_lembur_jam",
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ data: "htoemtd.keterangan"},
					{ 
						data: "htoemtd.is_valid_checkclock" ,
						render: function (data){
							if (data == 0){
								return '<i class="fa fa-remove text-danger"></i>';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-check text-warning"></i>';
							}else{
								return '';
							}
						}
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htoemtd';
						$table       = 'tblhtoemtd';
						$edt         = 'edthtoemtd';
						$show_status = '_htoemtd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htoemtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
					// hitung jumlah 
					sum_durasi_lembur_jam = api.column( 7 ).data().sum();

					// Update footer v1
					$( '#sum_durasi_lembur_jam' ).html( numFormat(sum_durasi_lembur_jam) );
				}
			} );

			tblhtoemtd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtoxxth, tblhtoemtd, 'htoemtd' );
				CekDrawDetailHDFinal(tblhtoxxth);
			} );

			tblhtoemtd.on( 'select', function( e, dt, type, indexes ) {
				data_htoemtd = tblhtoemtd.row( { selected: true } ).data().htoemtd;
				id_htoemtd   = data_htoemtd.id;
				id_transaksi_d    = id_htoemtd; // dipakai untuk general
				is_active_d       = data_htoemtd.is_active;

				id_hemxxmh_old    = data_htoemtd.id_hemxxmh;
				id_htotpmh_old    = data_htoemtd.id_htotpmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtoxxth, tblhtoemtd );
			} );

			tblhtoemtd.on( 'deselect', function() {
				id_htoemtd = 0;
				is_active_d = 0;

				id_hemxxmh_old = 0;
				id_htotpmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtoxxth, tblhtoemtd );
			} );

// --------- end _detail --------------- //		
			
			$("#frmFilter").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmFilter) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					kode_hto
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhtoxxth.rows().deselect();
					tblhtoemtd.rows().deselect();
					tblhtoxxth.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					cekApproveTanggal()
					return false; 
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
