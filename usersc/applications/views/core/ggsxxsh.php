<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'ggsxxsh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblggsxxsh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Posisi Logo</th>
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
		var edtggsxxsh, tblggsxxsh, show_inactive_status_ggsxxsh = 0, id_ggsxxsh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtggsxxsh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/ggsxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ggsxxsh = show_inactive_status_ggsxxsh;
					}
				},
				table: "#tblggsxxsh",
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
						def: "ggsxxsh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "ggsxxsh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Logo Perusahaan",
						name: "ggsxxsh.id_files_company_logo",
						type: "upload",
                        display: function(id) {
							if (id > 0) {
								return '<img src="' + edtggsxxsh.file('files', id).web_path + '"/>';
							} else {
								return 'Belum ada gambar';
							}
						},
					},	{
						label: "Posisi Logo Login",
						name: "ggsxxsh.posisi_logo_login",
						type: "select",
						options: [
							{ "label": "Atas", "value": 1 },
							{ "label": "Bawah", "value": 2 }
						]
					}, 	{
						label: "Keterangan",
						name: "ggsxxsh.keterangan",
						type: "textarea"
					}
				]
			} );

			edtggsxxsh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtggsxxsh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblggsxxsh.rows().deselect();
				}
			});

			edtggsxxsh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtggsxxsh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edtggsxxsh.inError() ) {
					return false;
				}
			});

			edtggsxxsh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtggsxxsh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblggsxxsh = $('#tblggsxxsh').DataTable( {
				ajax: {
					url: "../../models/core/ggsxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ggsxxsh = show_inactive_status_ggsxxsh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "ggsxxsh.id",visible:false },
					{ 
                        data: "ggsxxsh.posisi_logo_login" ,
                        render: function (data){
							if (data == 1){
								return 'Atas';
							}else if(data == 2){
								return 'Bawah';
							}else{
                                return '';
                            }
						}
                    }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_ggsxxsh';
						$table       = 'tblggsxxsh';
						$edt         = 'edtggsxxsh';
						$show_status = '_ggsxxsh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['edit'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.ggsxxsh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblggsxxsh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblggsxxsh);
			} );
			
			tblggsxxsh.on( 'select', function( e, dt, type, indexes ) {
				ggsxxsh_data    = tblggsxxsh.row( { selected: true } ).data().ggsxxsh;
				id_ggsxxsh      = ggsxxsh_data.id;
				id_transaksi_h = id_ggsxxsh; // dipakai untuk general
				is_approve     = ggsxxsh_data.is_approve;
				is_nextprocess = ggsxxsh_data.is_nextprocess;
				is_jurnal      = ggsxxsh_data.is_jurnal;
				is_active      = ggsxxsh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblggsxxsh);
			} );

			tblggsxxsh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_ggsxxsh = '';

				// atur hak akses
				CekDeselectHeaderH(tblggsxxsh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
