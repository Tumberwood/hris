<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hpyxxth_cuti';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hpyemtd_cuti';
    $nama_tabels_d[1] = 'hpyemtd_cuti_kbm_reg';
    $nama_tabels_d[2] = 'hpyemtd_cuti_karyawan';
    $nama_tabels_d[3] = 'hpyemtd_cuti_kmj';
    $nama_tabels_d[4] = 'hpyemtd_cuti_freelance';
    $nama_tabels_d[5] = 'hpyemtd_cuti_kbm_tr';
    $nama_tabels_d[6] = 'hpyemtd_cuti_kontrak';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhpyxxth_cuti" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Tanggal Awal</th>
                                <th>Periode</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Generated On</th>
                            </tr>
                        </thead>
                    </table>
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhpyemtd_cuti"> All</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_kbm_reg"> KBM Reguler</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_kbm_tr"> KBM Pelatihan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_karyawan"> Tetap</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_kontrak"> Kontrak</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_kmj"> KMJ</a></li>
							<li id="tab_freelance"><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_cuti_freelance"> Freelance</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd_cuti" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="all_10"></th>
													<th id="all_11"></th>
													<th id="all_12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_kbm_reg" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_kbm_reg" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="kbm_reg10"></th>
													<th id="kbm_reg11"></th>
													<th id="kbm_reg12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_kbm_tr" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_kbm_tr" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="kbm_tr10"></th>
													<th id="kbm_tr11"></th>
													<th id="kbm_tr12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_karyawan" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="karyawan_10"></th>
													<th id="karyawan_11"></th>
													<th id="karyawan_12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_kontrak" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_kontrak" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="kontrak_10"></th>
													<th id="kontrak_11"></th>
													<th id="kontrak_12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_kmj" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_kmj" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="kmj_10"></th>
													<th id="kmj_11"></th>
													<th id="kmj_12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_cuti_freelance" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_cuti_freelance" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_cuti</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Sisa Cuti</th>
													<th>Nominal Pengali</th>
													<th>Kompensasi Cuti</th>
													
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th></th>
													<th>Total</th>
													<th></th>
													<th id="freelance_10"></th>
													<th id="freelance_11"></th>
													<th id="freelance_12"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
						</div>

					</div>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth_cuti/fn/hpyxxth_cuti_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth_cuti, tblhpyxxth_cuti, show_inactive_status_hpyxxth_cuti = 0, id_hpyxxth_cuti;
        var edthpyemtd_cuti_kbm_reg, tblhpyemtd_cuti_kbm_reg, show_inactive_status_hpyemtd_cuti = 0, id_hpyemtd_cuti;
		// ------------- end of default variable
		var id_heyxxmh_old = 0;
		

		$(document).ready(function() {
			$('.toggle-alert').click(function () {
				var $content = $(this).closest('.alert').find('.alert-content');
				$content.slideToggle(); // smooth hide/show
				var current = $(this).text();
				$(this).text(current === '−' ? '+' : '−');
			});
			
			//start datatables editor
			edthpyxxth_cuti = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyxxth_cuti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_cuti = show_inactive_status_hpyxxth_cuti;
					}
				},
				table: "#tblhpyxxth_cuti",
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
						def: "hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyxxth_cuti.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hpyxxth_cuti.tanggal_awal",
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
						name: "hpyxxth_cuti.tanggal_akhir",
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
						name: "hpyxxth_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyxxth_cuti.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_cuti.field('start_on').val(start_on);

				if(action == 'create'){
					tblhpyxxth_cuti.rows().deselect();
				}
			});

            edthpyxxth_cuti.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyxxth_cuti.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpyxxth_cuti.tanggal_awal
					if ( ! edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_cuti.tanggal_awal

					// BEGIN of validasi hpyxxth_cuti.tanggal_akhir
					if ( ! edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthpyxxth_cuti.field('hpyxxth_cuti.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_cuti.tanggal_akhir

				}
				
				if ( edthpyxxth_cuti.inError() ) {
					return false;
				}
			});

			edthpyxxth_cuti.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_cuti.field('finish_on').val(finish_on);
			});
			
			edthpyxxth_cuti.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyxxth_cuti = $('#tblhpyxxth_cuti').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyxxth_cuti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_cuti = show_inactive_status_hpyxxth_cuti;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hpyxxth_cuti.id",visible:false },
					{ data: "hpyxxth_cuti.tanggal_awal",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth_cuti.tanggal_awal + " - " + row.hpyxxth_cuti.tanggal_akhir;
					   	}
					},
					{ data: "heyxxmh.nama",visible:false },
					{ data: "hpyxxth_cuti.keterangan" },
					{ data: "hpyxxth_cuti.generated_on" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyxxth_cuti';
						$table       = 'tblhpyxxth_cuti';
						$edt         = 'edthpyxxth_cuti';
						$show_status = '_hpyxxth_cuti';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensiNew',
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
								url: "../../models/hpyxxth_cuti/hpyxxth_cuti_fn_gen_kompensasi_cuti.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hpyxxth_cuti		: id_hpyxxth_cuti,
									tanggal_awal	: tanggal_awal_select,
									tanggal_akhir	: tanggal_akhir_select,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhpyxxth_cuti.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth_cuti.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpyxxth_cuti.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhpyemtd_cuti, tblhpyemtd_cuti_kbm_reg, tblhpyemtd_cuti_karyawan, tblhpyemtd_cuti_kontrak, tblhpyemtd_cuti_kmj, tblhpyemtd_cuti_freelance, tblhpyemtd_cuti_kbm_tr];
				CekInitHeaderHD(tblhpyxxth_cuti, tbl_details);
				tblhpyxxth_cuti.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth_cuti.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth_cuti.button( 'btnGenPPh21:name' ).disable();
				
				tblhpyemtd_cuti_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd_cuti.button( 'btnPrintSingle:name' ).disable();
			} );
			
			tblhpyxxth_cuti.on( 'select', function( e, dt, type, indexes ) {
				data_hpyxxth_cuti = tblhpyxxth_cuti.row( { selected: true } ).data().hpyxxth_cuti;
				id_hpyxxth_cuti  = data_hpyxxth_cuti.id;
				id_transaksi_h   = id_hpyxxth_cuti; // dipakai untuk general
				is_approve       = data_hpyxxth_cuti.is_approve;
				is_nextprocess   = data_hpyxxth_cuti.is_nextprocess;
				is_jurnal        = data_hpyxxth_cuti.is_jurnal;
				is_active        = data_hpyxxth_cuti.is_active;
				tanggal_awal_select        = data_hpyxxth_cuti.tanggal_awal;
				tanggal_akhir_select        = data_hpyxxth_cuti.tanggal_akhir;
				id_heyxxmh_select        = data_hpyxxth_cuti.id_heyxxmh;

				id_heyxxmh_old = data_hpyxxth_cuti.id_heyxxmh;
				
				// atur hak akses
				tbl_details = [tblhpyemtd_cuti, tblhpyemtd_cuti_kbm_reg, tblhpyemtd_cuti_karyawan, tblhpyemtd_cuti_kontrak, tblhpyemtd_cuti_kmj, tblhpyemtd_cuti_freelance, tblhpyemtd_cuti_kbm_tr];
				CekSelectHeaderHD(tblhpyxxth_cuti, tbl_details);
				tblhpyxxth_cuti.button( 'btnGeneratePresensi:name' ).enable();
				tblhpyxxth_cuti.button( 'btnGeneratePresensiNew:name' ).enable();
				tblhpyxxth_cuti.button( 'btnGenPPh21:name' ).enable();
				tblhpyemtd_cuti_kbm_reg.button( 'btnPrint:name' ).enable();
				tblhpyemtd_cuti_karyawan.button( 'btnPrint:name' ).enable();
				tblhpyemtd_cuti_kontrak.button( 'btnPrint:name' ).enable();
				tblhpyemtd_cuti_kmj.button( 'btnPrint:name' ).enable();
				tblhpyemtd_cuti_freelance.button( 'btnPrint:name' ).enable();
			} );
			
			tblhpyxxth_cuti.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth_cuti = 0;
				id_heyxxmh_old = 0;
				id_heyxxmh = 0

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				tbl_details = [tblhpyemtd_cuti, tblhpyemtd_cuti_kbm_reg, tblhpyemtd_cuti_karyawan, tblhpyemtd_cuti_kontrak, tblhpyemtd_cuti_kmj, tblhpyemtd_cuti_freelance, tblhpyemtd_cuti_kbm_tr];
				CekDeselectHeaderHD(tblhpyxxth_cuti, tbl_details);
				tblhpyxxth_cuti.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth_cuti.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth_cuti.button( 'btnGenPPh21:name' ).disable();
				tblhpyemtd_cuti_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_cuti_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd_cuti.button( 'btnPrintSingle:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti.rows().deselect();
				}
			});

            edthpyemtd_cuti.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti = $('#tblhpyemtd_cuti').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				fixedColumns:   {
					left: 1
				},
				// scrollX: true,
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti';
						$edt         = 'edthpyemtd_cuti';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate 
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrintSingle',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji Per Pegawai',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_cuti_print_single.php?id_hpyemtd_cuti=' + id_hpyemtd_cuti, 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#all_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				id_hemxxmh       = data_hpyemtd_cuti.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti );
				tblhpyemtd_cuti.button( 'btnPrintSingle:name' ).enable();
			} );

			tblhpyemtd_cuti.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				id_hemxxmh = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti );
				tblhpyemtd_cuti.button( 'btnPrintSingle:name' ).disable();
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_kbm_reg = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_kbm_reg",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_kbm_reg.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_kbm_reg.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kbm_reg.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_kbm_reg.rows().deselect();
				}
			});

            edthpyemtd_cuti_kbm_reg.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_kbm_reg.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_kbm_reg.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_kbm_reg.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kbm_reg.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_kbm_reg.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_kbm_reg = $('#tblhpyemtd_cuti_kbm_reg').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_kbm_reg';
						$edt         = 'edthpyemtd_cuti_kbm_reg';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=1&id_hesxxmh=4', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_reg' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_kbm_reg.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_reg, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_kbm_reg.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_kbm_reg.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_reg );
			} );

			tblhpyemtd_cuti_kbm_reg.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_reg );
			} );

// --------- end _detail --------------- //		
			
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_kbm_tr = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_kbm_tr",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_kbm_tr.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_kbm_tr.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kbm_tr.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_kbm_tr.rows().deselect();
				}
			});

            edthpyemtd_cuti_kbm_tr.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_kbm_tr.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_kbm_tr.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_kbm_tr.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kbm_tr.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_kbm_tr.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_kbm_tr = $('#tblhpyemtd_cuti_kbm_tr').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_kbm_tr';
						$edt         = 'edthpyemtd_cuti_kbm_tr';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[5];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=1&id_hesxxmh=3', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_tr' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_kbm_tr.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_tr, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_kbm_tr.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_kbm_tr.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_tr );
			} );

			tblhpyemtd_cuti_kbm_tr.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kbm_tr );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_karyawan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_karyawan",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_karyawan.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_karyawan.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_karyawan.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_karyawan.rows().deselect();
				}
			});

            edthpyemtd_cuti_karyawan.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_karyawan.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_karyawan.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_karyawan.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_karyawan.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_karyawan.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_karyawan = $('#tblhpyemtd_cuti_karyawan').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_karyawan';
						$edt         = 'edthpyemtd_cuti_karyawan';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[2];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=3', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#karyawan_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_karyawan.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_karyawan, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_karyawan.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_karyawan.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_karyawan );
			} );

			tblhpyemtd_cuti_karyawan.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_karyawan );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_kontrak = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_kontrak",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_kontrak.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_kontrak.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kontrak.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_kontrak.rows().deselect();
				}
			});

            edthpyemtd_cuti_kontrak.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_kontrak.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_kontrak.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_kontrak.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kontrak.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_kontrak.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_kontrak = $('#tblhpyemtd_cuti_kontrak').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_kontrak';
						$edt         = 'edthpyemtd_cuti_kontrak';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[6];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=3', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kontrak_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_kontrak.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kontrak, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_kontrak.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_kontrak.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kontrak );
			} );

			tblhpyemtd_cuti_kontrak.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kontrak );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_kmj = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_kmj",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_kmj.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_kmj.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kmj.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_kmj.rows().deselect();
				}
			});

            edthpyemtd_cuti_kmj.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_kmj.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_kmj.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_kmj.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_kmj.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_kmj.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_kmj = $('#tblhpyemtd_cuti_kmj').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_kmj';
						$edt         = 'edthpyemtd_cuti_kmj';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[3];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=4', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kmj_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_kmj.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kmj, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_kmj.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_kmj.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kmj );
			} );

			tblhpyemtd_cuti_kmj.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_kmj );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_cuti_freelance = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				table: "#tblhpyemtd_cuti_freelance",
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
						def: "hpyemtd_cuti",
						type: "hidden"
					},	{
						label: "id_hpyxxth_cuti",
						name: "hpyemtd_cuti.id_hpyxxth_cuti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_cuti.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_cuti.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_cuti_freelance.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_cuti_freelance.field('hpyemtd_cuti.id_hpyxxth_cuti').val(id_hpyxxth_cuti);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_freelance.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_cuti_freelance.rows().deselect();
				}
			});

            edthpyemtd_cuti_freelance.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_cuti_freelance.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_cuti_freelance.inError() ) {
					return false;
				}
			});

			edthpyemtd_cuti_freelance.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_cuti_freelance.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_cuti_freelance.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_cuti_freelance = $('#tblhpyemtd_cuti_freelance').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cuti/hpyemtd_cuti_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_cuti = show_inactive_status_hpyemtd_cuti;
						d.id_hpyxxth_cuti = id_hpyxxth_cuti;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				
				columns: [
					{ data: "hpyemtd_cuti.id",visible:false },
					{ data: "hpyemtd_cuti.id_hpyxxth_cuti",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					
					{ 
						data: "hpyemtd_cuti.sisa_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cuti.kompensasi_cuti",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_cuti';
						$table       = 'tblhpyemtd_cuti_freelance';
						$edt         = 'edthpyemtd_cuti_freelance';
						$show_status = '_hpyemtd_cuti';
						$table_name  = $nama_tabels_d[4];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
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
							window.open('hpyxxth_cuti_print.php?id_hpyxxth_cuti=' + id_hpyxxth_cuti + '&id_heyxxmd=5', 'hpyxxth_cuti');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 12; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#freelance_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_cuti_freelance.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_freelance, 'hpyemtd_cuti' );
				CekDrawDetailHDFinal(tblhpyxxth_cuti);
			} );

			tblhpyemtd_cuti_freelance.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_cuti = tblhpyemtd_cuti_freelance.row( { selected: true } ).data().hpyemtd_cuti;
				id_hpyemtd_cuti   = data_hpyemtd_cuti.id;
				id_transaksi_d    = id_hpyemtd_cuti; // dipakai untuk general
				is_active_d       = data_hpyemtd_cuti.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_freelance );
			} );

			tblhpyemtd_cuti_freelance.on( 'deselect', function() {
				id_hpyemtd_cuti = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_cuti, tblhpyemtd_cuti_freelance );
			} );

// --------- end _detail --------------- //		
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
