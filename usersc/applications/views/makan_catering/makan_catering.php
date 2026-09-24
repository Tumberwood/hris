<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'makan_catering';
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
                    <table id="tblmakan_catering" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Catering</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
<table id="tblcetak_makan_d"
       class="table table-striped table-bordered table-hover nowrap"
       width="100%">

    <thead>
        <!-- HEADER GROUP -->
        <tr>
            <th rowspan="2" class="text-center align-middle">
                Tanggal
            </th>

            <th colspan="2" class="text-center">
                Shift 1
            </th>

            <th colspan="2" class="text-center">
                Shift 2
            </th>

            <th colspan="2" class="text-center">
                Shift 3
            </th>

            <th colspan="2" class="text-center">
                Total
            </th>

            <th rowspan="2" class="text-center align-middle">
                Grand Total
            </th>
        </tr>

        <!-- HEADER DETAIL -->
        <tr>
            <th class="text-center">
                Karyawan
            </th>

            <th class="text-center">
                Staff
            </th>

            <th class="text-center">
                Karyawan
            </th>

            <th class="text-center">
                Staff
            </th>

            <th class="text-center">
                Karyawan
            </th>

            <th class="text-center">
                Staff
            </th>

            <th class="text-center">
                Karyawan
            </th>

            <th class="text-center">
                Staff
            </th>
        </tr>
    </thead>

    <tbody>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="10" class="font-weight-bold pt-3">
                Footer
            </td>
        </tr>

        <tr>
            <td colspan="10">
                Jumlah makan Karyawan :
                <span id="footer_jumlah_karyawan">0</span>
                x harga =
                <span id="footer_harga_karyawan">0</span>
            </td>
        </tr>

        <tr>
            <td colspan="10">
                Jumlah Makan Staff :
                <span id="footer_jumlah_staff">0</span>
                x harga =
                <span id="footer_harga_staff">0</span>
            </td>
        </tr>

        <tr>
            <td colspan="10" class="font-weight-bold">
                Total :
                <span id="footer_total">0</span>
            </td>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/makan_catering/fn/makan_catering_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmakan_catering, tblmakan_catering, show_inactive_status_makan_catering = 0, id_makan_catering;
        var edtcetak_makan_d, tblcetak_makan_d, show_inactive_status_cetak_makan_d = 0, id_cetak_makan_d;
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
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			//start datatables editor
			edtmakan_catering = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/makan_catering/makan_catering.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_catering = show_inactive_status_makan_catering;
					}
				},
				table: "#tblmakan_catering",
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
						def: "makan_catering",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_catering.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Catering <sup class='text-danger'>*<sup>",
						name: "makan_catering.nama",
					},
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "makan_catering.tanggal_awal",
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
						name: "makan_catering.tanggal_akhir",
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
						name: "makan_catering.keterangan",
						type: "textarea"
					},
				]
			} );
			
			edtmakan_catering.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_catering.rows().deselect();
				}
			});

            edtmakan_catering.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtmakan_catering.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					nama = edtmakan_catering.field('makan_catering.nama').val();
					if(!nama || nama == ''){
						edtmakan_catering.field('makan_catering.nama').error( 'Wajib diisi!' );
					}
					tanggal_awal = edtmakan_catering.field('makan_catering.tanggal_awal').val();
					if(!tanggal_awal || tanggal_awal == ''){
						edtmakan_catering.field('makan_catering.tanggal_awal').error( 'Wajib diisi!' );
					}

					tanggal_akhir = edtmakan_catering.field('makan_catering.tanggal_akhir').val();
					if(!tanggal_akhir || tanggal_akhir == ''){
						edtmakan_catering.field('makan_catering.tanggal_akhir').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtmakan_catering.inError() ) {
					return false;
				}
			});

			edtmakan_catering.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering.field('finish_on').val(finish_on);
			});
			
			edtmakan_catering.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblmakan_catering.rows().deselect();
				tblmakan_catering.ajax.reload(null, false);
			} );
			
			//start datatables
			tblmakan_catering = $('#tblmakan_catering').DataTable( {
				ajax: {
					url: "../../models/makan_catering/makan_catering.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_catering = show_inactive_status_makan_catering;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "makan_catering.id",visible:false },
					{ data: "makan_catering.nama" },
					{ data: "makan_catering.tanggal_awal" },
					{ data: "makan_catering.tanggal_akhir" },
					{ data: "makan_catering.keterangan" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_catering';
						$table       = 'tblmakan_catering';
						$edt         = 'edtmakan_catering';
						$show_status = '_makan_catering';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('makan_catering_print.php?id_makan_catering=' + id_makan_catering, 'makan_catering');
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_catering.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblmakan_catering.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekInitHeaderHD(tblmakan_catering, tbl_details);
				tblmakan_catering.button( 'btnPrint:name' ).disable();
			} );
			
			tblmakan_catering.on( 'select', function( e, dt, type, indexes ) {
				data_makan_catering = tblmakan_catering.row( { selected: true } ).data().makan_catering;
				id_makan_catering  = data_makan_catering.id;
				id_transaksi_h   = id_makan_catering; // dipakai untuk general
				is_approve       = data_makan_catering.is_approve;
				is_nextprocess   = data_makan_catering.is_nextprocess;
				is_jurnal        = data_makan_catering.is_jurnal;
				is_active        = data_makan_catering.is_active;
				
				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekSelectHeaderHD(tblmakan_catering, tbl_details);
				tblmakan_catering.button( 'btnPrint:name' ).enable();

			} );
			
			tblmakan_catering.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_catering = '';

				// atur hak akses
				tbl_details = [tblcetak_makan_d];
				CekDeselectHeaderHD(tblmakan_catering, tbl_details);
				tblmakan_catering.button( 'btnPrint:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtcetak_makan_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/makan_catering/cetak_makan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_cetak_makan_d = show_inactive_status_cetak_makan_d;
						d.id_makan_catering = id_makan_catering;
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
						label: "id_makan_catering",
						name: "cetak_makan_d.id_makan_catering",
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
				edtcetak_makan_d.field('cetak_makan_d.id_makan_catering').val(id_makan_catering);
				
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
				tblmakan_catering.rows().deselect();
				tblmakan_catering.ajax.reload(null, false);
			} );
			
			//start datatables
			tblcetak_makan_d = $('#tblcetak_makan_d').DataTable( {
				ajax: {
					url: "../../models/makan_catering/cetak_makan_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_cetak_makan_d = show_inactive_status_cetak_makan_d;
						d.id_makan_catering = id_makan_catering;
					},
					dataSrc: 'data.lembur'
				},
				order: [[ 0, "asc" ]],
				columns: [
					{
						data: "tanggal",
						className: "text-center"
					},

					// =========================
					// SHIFT 1
					// =========================
					{
						data: "shift1_kary",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},
					{
						data: "shift1_staff",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},

					// =========================
					// SHIFT 2
					// =========================
					{
						data: "shift2_kary",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},
					{
						data: "shift2_staff",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},

					// =========================
					// SHIFT 3
					// =========================
					{
						data: "shift3_kary",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},
					{
						data: "shift3_staff",
						className: "text-center",
						render: function(data) {
							return data ?? 0;
						}
					},

					// =========================
					// TOTAL
					// =========================
					{
						data: "total_kary",
						className: "text-center font-weight-bold",
						render: function(data) {
							return data ?? 0;
						}
					},
					{
						data: "total_staff",
						className: "text-center font-weight-bold",
						render: function(data) {
							return data ?? 0;
						}
					},

					// =========================
					// GRAND TOTAL
					// =========================
					{
						data: "grand_total",
						className: "text-center font-weight-bold",
						render: function(data) {
							return data ?? 0;
						}
					}
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
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
				}
			} );

			tblcetak_makan_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblmakan_catering, tblcetak_makan_d, 'cetak_makan_d' );
				CekDrawDetailHDFinal(tblmakan_catering);
			} );

			tblcetak_makan_d.on( 'select', function( e, dt, type, indexes ) {
				data_cetak_makan_d = tblcetak_makan_d.row( { selected: true } ).data().cetak_makan_d;
				id_cetak_makan_d   = data_cetak_makan_d.id;
				id_transaksi_d    = id_cetak_makan_d; // dipakai untuk general
				is_active_d       = data_cetak_makan_d.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblmakan_catering, tblcetak_makan_d );
			} );

			tblcetak_makan_d.on( 'deselect', function() {
				id_cetak_makan_d = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblmakan_catering, tblcetak_makan_d );
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

					tblmakan_catering.rows().deselect();
					tblcetak_makan_d.rows().deselect();
					tblmakan_catering.ajax.reload(function ( json ) {
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
