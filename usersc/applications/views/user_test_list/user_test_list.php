<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'user_test_list';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tbluser_test_list" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Menu</th>
                                <th>Catatan User</th>
                                <th>User</th>
                                <th>Tanggal User Test</th>
                                <th>Catatan OmF</th>
                                <th>Tanggal Done</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/user_test_list/fn/user_test_list_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtuser_test_list, tbluser_test_list, show_inactive_status_user_test_list = 0, id_user_test_list;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtuser_test_list = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/user_test_list/user_test_list.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_user_test_list = show_inactive_status_user_test_list;
					}
				},
				table: "#tbluser_test_list",
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
						def: "user_test_list",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "user_test_list.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Menu <sup class='text-danger'>*<sup>",
						name: "user_test_list.menu"
					},
					{
						label: "Catatan User <sup class='text-danger'>*<sup>",
						name: "user_test_list.catatan_user",
						type: "quill"
					},
					{
						label: "User <sup class='text-danger'>*<sup>",
						name: "user_test_list.username"
					},
					{
						label: "Tanggal User Tes  <sup class='text-danger'>*<sup>",
						name: "user_test_list.tanggal_user_tes",
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
						label: "Catatan OmF",
						name: "user_test_list.catatan_omf",
						type: "quill"
					},
					{
						label: "Tanggal Done ",
						name: "user_test_list.tanggal_done",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
				]
			} );

			edtuser_test_list.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtuser_test_list.field('start_on').val(start_on);
				
				if(action == 'create'){
					tbluser_test_list.rows().deselect();
				}
			});

			edtuser_test_list.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtuser_test_list.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				
					menu = edtuser_test_list.field('user_test_list.menu').val();
					if(!menu || menu == ''){
						edtuser_test_list.field('user_test_list.menu').error( 'Wajib diisi!' );
					}
				
					username = edtuser_test_list.field('user_test_list.username').val();
					if(!username || username == ''){
						edtuser_test_list.field('user_test_list.username').error( 'Wajib diisi!' );
					}
				
					catatan_user = edtuser_test_list.field('user_test_list.catatan_user').val();
					if(!catatan_user || catatan_user == '' || catatan_user == '<p><br></p>'){
						edtuser_test_list.field('user_test_list.catatan_user').error( 'Wajib diisi!' );
					}
				
					tanggal_user_tes = edtuser_test_list.field('user_test_list.tanggal_user_tes').val();
					if(!tanggal_user_tes || tanggal_user_tes == ''){
						edtuser_test_list.field('user_test_list.tanggal_user_tes').error( 'Wajib diisi!' );
					}
					
				}
				
				if ( edtuser_test_list.inError() ) {
					return false;
				}
			});
			
			edtuser_test_list.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtuser_test_list.field('finish_on').val(finish_on);
			});

			//start datatables
			tbluser_test_list = $('#tbluser_test_list').DataTable( {
				ajax: {
					url: "../../models/user_test_list/user_test_list.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_user_test_list = show_inactive_status_user_test_list;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "user_test_list.id",visible:false },
					{ data: "user_test_list.menu" },
					{ data: "user_test_list.catatan_user" },
					{ data: "user_test_list.username" },
					{ data: "user_test_list.tanggal_user_tes" },
					{ data: "user_test_list.catatan_omf" },
					{ data: "user_test_list.tanggal_done" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_user_test_list';
						$table       = 'tbluser_test_list';
						$edt         = 'edtuser_test_list';
						$show_status = '_user_test_list';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.user_test_list.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tbluser_test_list.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tbluser_test_list);
			} );
			
			tbluser_test_list.on( 'select', function( e, dt, type, indexes ) {
				user_test_list_data    = tbluser_test_list.row( { selected: true } ).data().user_test_list;
				id_user_test_list      = user_test_list_data.id;
				id_transaksi_h = id_user_test_list; // dipakai untuk general
				is_approve     = user_test_list_data.is_approve;
				is_nextprocess = user_test_list_data.is_nextprocess;
				is_jurnal      = user_test_list_data.is_jurnal;
				is_active      = user_test_list_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tbluser_test_list);
			} );

			tbluser_test_list.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_user_test_list = '';

				// atur hak akses
				CekDeselectHeaderH(tbluser_test_list);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
