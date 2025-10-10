<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'grup_pekerjaan_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgrup_pekerjaan_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/grup_pekerjaan_m/fn/grup_pekerjaan_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtgrup_pekerjaan_m, tblgrup_pekerjaan_m, show_inactive_status_grup_pekerjaan_m = 0, id_grup_pekerjaan_m;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtgrup_pekerjaan_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/grup_pekerjaan_m/grup_pekerjaan_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_grup_pekerjaan_m = show_inactive_status_grup_pekerjaan_m;
					}
				},
				table: "#tblgrup_pekerjaan_m",
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
						def: "grup_pekerjaan_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "grup_pekerjaan_m.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "grup_pekerjaan_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "grup_pekerjaan_m.nama"
					}, 	{
						label: "Keterangan",
						name: "grup_pekerjaan_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtgrup_pekerjaan_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgrup_pekerjaan_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgrup_pekerjaan_m.rows().deselect();
				}
			});

			edtgrup_pekerjaan_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgrup_pekerjaan_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi grup_pekerjaan_m.kode
					if ( ! edtgrup_pekerjaan_m.field('grup_pekerjaan_m.kode').isMultiValue() ) {
						kode = edtgrup_pekerjaan_m.field('grup_pekerjaan_m.kode').val();
						if(!kode || kode == ''){
							edtgrup_pekerjaan_m.field('grup_pekerjaan_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik grup_pekerjaan_m.kode
						if(action == 'create'){
							id_grup_pekerjaan_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'grup_pekerjaan_m',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_grup_pekerjaan_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtgrup_pekerjaan_m.field('grup_pekerjaan_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik grup_pekerjaan_m.kode
					}
					// END of validasi grup_pekerjaan_m.kode
					
					// BEGIN of validasi grup_pekerjaan_m.nama
					if ( ! edtgrup_pekerjaan_m.field('grup_pekerjaan_m.nama').isMultiValue() ) {
						nama = edtgrup_pekerjaan_m.field('grup_pekerjaan_m.nama').val();
						if(!nama || nama == ''){
							edtgrup_pekerjaan_m.field('grup_pekerjaan_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik grup_pekerjaan_m.nama
						if(action == 'create'){
							id_grup_pekerjaan_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'grup_pekerjaan_m',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_grup_pekerjaan_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtgrup_pekerjaan_m.field('grup_pekerjaan_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik grup_pekerjaan_m.nama
					}
					// END of validasi grup_pekerjaan_m.nama
				}
				
				if ( edtgrup_pekerjaan_m.inError() ) {
					return false;
				}
			});
			
			edtgrup_pekerjaan_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgrup_pekerjaan_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblgrup_pekerjaan_m = $('#tblgrup_pekerjaan_m').DataTable( {
				ajax: {
					url: "../../models/grup_pekerjaan_m/grup_pekerjaan_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_grup_pekerjaan_m = show_inactive_status_grup_pekerjaan_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "grup_pekerjaan_m.id",visible:false },
					{ data: "grup_pekerjaan_m.kode" },
					{ data: "grup_pekerjaan_m.nama" },
					{ data: "grup_pekerjaan_m.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_grup_pekerjaan_m';
						$table       = 'tblgrup_pekerjaan_m';
						$edt         = 'edtgrup_pekerjaan_m';
						$show_status = '_grup_pekerjaan_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.grup_pekerjaan_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgrup_pekerjaan_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgrup_pekerjaan_m);
			} );
			
			tblgrup_pekerjaan_m.on( 'select', function( e, dt, type, indexes ) {
				grup_pekerjaan_m_data    = tblgrup_pekerjaan_m.row( { selected: true } ).data().grup_pekerjaan_m;
				id_grup_pekerjaan_m      = grup_pekerjaan_m_data.id;
				id_transaksi_h = id_grup_pekerjaan_m; // dipakai untuk general
				is_approve     = grup_pekerjaan_m_data.is_approve;
				is_nextprocess = grup_pekerjaan_m_data.is_nextprocess;
				is_jurnal      = grup_pekerjaan_m_data.is_jurnal;
				is_active      = grup_pekerjaan_m_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblgrup_pekerjaan_m);
			} );

			tblgrup_pekerjaan_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_grup_pekerjaan_m = '';

				// atur hak akses
				CekDeselectHeaderH(tblgrup_pekerjaan_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
