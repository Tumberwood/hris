<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htscctd';
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
				<form class="form-horizontal" id="frmhtscctd">
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">Tanggal Awal</label>
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
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtscctd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Pengaju</th>
                                <th>Pengganti</th>
                                <th>Keterangan</th>
                                <th>Appr</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htscctd/fn/htscctd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtscctd, tblhtscctd, show_inactive_status_htscctd = 0, id_htscctd;
		// ------------- end of default variable

		is_need_approval = 1, is_need_generate_kode = 1;

		var id_hemxxmh_pengaju_old = 0, id_hemxxmh_pengganti_old = 0;
		
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
			edthtscctd = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/htscctd/htscctd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htscctd = show_inactive_status_htscctd;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblhtscctd",
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
						def: "htscctd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htscctd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htscctd.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Pengaju <sup class='text-danger'>*<sup>",
						name: "htscctd.id_hemxxmh_pengaju",
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
										id_hemxxmh_old: id_hemxxmh_pengaju_old,
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
					}, 	{
						label: "htscctd.id_htsxxmh_pengaju",
						name: "htscctd.id_htsxxmh_pengaju",
						type: "hidden"
					}, 	{
						label: "Shift Pengaju <sup class='text-danger'>*<sup>",
						name: "htsxxmh_pengaju_data",
						type: "readonly"
					}, 	{
						label: "Pengganti <sup class='text-danger'>*<sup>",
						name: "htscctd.id_hemxxmh_pengganti",
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
										id_hemxxmh_old: id_hemxxmh_pengganti_old,
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
					}, 	{
						label: "htscctd.id_htsxxmh_pengganti",
						name: "htscctd.id_htsxxmh_pengganti",
						type: "hidden"
					}, 	{
						label: "Shift Pengganti <sup class='text-danger'>*<sup>",
						name: "htsxxmh_pengganti_data",
						type: "readonly"
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htscctd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtscctd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtscctd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtscctd.rows().deselect();
				}
			});

			edthtscctd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtscctd.dependent( 'htscctd.tanggal', function ( val, data, callback ) {
				tanggal = edthtscctd.field('htscctd.tanggal').val();
				id_hemxxmh_pengaju = edthtscctd.field('htscctd.id_hemxxmh_pengaju').val();
				id_hemxxmh_pengganti = edthtscctd.field('htscctd.id_hemxxmh_pengganti').val();
				if(tanggal != '' && id_hemxxmh_pengaju > 0){
					get_htsxxmh_pengaju();
				}
				if(tanggal != '' && id_hemxxmh_pengganti > 0){
					get_htsxxmh_pengganti();
				}
				return {}
			}, {event: 'keyup change'});

			edthtscctd.dependent( 'htscctd.id_hemxxmh_pengaju', function ( val, data, callback ) {
				tanggal = edthtscctd.field('htscctd.tanggal').val();
				id_hemxxmh_pengaju = edthtscctd.field('htscctd.id_hemxxmh_pengaju').val();
				if(tanggal != '' && id_hemxxmh_pengaju > 0){
					get_htsxxmh_pengaju();
				}
				return {}
			}, {event: 'keyup change'});

			edthtscctd.dependent( 'htscctd.id_hemxxmh_pengganti', function ( val, data, callback ) {
				tanggal = edthtscctd.field('htscctd.tanggal').val();
				id_hemxxmh_pengganti = edthtscctd.field('htscctd.id_hemxxmh_pengganti').val();
				if(tanggal != '' && id_hemxxmh_pengganti > 0){
					get_htsxxmh_pengganti();
				}
				return {}
			}, {event: 'keyup change'});

            edthtscctd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi htscctd.tanggal
					if ( ! edthtscctd.field('htscctd.tanggal').isMultiValue() ) {
						tanggal = edthtscctd.field('htscctd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtscctd.field('htscctd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htscctd.tanggal

					// BEGIN of validasi htscctd.id_hemxxmh_pengaju
					if ( ! edthtscctd.field('htscctd.id_hemxxmh_pengaju').isMultiValue() ) {
						id_hemxxmh_pengaju = edthtscctd.field('htscctd.id_hemxxmh_pengaju').val();
						if(!id_hemxxmh_pengaju || id_hemxxmh_pengaju == ''){
							edthtscctd.field('htscctd.id_hemxxmh_pengaju').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htscctd.id_hemxxmh_pengaju

					// BEGIN of validasi htscctd.id_hemxxmh_pengganti
					if ( ! edthtscctd.field('htscctd.id_hemxxmh_pengganti').isMultiValue() ) {
						id_hemxxmh_pengganti = edthtscctd.field('htscctd.id_hemxxmh_pengganti').val();
						if(!id_hemxxmh_pengganti || id_hemxxmh_pengganti == ''){
							edthtscctd.field('htscctd.id_hemxxmh_pengganti').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htscctd.id_hemxxmh_pengganti

					// BEGIN of validasi htscctd.keterangan
					if ( ! edthtscctd.field('htscctd.keterangan').isMultiValue() ) {
						keterangan = edthtscctd.field('htscctd.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtscctd.field('htscctd.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htscctd.keterangan

					// BEGIN validasi pengaju dan pengganti tidak boleh sama
					if(id_hemxxmh_pengaju == id_hemxxmh_pengganti) {
						edthtscctd.field('htscctd.id_hemxxmh_pengaju').error( 'Pengaju dan Pengganti Tidak Boleh Sama!' );
						edthtscctd.field('htscctd.id_hemxxmh_pengganti').error( 'Pengaju dan Pengganti Tidak Boleh Sama!' );
					}
					// END validasi pengaju dan pengganti tidak boleh sama
				}
				
				if ( edthtscctd.inError() ) {
					return false;
				}
			});
			
			edthtscctd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtscctd.field('finish_on').val(finish_on);
			});

			//start datatables
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			tblhtscctd = $('#tblhtscctd').DataTable( {
				ajax: {
					url: "../../models/htscctd/htscctd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htscctd = show_inactive_status_htscctd;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "htscctd.id",visible:false },
					{ data: "htscctd.kode" },
					{ data: "htscctd.tanggal" },
					{ data: "hemxxmh_pengaju" },
					{ data: "hemxxmh_pengganti" },
					{ data: "htscctd.keterangan" },
					{ 
						data: "htscctd.is_approve" ,
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
						$id_table    = 'id_htscctd';
						$table       = 'tblhtscctd';
						$edt         = 'edthtscctd';
						$show_status = '_htscctd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htscctd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtscctd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtscctd);
			} );
			
			tblhtscctd.on( 'select', function( e, dt, type, indexes ) {
				htscctd_data    = tblhtscctd.row( { selected: true } ).data().htscctd;
				id_htscctd      = htscctd_data.id;
				id_transaksi_h = id_htscctd; // dipakai untuk general
				is_approve     = htscctd_data.is_approve;
				is_nextprocess = htscctd_data.is_nextprocess;
				is_jurnal      = htscctd_data.is_jurnal;
				is_active      = htscctd_data.is_active;

				id_hemxxmh_pengaju_old    = htscctd_data.id_hemxxmh_pengaju;
				id_hemxxmh_pengganti_old  = htscctd_data.id_hemxxmh_pengganti;

				// atur hak akses
				CekSelectHeaderH(tblhtscctd);
			} );

			tblhtscctd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htscctd = 0;

				id_hemxxmh_pengaju_old    = 0;
				id_hemxxmh_pengganti_old  = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtscctd);
			} );

			$("#frmhtscctd").submit(function(e) {
					e.preventDefault();
				}).validate({
					rules: {
						
					},
					submitHandler: function(frmhtscctd) {
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

						tblhtscctd.ajax.reload(function ( json ) {
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
