<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'cetak_makan_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'cetak_makan_d';
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
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblcetak_makan_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Jumlah Orang</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
                    <table id="tblcetak_makan_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_cetak_makan_h</th>
                                <th>Nama</th>
                                <th>Perusahaan</th>
                                <th>PIC Tamu PMI</th>
                                <th>Catatan</th>
                                <th>Konfirmasi Kantin</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/cetak_makan_h/fn/cetak_makan_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtcetak_makan_h, tblcetak_makan_h, show_inactive_status_cetak_makan_h = 0, id_cetak_makan_h;
        var edtcetak_makan_d, tblcetak_makan_d, show_inactive_status_cetak_makan_d = 0, id_cetak_makan_d;
		// ------------- end of default variable
		is_need_approval = 1;
		
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
			edtcetak_makan_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/cetak_makan_h/cetak_makan_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_cetak_makan_h = show_inactive_status_cetak_makan_h;
					}
				},
				table: "#tblcetak_makan_h",
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
						def: "cetak_makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "cetak_makan_h.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "cetak_makan_h.tanggal",
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
						label: "Jumlah Orang",
						name: "cetak_makan_h.nama",
						type: "readonly"
					}, 	{
						label: "Keterangan",
						name: "cetak_makan_h.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edtcetak_makan_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtcetak_makan_h.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblcetak_makan_h.rows().deselect();
				}
			});

            edtcetak_makan_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtcetak_makan_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi cetak_makan_h.tanggal 
					tanggal = edtcetak_makan_h.field('cetak_makan_h.tanggal').val();
					if(!tanggal || tanggal == ''){
						edtcetak_makan_h.field('cetak_makan_h.tanggal').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtcetak_makan_h.inError() ) {
					return false;
				}
			});

			edtcetak_makan_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtcetak_makan_h.field('finish_on').val(finish_on);
			});
			
			edtcetak_makan_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblcetak_makan_h.rows().deselect();
				tblcetak_makan_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblcetak_makan_h = $('#tblcetak_makan_h').DataTable( {
				ajax: {
					url: "../../models/cetak_makan_h/cetak_makan_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_cetak_makan_h = show_inactive_status_cetak_makan_h;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "cetak_makan_h.id",visible:false },
					{ data: "cetak_makan_h.tanggal" },
					{ data: "cetak_makan_h.jumlah_orang" },
					{ data: "cetak_makan_h.keterangan" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_cetak_makan_h';
						$table       = 'tblcetak_makan_h';
						$edt         = 'edtcetak_makan_h';
						$show_status = '_cetak_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('cetak_makan_h_print.php?id_cetak_makan_h=' + id_cetak_makan_h, 'cetak_makan_h');
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.cetak_makan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblcetak_makan_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekInitHeaderHD(tblcetak_makan_h, tbl_details);
				tblcetak_makan_h.button( 'btnPrint:name' ).disable();
			} );
			
			tblcetak_makan_h.on( 'select', function( e, dt, type, indexes ) {
				data_cetak_makan_h = tblcetak_makan_h.row( { selected: true } ).data().cetak_makan_h;
				id_cetak_makan_h  = data_cetak_makan_h.id;
				id_transaksi_h   = id_cetak_makan_h; // dipakai untuk general
				is_approve       = data_cetak_makan_h.is_approve;
				is_nextprocess   = data_cetak_makan_h.is_nextprocess;
				is_jurnal        = data_cetak_makan_h.is_jurnal;
				is_active        = data_cetak_makan_h.is_active;
				
				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekSelectHeaderHD(tblcetak_makan_h, tbl_details);
				tblcetak_makan_h.button( 'btnPrint:name' ).enable();

			} );
			
			tblcetak_makan_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_cetak_makan_h = '';

				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekDeselectHeaderHD(tblcetak_makan_h, tbl_details);
				tblcetak_makan_h.button( 'btnPrint:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtcetak_makan_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/cetak_makan_h/cetak_makan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_cetak_makan_d = show_inactive_status_cetak_makan_d;
						d.id_cetak_makan_h = id_cetak_makan_h;
					}
				},
				table: "#tblcetak_makan_d",
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
						def: "cetak_makan_d",
						type: "hidden"
					},	{
						label: "id_cetak_makan_h",
						name: "cetak_makan_d.id_cetak_makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "cetak_makan_d.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "cetak_makan_d.nama"
					}, 	
					{
						label: "Perusahaan <sup class='text-danger'>*<sup>",
						name: "cetak_makan_d.perusahaan"
					}, 	
					{
						label: "PIC Tamu PMI <sup class='text-danger'>*<sup>",
						name: "cetak_makan_d.pic_tamu"
					}, 
					{
						label: "Catatan",
						name: "cetak_makan_d.keterangan",
						type: "textarea"
					},
					{
						label: "Konfirmasi Kantin",
						name: "cetak_makan_d.konfirmasi",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "", "value": "" },
							{ "label": "Makan", "value": "Makan" },
							{ "label": "Tidak Makan", "value": "Tidak Makan" },
						]
					},
				]
			} );
			
			edtcetak_makan_d.on( 'preOpen', function( e, mode, action ) {
				edtcetak_makan_d.field('cetak_makan_d.id_cetak_makan_h').val(id_cetak_makan_h);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtcetak_makan_d.field('start_on').val(start_on);

				if(action == 'create'){
					tblcetak_makan_d.rows().deselect();
				}
			});

            edtcetak_makan_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtcetak_makan_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					const requiredFields = [
						'nama',
						'perusahaan',
						'pic_tamu'
					];

					requiredFields.forEach(function(fieldName) {

						const value = edtcetak_makan_d
							.field(`cetak_makan_d.${fieldName}`)
							.val();

						if (!value || value === '') {
							edtcetak_makan_d
								.field(`cetak_makan_d.${fieldName}`)
								.error('Wajib diisi!');
						}

					});
				}
				
				if ( edtcetak_makan_d.inError() ) {
					return false;
				}
			});

			edtcetak_makan_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtcetak_makan_d.field('finish_on').val(finish_on);
			});
			
			edtcetak_makan_d.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblcetak_makan_h.rows().deselect();
				tblcetak_makan_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tblcetak_makan_d = $('#tblcetak_makan_d').DataTable( {
				ajax: {
					url: "../../models/cetak_makan_h/cetak_makan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_cetak_makan_d = show_inactive_status_cetak_makan_d;
						d.id_cetak_makan_h = id_cetak_makan_h;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "cetak_makan_d.id",visible:false },
					{ data: "cetak_makan_d.id_cetak_makan_h",visible:false },
					{ data: "cetak_makan_d.nama" },
					{ data: "cetak_makan_d.perusahaan" },
					{ data: "cetak_makan_d.pic_tamu" },
					{ data: "cetak_makan_d.keterangan" },
					{ data: "cetak_makan_d.konfirmasi" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_cetak_makan_d';
						$table       = 'tblcetak_makan_d';
						$edt         = 'edtcetak_makan_d';
						$show_status = '_cetak_makan_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.cetak_makan_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblcetak_makan_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblcetak_makan_h, tblcetak_makan_d, 'cetak_makan_d' );
				CekDrawDetailHDFinal(tblcetak_makan_h);
			} );

			tblcetak_makan_d.on( 'select', function( e, dt, type, indexes ) {
				data_cetak_makan_d = tblcetak_makan_d.row( { selected: true } ).data().cetak_makan_d;
				id_cetak_makan_d   = data_cetak_makan_d.id;
				id_transaksi_d    = id_cetak_makan_d; // dipakai untuk general
				is_active_d       = data_cetak_makan_d.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblcetak_makan_h, tblcetak_makan_d );
			} );

			tblcetak_makan_d.on( 'deselect', function() {
				id_cetak_makan_d = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblcetak_makan_h, tblcetak_makan_d );
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

					tblcetak_makan_h.rows().deselect();
					tblcetak_makan_d.rows().deselect();
					tblcetak_makan_h.ajax.reload(function ( json ) {
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
