<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2">ID</th>
                                <th rowspan="2">Kode</th>
                                <th colspan="2">Jam Kerja</th>
                                <th colspan="2">Istirahat</th>
                                <th colspan="2">Durasi Toleransi Masuk</th>
                                <th colspan="2">Durasi Toleransi Pulang</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
							<tr>
                                <th>Awal</th>
                                <th>Akhir</th>
                                <th>Awal</th>
                                <th>Akhir</th>
                                <th>Awal</th>
                                <th>Akhir</th>
								<th>Awal</th>
                                <th>Akhir</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsxxmh/fn/htsxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsxxmh, tblhtsxxmh, show_inactive_status_htsxxmh = 0, id_htsxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtsxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsxxmh/htsxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsxxmh = show_inactive_status_htsxxmh;
					}
				},
				table: "#tblhtsxxmh",
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
						def: "htsxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "htsxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsxxmh.nama"
					}, 	{
						label: "Jam Awal <sup class='text-danger'>*<sup>",
						name: "htsxxmh.jam_awal",
						type: "datetime",
						format: 'HH:mm'
					}, 	{
						label: "Jam Akhir <sup class='text-danger'>*<sup>",
						name: "htsxxmh.jam_akhir",
						type: "datetime",
						format: 'HH:mm'
					}, 	{
						label: "Jam Awal Istirahat",
						name: "htsxxmh.jam_awal_istirahat",
						type: "datetime",
						format: 'HH:mm'
					}, 	{
						label: "Jam Akhir Istirahat",
						name: "htsxxmh.jam_akhir_istirahat",
						type: "datetime",
						format: 'HH:mm'
					},	{
						label: "Toleransi Awal Masuk (menit) <sup class='text-danger'>*<sup>",
						name: "htsxxmh.menit_toleransi_awal_in"
					},	{
						label: "Toleransi Akhir Masuk (menit) <sup class='text-danger'>*<sup>",
						name: "htsxxmh.menit_toleransi_akhir_in"
					},	{
						label: "Toleransi Awal Pulang (menit) <sup class='text-danger'>*<sup>",
						name: "htsxxmh.menit_toleransi_awal_out"
					},	{
						label: "Toleransi Akhir Pulang (menit) <sup class='text-danger'>*<sup>",
						name: "htsxxmh.menit_toleransi_akhir_out"
					},	{
						label: "Keterangan",
						name: "htsxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtsxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsxxmh.rows().deselect();
				}
			});

			edthtsxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtsxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsxxmh.kode
					if ( ! edthtsxxmh.field('htsxxmh.kode').isMultiValue() ) {
						kode = edthtsxxmh.field('htsxxmh.kode').val();
						if(!kode || kode == ''){
							edthtsxxmh.field('htsxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htsxxmh.kode
						if(action == 'create'){
							id_htsxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'htsxxmh',
								nama_field       : 'kode',
								nama_field_value : '"' + kode + '"',
								id_transaksi     : id_htsxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtsxxmh.field('htsxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htsxxmh.kode
					}
					// END of validasi htsxxmh.kode
					
					// BEGIN of validasi htsxxmh.nama
					if ( ! edthtsxxmh.field('htsxxmh.nama').isMultiValue() ) {
						nama = edthtsxxmh.field('htsxxmh.nama').val();
						if(!nama || nama == ''){
							edthtsxxmh.field('htsxxmh.nama').error( 'Wajib diisi!' );
						}

						// BEGIN of cek unik htsxxmh.nama
						if(action == 'create'){
							id_htsxxmh = 0;
						}
						
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name       : 'htsxxmh',
								nama_field       : 'nama',
								nama_field_value : '"' + nama + '"',
								id_transaksi     : id_htsxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtsxxmh.field('htsxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htsxxmh.nama
					}
					// END of validasi htsxxmh.nama

					// BEGIN of validasi htsxxmh.jam_awal
					if ( ! edthtsxxmh.field('htsxxmh.jam_awal').isMultiValue() ) {
						jam_awal = edthtsxxmh.field('htsxxmh.jam_awal').val();
						if(!jam_awal || jam_awal == ''){
							edthtsxxmh.field('htsxxmh.jam_awal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsxxmh.jam_awal

					// BEGIN of validasi htsxxmh.jam_akhir
					if ( ! edthtsxxmh.field('htsxxmh.jam_akhir').isMultiValue() ) {
						jam_akhir = edthtsxxmh.field('htsxxmh.jam_akhir').val();
						if(!jam_akhir || jam_akhir == ''){
							edthtsxxmh.field('htsxxmh.jam_akhir').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsxxmh.jam_akhir

					// BEGIN of validasi htsxxmh.menit_toleransi_awal_in
					if ( ! edthtsxxmh.field('htsxxmh.menit_toleransi_awal_in').isMultiValue() ) {
						menit_toleransi_awal_in = edthtsxxmh.field('htsxxmh.menit_toleransi_awal_in').val();
						if(!menit_toleransi_awal_in || menit_toleransi_awal_in == ''){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_in').error( 'Wajib diisi!' );
						}

						if(menit_toleransi_awal_in < 0 ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_in').error( 'Inputan minimal 0' );
						}
						if(isNaN(menit_toleransi_awal_in) ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_in').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htsxxmh.menit_toleransi_awal_in

					// BEGIN of validasi htsxxmh.menit_toleransi_akhir_in
					if ( ! edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_in').isMultiValue() ) {
						menit_toleransi_akhir_in = edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_in').val();
						if(!menit_toleransi_akhir_in || menit_toleransi_akhir_in == ''){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_in').error( 'Wajib diisi!' );
						}

						if(menit_toleransi_akhir_in < 0 ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_in').error( 'Inputan minimal 0' );
						}
						if(isNaN(menit_toleransi_akhir_in) ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_in').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htsxxmh.menit_toleransi_akhir_in

					// BEGIN of validasi htsxxmh.menit_toleransi_awal_out
					if ( ! edthtsxxmh.field('htsxxmh.menit_toleransi_awal_out').isMultiValue() ) {
						menit_toleransi_awal_out = edthtsxxmh.field('htsxxmh.menit_toleransi_awal_out').val();
						if(!menit_toleransi_awal_out || menit_toleransi_awal_out == ''){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_out').error( 'Wajib diisi!' );
						}

						if(menit_toleransi_awal_out < 0 ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_out').error( 'Inputan minimal 0' );
						}
						if(isNaN(menit_toleransi_awal_out) ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_awal_out').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htsxxmh.menit_toleransi_awal_out

					// BEGIN of validasi htsxxmh.menit_toleransi_akhir_out
					if ( ! edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_out').isMultiValue() ) {
						menit_toleransi_akhir_out = edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_out').val();
						if(!menit_toleransi_akhir_out || menit_toleransi_akhir_out == ''){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_out').error( 'Wajib diisi!' );
						}

						if(menit_toleransi_akhir_out < 0 ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_out').error( 'Inputan minimal 0' );
						}
						if(isNaN(menit_toleransi_akhir_out) ){
							edthtsxxmh.field('htsxxmh.menit_toleransi_akhir_out').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htsxxmh.menit_toleransi_akhir_out

				}
				
				if ( edthtsxxmh.inError() ) {
					return false;
				}
			});
			
			edthtsxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtsxxmh = $('#tblhtsxxmh').DataTable( {
				ajax: {
					url: "../../models/htsxxmh/htsxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsxxmh = show_inactive_status_htsxxmh;
					}
				},
				order: [[ 0, "asc" ]],
				columns: [
					{ data: "htsxxmh.id",visible:false },
					{ data: "htsxxmh.kode" },
					{ data: "htsxxmh.jam_awal" },
					{ data: "htsxxmh.jam_akhir" },
					{ data: "htsxxmh.jam_awal_istirahat" },
					{ data: "htsxxmh.jam_akhir_istirahat" },
					{ data: "htsxxmh.menit_toleransi_awal_in" },
					{ data: "htsxxmh.menit_toleransi_akhir_in" },
					{ data: "htsxxmh.menit_toleransi_awal_out" },
					{ data: "htsxxmh.menit_toleransi_akhir_out" },
					{ data: "htsxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsxxmh';
						$table       = 'tblhtsxxmh';
						$edt         = 'edthtsxxmh';
						$show_status = '_htsxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtsxxmh);
			} );
			
			tblhtsxxmh.on( 'select', function( e, dt, type, indexes ) {
				htsxxmh_data    = tblhtsxxmh.row( { selected: true } ).data().htsxxmh;
				id_htsxxmh      = htsxxmh_data.id;
				id_transaksi_h = id_htsxxmh; // dipakai untuk general
				is_approve     = htsxxmh_data.is_approve;
				is_nextprocess = htsxxmh_data.is_nextprocess;
				is_jurnal      = htsxxmh_data.is_jurnal;
				is_active      = htsxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtsxxmh);
			} );

			tblhtsxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtsxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
