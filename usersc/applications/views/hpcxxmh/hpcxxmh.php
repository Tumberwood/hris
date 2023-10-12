<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpcxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpcxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Periode</th>
                                <th>Komp Tetap</th>
                                <th>Lain-lain</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpcxxmh/fn/hpcxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpcxxmh, tblhpcxxmh, show_inactive_status_hpcxxmh = 0, id_hpcxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpcxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpcxxmh/hpcxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpcxxmh = show_inactive_status_hpcxxmh;
					}
				},
				table: "#tblhpcxxmh",
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
						def: "hpcxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpcxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hpcxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hpcxxmh.nama"
					}, 	{
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "hpcxxmh.jenis",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Penambah", "value": 1 },
							{ "label": "Pengurang", "value": 2 }
						]
					}, 	{
						label: "Periode <sup class='text-danger'>*<sup>",
						name: "hpcxxmh.periode",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Jam", "value": "Jam" },
							{ "label": "Harian", "value": "Harian" },
							{ "label": "Mingguan", "value": "Mingguan" },
							{ "label": "Bulanan", "value": "Bulanan" },
							{ "label": "Tahunan", "value": "Tahunan" }
						]
					}, 	{
						label: "Fix / Variable <sup class='text-danger'>*<sup>",
						name: "hpcxxmh.is_fix",
						type: "select",
						placeholder : "Select",
						def: 1,
						options: [
							{ "label": "Variable", "value": 0 },
							{ "label": "Fix", "value": 1 }
						]
					},
					{
						label: "Lain-lain",
						name: "hpcxxmh.is_lain",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},	
					{
						label: "Nominal",
						name: "hpcxxmh.nominal"
					},
					{
						label: "Keterangan",
						name: "hpcxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthpcxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpcxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpcxxmh.rows().deselect();
				}
			});

			edthpcxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpcxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpcxxmh.kode
					if ( ! edthpcxxmh.field('hpcxxmh.kode').isMultiValue() ) {
						kode = edthpcxxmh.field('hpcxxmh.kode').val();
						if(!kode || kode == ''){
							edthpcxxmh.field('hpcxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hpcxxmh.kode
						if(action == 'create'){
							id_hpcxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hpcxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_hpcxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthpcxxmh.field('hpcxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hpcxxmh.kode
					}
					// END of validasi hpcxxmh.kode
					
					// BEGIN of validasi hpcxxmh.nama
					if ( ! edthpcxxmh.field('hpcxxmh.nama').isMultiValue() ) {
						nama = edthpcxxmh.field('hpcxxmh.nama').val();
						if(!nama || nama == ''){
							edthpcxxmh.field('hpcxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hpcxxmh.nama
						if(action == 'create'){
							id_hpcxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hpcxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_hpcxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthpcxxmh.field('hpcxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hpcxxmh.nama
					}
					// END of validasi hpcxxmh.nama

					// BEGIN of validasi hpcxxmh.jenis
					if ( ! edthpcxxmh.field('hpcxxmh.jenis').isMultiValue() ) {
						jenis = edthpcxxmh.field('hpcxxmh.jenis').val();
						if(!jenis || jenis == ''){
							edthpcxxmh.field('hpcxxmh.jenis').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hpcxxmh.jenis

					// BEGIN of validasi hpcxxmh.periode
					if ( ! edthpcxxmh.field('hpcxxmh.periode').isMultiValue() ) {
						periode = edthpcxxmh.field('hpcxxmh.periode').val();
						if(!periode || periode == ''){
							edthpcxxmh.field('hpcxxmh.periode').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hpcxxmh.periode

					// BEGIN of validasi hpcxxmh.is_lain
					// is_lain = edthpcxxmh.field('hpcxxmh.is_lain').val();
					// if(is_lain == ''){
					// 	edthpcxxmh.field('hpcxxmh.is_lain').error( 'Wajib diisi!' );
					// }
					// END of validasi hpcxxmh.is_lain
					
					//  validasi blank
					nominal = edthpcxxmh.field('hpcxxmh.nominal').val();
					if(nominal != ''){
						// validasi min atau max angka
						if(nominal <= 0 ){
							edthpcxxmh.field('hpcxxmh.nominal').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(nominal) ){
							edthpcxxmh.field('hpcxxmh.nominal').error( 'Inputan harus berupa Angka!' );
						}
					}
				}
				
				if ( edthpcxxmh.inError() ) {
					return false;
				}
			});
			
			edthpcxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpcxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpcxxmh = $('#tblhpcxxmh').DataTable( {
				ajax: {
					url: "../../models/hpcxxmh/hpcxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpcxxmh = show_inactive_status_hpcxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hpcxxmh.id",visible:false },
					{ data: "hpcxxmh.kode" },
					{ data: "hpcxxmh.nama" },
					{ 
						data: "hpcxxmh.jenis" ,
						render: function (data){
							if (data == 1){
								return 'Penambah';
							}else if(data == 2){
								return 'Pengurang';
							}else{
								return '<span class="text-danger">Invalid Data</span>';
							}
						}
					},
					{ data: "hpcxxmh.periode" },
					{ 
						data: "hpcxxmh.is_fix" ,
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else{
								return '<span class="text-danger">Invalid Data</span>';
							}
						}
					},
					{ 
						data: "hpcxxmh.is_lain" ,
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else{
								return '<span class="text-danger">Invalid Data</span>';
							}
						}
					},
					{ data: "hpcxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpcxxmh';
						$table       = 'tblhpcxxmh';
						$edt         = 'edthpcxxmh';
						$show_status = '_hpcxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpcxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpcxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpcxxmh);
			} );
			
			tblhpcxxmh.on( 'select', function( e, dt, type, indexes ) {
				hpcxxmh_data    = tblhpcxxmh.row( { selected: true } ).data().hpcxxmh;
				id_hpcxxmh      = hpcxxmh_data.id;
				id_transaksi_h = id_hpcxxmh; // dipakai untuk general
				is_approve     = hpcxxmh_data.is_approve;
				is_nextprocess = hpcxxmh_data.is_nextprocess;
				is_jurnal      = hpcxxmh_data.is_jurnal;
				is_active      = hpcxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhpcxxmh);
			} );

			tblhpcxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpcxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhpcxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
