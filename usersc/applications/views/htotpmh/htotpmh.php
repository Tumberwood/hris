<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htotpmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtotpmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htotpmh/fn/htotpmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtotpmh, tblhtotpmh, show_inactive_status_htotpmh = 0, id_htotpmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtotpmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htotpmh/htotpmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htotpmh = show_inactive_status_htotpmh;
					}
				},
				table: "#tblhtotpmh",
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
						def: "htotpmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htotpmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "htotpmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htotpmh.nama"
					}, 	{
						label: "Keterangan",
						name: "htotpmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtotpmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtotpmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtotpmh.rows().deselect();
				}
			});

			edthtotpmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtotpmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htotpmh.kode
					if ( ! edthtotpmh.field('htotpmh.kode').isMultiValue() ) {
						kode = edthtotpmh.field('htotpmh.kode').val();
						if(!kode || kode == ''){
							edthtotpmh.field('htotpmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htotpmh.kode
						if(action == 'create'){
							id_htotpmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htotpmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_htotpmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtotpmh.field('htotpmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htotpmh.kode
					}
					// END of validasi htotpmh.kode
					
					// BEGIN of validasi htotpmh.nama
					if ( ! edthtotpmh.field('htotpmh.nama').isMultiValue() ) {
						nama = edthtotpmh.field('htotpmh.nama').val();
						if(!nama || nama == ''){
							edthtotpmh.field('htotpmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htotpmh.nama
						if(action == 'create'){
							id_htotpmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htotpmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_htotpmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtotpmh.field('htotpmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htotpmh.nama
					}
					// END of validasi htotpmh.nama
				}
				
				if ( edthtotpmh.inError() ) {
					return false;
				}
			});
			
			edthtotpmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtotpmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtotpmh = $('#tblhtotpmh').DataTable( {
				ajax: {
					url: "../../models/htotpmh/htotpmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htotpmh = show_inactive_status_htotpmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htotpmh.id",visible:false },
					{ data: "htotpmh.kode" },
					{ data: "htotpmh.nama" },
					{ data: "htotpmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htotpmh';
						$table       = 'tblhtotpmh';
						$edt         = 'edthtotpmh';
						$show_status = '_htotpmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htotpmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtotpmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtotpmh);
			} );
			
			tblhtotpmh.on( 'select', function( e, dt, type, indexes ) {
				htotpmh_data    = tblhtotpmh.row( { selected: true } ).data().htotpmh;
				id_htotpmh      = htotpmh_data.id;
				id_transaksi_h = id_htotpmh; // dipakai untuk general
				is_approve     = htotpmh_data.is_approve;
				is_nextprocess = htotpmh_data.is_nextprocess;
				is_jurnal      = htotpmh_data.is_jurnal;
				is_active      = htotpmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtotpmh);
			} );

			tblhtotpmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htotpmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtotpmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
