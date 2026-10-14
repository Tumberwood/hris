<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'bpjs_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'bpjs_d';
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
                    <table id="tblbpjs_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
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
                    <table id="tblbpjs_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_bpjs_h</th>
								<th>Karyawan</th>
								<th>JHT Perusahaan</th>
								<th>JKK Perusahaan</th>
								<th>JKM Perusahaan</th>
								<th>JP Perusahaan</th>
								<th>JHT Karyawan</th>
								<th>JP Karyawan</th>
								<th>Kes Perusahaan</th>
								<th>Kes Karyawan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/bpjs_h/fn/bpjs_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtbpjs_h, tblbpjs_h, show_inactive_status_bpjs_h = 0, id_bpjs_h;
        var edtbpjs_d, tblbpjs_d, show_inactive_status_bpjs_d = 0, id_bpjs_d;
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
			edtbpjs_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/bpjs_h/bpjs_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_bpjs_h = show_inactive_status_bpjs_h;
					}
				},
				table: "#tblbpjs_h",
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
						def: "bpjs_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "bpjs_h.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Periode Payroll <sup class='text-danger'>*<sup>",
						name: "bpjs_h.id_periode_payroll",
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
						name: "bpjs_h.keterangan",
						type: "textarea"
					},
				]
			} );
			
			edtbpjs_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtbpjs_h.field('start_on').val(start_on);

				if(action == 'create'){
					tblbpjs_h.rows().deselect();
					edtbpjs_h.field('kategori_dokumen').val('');
					edtbpjs_h.field('kategori_dokumen_value').val('');
					edtbpjs_h.field('field_tanggal').val('created_on');
				}
			});

            edtbpjs_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtbpjs_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					id_periode_payroll = edtbpjs_h.field('bpjs_h.id_periode_payroll').val();
					if(!id_periode_payroll || id_periode_payroll == ''){
						edtbpjs_h.field('bpjs_h.id_periode_payroll').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtbpjs_h.inError() ) {
					return false;
				}
			});

			edtbpjs_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtbpjs_h.field('finish_on').val(finish_on);
			});
			
			edtbpjs_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblbpjs_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblbpjs_h = $('#tblbpjs_h').DataTable( {
				ajax: {
					url: "../../models/bpjs_h/bpjs_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_bpjs_h = show_inactive_status_bpjs_h;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "bpjs_h.id",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.periode_payroll.tanggal_awal + " - " + row.periode_payroll.tanggal_akhir;
					   	}
					},
					{ data: "bpjs_h.keterangan" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_bpjs_h';
						$table       = 'tblbpjs_h';
						$edt         = 'edtbpjs_h';
						$show_status = '_bpjs_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.bpjs_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblbpjs_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblbpjs_d];
				CekInitHeaderHD(tblbpjs_h, tbl_details);
			} );
			
			tblbpjs_h.on( 'select', function( e, dt, type, indexes ) {
				data_bpjs_h = tblbpjs_h.row( { selected: true } ).data().bpjs_h;
				id_bpjs_h  = data_bpjs_h.id;
				id_transaksi_h   = id_bpjs_h; // dipakai untuk general
				is_approve       = data_bpjs_h.is_approve;
				is_nextprocess   = data_bpjs_h.is_nextprocess;
				is_jurnal        = data_bpjs_h.is_jurnal;
				is_active        = data_bpjs_h.is_active;
				id_periode_payroll_old        = data_bpjs_h.id_periode_payroll;
				
				// atur hak akses
				tbl_details = [tblbpjs_d];
				CekSelectHeaderHD(tblbpjs_h, tbl_details);

			} );
			
			tblbpjs_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_bpjs_h = '';
				id_periode_payroll_old = 0;

				// atur hak akses
				tbl_details = [tblbpjs_d];
				CekDeselectHeaderHD(tblbpjs_h, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtbpjs_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/bpjs_h/bpjs_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_bpjs_d = show_inactive_status_bpjs_d;
						d.id_bpjs_h = id_bpjs_h;
					}
				},
				table: "#tblbpjs_d",
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
						def: "bpjs_d",
						type: "hidden"
					},	{
						label: "id_bpjs_h",
						name: "bpjs_d.id_bpjs_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "bpjs_d.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>", 
						name: "bpjs_d.id_hemxxmh",
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
					{
						label: "JHT Perusahaan",
						name: "bpjs_d.jht_perusahaan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "JKK Perusahaan",
						name: "bpjs_d.jkk",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "JKM Perusahaan",
						name: "bpjs_d.jkm",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "JP Perusahaan",
						name: "bpjs_d.jp_perusahaan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "JHT Karyawan",
						name: "bpjs_d.pot_jht_karyawan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "JP Karyawan",
						name: "bpjs_d.pot_jp_karyawan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "Kes Perusahaan",
						name: "bpjs_d.bpjs_kes_perusahaan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "Kes Karyawan",
						name: "bpjs_d.bpjs_kes_karyawan",
						type: "select",
						placeholder: "Select",
						def: 0,
						options: [
							{ label: "Tidak Berubah", value: 1 },
							{ label: "Nol", value: 0 }
						]
					},
					{
						label: "Keterangan",
						name: "bpjs_d.keterangan",
                        type: "textarea",
					},	
				]
			} );
			
			edtbpjs_d.on( 'preOpen', function( e, mode, action ) {
				edtbpjs_d.field('bpjs_d.id_bpjs_h').val(id_bpjs_h);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtbpjs_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblbpjs_d.rows().deselect();
				}
			});

            edtbpjs_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtbpjs_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edtbpjs_d.inError() ) {
					return false;
				}
			});

			edtbpjs_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtbpjs_d.field('finish_on').val(finish_on);
			});

			
			edtbpjs_d.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblbpjs_d = $('#tblbpjs_d').DataTable( {
				ajax: {
					url: "../../models/bpjs_h/bpjs_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_bpjs_d = show_inactive_status_bpjs_d;
						d.id_bpjs_h = id_bpjs_h;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "bpjs_d.id",visible:false },
					{ data: "bpjs_d.id_bpjs_h",visible:false },
					{ data: "hemxxmh.nama" },
					{
						data: "bpjs_d.jht_perusahaan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.jkk",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.jkm",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.jp_perusahaan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.pot_jht_karyawan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.pot_jp_karyawan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.bpjs_kes_perusahaan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
					{
						data: "bpjs_d.bpjs_kes_karyawan",
						render: function (data) {
							return data == 0 ? "Nol" : "Tidak Berubah";
						}
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_bpjs_d';
						$table       = 'tblbpjs_d';
						$edt         = 'edtbpjs_d';
						$show_status = '_bpjs_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.bpjs_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblbpjs_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblbpjs_h, tblbpjs_d, 'bpjs_d' );
				CekDrawDetailHDFinal(tblbpjs_h);
			} );

			tblbpjs_d.on( 'select', function( e, dt, type, indexes ) {
				data_bpjs_d = tblbpjs_d.row( { selected: true } ).data().bpjs_d;
				id_bpjs_d   = data_bpjs_d.id;
				id_transaksi_d    = id_bpjs_d; // dipakai untuk general
				is_active_d       = data_bpjs_d.is_active;
				id_hemxxmh_old       = data_bpjs_d.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblbpjs_h, tblbpjs_d );
			} );

			tblbpjs_d.on( 'deselect', function() {
				id_bpjs_d = '';
				is_active_d = 0;
				id_hemxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblbpjs_h, tblbpjs_d );
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

					tblbpjs_h.rows().deselect();
					tblbpjs_d.rows().deselect();
					tblbpjs_h.ajax.reload(function ( json ) {
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
