<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'ruang_meeting_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblruang_meeting_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/ruang_meeting_m/fn/ruang_meeting_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtruang_meeting_m, tblruang_meeting_m, show_inactive_status_ruang_meeting_m = 0, id_ruang_meeting_m;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtruang_meeting_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/ruang_meeting_m/ruang_meeting_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ruang_meeting_m = show_inactive_status_ruang_meeting_m;
					}
				},
				table: "#tblruang_meeting_m",
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
						def: "ruang_meeting_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "ruang_meeting_m.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "ruang_meeting_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "ruang_meeting_m.nama"
					}, 	{
						label: "Keterangan",
						name: "ruang_meeting_m.keterangan",
						type: "textarea"
					},
				]
			} );

			edtruang_meeting_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblruang_meeting_m.rows().deselect();
				}
			});

			edtruang_meeting_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtruang_meeting_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi ruang_meeting_m.kode
					if ( ! edtruang_meeting_m.field('ruang_meeting_m.kode').isMultiValue() ) {
						kode = edtruang_meeting_m.field('ruang_meeting_m.kode').val();
						if(!kode || kode == ''){
							edtruang_meeting_m.field('ruang_meeting_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik ruang_meeting_m.kode
						if(action == 'create'){
							id_ruang_meeting_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'ruang_meeting_m',
								kode_field       : 'kode',
								kode_field_value : '"' + kode + '"',
								id_transaksi     : id_ruang_meeting_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtruang_meeting_m.field('ruang_meeting_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik ruang_meeting_m.kode

						nama = edtruang_meeting_m.field('ruang_meeting_m.nama').val();
						if(!nama || nama == ''){
							edtruang_meeting_m.field('ruang_meeting_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik ruang_meeting_m.nama
						if(action == 'create'){
							id_ruang_meeting_m = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'ruang_meeting_m',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_ruang_meeting_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtruang_meeting_m.field('ruang_meeting_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik ruang_meeting_m.nama
					}
					// END of validasi ruang_meeting_m.nama
					
				}
				
				if ( edtruang_meeting_m.inError() ) {
					return false;
				}
			});
			
			edtruang_meeting_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtruang_meeting_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblruang_meeting_m = $('#tblruang_meeting_m').DataTable( {
				ajax: {
					url: "../../models/ruang_meeting_m/ruang_meeting_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_ruang_meeting_m = show_inactive_status_ruang_meeting_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "ruang_meeting_m.id",visible:false },
					{ data: "ruang_meeting_m.kode" },
					{ data: "ruang_meeting_m.nama" },
					{ data: "ruang_meeting_m.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_ruang_meeting_m';
						$table       = 'tblruang_meeting_m';
						$edt         = 'edtruang_meeting_m';
						$show_status = '_ruang_meeting_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.ruang_meeting_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblruang_meeting_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblruang_meeting_m);
			} );
			
			tblruang_meeting_m.on( 'select', function( e, dt, type, indexes ) {
				ruang_meeting_m_data    = tblruang_meeting_m.row( { selected: true } ).data().ruang_meeting_m;
				id_ruang_meeting_m      = ruang_meeting_m_data.id;
				id_transaksi_h = id_ruang_meeting_m; // dipakai untuk general
				is_approve     = ruang_meeting_m_data.is_approve;
				is_nextprocess = ruang_meeting_m_data.is_nextprocess;
				is_jurnal      = ruang_meeting_m_data.is_jurnal;
				is_active      = ruang_meeting_m_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblruang_meeting_m);
			} );

			tblruang_meeting_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_ruang_meeting_m = '';

				// atur hak akses
				CekDeselectHeaderH(tblruang_meeting_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
