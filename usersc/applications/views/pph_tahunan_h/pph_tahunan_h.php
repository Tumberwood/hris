<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'pph_tahunan_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'pph_tahunan_d';
?>

<!-- begin content here -->
<div class="row" hidden>
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
                    <table id="tblpph_tahunan_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Periode Lembur</th>
                                <th>Keterangan</th>
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
				<h5>Peserta</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblpph_tahunan_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_pph_tahunan_h</th>
								<th>Karyawan</th>
								<!-- <th>Bulan</th>
								<th>Bruto</th> -->
								<th>PPh 21 Bulan Ini</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/pph_tahunan_h/fn/pph_tahunan_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtpph_tahunan_h, tblpph_tahunan_h, show_inactive_status_pph_tahunan_h = 0, id_pph_tahunan_h;
        var edtpph_tahunan_d, tblpph_tahunan_d, show_inactive_status_pph_tahunan_d = 0, id_pph_tahunan_d;
		// ------------- end of default variable
		var id_periode_payroll_old = 0, id_hemxxmh_old = 0;
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
			edtpph_tahunan_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/pph_tahunan_h/pph_tahunan_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_pph_tahunan_h = show_inactive_status_pph_tahunan_h;
					}
				},
				table: "#tblpph_tahunan_h",
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
						def: "pph_tahunan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "pph_tahunan_h.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Periode Payroll <sup class='text-danger'>*<sup>",
						name: "pph_tahunan_h.id_periode_payroll",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/periode_payroll/periode_payroll_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_periode_payroll_old: id_periode_payroll_old,
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
						label: "Keterangan",
						name: "pph_tahunan_h.keterangan",
						type: "textarea"
					},
				]
			} );
			
			edtpph_tahunan_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpph_tahunan_h.field('start_on').val(start_on);

				if(action == 'create'){
					tblpph_tahunan_h.rows().deselect();
					edtpph_tahunan_h.field('kategori_dokumen').val('');
					edtpph_tahunan_h.field('kategori_dokumen_value').val('');
					edtpph_tahunan_h.field('field_tanggal').val('created_on');
				}
			});

            edtpph_tahunan_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtpph_tahunan_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					id_periode_payroll = edtpph_tahunan_h.field('pph_tahunan_h.id_periode_payroll').val();
					if(!id_periode_payroll || id_periode_payroll == ''){
						edtpph_tahunan_h.field('pph_tahunan_h.id_periode_payroll').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtpph_tahunan_h.inError() ) {
					return false;
				}
			});

			edtpph_tahunan_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpph_tahunan_h.field('finish_on').val(finish_on);
			});
			
			edtpph_tahunan_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblpph_tahunan_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblpph_tahunan_h = $('#tblpph_tahunan_h').DataTable( {
				ajax: {
					url: "../../models/pph_tahunan_h/pph_tahunan_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_pph_tahunan_h = show_inactive_status_pph_tahunan_h;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "pph_tahunan_h.id",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.periode_payroll.tanggal_awal + " - " + row.periode_payroll.tanggal_akhir;
					   	}
					},
					{ data: "pph_tahunan_h.keterangan" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_pph_tahunan_h';
						$table       = 'tblpph_tahunan_h';
						$edt         = 'edtpph_tahunan_h';
						$show_status = '_pph_tahunan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.pph_tahunan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblpph_tahunan_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblpph_tahunan_d];
				CekInitHeaderHD(tblpph_tahunan_h, tbl_details);
			} );
			
			tblpph_tahunan_h.on( 'select', function( e, dt, type, indexes ) {
				data_pph_tahunan_h = tblpph_tahunan_h.row( { selected: true } ).data().pph_tahunan_h;
				id_pph_tahunan_h  = data_pph_tahunan_h.id;
				id_transaksi_h   = id_pph_tahunan_h; // dipakai untuk general
				is_approve       = data_pph_tahunan_h.is_approve;
				is_nextprocess   = data_pph_tahunan_h.is_nextprocess;
				is_jurnal        = data_pph_tahunan_h.is_jurnal;
				is_active        = data_pph_tahunan_h.is_active;
				id_periode_payroll_old        = data_pph_tahunan_h.id_periode_payroll;
				
				// atur hak akses
				tbl_details = [tblpph_tahunan_d];
				CekSelectHeaderHD(tblpph_tahunan_h, tbl_details);

			} );
			
			tblpph_tahunan_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_pph_tahunan_h = '';
				id_periode_payroll_old = 0;

				// atur hak akses
				tbl_details = [tblpph_tahunan_d];
				CekDeselectHeaderHD(tblpph_tahunan_h, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtpph_tahunan_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/pph_tahunan_h/pph_tahunan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pph_tahunan_d = show_inactive_status_pph_tahunan_d;
						d.id_pph_tahunan_h = id_pph_tahunan_h;
					}
				},
				table: "#tblpph_tahunan_d",
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
						def: "pph_tahunan_d",
						type: "hidden"
					},	{
						label: "id_pph_tahunan_h",
						name: "pph_tahunan_d.id_pph_tahunan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "pph_tahunan_d.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>", 
						name: "pph_tahunan_d.id_hemxxmh",
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
					// {
					// 	label: "Bulan <sup class='text-danger'>*<sup>",  
					// 	name: "pph_tahunan_d.bulan",
					// 	type: "select",
					// 	placeholder: "Select",
					// 	options: [
					// 		{ label: "Jan", value: "Jan" },
					// 		{ label: "Feb", value: "Feb" },
					// 		{ label: "Mar", value: "Mar" },
					// 		{ label: "Apr", value: "Apr" },
					// 		{ label: "Mei", value: "Mei" },
					// 		{ label: "Jun", value: "Jun" },
					// 		{ label: "Jul", value: "Jul" },
					// 		{ label: "Agu", value: "Agu" },
					// 		{ label: "Sep", value: "Sep" },
					// 		{ label: "Okt", value: "Okt" },
					// 		{ label: "Nov", value: "Nov" },
					// 		{ label: "Des", value: "Des" },
					// 	]
					// },
					// {
					// 	label: "Bruto <sup class='text-danger'>*<sup>", 
					// 	name: "pph_tahunan_d.bruto",
					// },	
					{
						label: "PPh 21 Bulan Ini <sup class='text-danger'>*<sup>", 
						name: "pph_tahunan_d.pph",
					},	
					{
						label: "Keterangan",
						name: "pph_tahunan_d.keterangan",
                        type: "textarea",
					},	
				]
			} );
			
			edtpph_tahunan_d.on( 'preOpen', function( e, mode, action ) {
				edtpph_tahunan_d.field('pph_tahunan_d.id_pph_tahunan_h').val(id_pph_tahunan_h);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpph_tahunan_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblpph_tahunan_d.rows().deselect();
				}
			});

            edtpph_tahunan_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtpph_tahunan_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi pph_tahunan_d.pph 
					pph = edtpph_tahunan_d.field('pph_tahunan_d.pph').val();
					
					// validasi angka
					if(isNaN(pph) ){
						edtpph_tahunan_d.field('pph_tahunan_d.pph').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi pph_tahunan_d.pph 
				}
				
				if ( edtpph_tahunan_d.inError() ) {
					return false;
				}
			});

			edtpph_tahunan_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpph_tahunan_d.field('finish_on').val(finish_on);
			});

			
			edtpph_tahunan_d.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblpph_tahunan_d = $('#tblpph_tahunan_d').DataTable( {
				ajax: {
					url: "../../models/pph_tahunan_h/pph_tahunan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pph_tahunan_d = show_inactive_status_pph_tahunan_d;
						d.id_pph_tahunan_h = id_pph_tahunan_h;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "pph_tahunan_d.id",visible:false },
					{ data: "pph_tahunan_d.id_pph_tahunan_h",visible:false },
					{ data: "hemxxmh.nama" },
					// { data: "pph_tahunan_d.bulan" },
					// {
					// 	data: "pph_tahunan_d.bruto",
					// 	render: function (data) {
					// 		return data == 0 ? "Nol" : "Tidak Berubah";
					// 	}
					// },
					{
						data: "pph_tahunan_d.pph",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_pph_tahunan_d';
						$table       = 'tblpph_tahunan_d';
						$edt         = 'edtpph_tahunan_d';
						$show_status = '_pph_tahunan_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.pph_tahunan_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblpph_tahunan_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblpph_tahunan_h, tblpph_tahunan_d, 'pph_tahunan_d' );
				CekDrawDetailHDFinal(tblpph_tahunan_h);
			} );

			tblpph_tahunan_d.on( 'select', function( e, dt, type, indexes ) {
				data_pph_tahunan_d = tblpph_tahunan_d.row( { selected: true } ).data().pph_tahunan_d;
				id_pph_tahunan_d   = data_pph_tahunan_d.id;
				id_transaksi_d    = id_pph_tahunan_d; // dipakai untuk general
				is_active_d       = data_pph_tahunan_d.is_active;
				id_hemxxmh_old       = data_pph_tahunan_d.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblpph_tahunan_h, tblpph_tahunan_d );
			} );

			tblpph_tahunan_d.on( 'deselect', function() {
				id_pph_tahunan_d = '';
				is_active_d = 0;
				id_hemxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblpph_tahunan_h, tblpph_tahunan_d );
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

					tblpph_tahunan_h.rows().deselect();
					tblpph_tahunan_d.rows().deselect();
					tblpph_tahunan_h.ajax.reload(function ( json ) {
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
