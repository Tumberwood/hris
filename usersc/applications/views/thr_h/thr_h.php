<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'thr_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'thr_d';
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
                    <table id="tblthr_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Periode Lembur</th>
                                <th>Tanggal Hari Raya</th>
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
                    <table id="tblthr_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_thr_h</th>
								<th>NIK</th>
								<th>Nama</th>
								<th>THR</th>
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


<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtthr_h, tblthr_h, show_inactive_status_thr_h = 0, id_thr_h;
        var edtthr_d, tblthr_d, show_inactive_status_thr_d = 0, id_thr_d;
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

		var tanggal_awal_select = null;
		var tanggal_akhir_select = null;
		var tanggal_hari_raya_select = null;

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			//start datatables editor
			edtthr_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/thr_h/thr_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_thr_h = show_inactive_status_thr_h;
					}
				},
				table: "#tblthr_h",
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
						def: "thr_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "thr_h.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Periode Payroll <sup class='text-danger'>*<sup>",
						name: "thr_h.id_periode_payroll",
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
						label: "Tanggal Hari Raya <sup class='text-danger'>*<sup>",
						name: "thr_h.tanggal_hari_raya",
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
						label: "Keterangan",
						name: "thr_h.keterangan",
						type: "textarea"
					},
				]
			} );
			
			edtthr_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtthr_h.field('start_on').val(start_on);

				if(action == 'create'){
					tblthr_h.rows().deselect();
					edtthr_h.field('kategori_dokumen').val('');
					edtthr_h.field('kategori_dokumen_value').val('');
					edtthr_h.field('field_tanggal').val('created_on');
				}
			});

            edtthr_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtthr_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					id_periode_payroll = edtthr_h.field('thr_h.id_periode_payroll').val();
					if(!id_periode_payroll || id_periode_payroll == ''){
						edtthr_h.field('thr_h.id_periode_payroll').error( 'Wajib diisi!' );
					}
					
					tanggal_hari_raya = edtthr_h.field('thr_h.tanggal_hari_raya').val();
					if(!tanggal_hari_raya || tanggal_hari_raya == ''){
						edtthr_h.field('thr_h.tanggal_hari_raya').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtthr_h.inError() ) {
					return false;
				}
			});

			edtthr_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtthr_h.field('finish_on').val(finish_on);
				
				edtthr_h.field('kategori_dokumen').val('');
				edtthr_h.field('kategori_dokumen_value').val();
				edtthr_h.field('field_tanggal').val('created_on');
			});
			
			edtthr_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblthr_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblthr_h = $('#tblthr_h').DataTable( {
				ajax: {
					url: "../../models/thr_h/thr_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_thr_h = show_inactive_status_thr_h;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "thr_h.id",visible:false },
					{ data: "thr_h.kode"},
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.periode_payroll.tanggal_awal + " - " + row.periode_payroll.tanggal_akhir;
					   	}
					},
					{ data: "thr_h.tanggal_hari_raya" },
					{ data: "thr_h.keterangan" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_thr_h';
						$table       = 'tblthr_h';
						$edt         = 'edtthr_h';
						$show_status = '_thr_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-google"></i>ENERATE',
						name: 'btnGen',
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
								url: "../../models/thr_h/thr_h_fn_gen.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_thr_h		: id_thr_h,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblthr_h.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);

									tblthr_d.ajax.reload(function ( json ) {
									}, false);
								}
							} );
						}
					},
					{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip THR',
						action: function (e, dt, node, config) {
							e.preventDefault();

							const selected = dt.row({ selected: true }).data();

							if (!selected) {
								alert('Silakan pilih transaksi THR terlebih dahulu.');
								return;
							}

							const params = new URLSearchParams({
								id_thr_h: selected.id
							});

							window.open(
								'thr_h_print.php?' + params.toString(),
								'print_thr'
							);
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.thr_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblthr_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblthr_d];
				CekInitHeaderHD(tblthr_h, tbl_details);
				tblthr_h.button( 'btnGen:name' ).disable();
				tblthr_h.button( 'btnPrint:name' ).disable();
			} );
			
			tblthr_h.on( 'select', function( e, dt, type, indexes ) {
				data_thr_h = tblthr_h.row( { selected: true } ).data().thr_h;
				id_thr_h  = data_thr_h.id;
				id_transaksi_h   = id_thr_h; // dipakai untuk general
				is_approve       = data_thr_h.is_approve;
				is_nextprocess   = data_thr_h.is_nextprocess;
				is_jurnal        = data_thr_h.is_jurnal;
				is_active        = data_thr_h.is_active;
				id_periode_payroll_old        = data_thr_h.id_periode_payroll;
				tanggal_awal_select        = data_thr_h.tanggal_awal;
				tanggal_akhir_select        = data_thr_h.tanggal_akhir;
				tanggal_hari_raya_select        = data_thr_h.tanggal_hari_raya;
				
				// atur hak akses
				tbl_details = [tblthr_d];
				CekSelectHeaderHD(tblthr_h, tbl_details);
				tblthr_h.button( 'btnGen:name' ).enable();
				tblthr_h.button( 'btnPrint:name' ).enable();

			} );
			
			tblthr_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_thr_h = '';
				id_periode_payroll_old = 0;
				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				tanggal_hari_raya_select = null;

				// atur hak akses
				tbl_details = [tblthr_d];
				CekDeselectHeaderHD(tblthr_h, tbl_details);
				tblthr_h.button( 'btnGen:name' ).disable();
				tblthr_h.button( 'btnPrint:name' ).disable();
			} );
			
// --------- start _detail --------------- //
			
			//start datatables
			tblthr_d = $('#tblthr_d').DataTable( {
				ajax: {
					url: "../../models/thr_h/thr_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_thr_d = show_inactive_status_thr_d;
						d.id_thr_h = id_thr_h;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "thr_d.id",visible:false },
					{ data: "thr_d.id_thr_h",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{
						data: "thr_d.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_thr_d';
						$table       = 'tblthr_d';
						$edt         = 'edtthr_d';
						$show_status = '_thr_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.thr_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblthr_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblthr_h, tblthr_d, 'thr_d' );
				CekDrawDetailHDFinal(tblthr_h);
			} );

			tblthr_d.on( 'select', function( e, dt, type, indexes ) {
				data_thr_d = tblthr_d.row( { selected: true } ).data().thr_d;
				id_thr_d   = data_thr_d.id;
				id_transaksi_d    = id_thr_d; // dipakai untuk general
				is_active_d       = data_thr_d.is_active;
				id_hemxxmh_old       = data_thr_d.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblthr_h, tblthr_d );
			} );

			tblthr_d.on( 'deselect', function() {
				id_thr_d = '';
				is_active_d = 0;
				id_hemxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblthr_h, tblthr_d );
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

					tblthr_h.rows().deselect();
					tblthr_d.rows().deselect();
					tblthr_h.ajax.reload(function ( json ) {
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
