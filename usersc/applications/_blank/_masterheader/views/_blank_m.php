<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = '_blank';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tbl_blank" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/_blank/fn/_blank_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edt_blank, tbl_blank, show_inactive_status__blank = 0, id__blank;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edt_blank = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/_blank/_blank.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status__blank = show_inactive_status__blank;
					}
				},
				table: "#tbl_blank",
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
						def: "_blank",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "_blank.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "_blank.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "_blank.nama"
					}, 	{
						label: "Keterangan",
						name: "_blank.keterangan",
						type: "textarea"
					}
				]
			} );

			edt_blank.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blank.field('start_on').val(start_on);
				
				if(action == 'create'){
					tbl_blank.rows().deselect();
				}
			});

			edt_blank.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edt_blank.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi _blank.kode
					if ( ! edt_blank.field('_blank.kode').isMultiValue() ) {
						kode = edt_blank.field('_blank.kode').val();
						if(!kode || kode == ''){
							edt_blank.field('_blank.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik _blank.kode
						if(action == 'create'){
							id__blank = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: '_blank',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id__blank
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edt_blank.field('_blank.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik _blank.kode
					}
					// END of validasi _blank.kode
					
					// BEGIN of validasi _blank.nama
					if ( ! edt_blank.field('_blank.nama').isMultiValue() ) {
						nama = edt_blank.field('_blank.nama').val();
						if(!nama || nama == ''){
							edt_blank.field('_blank.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik _blank.nama
						if(action == 'create'){
							id__blank = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: '_blank',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id__blank
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edt_blank.field('_blank.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik _blank.nama
					}
					// END of validasi _blank.nama
				}
				
				if ( edt_blank.inError() ) {
					return false;
				}
			});
			
			edt_blank.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blank.field('finish_on').val(finish_on);
			});

			//start datatables
			tbl_blank = $('#tbl_blank').DataTable( {
				ajax: {
					url: "../../models/_blank/_blank.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status__blank = show_inactive_status__blank;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "_blank.id",visible:false },
					{ data: "_blank.kode" },
					{ data: "_blank.nama" },
					{ data: "_blank.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id__blank';
						$table       = 'tbl_blank';
						$edt         = 'edt_blank';
						$show_status = '__blank';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data._blank.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tbl_blank.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tbl_blank);
			} );
			
			tbl_blank.on( 'select', function( e, dt, type, indexes ) {
				_blank_data    = tbl_blank.row( { selected: true } ).data()._blank;
				id__blank      = _blank_data.id;
				id_transaksi_h = id__blank; // dipakai untuk general
				is_approve     = _blank_data.is_approve;
				is_nextprocess = _blank_data.is_nextprocess;
				is_jurnal      = _blank_data.is_jurnal;
				is_active      = _blank_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tbl_blank);
			} );

			tbl_blank.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id__blank = '';

				// atur hak akses
				CekDeselectHeaderH(tbl_blank);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
