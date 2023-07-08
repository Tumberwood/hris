<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hesxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhesxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hesxxmh/fn/hesxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxmh, tblhesxxmh, show_inactive_status_hesxxmh = 0, id_hesxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthesxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hesxxmh/hesxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxmh = show_inactive_status_hesxxmh;
					}
				},
				table: "#tblhesxxmh",
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
						def: "hesxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hesxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hesxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hesxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hesxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthesxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhesxxmh.rows().deselect();
				}
			});

			edthesxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthesxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hesxxmh.kode
					if ( ! edthesxxmh.field('hesxxmh.kode').isMultiValue() ) {
						kode = edthesxxmh.field('hesxxmh.kode').val();
						if(!kode || kode == ''){
							edthesxxmh.field('hesxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hesxxmh.kode
						if(action == 'create'){
							id_hesxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hesxxmh',
								kode_field       : 'kode',
								kode_field_value : '"' + kode + '"',
								id_transaksi     : id_hesxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthesxxmh.field('hesxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						
						// END of cek unik hesxxmh.kode
					}
					// END of validasi hesxxmh.kode

					// BEGIN of validasi hesxxmh.nama
					if ( ! edthesxxmh.field('hesxxmh.nama').isMultiValue() ) {
						nama = edthesxxmh.field('hesxxmh.nama').val();
						if(!nama || nama == ''){
							edthesxxmh.field('hesxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hesxxmh.nama
						if(action == 'create'){
							id_hesxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hesxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_hesxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthesxxmh.field('hesxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						
						// END of cek unik hesxxmh.nama
					}
					// END of validasi hesxxmh.nama
					
				}
				
				if ( edthesxxmh.inError() ) {
					return false;
				}
			});
			
			edthesxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhesxxmh = $('#tblhesxxmh').DataTable( {
				ajax: {
					url: "../../models/hesxxmh/hesxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxmh = show_inactive_status_hesxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hesxxmh.id",visible:false },
					{ data: "hesxxmh.kode" },
					{ data: "hesxxmh.nama" },
					{ data: "hesxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hesxxmh';
						$table       = 'tblhesxxmh';
						$edt         = 'edthesxxmh';
						$show_status = '_hesxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hesxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhesxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhesxxmh);
			} );
			
			tblhesxxmh.on( 'select', function( e, dt, type, indexes ) {
				hesxxmh_data    = tblhesxxmh.row( { selected: true } ).data().hesxxmh;
				id_hesxxmh      = hesxxmh_data.id;
				id_transaksi_h = id_hesxxmh; // dipakai untuk general
				is_approve     = hesxxmh_data.is_approve;
				is_nextprocess = hesxxmh_data.is_nextprocess;
				is_jurnal      = hesxxmh_data.is_jurnal;
				is_active      = hesxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhesxxmh);
			} );

			tblhesxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hesxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhesxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
