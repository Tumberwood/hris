<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'menus';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblmenus" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Label</th>
                                <th>Parent</th>
                                <th>Link</th>
                                <th>Dropdown</th>
                                <th>Authorized Group</th>
                                <th>Logged In</th>
                                <th>Display Order</th>
                                <th>Icon Class</th>
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

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmenus, tblmenus, show_inactive_status_menus = 0, id_menus;
		// ------------- end of default variable

		var id_menus_old = 0;

		$(document).ready(function() {

			//start datatables editor
			edtmenus = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/menus.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_menus = show_inactive_status_menus;
					}
				},
				table: "#tblmenus",
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
						def: "menus",
						type: "hidden"
					},	{
						label: "Parent",
						name: "menus.parent",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/menus_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_menus_old: id_menus_old,
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
						label: "Dropdown",
						name: "menus.dropdown",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 0 }
						]
					},	{
						label: "Authorized Groups",
						name: "permissions[].id",
						type: "select2",
						opts: {
							"multiple": true
						}
					},	{
						label: "User must be logged in",
						name: "menus.logged_in",
						type: "select",
						def: 1,
						placeholder : "Select",
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 0 }
						]
					}, 	{
						label: "Display Order <sup class='text-danger'>*<sup>",
						name: "menus.display_order",
						def: "9999"
					}, 	{
						label: "Label <sup class='text-danger'>*<sup>",
						name: "menus.label"
					}, {
						label: "Link",
						name: "menus.link",
						def: "#"
					}, {
						label: "Icon Class",
						name: "menus.icon_class",
						fieldInfo: "Be sure to add fa fa-fw before the shortcode to display properly"
					}
				]
			} );

			edtmenus.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmenus.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmenus.rows().deselect();
				}
			});

			edtmenus.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtmenus.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

                    // BEGIN of validasi menus.display_order
					if ( ! edtmenus.field('menus.display_order').isMultiValue() ) {
						display_order = edtmenus.field('menus.display_order').val();
						if(!display_order || display_order == ''){
							edtmenus.field('menus.display_order').error( 'Wajib diisi!' );
						}
						if(display_order <= 0 ){
							edtmenus.field('menus.display_order').error( 'Inputan harus > 0' );
						}
						if(isNaN(display_order) ){
							edtmenus.field('menus.display_order').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi menus.display_order

					// BEGIN of validasi menus.label
					if ( ! edtmenus.field('menus.label').isMultiValue() ) {
						label = edtmenus.field('menus.label').val();
						if(!label || label == ''){
							edtmenus.field('menus.label').error( 'Wajib diisi!' );
						}
					}
					// END of validasi menus.label

					// BEGIN of validasi menus.link
					if ( ! edtmenus.field('menus.link').isMultiValue() ) {
						link = edtmenus.field('menus.link').val();
						if(!link || link == ''){
							edtmenus.field('menus.link').error( 'Wajib diisi!' );
						}
					}
					// END of validasi menus.link

                    
				}
				
				if ( edtmenus.inError() ) {
					return false;
				}
			});

			edtmenus.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmenus.field('finish_on').val(finish_on);
			});

			edtmenus.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblmenus.ajax.reload(null, false);
			});
			
			//start datatables
			tblmenus = $('#tblmenus').DataTable( {
				ajax: {
					url: "../../models/core/menus.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_menus = show_inactive_status_menus;
					}
				},
				order: [[ 7, "asc" ]],
				columns: [
					{ data: "menus.id",visible:false },
					{ data: "menus.label" },
					{ data: "menus_parent.label" },
					{ data: "menus.link" },
					{ 
						data: "menus.dropdown" ,
						render: function (data){
							if (data == 0){
								return 'No';
							}else if(data == 1){
								return 'Yes';
							}
						}
					},
					{ data: "permissions[].name" },
					{ 
						data: "menus.logged_in" ,
						render: function (data){
							if (data == 0){
								return 'No';
							}else if(data == 1){
								return 'Yes';
							}
						}
					},
					{ data: "menus.display_order" },
					{ data: "menus.icon_class" }
					
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_menus';
						$table       = 'tblmenus';
						$edt         = 'edtmenus';
						$show_status = '_menus';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['copy','excel'];
						$arr_buttons_action 	= ['create', 'edit', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					
				}
			} );
			
			tblmenus.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblmenus);
			} );
			
			tblmenus.on( 'select', function( e, dt, type, indexes ) {
				menus_data    = tblmenus.row( { selected: true } ).data().menus;
				id_menus      = menus_data.id;
				id_transaksi_h = id_menus; // dipakai untuk general
				is_approve     = menus_data.is_approve;
				is_nextprocess = menus_data.is_nextprocess;
				is_jurnal      = menus_data.is_jurnal;
				is_active      = menus_data.is_active;
				
				id_menus_old   = menus_data.id;

				// atur hak akses
				CekSelectHeaderH(tblmenus);
			} );

			tblmenus.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_menus = 0;
				id_menus_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblmenus);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
