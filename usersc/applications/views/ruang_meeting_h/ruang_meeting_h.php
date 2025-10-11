<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'ruang_meeting_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'ruang_meeting_d';
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
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblruang_meeting_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Meeting Room</th>
                                <th>Tanggal</th>
                                <th>Acara</th>
                                <th>Start</th>
                                <th>Finish</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblruang_meeting_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_ruang_meeting_h</th>
                                <th>NRP</th>
                                <th>Nama</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/ruang_meeting_h/fn/ruang_meeting_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtruang_meeting_h, tblruang_meeting_h, show_inactive_status_ruang_meeting_h = 0, id_ruang_meeting_h;
        var edtruang_meeting_d, tblruang_meeting_d, show_inactive_status_ruang_meeting_d = 0, id_ruang_meeting_d;
		// ------------- end of default variable
		var id_acara_m_old = 0, id_ruang_meeting_m_old = 0, id_hemxxmh_old = 0;
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
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			//start datatables editor
			edtruang_meeting_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/ruang_meeting_h/ruang_meeting_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_ruang_meeting_h = show_inactive_status_ruang_meeting_h;
					}
				},
				table: "#tblruang_meeting_h",
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
					},	
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
						def: "ruang_meeting_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "ruang_meeting_h.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Acara",
						name: "ruang_meeting_h.id_acara_m",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/acara_m/acara_m_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_acara_m_old: id_acara_m_old,
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
								minimumResultsForSearch: -1
							}
						}
					},
					{
						label: "Meeting Room",
						name: "ruang_meeting_h.id_ruang_meeting_m",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/ruang_meeting_m/ruang_meeting_m_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_ruang_meeting_m_old: id_ruang_meeting_m_old,
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
								minimumResultsForSearch: -1
							}
						}
					},
					{
						label: "Tanggal",
						name: "ruang_meeting_h.tanggal",
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
						label: "Start",
						name: "ruang_meeting_h.start",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'HH:mm'
					},
					{
						label: "Finish",
						name: "ruang_meeting_h.finish",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'HH:mm'
					},
					{
						label: "Jumlah Peserta",
						name: "ruang_meeting_h.jumlah_peserta",
					},
					{
						label: "Keterangan",
						name: "ruang_meeting_h.keterangan",
						type: "textarea"
					},
					{
						label: "Konsumsi",
						name: "ruang_meeting_h.is_konsumsi",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},
					{
						label: "Keterangan Konsumsi",
						name: "ruang_meeting_h.keterangan_konsumsi",
						type: "textarea"
					},
				]
			} );
			
			edtruang_meeting_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_h.field('start_on').val(start_on);

				if(action == 'create'){
					tblruang_meeting_h.rows().deselect();
					edtruang_meeting_h.field('kategori_dokumen').val('');
					edtruang_meeting_h.field('kategori_dokumen_value').val('');
					edtruang_meeting_h.field('field_tanggal').val('created_on');
				}
			});

            edtruang_meeting_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtruang_meeting_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					jumlah_peserta = edtruang_meeting_h.field('ruang_meeting_h.jumlah_peserta').val();
					if(jumlah_peserta || jumlah_peserta != ''){
						if(jumlah_peserta <= 0 ){
							edtruang_meeting_h.field('ruang_meeting_h.jumlah_peserta').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(jumlah_peserta) ){
							edtruang_meeting_h.field('ruang_meeting_h.jumlah_peserta').error( 'Inputan harus berupa Angka!' );
						}
					}
					
					// kode = edtruang_meeting_h.field('ruang_meeting_h.kode').val();
					// if(!kode || kode == ''){
					// 	edtruang_meeting_h.field('ruang_meeting_h.kode').error( 'Wajib diisi!' );
					// }
				}
				
				if ( edtruang_meeting_h.inError() ) {
					return false;
				}
			});

			edtruang_meeting_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_h.field('finish_on').val(finish_on);
			});
			
			edtruang_meeting_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblruang_meeting_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblruang_meeting_h = $('#tblruang_meeting_h').DataTable( {
				ajax: {
					url: "../../models/ruang_meeting_h/ruang_meeting_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_ruang_meeting_h = show_inactive_status_ruang_meeting_h;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "ruang_meeting_h.id",visible:false },
					{ data: "ruang_meeting_h.kode" },
					{ data: "users.fname" },
					{ data: "ruang_meeting_m.nama" },
					{ data: "ruang_meeting_h.tanggal" },
					{ data: "acara_m.nama" },
					{ data: "ruang_meeting_h.start" },
					{ data: "ruang_meeting_h.finish" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_ruang_meeting_h';
						$table       = 'tblruang_meeting_h';
						$edt         = 'edtruang_meeting_h';
						$show_status = '_ruang_meeting_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.ruang_meeting_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblruang_meeting_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblruang_meeting_d];
				CekInitHeaderHD(tblruang_meeting_h, tbl_details);
			} );
			
			tblruang_meeting_h.on( 'select', function( e, dt, type, indexes ) {
				data_ruang_meeting_h = tblruang_meeting_h.row( { selected: true } ).data().ruang_meeting_h;
				id_ruang_meeting_h  = data_ruang_meeting_h.id;
				id_transaksi_h   = id_ruang_meeting_h; // dipakai untuk general
				is_approve       = data_ruang_meeting_h.is_approve;
				is_nextprocess   = data_ruang_meeting_h.is_nextprocess;
				is_jurnal        = data_ruang_meeting_h.is_jurnal;
				is_active        = data_ruang_meeting_h.is_active;
				id_acara_m_old        = data_ruang_meeting_h.id_acara_m;
				id_ruang_meeting_m_old        = data_ruang_meeting_h.id_ruang_meeting_m;
				
				// atur hak akses
				tbl_details = [tblruang_meeting_d];
				CekSelectHeaderHD(tblruang_meeting_h, tbl_details);

			} );
			
			tblruang_meeting_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_ruang_meeting_h = '';
				id_acara_m_old = 0;
				id_ruang_meeting_m_old = 0;

				// atur hak akses
				tbl_details = [tblruang_meeting_d];
				CekDeselectHeaderHD(tblruang_meeting_h, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtruang_meeting_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/ruang_meeting_h/ruang_meeting_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ruang_meeting_d = show_inactive_status_ruang_meeting_d;
						d.id_ruang_meeting_h = id_ruang_meeting_h;
					}
				},
				table: "#tblruang_meeting_d",
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
						def: "ruang_meeting_d",
						type: "hidden"
					},	{
						label: "id_ruang_meeting_h",
						name: "ruang_meeting_d.id_ruang_meeting_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "ruang_meeting_d.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Pegawai",
						name: "ruang_meeting_d.id_hemxxmh",
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
								minimumResultsForSearch: -1
							}
						}
					},
				]
			} );
			
			edtruang_meeting_d.on( 'preOpen', function( e, mode, action ) {
				edtruang_meeting_d.field('ruang_meeting_d.id_ruang_meeting_h').val(id_ruang_meeting_h);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblruang_meeting_d.rows().deselect();
				}
			});

            edtruang_meeting_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtruang_meeting_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edtruang_meeting_d.inError() ) {
					return false;
				}
			});

			edtruang_meeting_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_d.field('finish_on').val(finish_on);
			});

			
			edtruang_meeting_d.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblruang_meeting_d = $('#tblruang_meeting_d').DataTable( {
				ajax: {
					url: "../../models/ruang_meeting_h/ruang_meeting_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ruang_meeting_d = show_inactive_status_ruang_meeting_d;
						d.id_ruang_meeting_h = id_ruang_meeting_h;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "ruang_meeting_d.id",visible:false },
					{ data: "ruang_meeting_d.id_ruang_meeting_h",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_ruang_meeting_d';
						$table       = 'tblruang_meeting_d';
						$edt         = 'edtruang_meeting_d';
						$show_status = '_ruang_meeting_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.ruang_meeting_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblruang_meeting_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblruang_meeting_h, tblruang_meeting_d, 'ruang_meeting_d' );
				CekDrawDetailHDFinal(tblruang_meeting_h);
			} );

			tblruang_meeting_d.on( 'select', function( e, dt, type, indexes ) {
				data_ruang_meeting_d = tblruang_meeting_d.row( { selected: true } ).data().ruang_meeting_d;
				id_ruang_meeting_d   = data_ruang_meeting_d.id;
				id_transaksi_d    = id_ruang_meeting_d; // dipakai untuk general
				is_active_d       = data_ruang_meeting_d.is_active;
				id_hemxxmh_old       = data_ruang_meeting_d.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblruang_meeting_h, tblruang_meeting_d );
			} );

			tblruang_meeting_d.on( 'deselect', function() {
				id_ruang_meeting_d = '';
				is_active_d = 0;
				id_hemxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblruang_meeting_h, tblruang_meeting_d );
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
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblruang_meeting_h.rows().deselect();
					tblruang_meeting_d.rows().deselect();
					tblruang_meeting_h.ajax.reload(function ( json ) {
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
