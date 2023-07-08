<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hevxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhevxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hevxxmh/fn/hevxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthevxxmh, tblhevxxmh, show_inactive_status_hevxxmh = 0, id_hevxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthevxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hevxxmh/hevxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevxxmh = show_inactive_status_hevxxmh;
					}
				},
				table: "#tblhevxxmh",
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
						def: "hevxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hevxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hevxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hevxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hevxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthevxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthevxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhevxxmh.rows().deselect();
				}
			});

			edthevxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthevxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hevxxmh.kode
					if ( ! edthevxxmh.field('hevxxmh.kode').isMultiValue() ) {
						kode = edthevxxmh.field('hevxxmh.kode').val();
						if(!kode || kode == ''){
							edthevxxmh.field('hevxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hevxxmh.kode
						if(action == 'create'){
							id_hevxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hevxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_hevxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthevxxmh.field('hevxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hevxxmh.kode
					}
					// END of validasi hevxxmh.kode
					
					// BEGIN of validasi hevxxmh.nama
					if ( ! edthevxxmh.field('hevxxmh.nama').isMultiValue() ) {
						nama = edthevxxmh.field('hevxxmh.nama').val();
						if(!nama || nama == ''){
							edthevxxmh.field('hevxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hevxxmh.nama
						if(action == 'create'){
							id_hevxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hevxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_hevxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthevxxmh.field('hevxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hevxxmh.nama
					}
					// END of validasi hevxxmh.nama
				}
				
				if ( edthevxxmh.inError() ) {
					return false;
				}
			});
			
			edthevxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthevxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhevxxmh = $('#tblhevxxmh').DataTable( {
				ajax: {
					url: "../../models/hevxxmh/hevxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevxxmh = show_inactive_status_hevxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hevxxmh.id",visible:false },
					{ data: "hevxxmh.kode" },
					{ data: "hevxxmh.nama" },
					{ data: "hevxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hevxxmh';
						$table       = 'tblhevxxmh';
						$edt         = 'edthevxxmh';
						$show_status = '_hevxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hevxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhevxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhevxxmh);
			} );
			
			tblhevxxmh.on( 'select', function( e, dt, type, indexes ) {
				hevxxmh_data    = tblhevxxmh.row( { selected: true } ).data().hevxxmh;
				id_hevxxmh      = hevxxmh_data.id;
				id_transaksi_h = id_hevxxmh; // dipakai untuk general
				is_approve     = hevxxmh_data.is_approve;
				is_nextprocess = hevxxmh_data.is_nextprocess;
				is_jurnal      = hevxxmh_data.is_jurnal;
				is_active      = hevxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhevxxmh);
			} );

			tblhevxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hevxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhevxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
