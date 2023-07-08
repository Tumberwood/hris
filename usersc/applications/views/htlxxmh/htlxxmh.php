<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htlxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtlxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htlxxmh/fn/htlxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtlxxmh, tblhtlxxmh, show_inactive_status_htlxxmh = 0, id_htlxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtlxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htlxxmh/htlxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxmh = show_inactive_status_htlxxmh;
					}
				},
				table: "#tblhtlxxmh",
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
						def: "htlxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htlxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "htlxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htlxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "htlxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtlxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtlxxmh.rows().deselect();
				}
			});

			edthtlxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtlxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htlxxmh.kode
					if ( ! edthtlxxmh.field('htlxxmh.kode').isMultiValue() ) {
						kode = edthtlxxmh.field('htlxxmh.kode').val();
						if(!kode || kode == ''){
							edthtlxxmh.field('htlxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htlxxmh.kode
						if(action == 'create'){
							id_htlxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htlxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_htlxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtlxxmh.field('htlxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htlxxmh.kode
					}
					// END of validasi htlxxmh.kode
					
					// BEGIN of validasi htlxxmh.nama
					if ( ! edthtlxxmh.field('htlxxmh.nama').isMultiValue() ) {
						nama = edthtlxxmh.field('htlxxmh.nama').val();
						if(!nama || nama == ''){
							edthtlxxmh.field('htlxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htlxxmh.nama
						if(action == 'create'){
							id_htlxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htlxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_htlxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtlxxmh.field('htlxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htlxxmh.nama
					}
					// END of validasi htlxxmh.nama
				}
				
				if ( edthtlxxmh.inError() ) {
					return false;
				}
			});
			
			edthtlxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtlxxmh = $('#tblhtlxxmh').DataTable( {
				ajax: {
					url: "../../models/htlxxmh/htlxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxmh = show_inactive_status_htlxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htlxxmh.id",visible:false },
					{ data: "htlxxmh.kode" },
					{ data: "htlxxmh.nama" },
					{ data: "htlxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htlxxmh';
						$table       = 'tblhtlxxmh';
						$edt         = 'edthtlxxmh';
						$show_status = '_htlxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htlxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtlxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtlxxmh);
			} );
			
			tblhtlxxmh.on( 'select', function( e, dt, type, indexes ) {
				htlxxmh_data    = tblhtlxxmh.row( { selected: true } ).data().htlxxmh;
				id_htlxxmh      = htlxxmh_data.id;
				id_transaksi_h = id_htlxxmh; // dipakai untuk general
				is_approve     = htlxxmh_data.is_approve;
				is_nextprocess = htlxxmh_data.is_nextprocess;
				is_jurnal      = htlxxmh_data.is_jurnal;
				is_active      = htlxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtlxxmh);
			} );

			tblhtlxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htlxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtlxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
