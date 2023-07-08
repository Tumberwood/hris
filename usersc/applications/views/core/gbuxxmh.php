<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gbuxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgbuxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
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
		var edtgbuxxmh, tblgbuxxmh, show_inactive_status_gbuxxmh = 0, id_gbuxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtgbuxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gbuxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gbuxxmh = show_inactive_status_gbuxxmh;
					}
				},
				table: "#tblgbuxxmh",
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
						def: "gbuxxmh",
						type: "hidden"
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "gbuxxmh.kode"
					}
				]
			} );

			edtgbuxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgbuxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgbuxxmh.rows().deselect();
				}
			});

			edtgbuxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgbuxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi gbuxxmh.kode
					if ( ! edtgbuxxmh.field('gbuxxmh.kode').isMultiValue() ) {
						gbuxxmh_kode = edtgbuxxmh.field('gbuxxmh.kode').val();
						if(!gbuxxmh_kode || gbuxxmh_kode == ''){
							edtgbuxxmh.field('gbuxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik gbuxxmh.kode
						if(action == 'create'){
							id_gbuxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'gbuxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + gbuxxmh_kode + '"',
								id_transaksi     : id_gbuxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtgbuxxmh.field('gbuxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik gbuxxmh.kode
					}
					// END of validasi gbuxxmh.kode
					
				}
				
				if ( edtgbuxxmh.inError() ) {
					return false;
				}
			});

			edtgbuxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgbuxxmh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblgbuxxmh = $('#tblgbuxxmh').DataTable( {
				ajax: {
					url: "../../models/core/gbuxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gbuxxmh = show_inactive_status_gbuxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "gbuxxmh.id",visible:false },
					{ data: "gbuxxmh.kode" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gbuxxmh';
						$table       = 'tblgbuxxmh';
						$edt         = 'edtgbuxxmh';
						$show_status = '_gbuxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gbuxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgbuxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgbuxxmh);
			} );
			
			tblgbuxxmh.on( 'select', function( e, dt, type, indexes ) {
				id_gbuxxmh = tblgbuxxmh.row( { selected: true } ).data().gbuxxmh.id;
				id_transaksi_h = id_gbuxxmh; // dipakai untuk general
				is_approve     = tblgbuxxmh.row( { selected: true } ).data().gbuxxmh.is_approve;
				is_nextprocess = tblgbuxxmh.row( { selected: true } ).data().gbuxxmh.is_nextprocess;
				is_jurnal      = tblgbuxxmh.row( { selected: true } ).data().gbuxxmh.is_jurnal;
				is_active      = tblgbuxxmh.row( { selected: true } ).data().gbuxxmh.is_active;

				// atur hak akses
				CekSelectHeaderH(tblgbuxxmh);
			} );

			tblgbuxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gbuxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblgbuxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
