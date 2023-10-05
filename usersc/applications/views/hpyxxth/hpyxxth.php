<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hpyxxth';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hpyemtd';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhpyxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Periode</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Generated On</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
					
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
                    <table id="tblhpyemtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>id_hpyxxth</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Jabatan</th>
                                <th>Tipe</th>
                                <th>Sub Tipe</th>
                                <th>Status</th>
                                <th>Level</th>
                                <th>Gaji Pokok</th>
                                <th>TJ. Jabatan</th>
                                <th>Var Cost</th>
                                <th>TJ. Masa Kerja</th>
                                <th>Premi Absen</th>
                                <th>JKK</th>
                                <th>JKM</th>
                                <th>Trm JKK JKM</th>
								<th>Lembur 1,5</th>
								<th>Rp Lembur 1,5</th>
								<th>Lembur 2</th>
								<th>Rp Lembur 2</th>
								<th>Lembur 3</th>
								<th>Rp Lembur 3</th>
								<th>Total Lembur (Jam)</th>
								<th>Total Lembur (Rp) </th>
                                <th>Pot Makan</th>
                                <th>Pot JKK JKM</th>
                                <th>Pot JHT</th>
                                <th>Pot Upah</th>
                                <th>Pot BPJS</th>
                                <th>Pot Pensiun</th>
                                <th>Gaji Bersih</th>
                                <th>Bulat</th>
                                <th>Gaji Diterima</th>
                                
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
								<th>Total <br><br> Grand Total</th>
								<th></th>
								<th id="s_9"></th>
								<th id="s_10"></th>
								<th id="s_11"></th>
								<th id="s_12"></th>
								<th id="s_13"></th>
								<th id="s_14"></th>
								<th id="s_15"></th>
								<th id="s_16"></th>
								<th id="s_17"></th>
								<th id="s_18"></th>
								<th id="s_19"></th>
								<th id="s_20"></th>
								<th id="s_21"></th>
								<th id="s_22"></th>
								<th id="s_23"></th>
								<th id="s_24"></th>
								<th id="s_25"></th>
								<th id="s_26"></th>
								<th id="s_27"></th>
								<th id="s_28"></th>
								<th id="s_29"></th>
								<th id="s_30"></th>
								<th id="s_31"></th>
								<th id="s_32"></th>
								<th id="s_33"></th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth/fn/hpyxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth, tblhpyxxth, show_inactive_status_hpyxxth = 0, id_hpyxxth;
        var edthpyemtd, tblhpyemtd, show_inactive_status_hpyemtd = 0, id_hpyemtd;
		// ------------- end of default variable
		var id_heyxxmh_old = 0;
		

		$(document).ready(function() {
			
			//start datatables editor
			edthpyxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth = show_inactive_status_hpyxxth;
					}
				},
				table: "#tblhpyxxth",
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
						def: "hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyxxth.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hpyxxth.tanggal_awal",
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
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "hpyxxth.tanggal_akhir",
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
					// {
					// 	label: "Jenis <sup class='text-danger'>*<sup>",
					// 	name: "hpyxxth.id_heyxxmh",
					// 	type: "select2",
					// 	opts: {
					// 		placeholder : "Select",
					// 		allowClear: true,
					// 		multiple: false,
					// 		ajax: {
					// 			url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
					// 			dataType: 'json',
					// 			data: function (params) {
					// 				var query = {
					// 					id_heyxxmh_old: id_heyxxmh_old,
					// 					search: params.term || '',
					// 					page: params.page || 1
					// 				}
					// 					return query;
					// 			},
					// 			processResults: function (data, params) {
					// 				return {
					// 					results: data.results,
					// 					pagination: {
					// 						more: true
					// 					}
					// 				};
					// 			},
					// 			cache: true,
					// 			minimumInputLength: 1,
					// 			maximum: 10,
					// 			delay: 500,
					// 			maximumSelectionLength: 5,
					// 			minimumResultsForSearch: -1,
					// 		},
					// 	}
					// },	
					{
						label: "Keterangan",
						name: "hpyxxth.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyxxth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhpyxxth.rows().deselect();
				}
			});

            edthpyxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpyxxth.tanggal_awal
					if ( ! edthpyxxth.field('hpyxxth.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthpyxxth.field('hpyxxth.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthpyxxth.field('hpyxxth.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth.tanggal_awal

					// BEGIN of validasi hpyxxth.tanggal_akhir
					if ( ! edthpyxxth.field('hpyxxth.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthpyxxth.field('hpyxxth.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthpyxxth.field('hpyxxth.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth.tanggal_akhir

					// BEGIN of validasi hpyxxth.id_heyxxmh
					// if ( ! edthpyxxth.field('hpyxxth.id_heyxxmh').isMultiValue() ) {
					// 	id_heyxxmh = edthpyxxth.field('hpyxxth.id_heyxxmh').val();
					// 	if(!id_heyxxmh || id_heyxxmh == ''){
					// 		edthpyxxth.field('hpyxxth.id_heyxxmh').error( 'Wajib diisi!' );
					// 	}
					// }
					// END of validasi hpyxxth.id_heyxxmh
				}
				
				if ( edthpyxxth.inError() ) {
					return false;
				}
			});

			edthpyxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth.field('finish_on').val(finish_on);
			});
			
			edthpyxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyxxth = $('#tblhpyxxth').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth = show_inactive_status_hpyxxth;
					}
				},
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hpyxxth.id",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth.tanggal_awal + " - " + row.hpyxxth.tanggal_akhir;
					   	}
					},
					{ data: "heyxxmh.nama",visible:false },
					{ data: "hpyxxth.keterangan" },
					{ data: "hpyxxth.generated_on" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyxxth';
						$table       = 'tblhpyxxth';
						$edt         = 'edthpyxxth';
						$show_status = '_hpyxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					// {
					// 	text: '<i class="fa fa-google"></i>',
					// 	name: 'btnGeneratePresensi',
					// 	className: 'btn btn-xs btn-outline',
					// 	titleAttr: '',
					// 	action: function ( e, dt, node, config ) {
					// 		e.preventDefault(); 

					// 		notifyprogress = $.notify({
					// 			message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					// 		},{
					// 			z_index: 9999,
					// 			allow_dismiss: false,
					// 			type: 'info',
					// 			delay: 0
					// 		});

					// 		$.ajax( {
					// 			url: "../../models/hpyxxth/hpyxxth_fn_gen_payroll.php",
					// 			dataType: 'json',
					// 			type: 'POST',
					// 			data: {
					// 				id_hpyxxth	: id_hpyxxth,
					// 				id_heyxxmh	: id_heyxxmh_select,
					// 				tanggal_awal	: tanggal_awal_select,
					// 				tanggal_akhir	: tanggal_akhir_select
					// 			},
					// 			success: function ( json ) {

					// 				$.notify({
					// 					message: json.data.message
					// 				},{
					// 					type: json.data.type_message
					// 				});

					// 				tblhpyxxth.ajax.reload(function ( json ) {
					// 					notifyprogress.close();
					// 				}, false);
					// 			}
					// 		} );
					// 	}
					// }

					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensi',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hpyxxth/hpyxxth_fn_gen_payroll_ferry.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hpyxxth		: id_hpyxxth,
									tanggal_awal	: tanggal_awal_select,
									tanggal_akhir	: tanggal_akhir_select,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhpyxxth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpyxxth.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhpyemtd];
				CekInitHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			tblhpyxxth.on( 'select', function( e, dt, type, indexes ) {
				data_hpyxxth = tblhpyxxth.row( { selected: true } ).data().hpyxxth;
				id_hpyxxth  = data_hpyxxth.id;
				id_transaksi_h   = id_hpyxxth; // dipakai untuk general
				is_approve       = data_hpyxxth.is_approve;
				is_nextprocess   = data_hpyxxth.is_nextprocess;
				is_jurnal        = data_hpyxxth.is_jurnal;
				is_active        = data_hpyxxth.is_active;
				tanggal_awal_select        = data_hpyxxth.tanggal_awal;
				tanggal_akhir_select        = data_hpyxxth.tanggal_akhir;
				id_heyxxmh_select        = data_hpyxxth.id_heyxxmh;

				id_heyxxmh_old = data_hpyxxth.id_heyxxmh;
				
				// atur hak akses
				tbl_details = [tblhpyemtd];
				CekSelectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).enable();
			} );
			
			tblhpyxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth = 0;
				id_heyxxmh_old = 0;
				id_heyxxmh = 0

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				tbl_details = [tblhpyemtd];
				CekDeselectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd.rows().deselect();
				}
			});

            edthpyemtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd.inError() ) {
					return false;
				}
			});

			edthpyemtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd.field('finish_on').val(finish_on);
			});

			
			edthpyemtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd = $('#tblhpyemtd').DataTable( {
				
				searchPanes:{
					layout: 'columns-3'
				},
				columnDefs:[
					{
						searchPanes:{
							show: true
						},
						targets: [5,6,7]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,1,2,3,4,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33]
					}
				],
				ajax: {
					url: "../../models/hpyxxth/hpyemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd';
						$edt         = 'edthpyemtd';
						$show_status = '_hpyemtd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 9; i <= 33; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#s_' + columnIndex).html(numFormat(sum) + "<br><br> <span style='color: blue;'>" + numFormat(sum_all) + " </span>");

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );

			tblhpyemtd.searchPanes.container().appendTo( '#searchPanes1' );
			tblhpyemtd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd );
			} );

			tblhpyemtd.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd );
			} );

// --------- end _detail --------------- //		
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
