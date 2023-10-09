<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hgsptth_new';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hgsemtd_new';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhgsptth_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Pola Shift</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Tipe</th>
                                <th>Dari Tanggal</th>
                                <th>Tanggal Generate</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>

                    <table id="tblhgsemtd_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>id_hgsptth_new</th>
                                <th>Nama</th>
                                <th>Minggu</th>
                                <th>Senin</th>
                                <th>Selasa</th>
                                <th>Rabu</th>
                                <th>Kamis</th>
                                <th>Jumat</th>
                                <th>Sabtu</th>
                            </tr>
                        </thead>

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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgsptth_new/fn/hgsptth_new_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgsptth_new, tblhgsptth_new, show_inactive_status_hgsptth_new = 0, id_hgsptth_new;
        var edthgsemtd_new, tblhgsemtd_new, show_inactive_status_hgsemtd_new = 0, id_hgsemtd_new;
		// ------------- end of default variable
		var id_htsptth_old = 0;
		

		$(document).ready(function() {
			
			//start datatables editor
			edthgsptth_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_new/hgsptth_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth_new = show_inactive_status_hgsptth_new;
					}
				},
				table: "#tblhgsptth_new",
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
						def: "hgsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsptth_new.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Pola Shift <sup class='text-danger'>*<sup>",
						name: "hgsptth_new.id_htsptth_new",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth_new/htsptth_new_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_old: id_htsptth_old,
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
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hgsptth_new.tanggal_awal",
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
						name: "hgsptth_new.tanggal_akhir",
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
						label: "Tipe <sup class='text-danger'>*<sup>",
						name: "hgsptth_new.tipe",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Continue", "value": "Continue" },
							{ "label": "Copy", "value": "Copy" }
						]
					},
					{
						name: "hgsptth_new.dari_tanggal",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	
				]
			} );
			
			edthgsptth_new.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth_new.field('start_on').val(start_on);

				if(action == 'create'){
					tblhgsptth_new.rows().deselect();
				}
			});

            edthgsptth_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthgsptth_new.dependent( 'hgsptth_new.tipe', function ( val, data, callback ) {
				if (val == "Continue") {
					edthgsptth_new.field('hgsptth_new.dari_tanggal').label("Dari Tanggal <sup class='text-danger'>*<sup>")
				} else {
					edthgsptth_new.field('hgsptth_new.dari_tanggal').label("Dari Tanggal")
				}
				return {}
			}, {event: 'keyup change'});

			edthgsptth_new.dependent( 'hgsptth_new.id_htsptth_new', function ( val, data, callback ) {
				id_htsptth_new = edthgsptth_new.field('hgsptth_new.id_htsptth_new').val();
				if (id_htsptth_new > 0) {
					PolaShift ();
				}
				return {}
			}, {event: 'keyup change'});

			edthgsptth_new.dependent( 'hgsptth_new.tanggal_awal', function ( val, data, callback ) {
				id_htsptth_new = edthgsptth_new.field('hgsptth_new.id_htsptth_new').val();
				if (id_htsptth_new > 0) {
					PolaShift ();
				}
				return {}
			}, {event: 'keyup change'});
			
			edthgsptth_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hgsptth_new.tanggal_awal
					if ( ! edthgsptth_new.field('hgsptth_new.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthgsptth_new.field('hgsptth_new.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthgsptth_new.field('hgsptth_new.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hgsptth_new.tanggal_awal

					// BEGIN of validasi hgsptth_new.tanggal_akhir
					if ( ! edthgsptth_new.field('hgsptth_new.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthgsptth_new.field('hgsptth_new.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthgsptth_new.field('hgsptth_new.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hgsptth_new.tanggal_akhir

					// BEGIN of validasi hgsptth_new.id_htsptth_new
					if ( ! edthgsptth_new.field('hgsptth_new.id_htsptth_new').isMultiValue() ) {
						id_htsptth_new = edthgsptth_new.field('hgsptth_new.id_htsptth_new').val();
						if(!id_htsptth_new || id_htsptth_new == ''){
							edthgsptth_new.field('hgsptth_new.id_htsptth_new').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgsptth_new.id_htsptth_new

					// BEGIN of validasi hgsptth_new.tipe
					tipe = edthgsptth_new.field('hgsptth_new.tipe').val();
					if ( ! edthgsptth_new.field('hgsptth_new.tipe').isMultiValue() ) {
						if(!tipe || tipe == ''){
							edthgsptth_new.field('hgsptth_new.tipe').error( 'Wajib diisi!' );
						}
					}

					if (tipe == "Continue") {
						// BEGIN of validasi hgsptth_new.dari_tanggal
						if ( ! edthgsptth_new.field('hgsptth_new.dari_tanggal').isMultiValue() ) {
							dari_tanggal = edthgsptth_new.field('hgsptth_new.dari_tanggal').val();
							if(!dari_tanggal || dari_tanggal == ''){
								edthgsptth_new.field('hgsptth_new.dari_tanggal').error( 'Wajib diisi!' );
							}else{
								dari_tanggal_ymd = moment(dari_tanggal).format('YYYY-MM-DD');
							}
						}
						// END of validasi hgsptth_new.dari_tanggal
					}
					// END of validasi hgsptth_new.tipe
					PolaShift();
					if(status_hari_pola == "Invalid"){
						edthgsptth_new.field('hgsptth_new.tanggal_awal').error( 'Tanggal Awal wajib hari Senin, karena Pola Grup = 3!' );
					}
				}
				
				if ( edthgsptth_new.inError() ) {
					return false;
				}
			});

			edthgsptth_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth_new.field('finish_on').val(finish_on);
			});
			
			edthgsptth_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhgsptth_new = $('#tblhgsptth_new').DataTable( {
				ajax: {
					url: "../../models/hgsptth_new/hgsptth_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth_new = show_inactive_status_hgsptth_new;
					}
				},
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hgsptth_new.id",visible:false },
					{ data: "htsptth.nama"},
					{ data: "hgsptth_new.tanggal_awal"},
					{ data: "hgsptth_new.tanggal_akhir"},
					{ data: "hgsptth_new.tipe" },
					{ data: "hgsptth_new.dari_tanggal"},
					{ data: "hgsptth_new.generated_on"}
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsptth_new';
						$table       = 'tblhgsptth_new';
						$edt         = 'edthgsptth_new';
						$show_status = '_hgsptth_new';
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
					// 			url: "../../models/hgsptth_new/hgsptth_new_fn_gen_payroll.php",
					// 			dataType: 'json',
					// 			type: 'POST',
					// 			data: {
					// 				id_hgsptth_new	: id_hgsptth_new,
					// 				id_htsptth	: id_htsptth_select,
					// 				tanggal_awal	: tanggal_awal_select,
					// 				tanggal_akhir	: tanggal_akhir_select
					// 			},
					// 			success: function ( json ) {

					// 				$.notify({
					// 					message: json.data.message
					// 				},{
					// 					type: json.data.type_message
					// 				});

					// 				tblhgsptth_new.ajax.reload(function ( json ) {
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
								url: "../../models/hgsptth_new/hgsptth_new_fn_gen_jadwal_ferry.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hgsptth_new		: id_hgsptth_new,
									tanggal_awal		: tanggal_awal_select,
									tanggal_akhir		: tanggal_akhir_select,
									dari_tanggal		: dari_tanggal_select,
									id_htsptth_new			: id_htsptth_new,
									tipe			: tipe,
									timestamp			: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhgsptth_new.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgsptth_new.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhgsptth_new.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhgsemtd_new];
				CekInitHeaderHD(tblhgsptth_new, tbl_details);
				tblhgsptth_new.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			tblhgsptth_new.on( 'select', function( e, dt, type, indexes ) {
				data_hgsptth_new = tblhgsptth_new.row( { selected: true } ).data().hgsptth_new;
				id_hgsptth_new  = data_hgsptth_new.id;
				id_transaksi_h   = id_hgsptth_new; // dipakai untuk general
				is_approve       = data_hgsptth_new.is_approve;
				is_nextprocess   = data_hgsptth_new.is_nextprocess;
				is_jurnal        = data_hgsptth_new.is_jurnal;
				is_active        = data_hgsptth_new.is_active;
				tanggal_awal_select        = data_hgsptth_new.tanggal_awal;
				dari_tanggal_select        = data_hgsptth_new.dari_tanggal;
				tipe        = data_hgsptth_new.tipe;
				tanggal_akhir_select        = data_hgsptth_new.tanggal_akhir;
				id_htsptth_new        = data_hgsptth_new.id_htsptth_new;

				id_htsptth_old = data_hgsptth_new.id_htsptth_new;
				
				// atur hak akses
				tbl_details = [tblhgsemtd_new];
				CekSelectHeaderHD(tblhgsptth_new, tbl_details);
				tblhgsptth_new.button( 'btnGeneratePresensi:name' ).enable();
			} );
			
			tblhgsptth_new.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgsptth_new = 0;
				id_htsptth_new = 0;
				id_htsptth_old = 0;
				id_htsptth = 0
				tipe = '';

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				dari_tanggal_select = null;
				id_htsptth_select = 0;

				// atur hak akses
				tbl_details = [tblhgsemtd_new];
				CekDeselectHeaderHD(tblhgsptth_new, tbl_details);
				tblhgsptth_new.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthgsemtd_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth_new/hgsemtd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsemtd_new = show_inactive_status_hgsemtd_new;
						d.id_hgsptth_new = id_hgsptth_new;
					}
				},
				table: "#tblhgsemtd_new",
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
						def: "hgsemtd_new",
						type: "hidden"
					},	{
						label: "id_hgsptth_new",
						name: "hgsemtd_new.id_hgsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsemtd_new.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hgsemtd_new.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthgsemtd_new.on( 'preOpen', function( e, mode, action ) {
				edthgsemtd_new.field('hgsemtd_new.id_hgsptth_new').val(id_hgsptth_new);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_new.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsemtd_new.rows().deselect();
				}
			});

            edthgsemtd_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthgsemtd_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthgsemtd_new.inError() ) {
					return false;
				}
			});

			edthgsemtd_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsemtd_new.field('finish_on').val(finish_on);
			});

			
			edthgsemtd_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhgsemtd_new = $('#tblhgsemtd_new').DataTable( {
				
				ajax: {
					url: "../../models/hgsptth_new/hgsemtd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsemtd_new = show_inactive_status_hgsemtd_new;
						d.id_hgsptth_new = id_hgsptth_new;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				scrollX: true,
				colReorder: true,
				columns: [
					{ data: "hgsemtd_new.id",visible:false },
					{ data: "hgsemtd_new.id_hgsptth_new",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hgsemtd_new.minggu" },
					{ data: "hgsemtd_new.senin" },
					{ data: "hgsemtd_new.selasa" },
					{ data: "hgsemtd_new.rabu" },
					{ data: "hgsemtd_new.kamis" },
					{ data: "hgsemtd_new.jumat" },
					{ data: "hgsemtd_new.sabtu" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsemtd_new';
						$table       = 'tblhgsemtd_new';
						$edt         = 'edthgsemtd_new';
						$show_status = '_hgsemtd_new';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblhgsemtd_new.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhgsptth_new, tblhgsemtd_new, 'hgsemtd_new' );
				CekDrawDetailHDFinal(tblhgsptth_new);
				hari();
			} );

			tblhgsemtd_new.on( 'select', function( e, dt, type, indexes ) {
				data_hgsemtd_new = tblhgsemtd_new.row( { selected: true } ).data().hgsemtd_new;
				id_hgsemtd_new   = data_hgsemtd_new.id;
				id_transaksi_d    = id_hgsemtd_new; // dipakai untuk general
				is_active_d       = data_hgsemtd_new.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhgsptth_new, tblhgsemtd_new );
			} );

			tblhgsemtd_new.on( 'deselect', function() {
				id_hgsemtd_new = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhgsptth_new, tblhgsemtd_new );
			} );

// --------- end _detail --------------- //		
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
