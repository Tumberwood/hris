<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'tutorial_menu_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'tutorial_menu_d';
?>

<!-- begin content here -->

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<h3 id="dayname" style="display: none"></h3>
				<div class="table-responsive">
                    <table id="tbltutorial_menu_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
							<tr>
								<th>ID</th>
								<th data-priority="1">Menu</th>
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
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tbltutorial_menu_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th class="text-center">ID</th>
								<th class="text-center">id_tutorial_menu_h</th>
								<th class="text-center">Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/tutorial_menu_h/fn/tutorial_menu_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edttutorial_menu_h, tbltutorial_menu_h, show_inactive_status_tutorial_menu_h = 0, id_tutorial_menu_h;
        var edttutorial_menu_d, tbltutorial_menu_d, show_inactive_status_tutorial_menu_d = 0, id_tutorial_menu_d;
		// ------------- end of default variable

		is_need_approval = 1;
		var id_holxxmd_old = 0, id_heyxxmh_old = 0;
		var id_heyxxmh = 0, id_htotpmh_old  = 0, id_hemxxmh_old = 0;
		var tanggal, is_valid_checkclock;

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			//start datatables editor
			edttutorial_menu_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/tutorial_menu_h/tutorial_menu_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tutorial_menu_h = show_inactive_status_tutorial_menu_h;
					}
				},
				table: "#tbltutorial_menu_h",
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
						def: "tutorial_menu_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "tutorial_menu_h.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Nama Menu <sup class='text-danger'>*<sup>", 
						name: "tutorial_menu_h.nama",
					},
					{
						label: "Keterangan",
						name: "tutorial_menu_h.keterangan",
						type: "textarea"
					},
				]
			} );
			
			edttutorial_menu_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttutorial_menu_h.field('start_on').val(start_on);

				if(action == 'create'){
					tbltutorial_menu_h.rows().deselect();	
				}
			});

            edttutorial_menu_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edttutorial_menu_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi tutorial_menu_h.nama 
					nama = edttutorial_menu_h.field('tutorial_menu_h.nama').val();
					if(!nama || nama == ''){
						edttutorial_menu_h.field('tutorial_menu_h.nama').error( 'Wajib diisi!' );
					}
					// END of validasi tutorial_menu_h.nama
				}
				
				if ( edttutorial_menu_h.inError() ) {
					return false;
				}
			});

			edttutorial_menu_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttutorial_menu_h.field('finish_on').val(finish_on);
			});

			edttutorial_menu_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tbltutorial_menu_h.rows().deselect();
				tbltutorial_menu_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tbltutorial_menu_h = $('#tbltutorial_menu_h').DataTable( {
				ajax: {
					url: "../../models/tutorial_menu_h/tutorial_menu_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tutorial_menu_h = show_inactive_status_tutorial_menu_h;
					}
				},
				order: [[0, "desc"]],
				columns: [
					{ data: "tutorial_menu_h.id",visible:false },
					{ data: "tutorial_menu_h.nama" },
					{ data: "tutorial_menu_h.keterangan" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_tutorial_menu_h';
						$table       = 'tbltutorial_menu_h';
						$edt         = 'edttutorial_menu_h';
						$show_status = '_tutorial_menu_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.tutorial_menu_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
					if ( data.tutorial_menu_h.is_approve == 1 ) {
						$('td', row).addClass('text-approved');
					}
				}
				
			} );

			tbltutorial_menu_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tbltutorial_menu_d];
				CekInitHeaderHD(tbltutorial_menu_h, tbl_details);
			} );
			
			tbltutorial_menu_h.on( 'select', function( e, dt, type, indexes ) {
				data_tutorial_menu_h = tbltutorial_menu_h.row( { selected: true } ).data().tutorial_menu_h;
				id_tutorial_menu_h  = data_tutorial_menu_h.id;
				id_transaksi_h   = id_tutorial_menu_h; // dipakai untuk general
				is_approve       = data_tutorial_menu_h.is_approve;
				is_nextprocess   = data_tutorial_menu_h.is_nextprocess;
				is_jurnal        = data_tutorial_menu_h.is_jurnal;
				is_active        = data_tutorial_menu_h.is_active;

				// atur hak akses
				tbl_details = [tbltutorial_menu_d];
				CekSelectHeaderHD(tbltutorial_menu_h, tbl_details);

			} );
			
			tbltutorial_menu_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_tutorial_menu_h = 0;
				id_holxxmd_old   = 0;
				id_heyxxmh_old   = 0;
				tanggal = '';

				// atur hak akses
				tbl_details = [tbltutorial_menu_d];
				CekDeselectHeaderHD(tbltutorial_menu_h, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edttutorial_menu_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/tutorial_menu_h/tutorial_menu_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tutorial_menu_d = show_inactive_status_tutorial_menu_d;
						d.id_tutorial_menu_h = id_tutorial_menu_h;
					}
				},
				table: "#tbltutorial_menu_d",
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
						def: "tutorial_menu_d",
						type: "hidden"
					},	{
						label: "id_tutorial_menu_h",
						name: "tutorial_menu_d.id_tutorial_menu_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "tutorial_menu_d.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Keterangan",
						name: "tutorial_menu_d.keterangan",
						type: "quill"
					}
				]
			} );
			
			edttutorial_menu_d.on( 'preOpen', function( e, mode, action ) {
				edttutorial_menu_d.field('tutorial_menu_d.id_tutorial_menu_h').val(id_tutorial_menu_h);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttutorial_menu_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tbltutorial_menu_d.rows().deselect();
				}
			});

            edttutorial_menu_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
			});

			
			edttutorial_menu_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

				}
				
				if ( edttutorial_menu_d.inError() ) {
					return false;
				}
			});

			edttutorial_menu_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttutorial_menu_d.field('finish_on').val(finish_on);
			});

			
			edttutorial_menu_d.on( 'postSubmit', function (e, json, data, action, xhr) {
				// tbltutorial_menu_d.rows().deselect();
				tbltutorial_menu_d.ajax.reload(null, false);
				tbltutorial_menu_h.ajax.reload(null, false);
			} );
			
			//start datatables
			tbltutorial_menu_d = $('#tbltutorial_menu_d').DataTable( {
				ajax: {
					url: "../../models/tutorial_menu_h/tutorial_menu_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_tutorial_menu_d = show_inactive_status_tutorial_menu_d;
						d.id_tutorial_menu_h = id_tutorial_menu_h;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				columns: [
					{ data: "tutorial_menu_d.id",visible:false },
					{ data: "tutorial_menu_d.id_tutorial_menu_h",visible:false },
					{ data: "tutorial_menu_d.keterangan"},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_tutorial_menu_d';
						$table       = 'tbltutorial_menu_d';
						$edt         = 'edttutorial_menu_d';
						$show_status = '_tutorial_menu_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.tutorial_menu_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tbltutorial_menu_d.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tbltutorial_menu_h, tbltutorial_menu_d, 'tutorial_menu_d' );
				CekDrawDetailHDFinal(tbltutorial_menu_h);
			} );

			tbltutorial_menu_d.on( 'select', function( e, dt, type, indexes ) {
				data_tutorial_menu_d = tbltutorial_menu_d.row( { selected: true } ).data().tutorial_menu_d;
				id_tutorial_menu_d   = data_tutorial_menu_d.id;
				id_transaksi_d    = id_tutorial_menu_d; // dipakai untuk general
				is_active_d       = data_tutorial_menu_d.is_active;

				// atur hak akses
				CekSelectDetailHD(tbltutorial_menu_h, tbltutorial_menu_d );
			} );

			tbltutorial_menu_d.on( 'deselect', function() {
				id_tutorial_menu_d = 0;
				is_active_d = 0;

				id_hemxxmh_old = 0;
				id_htotpmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbltutorial_menu_h, tbltutorial_menu_d );
			} );

// --------- end _detail --------------- //		

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
