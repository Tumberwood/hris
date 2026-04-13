<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hevgrmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhevgrmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode Grup Jabatan</th>
                                <th>Nama Grup Jabatan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hevgrmh/fn/hevgrmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthevgrmh, tblhevgrmh, show_inactive_status_hevgrmh = 0, id_hevgrmh;
		// ------------- end of default variable

		var id_hevgrmh_old = 0, id_hevgrmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthevgrmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hevgrmh/hevgrmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevgrmh = show_inactive_status_hevgrmh;
					}
				},
				table: "#tblhevgrmh",
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
						def: "hevgrmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hevgrmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode Grup Jabatan <sup class='text-danger'>*<sup>",
						name: "hevgrmh.kode"
					}, 	{
						label: "Nama Grup Jabatan <sup class='text-danger'>*<sup>",
						name: "hevgrmh.nama"
					}, 	
					{
						label: "Keterangan",
						name: "hevgrmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthevgrmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthevgrmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhevgrmh.rows().deselect();
				}
			});

			edthevgrmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthevgrmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hevgrmh.kode
					if ( ! edthevgrmh.field('hevgrmh.kode').isMultiValue() ) {
						kode = edthevgrmh.field('hevgrmh.kode').val();
						if(!kode || kode == ''){
							edthevgrmh.field('hevgrmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hevgrmh.kode
						if(action == 'create'){
							id_hevgrmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hevgrmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_hevgrmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthevgrmh.field('hevgrmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hevgrmh.kode
					}
					// END of validasi hevgrmh.kode
					
					// BEGIN of validasi hevgrmh.nama
					if ( ! edthevgrmh.field('hevgrmh.nama').isMultiValue() ) {
						nama = edthevgrmh.field('hevgrmh.nama').val();
						if(!nama || nama == ''){
							edthevgrmh.field('hevgrmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hevgrmh.nama
						if(action == 'create'){
							id_hevgrmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hevgrmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_hevgrmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthevgrmh.field('hevgrmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hevgrmh.nama
					}
					// END of validasi hevgrmh.nama
				}
				
				if ( edthevgrmh.inError() ) {
					return false;
				}
			});
			
			edthevgrmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthevgrmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhevgrmh = $('#tblhevgrmh').DataTable( {
				ajax: {
					url: "../../models/hevgrmh/hevgrmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hevgrmh = show_inactive_status_hevgrmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hevgrmh.id",visible:false },
					{ data: "hevgrmh.kode" },
					{ data: "hevgrmh.nama" },
					{ data: "hevgrmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hevgrmh';
						$table       = 'tblhevgrmh';
						$edt         = 'edthevgrmh';
						$show_status = '_hevgrmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hevgrmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhevgrmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhevgrmh);
			} );
			
			tblhevgrmh.on( 'select', function( e, dt, type, indexes ) {
				hevgrmh_data    = tblhevgrmh.row( { selected: true } ).data().hevgrmh;
				id_hevgrmh      = hevgrmh_data.id;
				id_transaksi_h = id_hevgrmh; // dipakai untuk general
				is_approve     = hevgrmh_data.is_approve;
				is_nextprocess = hevgrmh_data.is_nextprocess;
				is_jurnal      = hevgrmh_data.is_jurnal;
				is_active      = hevgrmh_data.is_active;

				id_hevgrmh_old = hevgrmh_data.id_hevgrmh;
				id_hevgrmh_old = hevgrmh_data.id_hevgrmh;

				// atur hak akses
				CekSelectHeaderH(tblhevgrmh);
			} );

			tblhevgrmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hevgrmh = 0;
				id_hevgrmh_old = 0;
				id_hevgrmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhevgrmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
