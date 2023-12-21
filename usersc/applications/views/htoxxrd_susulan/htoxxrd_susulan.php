<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htoxxrd_susulan';
	$nama_tabels_d = [];
	
	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= $_GET['id_hemxxmh'];
	} else {
		$id_hemxxmh		= 0;
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
                <form class="form-horizontal" id="frmhtoxxrd_susulan">
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
                        <label class="col-sm-2 col-form-label">Employee</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_hemxxmh" name="select_hemxxmh"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary" type="submit" id="go">Submit</button>
                        </div>
                    </div>
                </form>
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
                    <table id="tblhtoxxrd_susulan" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Kode</th>
								<th>Periode Payroll</th>
								<th>Tanggal</th>
								<th>Karyawan</th>
								<th>Jenis</th>
								<th>Tipe</th>
								<th>Istirahat</th>
								<th>Keterangan</th>
								<th>Jam Awal</th>
								<th>Jam Akhir</th>
								<th>Durasi</th>
								<!-- <th>Makan</th> -->
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
								<th></th>
								<th></th>
								<!-- <th id=""></th> -->
								<th></th>
								<th>Grand Total</th>
								<th class="text-right bg-primary" id="s_jam"></th>
							</tr>
						</tfoot>
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
<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htoxxrd_susulan/fn/htoxxrd_susulan_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtoxxrd_susulan, tblhtoxxrd_susulan, show_inactive_status_htoxxrd_susulan = 0;
		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var id_hpyxxth_old = 0;
		var id_htotpmh_old  = 0, id_hemxxmh_old = 0;
		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var tanggal_get = "<?php echo $awal ?>";
		// var id_hpyxxmh = <?php echo  $_SESSION['str_arr_ha_heyxxmh'] ?>;

		// console.log(id_hem_get);
		// console.log(tanggal_get);
		// ------------- end of default variable

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		
		if (tanggal_get === '') {
			$('#start_date').datepicker('setDate', awal_bulan_dmy);
			$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_get));
			$('#end_date').datepicker('setDate', new Date(tanggal_get));
		}
        // END datepicker init

		//Select2 init
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
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
					if (id_hem_get > 0) {
						var options = data.results.map(function (result) {
							return {
								id: result.id,
								text: result.text
							};
						});

						//add by ferry agar auto select 07 sep 23
						if (params.page && params.page === 1) {
							$('#select_hemxxmh').empty().select2({ data: options });
						} else {
							$('#select_hemxxmh').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
						}

						return {
							results: options,
							pagination: {
								more: true
							}
						};
					} else {
						return {
							results: data.results,
							pagination: {
								more: true
							}
						};
					}
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
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			id_hemxxmh_old = id_hem_get;
			
			$('#select_hemxxmh').select2('open');

			setTimeout(function() {
				$('#select_hemxxmh').select2('close');
			}, 5);

			
			//start datatables editor
			edthtoxxrd_susulan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htoxxrd_susulan/htoxxrd_susulan.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
						d.show_inactive_status_htoxxrd_susulan = show_inactive_status_htoxxrd_susulan;
					}
				},
				table: "#tblhtoxxrd_susulan",
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
						def: "htoxxrd_susulan",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htoxxrd_susulan.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Periode Payroll <sup class='text-danger'>*<sup>",
						name: "htoxxrd_susulan.id_hpyxxth",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hpyxxth/hpyxxth_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hpyxxth_old: id_hpyxxth_old,
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
						label: "Tanggal <sup class='text-danger'>*</sup>",
						name: "htoxxrd_susulan.tanggal",
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
						label: "Tipe <sup class='text-danger'>*</sup>",
						name: "htoxxrd_susulan.id_htotpmh",
						id: "select2_tipe_karyawan", //tambahkan id di select2nya
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
										$('#select2_tipe_karyawan').empty().select2({ data: options });
									} else {
										$('#select2_tipe_karyawan').append(new Option(options[1].text, options[1].id, false, false)).trigger('change');
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
						name: "htoxxrd_susulan.id_hemxxmh",
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
										// id_heyxxmh: id_heyxxmh,
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
						name: "htoxxrd_susulan.jam_awal",
						type: "datetime",
						format: 'HH:mm'
					},	
					{
						label: "Jam Selesai <sup class='text-danger'>*<sup>",
						name: "htoxxrd_susulan.jam_akhir",
						type: "datetime",
						format: 'HH:mm'
					},	
					{
						label: "Istirahat",
						name: "htoxxrd_susulan.is_istirahat",
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
						name: "htoxxrd_susulan.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtoxxrd_susulan.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoxxrd_susulan.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtoxxrd_susulan.rows().deselect();	
					// edthtoxxrd_susulan.field('field_tanggal').val('tanggal');
				}
			});

            edthtoxxrd_susulan.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				//add by ferry agar auto select 07 sep 23
				//perlu dibuka dulu, set 5 detik terus close select2 nya. setelah itu bisa auto select value
				$('#select2_tipe_karyawan').select2('open');

				setTimeout(function() {
					$('#select2_tipe_karyawan').select2('close');
				}, 5);
			});

			edthtoxxrd_susulan.dependent( 'htoxxrd_susulan.tanggal', function ( val, data, callback ) {
				if (val != null) {
					get_htsxxmh();
				}
				return {}
			}, {event: 'keyup change'});

			edthtoxxrd_susulan.dependent( 'htoxxrd_susulan.id_hemxxmh', function ( val, data, callback ) {
				get_htsxxmh();
				return {}
			}, {event: 'keyup change'});

			edthtoxxrd_susulan.dependent( 'htoxxrd_susulan.id_htotpmh', function ( val, data, callback ) {
				id_htotpmh = edthtoxxrd_susulan.field('htoxxrd_susulan.id_htotpmh').val();
				if (id_htotpmh == 5 || id_htotpmh == 6 || id_htotpmh == 7){
					// jika tipe overtime = Istirahat1, Istirahat2, atau Istirahat3
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_awal').hide();
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_awal').val('');
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_akhir').hide();
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_akhir').val('');
					edthtoxxrd_susulan.field('htoxxrd_susulan.is_istirahat').hide();
					edthtoxxrd_susulan.field('htoxxrd_susulan.is_istirahat').val(2);
				}else{
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_awal').show();
					edthtoxxrd_susulan.field('htoxxrd_susulan.jam_akhir').show();
					edthtoxxrd_susulan.field('htoxxrd_susulan.is_istirahat').show();
				}
				return {}
			}, {event: 'keyup change'});
			
			edthtoxxrd_susulan.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htoxxrd_susulan.id_hpyxxth 
					id_hpyxxth = edthtoxxrd_susulan.field('htoxxrd_susulan.id_hpyxxth').val();
					if(!id_hpyxxth || id_hpyxxth == ''){
						edthtoxxrd_susulan.field('htoxxrd_susulan.id_hpyxxth').error( 'Wajib diisi!' );
					}
					// END of validasi htoxxrd_susulan.jam_akhir

					// BEGIN of validasi htoxxrd_susulan.id_htotpmh 
					id_htotpmh = edthtoxxrd_susulan.field('htoxxrd_susulan.id_htotpmh').val();
					if(!id_htotpmh || id_htotpmh == ''){
						edthtoxxrd_susulan.field('htoxxrd_susulan.id_htotpmh').error( 'Wajib diisi!' );
					}else{
						if(id_htotpmh == 1 || id_htotpmh == 2 || id_htotpmh == 4){
							if ( edthtoxxrd_susulan.field('htoxxrd_susulan.jam_awal').val() == '') {
								edthtoxxrd_susulan.field('htoxxrd_susulan.jam_awal').error( 'Jam Awal Lembur Harus Diisi!' );
							}
							
							if ( edthtoxxrd_susulan.field('htoxxrd_susulan.jam_akhir').val() == '' ) {
								edthtoxxrd_susulan.field('htoxxrd_susulan.jam_akhir').error( 'Jam Akhir Lembur Harus Diisi!' );
							}
						}
					}
					// END of validasi htoxxrd_susulan.id_htotpmh 

					// BEGIN of validasi htoxxrd_susulan.id_hemxxmh 
					id_hemxxmh = edthtoxxrd_susulan.field('htoxxrd_susulan.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthtoxxrd_susulan.field('htoxxrd_susulan.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htoxxrd_susulan.id_hemxxmh 

					// BEGIN of cek unik kombinasi htoxxrd_susulan.id_htotpmh dan htoxxrd_susulan.id_hemxxmh 
					if(action == 'create'){
						id_htoxxrd_susulan = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'htoxxrd_susulan',
							nama_field: 'id_htotpmh,id_hemxxmh',
							nama_field_value: id_htotpmh+','+id_hemxxmh,
							id_transaksi: id_htoxxrd_susulan
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtoxxrd_susulan.field('htoxxrd_susulan.id_htotpmh').error( 'Kombinasi Nama Karyawan dan Tipe Lembur sudah ada!' );
								edthtoxxrd_susulan.field('htoxxrd_susulan.id_htotpmh').error( 'Kombinasi Nama Karyawan dan Tipe Lembur sudah ada!' );
							}
						}
					} );
					// END of cek unik kombinasi htoxxrd_susulan.id_htotpmh dan htoxxrd_susulan.id_hemxxmh 

					// BEGIN validasi jadwal
					jadwal = edthtoxxrd_susulan.field('jadwal').val();
					if(jadwal == ''){
						edthtoxxrd_susulan.field('jadwal').error( 'Jadwal belum ada!' );
					}
					// END validasi jadwal
					// BEGIN validasi checkclock
					check_valid_checkclock();
					if(is_valid_checkclock == 0){
						edthtoxxrd_susulan.field('htoxxrd_susulan.is_valid_checkclock').error( 'Checkclock belum valid!' );
					}
					// END validasi checkclock
					
					lemburLibur(id_hemxxmh, tanggal, id_htotpmh);
				}
				
				if ( edthtoxxrd_susulan.inError() ) {
					return false;
				}
			});

			edthtoxxrd_susulan.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtoxxrd_susulan.field('finish_on').val(finish_on);
			});

			edthtoxxrd_susulan.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhtoxxrd_susulan.rows().deselect();
				tblhtoxxrd_susulan.ajax.reload(null, false);
			} );

			//start datatables
			tblhtoxxrd_susulan = $('#tblhtoxxrd_susulan').DataTable( {
				searchPanes:{
					layout: 'columns-4',
				},
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
						targets: [2,3,4,5]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,1,6,7,8,9,10]
					}
				],
				ajax: {
					url: "../../models/htoxxrd_susulan/htoxxrd_susulan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htoxxrd_susulan = show_inactive_status_htoxxrd_susulan;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				columns: [
					{ 
						data: "htoxxrd_susulan.id",
						visible:false 
					},
					{ 
						data: "htoxxrd_susulan.kode" ,
						visible:false 
					},
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth.tanggal_awal + " - " + row.hpyxxth.tanggal_akhir;
					   	}
					},
					{ data: "htoxxrd_susulan.tanggal" },
					{ data: "hemxxmh_data" },
					{ data: "heyxxmh.nama" },
					{ data: "htotpmh.nama" },
					{ 
						data: "htoxxrd_susulan.is_istirahat" ,
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else if(data == 2){
								return 'TI';
							}else{
								return 'Data Salah';
							}
						}
					},
					{ data: "htoxxrd_susulan.keterangan" },
					{ data: "htoxxrd_susulan.jam_awal" },
					{ data: "htoxxrd_susulan.jam_akhir" },
					{ 
						data: "htoxxrd_susulan.durasi_lembur_jam" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					// { data: null },
					// { 
					// 	data: "htoxxrd_susulan.is_approve" ,
					// 	render: function (data){
					// 		if (data == 0){
					// 			return '';
					// 		}else if(data == 1){
					// 			return '<i class="fa fa-check text-navy"></i>';
					// 		}else if(data == 2){
					// 			return '<i class="fa fa-undo text-muted"></i>';
					// 		}else if(data == -9){
					// 			return '<i class="fa fa-remove text-danger"></i>';
					// 		}
					// 	}
					// }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htoxxrd_susulan';
						$table       = 'tblhtoxxrd_susulan';
						$edt         = 'edthtoxxrd_susulan';
						$show_status = '_htoxxrd_susulan';
						$table_name  = $nama_tabel;

						$arr_buttons_tools = ['copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve = [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htoxxrd_susulan.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat1 = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
					var numFormat0 = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
					// hitung jumlah 
					s_jam = api.column( 11 ).data().sum();
					

					$( '#s_jam' ).html( numFormat1(s_jam) );
				}
			} );

			tblhtoxxrd_susulan.searchPanes.container().appendTo( '#searchPanes1' );

			tblhtoxxrd_susulan.on( 'select', function( e, dt, type, indexes ) {
				data_htoxxrd_susulan = tblhtoxxrd_susulan.row( { selected: true } ).data().htoxxrd_susulan;
				id_htoxxrd_susulan   = data_htoxxrd_susulan.id;
				id_transaksi_d    = id_htoxxrd_susulan; // dipakai untuk general
				is_active_d       = data_htoxxrd_susulan.is_active;

				id_hpyxxth_old    = data_htoxxrd_susulan.id_hpyxxth;
				id_hemxxmh_old    = data_htoxxrd_susulan.id_hemxxmh;
				id_htotpmh_old    = data_htoxxrd_susulan.id_htotpmh;
				
				// atur hak akses
			} );

			tblhtoxxrd_susulan.on( 'deselect', function() {
				id_htoxxrd_susulan = 0;
				is_active_d = 0;

				id_hpyxxth_old = 0;
				id_hemxxmh_old = 0;
				id_htotpmh_old = 0;
				
				// atur hak akses
			} );
			
			$("#frmhtoxxrd_susulan").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtoxxrd_susulan) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					if ($('#select_hemxxmh').val() > 0) {
						id_hemxxmh = $('#select_hemxxmh').val();
					} else {
						if (id_hem_get != 0) {
							id_hemxxmh = id_hem_get;
						} else {
							id_hemxxmh = $('#select_hemxxmh').val();
						}
					}

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhtoxxrd_susulan.rows().deselect();
					tblhtoxxrd_susulan.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});
			
			if (id_hem_get > 0) {
				$("#frmhtoxxrd_susulan").submit();
			}
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
