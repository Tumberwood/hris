<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htlxxth';
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
				<form class="form-horizontal" id="frmhtlxxth">
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
				<div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtlxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Karyawan</th>
								<th>Jabatan</th>
								<th>Department</th>
                                <th>Jenis</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htlxxth/fn/htlxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtlxxth, tblhtlxxth, show_inactive_status_htlxxth = 0, id_htlxxth;
		// ------------- end of default variable
		var notifyprogress;
		is_need_approval = 1;
		id_heyxxmh = "<?php echo $_SESSION['str_arr_ha_heyxxmh']; ?>";
		console.log(id_heyxxmh);
		// is_need_generate_kode = 1;
		
		var id_hemxxmh_old = 0, id_htlxxmh_old = 0;
		
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
			edthtlxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htlxxth/htlxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxth = show_inactive_status_htlxxth;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblhtlxxth",
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
					},	{
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
						def: "htlxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htlxxth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htlxxth.id_hemxxmh",
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
										id_heyxxmh: id_heyxxmh,
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
					},	{
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "htlxxth.id_htlxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htlxxmh/htlxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htlxxmh_old: id_htlxxmh_old,
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
					},	{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "htlxxth.tanggal_awal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "htlxxth.tanggal_akhir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htlxxth.keterangan",
						type: "textarea"
					},	{
						label: "Lampiran",
						name: "htlxxth.id_files_lampiran",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthtlxxth.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada gambar'
					}
				]
			} );

			edthtlxxth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtlxxth.rows().deselect();

					edthtlxxth.field('kategori_dokumen').val('');
					edthtlxxth.field('kategori_dokumen_value').val('');
					edthtlxxth.field('field_tanggal').val('tanggal_awal');
				}
			});

			edthtlxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtlxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htlxxth.id_hemxxmh
					if ( ! edthtlxxth.field('htlxxth.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edthtlxxth.field('htlxxth.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtlxxth.field('htlxxth.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlxxth.id_hemxxmh

					// BEGIN of validasi htlxxth.id_htlxxmh
					if ( ! edthtlxxth.field('htlxxth.id_htlxxmh').isMultiValue() ) {
						id_htlxxmh = edthtlxxth.field('htlxxth.id_htlxxmh').val();
						if(!id_htlxxmh || id_htlxxmh == ''){
							edthtlxxth.field('htlxxth.id_htlxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlxxth.id_htlxxmh

					// BEGIN of validasi htlxxth.tanggal_awal
					if ( ! edthtlxxth.field('htlxxth.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthtlxxth.field('htlxxth.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthtlxxth.field('htlxxth.tanggal_awal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlxxth.tanggal_awal

					// BEGIN of validasi htlxxth.tanggal_akhir
					if ( ! edthtlxxth.field('htlxxth.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthtlxxth.field('htlxxth.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthtlxxth.field('htlxxth.tanggal_akhir').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlxxth.tanggal_akhir

					// BEGIN validasi tanggal awal tidak boleh lebih besar dari tanggal akhir
					tanggal_awal = moment(edthtlxxth.field('htlxxth.tanggal_awal').val()).format('YYYY-MM-DD');
					tanggal_akhir = moment(edthtlxxth.field('htlxxth.tanggal_akhir').val()).format('YYYY-MM-DD');

					if( tanggal_akhir < tanggal_awal ){
						edthtlxxth.field('htlxxth.tanggal_awal').error( 'Tanggal Awal harus lebih kecil dari Tanggal Akhir!' );
						edthtlxxth.field('htlxxth.tanggal_akhir').error( 'Tanggal Awal harus lebih kecil dari Tanggal Akhir' );
					}
					// END validasi tanggal awal tidak boleh lebih besar dari tanggal akhir

					// BEGIN of validasi htlxxth.keterangan
					if ( ! edthtlxxth.field('htlxxth.keterangan').isMultiValue() ) {
						keterangan = edthtlxxth.field('htlxxth.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtlxxth.field('htlxxth.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlxxth.keterangan

				}
				
				if ( edthtlxxth.inError() ) {
					return false;
				}
			});
			
			edthtlxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxth.field('finish_on').val(finish_on);
			});

			edthtlxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblhtlxxth.rows().deselect();
				tblhtlxxth.ajax.reload(null, false);
			});

			//start datatables
			tblhtlxxth = $('#tblhtlxxth').DataTable( {
				searchPanes:{
					layout: 'columns-4',
				},
				dom: 
					"<P>"+
					"<lf>"+
					"<B>"+
					"<rt>"+
					"<'row'<'col-sm-4'i><'col-sm-8'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true,
						},
						targets: [2,3,4,5,9]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,1,6,7,8]
					}
				],
				ajax: {
					url: "../../models/htlxxth/htlxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxth = show_inactive_status_htlxxth;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				scrollX: true,
				responsive: false,
				order: [[ 6, "desc" ],[ 1, "desc" ]],
				columns: [
					{ data: "htlxxth.id",visible:false },
					{ data: "htlxxth.kode" },
					{ data: "hemxxmh_data" },
					{ data: "hetxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "htlxxmh.nama" },
					{ data: "htlxxth.tanggal_awal" },
					{ data: "htlxxth.tanggal_akhir" },
					{ data: "htlxxth.keterangan" },
					{ 
						data: "htlxxth.is_approve" ,
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
						$id_table    = 'id_htlxxth';
						$table       = 'tblhtlxxth';
						$edt         = 'edthtlxxth';
						$show_status = '_htlxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htlxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblhtlxxth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtlxxth);
			} );
			
			tblhtlxxth.on( 'select', function( e, dt, type, indexes ) {
				htlxxth_data    = tblhtlxxth.row( { selected: true } ).data().htlxxth;
				id_htlxxth      = htlxxth_data.id;
				id_transaksi_h = id_htlxxth; // dipakai untuk general
				is_approve     = htlxxth_data.is_approve;
				is_nextprocess = htlxxth_data.is_nextprocess;
				is_jurnal      = htlxxth_data.is_jurnal;
				is_active      = htlxxth_data.is_active;
				
				id_hemxxmh_old  = htlxxth_data.id_hemxxmh;
				id_htlxxmh_old  = htlxxth_data.id_htlxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhtlxxth);
			} );

			tblhtlxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htlxxth = 0;
				id_hemxxmh_old = 0;
				id_htlxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtlxxth);
			} );

			tblhtlxxth.searchPanes.container().appendTo( '#searchPanes1' );

			$("#frmhtlxxth").submit(function(e) {
					e.preventDefault();
				}).validate({
					rules: {
						
					},
					submitHandler: function(frmhtlxxth) {
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

						tblhtlxxth.ajax.reload(function ( json ) {
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
