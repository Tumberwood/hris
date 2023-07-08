<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = '_blankheader';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = '_blankdetail';
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
                    <table id="tbl_blankheader" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
                    <table id="tbl_blankdetail" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id__blankheader</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/_blankheader/fn/_blankheader_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edt_blankheader, tbl_blankheader, show_inactive_status__blankheader = 0, id__blankheader;
        var edt_blankdetail, tbl_blankdetail, show_inactive_status__blankdetail = 0, id__blankdetail;
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
			edt_blankheader = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/_blankheader/_blankheader.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status__blankheader = show_inactive_status__blankheader;
					}
				},
				table: "#tbl_blankheader",
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
						def: "_blankheader",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "_blankheader.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "_blankheader.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "_blankheader.nama"
					}, 	{
						label: "Keterangan",
						name: "_blankheader.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edt_blankheader.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blankheader.field('start_on').val(start_on);
				
				if(action == 'create'){
					tbl_blankheader.rows().deselect();
				}
			});

            edt_blankheader.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edt_blankheader.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi _blankheader.kode 
					kode = edt_blankheader.field('_blankheader.kode').val();
					if(!kode || kode == ''){
						edt_blankheader.field('_blankheader.kode').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik _blankheader.kode 
					if(action == 'create'){
						id__blankheader = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: '_blankheader',
							nama_field: 'kode',
							nama_field_value: '"'+kode+'"',
							id_transaksi: id__blankheader
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edt_blankheader.field('_blankheader.kode').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik _blankheader.kode 
					// END of validasi _blankheader.kode 
				}
				
				if ( edt_blankheader.inError() ) {
					return false;
				}
			});

			edt_blankheader.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blankheader.field('finish_on').val(finish_on);
			});
			
			edt_blankheader.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tbl_blankheader = $('#tbl_blankheader').DataTable( {
				ajax: {
					url: "../../models/_blankheader/_blankheader.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status__blankheader = show_inactive_status__blankheader;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "_blankheader.id",visible:false },
					{ data: "_blankheader.kode" },
					{ data: "_blankheader.nama" },
					{ data: "_blankheader.keterangan" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id__blankheader';
						$table       = 'tbl_blankheader';
						$edt         = 'edt_blankheader';
						$show_status = '__blankheader';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data._blankheader.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tbl_blankheader.on( 'init', function () {
				// atur hak akses
				tbl_details = [tbl_blankdetail];
				CekInitHeaderHD(tbl_blankheader, tbl_details);
			} );
			
			tbl_blankheader.on( 'select', function( e, dt, type, indexes ) {
				data__blankheader = tbl_blankheader.row( { selected: true } ).data()._blankheader;
				id__blankheader  = data__blankheader.id;
				id_transaksi_h   = id__blankheader; // dipakai untuk general
				is_approve       = data__blankheader.is_approve;
				is_nextprocess   = data__blankheader.is_nextprocess;
				is_jurnal        = data__blankheader.is_jurnal;
				is_active        = data__blankheader.is_active;
				
				// atur hak akses
				tbl_details = [tbl_blankdetail];
				CekSelectHeaderHD(tbl_blankheader, tbl_details);

			} );
			
			tbl_blankheader.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id__blankheader = '';

				// atur hak akses
				tbl_details = [tbl_blankdetail];
				CekDeselectHeaderHD(tbl_blankheader, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edt_blankdetail = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/_blankheader/_blankdetail.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status__blankdetail = show_inactive_status__blankdetail;
						d.id__blankheader = id__blankheader;
					}
				},
				table: "#tbl_blankdetail",
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
						def: "_blankdetail",
						type: "hidden"
					},	{
						label: "id__blankheader",
						name: "_blankdetail.id__blankheader",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "_blankdetail.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "_blankdetail.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "_blankdetail.nama"
					}, 	{
						label: "Keterangan",
						name: "_blankdetail.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edt_blankdetail.on( 'preOpen', function( e, mode, action ) {
				edt_blankdetail.field('_blankdetail.id__blankheader').val(id__blankheader);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blankdetail.field('start_on').val(start_on);

				if(action == 'create'){
					tbl_blankdetail.rows().deselect();
				}
			});

            edt_blankdetail.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edt_blankdetail.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi _blankdetail.kode 
					kode = edt_blankdetail.field('_blankdetail.kode').val();
					if(!kode || kode == ''){
						edt_blankdetail.field('_blankdetail.kode').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik _blankdetail.kode 
					if(action == 'create'){
						id__blankdetail = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: '_blankdetail',
							nama_field: 'kode',
							nama_field_value: '"'+kode+'"',
							id_transaksi: id__blankdetail
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edt_blankdetail.field('_blankdetail.kode').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik _blankdetail.kode 
					// END of validasi _blankdetail.kode
				
				}
				
				if ( edt_blankdetail.inError() ) {
					return false;
				}
			});

			edt_blankdetail.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blankdetail.field('finish_on').val(finish_on);
			});
			
			edt_blankdetail.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tbl_blankdetail = $('#tbl_blankdetail').DataTable( {
				ajax: {
					url: "../../models/_blankheader/_blankdetail.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status__blankdetail = show_inactive_status__blankdetail;
						d.id__blankheader = id__blankheader;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "_blankdetail.id",visible:false },
					{ data: "_blankdetail.id__blankheader",visible:false },
					{ data: "_blankdetail.kode" },
					{ data: "_blankdetail.nama" },
					{ data: "_blankdetail.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id__blankdetail';
						$table       = 'tbl_blankdetail';
						$edt         = 'edt_blankdetail';
						$show_status = '__blankdetail';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data._blankdetail.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tbl_blankdetail.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tbl_blankheader, tbl_blankdetail, '_blankdetail' );
				CekDrawDetailHDFinal(tbl_blankheader);
			} );

			tbl_blankdetail.on( 'select', function( e, dt, type, indexes ) {
				data__blankdetail = tbl_blankdetail.row( { selected: true } ).data()._blankdetail;
				id__blankdetail   = data__blankdetail.id;
				id_transaksi_d    = id__blankdetail; // dipakai untuk general
				is_active_d       = data__blankdetail.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tbl_blankheader, tbl_blankdetail );
			} );

			tbl_blankdetail.on( 'deselect', function() {
				id__blankdetail = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbl_blankheader, tbl_blankdetail );
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

					tbl_blankheader.rows().deselect();
					tbl_blankdetail.rows().deselect();
					tbl_blankheader.ajax.reload(function ( json ) {
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
