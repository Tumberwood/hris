<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htssctd_tukarhari';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htssctd_tukarhari_pegawai';
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
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtssctd_tukarhari" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtssctd_tukarhari_pegawai" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_htssctd_tukarhari</th>
								<th>NIK</th>
								<th>Nama</th>
								<th>Department</th>
								<th>Jabatan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htssctd_tukarhari/fn/htssctd_tukarhari_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtssctd_tukarhari, tblhtssctd_tukarhari, show_inactive_status_htssctd_tukarhari = 0, id_htssctd_tukarhari;
        var edthtssctd_tukarhari_pegawai, tblhtssctd_tukarhari_pegawai, show_inactive_status_htssctd_tukarhari_pegawai = 0, id_htssctd_tukarhari_pegawai;
		// ------------- end of default variable
		var is_need_approval = 1;
		var is_need_generate_kode = 1;
		
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
			edthtssctd_tukarhari = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htssctd_tukarhari/htssctd_tukarhari.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htssctd_tukarhari = show_inactive_status_htssctd_tukarhari;
					}
				},
				table: "#tblhtssctd_tukarhari",
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
						def: "htssctd_tukarhari",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htssctd_tukarhari.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal Merah<sup class='text-danger'>*<sup>",
						name: "htssctd_tukarhari.tanggal_terpilih",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01')
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Tukar Dengan Tanggal<sup class='text-danger'>*<sup>",
						name: "htssctd_tukarhari.tanggal_pengganti",
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
						name: "htssctd_tukarhari.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtssctd_tukarhari.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd_tukarhari.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtssctd_tukarhari.rows().deselect();
					edthtssctd_tukarhari.field('kategori_dokumen').val('');
					edthtssctd_tukarhari.field('kategori_dokumen_value').val('');
					edthtssctd_tukarhari.field('field_tanggal').val('created_on');
				}
			});

            edthtssctd_tukarhari.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtssctd_tukarhari.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htssctd_tukarhari.tanggal_pengganti 
					tanggal_pengganti = edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_pengganti').val();
					if(!tanggal_pengganti || tanggal_pengganti == ''){
						edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_pengganti').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik htssctd_tukarhari.tanggal_pengganti 
					if(action == 'create'){
						id_htssctd_tukarhari = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'htssctd_tukarhari',
							nama_field: 'tanggal_pengganti',
							nama_field_value: '"'+tanggal_pengganti+'"',
							id_transaksi: id_htssctd_tukarhari
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_pengganti').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik htssctd_tukarhari.tanggal_pengganti 
					// END of validasi htssctd_tukarhari.tanggal_pengganti
					
					// BEGIN of validasi htssctd_tukarhari.tanggal_terpilih 
					tanggal_terpilih = edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_terpilih').val();
					if(!tanggal_terpilih || tanggal_terpilih == ''){
						edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_terpilih').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik htssctd_tukarhari.tanggal_terpilih 
					if(action == 'create'){
						id_htssctd_tukarhari = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'htssctd_tukarhari',
							nama_field: 'tanggal_terpilih',
							nama_field_value: '"'+tanggal_terpilih+'"',
							id_transaksi: id_htssctd_tukarhari
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtssctd_tukarhari.field('htssctd_tukarhari.tanggal_terpilih').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik htssctd_tukarhari.tanggal_terpilih 
					// END of validasi htssctd_tukarhari.tanggal_terpilih

					// BEGIN of validasi htssctd_tukarhari.keterangan
					keterangan = edthtssctd_tukarhari.field('htssctd_tukarhari.keterangan').val();
					if ( ! edthtssctd_tukarhari.field('htssctd_tukarhari.keterangan').isMultiValue() ) {
						if(!keterangan || keterangan == ''){
							edthtssctd_tukarhari.field('htssctd_tukarhari.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd_tukarhari.keterangan
				}
				
				if ( edthtssctd_tukarhari.inError() ) {
					return false;
				}
			});

			edthtssctd_tukarhari.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd_tukarhari.field('finish_on').val(finish_on);
			});
			
			edthtssctd_tukarhari.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				tblhtssctd_tukarhari.rows().deselect();
				tblhtssctd_tukarhari.ajax.reload(null, false);
			} );
			
			//start datatables
			tblhtssctd_tukarhari = $('#tblhtssctd_tukarhari').DataTable( {
				ajax: {
					url: "../../models/htssctd_tukarhari/htssctd_tukarhari.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htssctd_tukarhari = show_inactive_status_htssctd_tukarhari;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "htssctd_tukarhari.id",visible:false },
					{ data: "htssctd_tukarhari.kode" },
					{ data: "htssctd_tukarhari.tanggal_terpilih" },
					{ data: "htssctd_tukarhari.tanggal_pengganti" },
					{ data: "htssctd_tukarhari.keterangan" },
					{ 
						data: "htssctd_tukarhari.is_approve",
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
						$id_table    = 'id_htssctd_tukarhari';
						$table       = 'tblhtssctd_tukarhari';
						$edt         = 'edthtssctd_tukarhari';
						$show_status = '_htssctd_tukarhari';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htssctd_tukarhari.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtssctd_tukarhari.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtssctd_tukarhari_pegawai];
				CekInitHeaderHD(tblhtssctd_tukarhari, tbl_details);
			} );
			
			tblhtssctd_tukarhari.on( 'select', function( e, dt, type, indexes ) {
				data_htssctd_tukarhari = tblhtssctd_tukarhari.row( { selected: true } ).data().htssctd_tukarhari;
				id_htssctd_tukarhari  = data_htssctd_tukarhari.id;
				id_transaksi_h   = id_htssctd_tukarhari; // dipakai untuk general
				is_approve       = data_htssctd_tukarhari.is_approve;
				is_nextprocess   = data_htssctd_tukarhari.is_nextprocess;
				is_jurnal        = data_htssctd_tukarhari.is_jurnal;
				is_active        = data_htssctd_tukarhari.is_active;
				
				// atur hak akses
				tbl_details = [tblhtssctd_tukarhari_pegawai];
				CekSelectHeaderHD(tblhtssctd_tukarhari, tbl_details);

			} );
			
			tblhtssctd_tukarhari.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htssctd_tukarhari = '';

				// atur hak akses
				tbl_details = [tblhtssctd_tukarhari_pegawai];
				CekDeselectHeaderHD(tblhtssctd_tukarhari, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthtssctd_tukarhari_pegawai = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htssctd_tukarhari/htssctd_tukarhari_pegawai.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htssctd_tukarhari_pegawai = show_inactive_status_htssctd_tukarhari_pegawai;
						d.id_htssctd_tukarhari = id_htssctd_tukarhari;
					}
				},
				table: "#tblhtssctd_tukarhari_pegawai",
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
						def: "htssctd_tukarhari_pegawai",
						type: "hidden"
					},	{
						label: "id_htssctd_tukarhari",
						name: "htssctd_tukarhari_pegawai.id_htssctd_tukarhari",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htssctd_tukarhari_pegawai.is_active",
                        type: "hidden",
						def: 1
					}
				]
			} );
			
			edthtssctd_tukarhari_pegawai.on( 'preOpen', function( e, mode, action ) {
				edthtssctd_tukarhari_pegawai.field('htssctd_tukarhari_pegawai.id_htssctd_tukarhari').val(id_htssctd_tukarhari);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd_tukarhari_pegawai.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtssctd_tukarhari_pegawai.rows().deselect();
				}
			});

            edthtssctd_tukarhari_pegawai.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtssctd_tukarhari_pegawai.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edthtssctd_tukarhari_pegawai.inError() ) {
					return false;
				}
			});

			edthtssctd_tukarhari_pegawai.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd_tukarhari_pegawai.field('finish_on').val(finish_on);
			});

			
			edthtssctd_tukarhari_pegawai.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtssctd_tukarhari_pegawai = $('#tblhtssctd_tukarhari_pegawai').DataTable( {
				ajax: {
					url: "../../models/htssctd_tukarhari/htssctd_tukarhari_pegawai.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htssctd_tukarhari_pegawai = show_inactive_status_htssctd_tukarhari_pegawai;
						d.id_htssctd_tukarhari = id_htssctd_tukarhari;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "htssctd_tukarhari_pegawai.id",visible:false },
					{ data: "htssctd_tukarhari_pegawai.id_htssctd_tukarhari",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htssctd_tukarhari_pegawai';
						$table       = 'tblhtssctd_tukarhari_pegawai';
						$edt         = 'edthtssctd_tukarhari_pegawai';
						$show_status = '_htssctd_tukarhari_pegawai';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htssctd_tukarhari_pegawai.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtssctd_tukarhari_pegawai.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtssctd_tukarhari, tblhtssctd_tukarhari_pegawai, 'htssctd_tukarhari_pegawai' );
				CekDrawDetailHDFinal(tblhtssctd_tukarhari);
			} );

			tblhtssctd_tukarhari_pegawai.on( 'select', function( e, dt, type, indexes ) {
				data_htssctd_tukarhari_pegawai = tblhtssctd_tukarhari_pegawai.row( { selected: true } ).data().htssctd_tukarhari_pegawai;
				id_htssctd_tukarhari_pegawai   = data_htssctd_tukarhari_pegawai.id;
				id_transaksi_d    = id_htssctd_tukarhari_pegawai; // dipakai untuk general
				is_active_d       = data_htssctd_tukarhari_pegawai.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhtssctd_tukarhari, tblhtssctd_tukarhari_pegawai );
			} );

			tblhtssctd_tukarhari_pegawai.on( 'deselect', function() {
				id_htssctd_tukarhari_pegawai = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtssctd_tukarhari, tblhtssctd_tukarhari_pegawai );
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

					tblhtssctd_tukarhari.rows().deselect();
					tblhtssctd_tukarhari_pegawai.rows().deselect();
					tblhtssctd_tukarhari.ajax.reload(function ( json ) {
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
