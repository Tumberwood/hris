<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpy_piutang_d';
	$nama_tabels_d = [];
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
                <form class="form-horizontal" id="frmhpy_piutang_d">
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
                    <table id="tblhpy_piutang_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Perhitungan</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpy_piutang_d/fn/hpy_piutang_d_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpy_piutang_d, tblhpy_piutang_d, show_inactive_status_hpy_piutang_d = 0, id_hpy_piutang_d;
		var id_hemxxmh_old = 0;
		var id_hpcxxmh_old = 0;
		var is_jenis = 0;
		var plus_min = 0;
		is_need_approval = 1;
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
		$('#start_date').datepicker('setDate', awal_bulan_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
        // END datepicker init
		
		$(document).ready(function() {
			//start datatables editor
			edthpy_piutang_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpy_piutang_d/hpy_piutang_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_d = show_inactive_status_hpy_piutang_d;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblhpy_piutang_d",
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
						def: "hpy_piutang_d",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpy_piutang_d.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.id_hemxxmh",
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
						label: "Perhitungan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.plus_min",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Penambah", "value": "Penambah" },
							{ "label": "Pengurang", "value": "Pengurang" }
						]
					},
					{
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.id_hpcxxmh",
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
										is_lain: 1,
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
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.nominal",
						fieldInfo: "Jika Pengurang, Maka Tidak Perlu menuliskan Minus (-)"
					}, 	{
						label: "Tanggal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.tanggal",
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
						label: "Keterangan<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.keterangan",
						type: "textarea"
					}
				]
			} );
			edthpy_piutang_d.field('hpy_piutang_d.nominal').input().addClass('text-right');

			edthpy_piutang_d.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpy_piutang_d.rows().deselect();
				}
			});

			edthpy_piutang_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthpy_piutang_d.dependent( 'hpy_piutang_d.plus_min', function ( val, data, callback ) {
				if (val == "Penambah") {
					if (val != plus_min) {
						edthpy_piutang_d.field('hpy_piutang_d.id_hpcxxmh').val('');
					}
					is_jenis = 1;
				} else {
					if (val != plus_min) {
						edthpy_piutang_d.field('hpy_piutang_d.id_hpcxxmh').val('');
					}
					is_jenis = 2;
				}
				return {}
			}, {event: 'keyup change'});

            edthpy_piutang_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpy_piutang_d.id_hemxxmh 
					id_hemxxmh = edthpy_piutang_d.field('hpy_piutang_d.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthpy_piutang_d.field('hpy_piutang_d.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.id_hemxxmh 
					
					// BEGIN of validasi hpy_piutang_d.id_hpcxxmh 
					jenis = edthpy_piutang_d.field('hpy_piutang_d.id_hpcxxmh').val();
					if(!jenis || jenis == ''){
						edthpy_piutang_d.field('hpy_piutang_d.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.id_hpcxxmh 
					
					// BEGIN of validasi hpy_piutang_d.keterangan 
					keterangan = edthpy_piutang_d.field('hpy_piutang_d.keterangan').val();
					if(!keterangan || keterangan == ''){
						edthpy_piutang_d.field('hpy_piutang_d.keterangan').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.keterangan 
					
					// BEGIN of validasi hpy_piutang_d.tanggal 
					tanggal = edthpy_piutang_d.field('hpy_piutang_d.tanggal').val();
					if(!tanggal || tanggal == ''){
						edthpy_piutang_d.field('hpy_piutang_d.tanggal').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.tanggal 
					
					// BEGIN of validasi hpy_piutang_d.plus_min 
					plus_min = edthpy_piutang_d.field('hpy_piutang_d.plus_min').val();
					if(!plus_min || plus_min == ''){
						edthpy_piutang_d.field('hpy_piutang_d.plus_min').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.plus_min 
					
					// BEGIN of validasi hpy_piutang_d.nominal 
					nominal = edthpy_piutang_d.field('hpy_piutang_d.nominal').val();
					
					// validasi min atau max angka
					if(nominal <= 0 ){
						edthpy_piutang_d.field('hpy_piutang_d.nominal').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal) ){
						edthpy_piutang_d.field('hpy_piutang_d.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_piutang_d.nominal 
				}
				
				if ( edthpy_piutang_d.inError() ) {
					return false;
				}
			});
			
			edthpy_piutang_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_d.field('finish_on').val(finish_on);
			});

			//start datatables
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			tblhpy_piutang_d = $('#tblhpy_piutang_d').DataTable( {
				
				searchPanes:{
					layout: 'columns-2'
				},
				dom:
					"<'row'<'col-lg-4 col-md-4 col-sm-12 col-xs-12'l><'col-lg-8 col-md-8 col-sm-12 col-xs-12'f>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'B>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
					"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true
						},
						targets: [1,2]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,3,4,5,6,7]
					}
				],
				ajax: {
					url: "../../models/hpy_piutang_d/hpy_piutang_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_d = show_inactive_status_hpy_piutang_d;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				responsive: false,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hpy_piutang_d.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpcxxmh.nama" },
					{ 
						data: "hpy_piutang_d.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ data: "hpy_piutang_d.plus_min" },
					{ data: "hpy_piutang_d.tanggal" },
					{ data: "hpy_piutang_d.keterangan" },
					{ 
						data: "hpy_piutang_d.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							} else {
								return '';
							}
						} 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpy_piutang_d';
						$table       = 'tblhpy_piutang_d';
						$edt         = 'edthpy_piutang_d';
						$show_status = '_hpy_piutang_d';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpy_piutang_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhpy_piutang_d.searchPanes.container().appendTo( '#searchPanes1' );
			
			tblhpy_piutang_d.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpy_piutang_d);
			} );
			
			tblhpy_piutang_d.on( 'select', function( e, dt, type, indexes ) {
				hpy_piutang_d_data    = tblhpy_piutang_d.row( { selected: true } ).data().hpy_piutang_d;
				id_hpy_piutang_d      = hpy_piutang_d_data.id;
				id_transaksi_h = id_hpy_piutang_d; // dipakai untuk general
				is_approve     = hpy_piutang_d_data.is_approve;
				is_nextprocess = hpy_piutang_d_data.is_nextprocess;
				is_jurnal      = hpy_piutang_d_data.is_jurnal;
				is_active      = hpy_piutang_d_data.is_active;
				plus_min      = hpy_piutang_d_data.plus_min;
				id_hemxxmh_old      = hpy_piutang_d_data.id_hemxxmh;
				id_hpcxxmh_old      = hpy_piutang_d_data.id_hpcxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhpy_piutang_d);
			} );

			tblhpy_piutang_d.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpy_piutang_d = '';
				id_hemxxmh_old = 0;
				plus_min = 0;
				id_hpcxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpy_piutang_d);
			} );
			
			$("#frmhpy_piutang_d").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhpy_piutang_d) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhpy_piutang_d.ajax.reload(function ( json ) {
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
