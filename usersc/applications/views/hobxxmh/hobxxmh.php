<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hobxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhobxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hobxxmh/fn/hobxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthobxxmh, tblhobxxmh, show_inactive_status_hobxxmh = 0, id_hobxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthobxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hobxxmh/hobxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hobxxmh = show_inactive_status_hobxxmh;
					}
				},
				table: "#tblhobxxmh",
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
						def: "hobxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hobxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hobxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hobxxmh.nama"
					}, 	{
						label: "Keterangan",
						name: "hobxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthobxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthobxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhobxxmh.rows().deselect();
				}
			});

			edthobxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthobxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hobxxmh.kode
					if ( ! edthobxxmh.field('hobxxmh.kode').isMultiValue() ) {
						kode = edthobxxmh.field('hobxxmh.kode').val();
						if(!kode || kode == ''){
							edthobxxmh.field('hobxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hobxxmh.kode
						if(action == 'create'){
							id_hobxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hobxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_hobxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthobxxmh.field('hobxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hobxxmh.kode
					}
					// END of validasi hobxxmh.kode
					
					// BEGIN of validasi hobxxmh.nama
					if ( ! edthobxxmh.field('hobxxmh.nama').isMultiValue() ) {
						nama = edthobxxmh.field('hobxxmh.nama').val();
						if(!nama || nama == ''){
							edthobxxmh.field('hobxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hobxxmh.nama
						if(action == 'create'){
							id_hobxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'hobxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_hobxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthobxxmh.field('hobxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hobxxmh.nama
					}
					// END of validasi hobxxmh.nama
				}
				
				if ( edthobxxmh.inError() ) {
					return false;
				}
			});
			
			edthobxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthobxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhobxxmh = $('#tblhobxxmh').DataTable( {
				ajax: {
					url: "../../models/hobxxmh/hobxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hobxxmh = show_inactive_status_hobxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hobxxmh.id",visible:false },
					{ data: "hobxxmh.kode" },
					{ data: "hobxxmh.nama" },
					{ data: "hobxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hobxxmh';
						$table       = 'tblhobxxmh';
						$edt         = 'edthobxxmh';
						$show_status = '_hobxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hobxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhobxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhobxxmh);
			} );
			
			tblhobxxmh.on( 'select', function( e, dt, type, indexes ) {
				hobxxmh_data    = tblhobxxmh.row( { selected: true } ).data().hobxxmh;
				id_hobxxmh      = hobxxmh_data.id;
				id_transaksi_h = id_hobxxmh; // dipakai untuk general
				is_approve     = hobxxmh_data.is_approve;
				is_nextprocess = hobxxmh_data.is_nextprocess;
				is_jurnal      = hobxxmh_data.is_jurnal;
				is_active      = hobxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhobxxmh);
			} );

			tblhobxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hobxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhobxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
