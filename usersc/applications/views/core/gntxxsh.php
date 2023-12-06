<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'gntxxsh';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'gntussd';
?>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgntxxsh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Notif</th>
                                <!-- <th>Messages</th> -->
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
                    <table id="tblgntussd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>id_gntxxsh</th>
                                <th>User Penerima</th>
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

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtgntxxsh, tblgntxxsh, show_inactive_status_gntxxsh = 0, id_gntxxsh;
        var edtgntussd, tblgntussd, show_inactive_status_gntussd = 0, id_gntussd;

		
        var id_users_old = 0;
		
		$(document).ready(function() {
			
			//start datatables editor
			edtgntxxsh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gntxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gntxxsh = show_inactive_status_gntxxsh;
					}
				},
				table: "#tblgntxxsh",
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
						def: "gntxxsh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "gntxxsh.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Nama Notifikasi<sup class='text-danger'>*<sup>",
						name: "gntxxsh.nama"
					}
					// , 	{
					// 	label: "Messages <sup class='text-danger'>*<sup>",
					// 	name: "gntxxsh.keterangan",
					// 	type: "textarea"
					// }
				]
			} );
			
			edtgntxxsh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgntxxsh.field('start_on').val(start_on);

				if(action == 'create'){
					tblgntxxsh.rows().deselect();
				}
			});

            edtgntxxsh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtgntxxsh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi gntxxsh.kode 
					nama = edtgntxxsh.field('gntxxsh.nama').val();
					if(!nama || nama == ''){
						edtgntxxsh.field('gntxxsh.nama').error( 'Wajib diisi!' );
					}
					
					// keterangan = edtgntxxsh.field('gntxxsh.keterangan').val();
					// if(!keterangan || keterangan == ''){
					// 	edtgntxxsh.field('gntxxsh.keterangan').error( 'Wajib diisi!' );
					// }
					// END of validasi gntxxsh.kode
				}
				
				if ( edtgntxxsh.inError() ) {
					return false;
				}
			});

			edtgntxxsh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgntxxsh.field('finish_on').val(finish_on);
			});
			
			edtgntxxsh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblgntxxsh = $('#tblgntxxsh').DataTable( {
				ajax: {
					url: "../../models/core/gntxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gntxxsh = show_inactive_status_gntxxsh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "gntxxsh.id",visible:false },
					{ data: "gntxxsh.nama" }
					// ,
					// { data: "gntxxsh.keterangan" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gntxxsh';
						$table       = 'tblgntxxsh';
						$edt         = 'edtgntxxsh';
						$show_status = '_gntxxsh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gntxxsh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgntxxsh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblgntussd];
				CekInitHeaderHD(tblgntxxsh, tbl_details);
			} );
			
			tblgntxxsh.on( 'select', function( e, dt, type, indexes ) {
				data_gntxxsh = tblgntxxsh.row( { selected: true } ).data().gntxxsh;
				id_gntxxsh  = data_gntxxsh.id;
				id_transaksi_h   = id_gntxxsh; // dipakai untuk general
				is_approve       = data_gntxxsh.is_approve;
				is_nextprocess   = data_gntxxsh.is_nextprocess;
				is_jurnal        = data_gntxxsh.is_jurnal;
				is_active        = data_gntxxsh.is_active;
				
				// atur hak akses
				tbl_details = [tblgntussd];
				CekSelectHeaderHD(tblgntxxsh, tbl_details);

			} );
			
			tblgntxxsh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gntxxsh = '';

				// atur hak akses
				tbl_details = [tblgntussd];
				CekDeselectHeaderHD(tblgntxxsh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edtgntussd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gntussd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gntussd = show_inactive_status_gntussd;
						d.id_gntxxsh = id_gntxxsh;
					}
				},
				table: "#tblgntussd",
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
						def: "gntussd",
						type: "hidden"
					},	{
						label: "id_gntxxsh",
						name: "gntussd.id_gntxxsh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "gntussd.is_active",
                        type: "hidden",
						def: 1
					},	{
                        label: "Username <sup class='text-danger'>*<sup>",
						name: "gntussd.id_users_penerima",
                        type: "select2",
                        opts: {
                            placeholder : "Select",
                            allowClear: true,
                            multiple: false,
                            ajax: {
                                url: "../../models/core/users_only_fn_opt.php",
                                dataType: 'json',
                                data: function (params) {
                                    var query = {
                                        id_users_old: id_users_old,
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
                    }
				]
			} );
			
			edtgntussd.on( 'preOpen', function( e, mode, action ) {
				edtgntussd.field('gntussd.id_gntxxsh').val(id_gntxxsh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgntussd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgntussd.rows().deselect();
				}
			});

            edtgntussd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtgntussd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi gntussd.id_users_penerima 
					id_users_penerima = edtgntussd.field('gntussd.id_users_penerima').val();
					if(!id_users_penerima || id_users_penerima == ''){
						edtgntussd.field('gntussd.id_users_penerima').error( 'Wajib diisi!' );
					}
					// END of validasi gntussd.id_users_penerima 
				}
				
				if ( edtgntussd.inError() ) {
					return false;
				}
			});

			edtgntussd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgntussd.field('finish_on').val(finish_on);
			});

			
			edtgntussd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblgntussd = $('#tblgntussd').DataTable( {
				ajax: {
					url: "../../models/core/gntussd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gntussd = show_inactive_status_gntussd;
						d.id_gntxxsh = id_gntxxsh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "gntussd.id",visible:false },
					{ data: "gntussd.id_gntxxsh",visible:false },
					{ data: "nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gntussd';
						$table       = 'tblgntussd';
						$edt         = 'edtgntussd';
						$show_status = '_gntussd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gntussd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblgntussd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblgntxxsh, tblgntussd, 'gntussd' );
				CekDrawDetailHDFinal(tblgntxxsh);
			} );

			tblgntussd.on( 'select', function( e, dt, type, indexes ) {
				data_gntussd = tblgntussd.row( { selected: true } ).data().gntussd;
				id_gntussd   = data_gntussd.id;
				id_transaksi_d    = id_gntussd; // dipakai untuk general
				is_active_d       = data_gntussd.is_active;
				id_users_old       = data_gntussd.id_users_penerima;
				
				// atur hak akses
				CekSelectDetailHD(tblgntxxsh, tblgntussd );
			} );

			tblgntussd.on( 'deselect', function() {
				id_gntussd = '';
				is_active_d = 0;
				id_users_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblgntxxsh, tblgntussd );
			} );

// --------- end _detail --------------- //		

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
