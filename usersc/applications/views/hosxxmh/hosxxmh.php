<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hosxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhosxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hosxxmh/fn/hosxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthosxxmh, tblhosxxmh, show_inactive_status_hosxxmh = 0, id_hosxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthosxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hosxxmh/hosxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hosxxmh = show_inactive_status_hosxxmh;
					}
				},
				table: "#tblhosxxmh",
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
						def: "hosxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hosxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hosxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hosxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hosxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthosxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthosxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhosxxmh.rows().deselect();
				}
			});

			edthosxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthosxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hosxxmh.kode
					if ( ! edthosxxmh.field('hosxxmh.kode').isMultiValue() ) {
						kode = edthosxxmh.field('hosxxmh.kode').val();
						if(!kode || kode == ''){
							edthosxxmh.field('hosxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hosxxmh.kode
						if(action == 'create'){
							id_hosxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hosxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_hosxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthosxxmh.field('hosxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hosxxmh.kode
					}
					// END of validasi hosxxmh.kode
					
					// BEGIN of validasi hosxxmh.nama
					if ( ! edthosxxmh.field('hosxxmh.nama').isMultiValue() ) {
						nama = edthosxxmh.field('hosxxmh.nama').val();
						if(!nama || nama == ''){
							edthosxxmh.field('hosxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hosxxmh.nama
						if(action == 'create'){
							id_hosxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hosxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_hosxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthosxxmh.field('hosxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hosxxmh.nama
					}
					// END of validasi hosxxmh.nama
				}
				
				if ( edthosxxmh.inError() ) {
					return false;
				}
			});
			
			edthosxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthosxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhosxxmh = $('#tblhosxxmh').DataTable( {
				ajax: {
					url: "../../models/hosxxmh/hosxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hosxxmh = show_inactive_status_hosxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hosxxmh.id",visible:false },
					{ data: "hosxxmh.kode" },
					{ data: "hosxxmh.nama" },
					{ data: "hosxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hosxxmh';
						$table       = 'tblhosxxmh';
						$edt         = 'edthosxxmh';
						$show_status = '_hosxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hosxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhosxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhosxxmh);
			} );
			
			tblhosxxmh.on( 'select', function( e, dt, type, indexes ) {
				hosxxmh_data    = tblhosxxmh.row( { selected: true } ).data().hosxxmh;
				id_hosxxmh      = hosxxmh_data.id;
				id_transaksi_h = id_hosxxmh; // dipakai untuk general
				is_approve     = hosxxmh_data.is_approve;
				is_nextprocess = hosxxmh_data.is_nextprocess;
				is_jurnal      = hosxxmh_data.is_jurnal;
				is_active      = hosxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhosxxmh);
			} );

			tblhosxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hosxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhosxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
