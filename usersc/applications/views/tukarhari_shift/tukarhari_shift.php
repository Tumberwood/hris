<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'tukarhari_shift';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'tukarhari_shift_pegawai';
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
    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tbltukarhari_shift" class="table table-striped table-bordered table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
								<th>Tanggal Terpilih</th>
								<th>Tanggal Pengganti</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tbltukarhari_shift_pegawai" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_tukarhari_shift</th>
								<th>NIK</th>
								<th>Nama</th>
								<th>Jabatan</th>
								<th>Sub Tipe</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/tukarhari_shift/fn/tukarhari_shift_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edttukarhari_shift, tbltukarhari_shift, show_inactive_status_tukarhari_shift = 0, id_tukarhari_shift;
        var edttukarhari_shift_pegawai, tbltukarhari_shift_pegawai, show_inactive_status_tukarhari_shift_pegawai = 0, id_tukarhari_shift_pegawai;
		// ------------- end of default variable
		var is_need_approval = 1;
		var is_need_generate_kode = 1;
		var id_hemxxmh_old = 0;
		
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
			edttukarhari_shift = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/tukarhari_shift/tukarhari_shift.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_tukarhari_shift = show_inactive_status_tukarhari_shift;
					}
				},
				table: "#tbltukarhari_shift",
				fields: [  
					{
						// untuk kode
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						// untuk kode
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						// untuk kode
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
						def: "tukarhari_shift",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "tukarhari_shift.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal Terpilih <sup class='text-danger'>*<sup>",
						name: "tukarhari_shift.tanggal_terpilih",
						type: "datetime",
						opts: {
							minDate: new Date('1900-01-01'),
						},
						format: 'DD MMM YYYY',
					}, 	{
						label: "Tukar Dengan Tanggal<sup class='text-danger'>*<sup>",
						name: "tukarhari_shift.tanggal_pengganti",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01')
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "tukarhari_shift.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edttukarhari_shift.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttukarhari_shift.field('start_on').val(start_on);

				if(action == 'create'){
					tbltukarhari_shift.rows().deselect();
					edttukarhari_shift.field('kategori_dokumen').val('');
					edttukarhari_shift.field('kategori_dokumen_value').val('');
					edttukarhari_shift.field('field_tanggal').val('created_on');
				}
			});

            edttukarhari_shift.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edttukarhari_shift.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi tukarhari_shift.tanggal_pengganti 
					tanggal_pengganti = edttukarhari_shift.field('tukarhari_shift.tanggal_pengganti').val();
					if(!tanggal_pengganti || tanggal_pengganti == ''){
						edttukarhari_shift.field('tukarhari_shift.tanggal_pengganti').error( 'Wajib Diisi!' );
					}
					
					// BEGIN of cek unik tukarhari_shift.tanggal_pengganti 
					if(action == 'create'){
						id_tukarhari_shift = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'tukarhari_shift',
							nama_field: 'tanggal_pengganti',
							nama_field_value: '"'+tanggal_pengganti+'"',
							id_transaksi: id_tukarhari_shift
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edttukarhari_shift.field('tukarhari_shift.tanggal_pengganti').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik tukarhari_shift.tanggal_pengganti 
					// END of validasi tukarhari_shift.tanggal_pengganti
					
					// BEGIN of validasi tukarhari_shift.tanggal_terpilih 
					tanggal_terpilih = edttukarhari_shift.field('tukarhari_shift.tanggal_terpilih').val();
					if(!tanggal_terpilih || tanggal_terpilih == ''){
						edttukarhari_shift.field('tukarhari_shift.tanggal_terpilih').error( 'Wajib Diisi!' );
					}
					
					// BEGIN of cek unik tukarhari_shift.tanggal_terpilih 
					if(action == 'create'){
						id_tukarhari_shift = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'tukarhari_shift',
							nama_field: 'tanggal_terpilih',
							nama_field_value: '"'+tanggal_terpilih+'"',
							id_transaksi: id_tukarhari_shift
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edttukarhari_shift.field('tukarhari_shift.tanggal_terpilih').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik tukarhari_shift.tanggal_terpilih 
					// END of validasi tukarhari_shift.tanggal_terpilih

					// BEGIN of validasi tukarhari_shift.keterangan
					keterangan = edttukarhari_shift.field('tukarhari_shift.keterangan').val();
					if ( ! edttukarhari_shift.field('tukarhari_shift.keterangan').isMultiValue() ) {
						if(!keterangan || keterangan == ''){
							edttukarhari_shift.field('tukarhari_shift.keterangan').error( 'Wajib Diisi!' );
						}
					}
					// END of validasi tukarhari_shift.keterangan
				}
				
				if ( edttukarhari_shift.inError() ) {
					return false;
				}
			});

			edttukarhari_shift.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttukarhari_shift.field('finish_on').val(finish_on);
			});
			
			edttukarhari_shift.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				tbltukarhari_shift.rows().deselect();
				tbltukarhari_shift.ajax.reload(null, false);
			} );
			
			edttukarhari_shift.on( 'close', function (e, json, data, action, xhr) {
				edttukarhari_shift.enable();
			} );
			
			//start datatables
			tbltukarhari_shift = $('#tbltukarhari_shift').DataTable( {
				ajax: {
					url: "../../models/tukarhari_shift/tukarhari_shift.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_tukarhari_shift = show_inactive_status_tukarhari_shift;
					}
				},
				order: [[ 1, "desc" ]],
				responsive: false,
				columns: [
					{ data: "tukarhari_shift.id",visible:false },
					{ data: "tukarhari_shift.kode" },
					{ data: "tukarhari_shift.tanggal_terpilih" },
					{ data: "tukarhari_shift.tanggal_pengganti" },
					{ data: "tukarhari_shift.keterangan" },
					{ 
						data: "tukarhari_shift.is_approve",
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
						$id_table    = 'id_tukarhari_shift';
						$table       = 'tbltukarhari_shift';
						$edt         = 'edttukarhari_shift';
						$show_status = '_tukarhari_shift';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'view', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve', 'cancel_approve', 'void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.tukarhari_shift.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tbltukarhari_shift.on( 'init', function () {
				// atur hak akses
				tbl_details = [tbltukarhari_shift_pegawai];
				CekInitHeaderHD(tbltukarhari_shift, tbl_details);
			} );
			
			tbltukarhari_shift.on( 'select', function( e, dt, type, indexes ) {
				data_tukarhari_shift = tbltukarhari_shift.row( { selected: true } ).data().tukarhari_shift;
				id_tukarhari_shift  = data_tukarhari_shift.id;
				id_transaksi_h   = id_tukarhari_shift; // dipakai untuk general
				is_approve       = data_tukarhari_shift.is_approve;
				is_nextprocess   = data_tukarhari_shift.is_nextprocess;
				is_jurnal        = data_tukarhari_shift.is_jurnal;
				is_active        = data_tukarhari_shift.is_active;
				
				// atur hak akses
				tbl_details = [tbltukarhari_shift_pegawai];
				CekSelectHeaderHD(tbltukarhari_shift, tbl_details);

				if(is_approve == 1 ) {
					tbltukarhari_shift_pegawai.button('btnRemove:name').disable();
				} else {
					tbltukarhari_shift_pegawai.button('btnRemove:name').enable();
				}
			} );
			
			tbltukarhari_shift.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_tukarhari_shift = '';
				// atur hak akses
				tbl_details = [tbltukarhari_shift_pegawai];
				CekDeselectHeaderHD(tbltukarhari_shift, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edttukarhari_shift_pegawai = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/tukarhari_shift/tukarhari_shift_pegawai.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tukarhari_shift_pegawai = show_inactive_status_tukarhari_shift_pegawai;
						d.id_tukarhari_shift = id_tukarhari_shift;
					}
				},
				table: "#tbltukarhari_shift_pegawai",
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
						def: "tukarhari_shift_pegawai",
						type: "hidden"
					},	{
						label: "id_tukarhari_shift",
						name: "tukarhari_shift_pegawai.id_tukarhari_shift",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "tukarhari_shift_pegawai.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "tukarhari_shift_pegawai.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/tukarhari_shift/hemxxmh_fn_tukar_hari.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
										id_tukarhari_shift: id_tukarhari_shift,
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
				]
			} );
			
			edttukarhari_shift_pegawai.on( 'preOpen', function( e, mode, action ) {
				edttukarhari_shift_pegawai.field('tukarhari_shift_pegawai.id_tukarhari_shift').val(id_tukarhari_shift);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttukarhari_shift_pegawai.field('start_on').val(start_on);
				
				if(action == 'create'){
					tbltukarhari_shift_pegawai.rows().deselect();
				}
			});

            edttukarhari_shift_pegawai.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edttukarhari_shift_pegawai.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					id_hemxxmh = edttukarhari_shift_pegawai.field('tukarhari_shift_pegawai.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edttukarhari_shift_pegawai.field('tukarhari_shift_pegawai.id_hemxxmh').error( 'Wajib Diisi!' );
					}
				}
				
				if ( edttukarhari_shift_pegawai.inError() ) {
					return false;
				}
			});

			edttukarhari_shift_pegawai.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttukarhari_shift_pegawai.field('finish_on').val(finish_on);
			});

			
			edttukarhari_shift_pegawai.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tbltukarhari_shift_pegawai = $('#tbltukarhari_shift_pegawai').DataTable( {
				ajax: {
					url: "../../models/tukarhari_shift/tukarhari_shift_pegawai.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tukarhari_shift_pegawai = show_inactive_status_tukarhari_shift_pegawai;
						d.id_tukarhari_shift = id_tukarhari_shift;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "tukarhari_shift_pegawai.id",visible:false },
					{ data: "tukarhari_shift_pegawai.id_tukarhari_shift",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmd.nama" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_tukarhari_shift_pegawai';
						$table       = 'tbltukarhari_shift_pegawai';
						$edt         = 'edttukarhari_shift_pegawai';
						$show_status = '_tukarhari_shift_pegawai';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.tukarhari_shift_pegawai.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
			} );
			
			tbltukarhari_shift_pegawai.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tbltukarhari_shift, tbltukarhari_shift_pegawai, 'tukarhari_shift_pegawai' );
				CekDrawDetailHDFinal(tbltukarhari_shift);
			} );

			tbltukarhari_shift_pegawai.on( 'select', function( e, dt, type, indexes ) {
				data_tukarhari_shift_pegawai = tbltukarhari_shift_pegawai.row( { selected: true } ).data().tukarhari_shift_pegawai;
				id_tukarhari_shift_pegawai   = data_tukarhari_shift_pegawai.id;
				id_transaksi_d    = id_tukarhari_shift_pegawai; // dipakai untuk general
				is_active_d       = data_tukarhari_shift_pegawai.is_active;
				id_hemxxmh_old       = data_tukarhari_shift_pegawai.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tbltukarhari_shift, tbltukarhari_shift_pegawai );
				
				if(is_approve == 1 ) {
					tbltukarhari_shift_pegawai.button('btnRemove:name').disable();
				} else {
					tbltukarhari_shift_pegawai.button('btnRemove:name').enable();
				}
			} );

			tbltukarhari_shift_pegawai.on( 'deselect', function() {
				id_tukarhari_shift_pegawai = '';
				is_active_d = 0;
				id_hemxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbltukarhari_shift, tbltukarhari_shift_pegawai );
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

					tbltukarhari_shift.rows().deselect();
					tbltukarhari_shift_pegawai.rows().deselect();
					tbltukarhari_shift.ajax.reload(function ( json ) {
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
