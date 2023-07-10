<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hadxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhadxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Masa Berlaku</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hadxxmh/fn/hadxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthadxxmh, tblhadxxmh, show_inactive_status_hadxxmh = 0, id_hadxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthadxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hadxxmh/hadxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxmh = show_inactive_status_hadxxmh;
					}
				},
				table: "#tblhadxxmh",
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
						def: "hadxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hadxxmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hadxxmh.kode"
					}, 	
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hadxxmh.nama"
					}, 	
					{
						label: "Masa Berlaku (Hari) <sup class='text-danger'>*<sup>",
						name: "hadxxmh.masa_berlaku_hari"
					}, 	
					{
						label: "Keterangan",
						name: "hadxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthadxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhadxxmh.rows().deselect();
				}
			});

			edthadxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthadxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hadxxmh.kode
					if ( ! edthadxxmh.field('hadxxmh.kode').isMultiValue() ) {
						kode = edthadxxmh.field('hadxxmh.kode').val();
						if(!kode || kode == ''){
							edthadxxmh.field('hadxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hadxxmh.kode
						if(action == 'create'){
							id_hadxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hadxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_hadxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthadxxmh.field('hadxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hadxxmh.kode
					}
					// END of validasi hadxxmh.kode
					
					// BEGIN of validasi hadxxmh.nama
					if ( ! edthadxxmh.field('hadxxmh.nama').isMultiValue() ) {
						nama = edthadxxmh.field('hadxxmh.nama').val();
						if(!nama || nama == ''){
							edthadxxmh.field('hadxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hadxxmh.nama
						if(action == 'create'){
							id_hadxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hadxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_hadxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthadxxmh.field('hadxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hadxxmh.nama
					}
					// END of validasi hadxxmh.nama

					// BEGIN of validasi hadxxmh.masa_berlaku_hari
					if ( ! edthadxxmh.field('hadxxmh.masa_berlaku_hari').isMultiValue() ) {
						masa_berlaku_hari = edthadxxmh.field('hadxxmh.masa_berlaku_hari').val();
						if(!masa_berlaku_hari || masa_berlaku_hari == ''){
							edthadxxmh.field('hadxxmh.masa_berlaku_hari').error( 'Wajib diisi!' );
						}
						if(masa_berlaku_hari <= 0 ){
							edthadxxmh.field('hadxxmh.masa_berlaku_hari').error( 'Inputan harus > 0' );
						}
						if(isNaN(masa_berlaku_hari) ){
							edthadxxmh.field('hadxxmh.masa_berlaku_hari').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hadxxmh.masa_berlaku_hari
				}
				
				if ( edthadxxmh.inError() ) {
					return false;
				}
			});
			
			edthadxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhadxxmh = $('#tblhadxxmh').DataTable( {
				ajax: {
					url: "../../models/hadxxmh/hadxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxmh = show_inactive_status_hadxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hadxxmh.id",visible:false },
					{ data: "hadxxmh.kode" },
					{ data: "hadxxmh.nama" },
					{ 
						data: "hadxxmh.masa_berlaku_hari" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "hadxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hadxxmh';
						$table       = 'tblhadxxmh';
						$edt         = 'edthadxxmh';
						$show_status = '_hadxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hadxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhadxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhadxxmh);
			} );
			
			tblhadxxmh.on( 'select', function( e, dt, type, indexes ) {
				hadxxmh_data    = tblhadxxmh.row( { selected: true } ).data().hadxxmh;
				id_hadxxmh      = hadxxmh_data.id;
				id_transaksi_h = id_hadxxmh; // dipakai untuk general
				is_approve     = hadxxmh_data.is_approve;
				is_nextprocess = hadxxmh_data.is_nextprocess;
				is_jurnal      = hadxxmh_data.is_jurnal;
				is_active      = hadxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhadxxmh);
			} );

			tblhadxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hadxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhadxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
