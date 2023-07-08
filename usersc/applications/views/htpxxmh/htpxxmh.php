<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Potong Gaji</th>
                                <th>Potong Premi</th>
                                <th>Jenis Jam</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpxxmh/fn/htpxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpxxmh, tblhtpxxmh, show_inactive_status_htpxxmh = 0, id_htpxxmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtpxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpxxmh/htpxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpxxmh = show_inactive_status_htpxxmh;
					}
				},
				table: "#tblhtpxxmh",
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
						def: "htpxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "htpxxmh.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htpxxmh.nama"
					}, 	{
						label: "Potong Gaji",
						name: "htpxxmh.is_potong_gaji",
						type: "select",
						placeholder : "Select",
						fieldInfo: "Akan memotong gaji, berlaku per 1 jam, kelipatan.",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					}, 	{
						label: "Potong Premi",
						name: "htpxxmh.is_potong_premi",
						type: "select",
						placeholder : "Select",
						fieldInfo: "Akan memotong Premi Hadir bulanan.",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},	{
						label: "Jenis Jam <sup class='text-danger'>*<sup>",
						name: "htpxxmh.jenis_jam",
						type: "select",
						placeholder : "Select",
						fieldInfo: "Inputan jam yang harus diisi saat pengajuan Izin",
						options: [
							{ "label": "Awal", "value": 1 },
							{ "label": "Akhir", "value": 2 },
							{ "label": "Awal dan Akhir", "value": 3 }
						]
					},	{
						label: "Keterangan",
						name: "htpxxmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtpxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpxxmh.rows().deselect();
				}
			});

			edthtpxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtpxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpxxmh.kode
					if ( ! edthtpxxmh.field('htpxxmh.kode').isMultiValue() ) {
						kode = edthtpxxmh.field('htpxxmh.kode').val();
						if(!kode || kode == ''){
							edthtpxxmh.field('htpxxmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htpxxmh.kode
						if(action == 'create'){
							id_htpxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htpxxmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_htpxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtpxxmh.field('htpxxmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htpxxmh.kode
					}
					// END of validasi htpxxmh.kode
					
					// BEGIN of validasi htpxxmh.nama
					if ( ! edthtpxxmh.field('htpxxmh.nama').isMultiValue() ) {
						nama = edthtpxxmh.field('htpxxmh.nama').val();
						if(!nama || nama == ''){
							edthtpxxmh.field('htpxxmh.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik htpxxmh.nama
						if(action == 'create'){
							id_htpxxmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htpxxmh',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_htpxxmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtpxxmh.field('htpxxmh.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htpxxmh.nama
					}
					// END of validasi htpxxmh.nama

					// BEGIN of validasi htpxxmh.jenis_jam
					if ( ! edthtpxxmh.field('htpxxmh.jenis_jam').isMultiValue() ) {
						jenis_jam = edthtpxxmh.field('htpxxmh.jenis_jam').val();
						if(!jenis_jam || jenis_jam == ''){
							edthtpxxmh.field('htpxxmh.jenis_jam').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpxxmh.jenis_jam
				}
				
				if ( edthtpxxmh.inError() ) {
					return false;
				}
			});
			
			edthtpxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpxxmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtpxxmh = $('#tblhtpxxmh').DataTable( {
				ajax: {
					url: "../../models/htpxxmh/htpxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpxxmh = show_inactive_status_htpxxmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htpxxmh.id",visible:false },
					{ data: "htpxxmh.kode" },
					{ data: "htpxxmh.nama" },
					{ 
						data: "htpxxmh.is_potong_gaji",
						render: function (data){
							if (data == 0){
								return '<i class="fa fa-remove"></i>';
							}else if(data == 1){
								return '<i class="fa fa-check text-danger"></i>';
							}else{
								return '';
							}
						}
					},
					{ 
						data: "htpxxmh.is_potong_premi",
						render: function (data){
							if (data == 0){
								return '<i class="fa fa-remove"></i>';
							}else if(data == 1){
								return '<i class="fa fa-check text-danger"></i>';
							}else{
								return '';
							}
						}
					},
					{ 
						data: "htpxxmh.jenis_jam" ,
						render: function (data){
							if(data == 1){
								return 'Awal';
							}else if(data == 2){
								return 'Akhir';
							}else if(data == 3){
								return 'Awal dan Akhir';
							}else{
								return 'Invalid';
							}
						}
					},
					{ data: "htpxxmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpxxmh';
						$table       = 'tblhtpxxmh';
						$edt         = 'edthtpxxmh';
						$show_status = '_htpxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtpxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpxxmh);
			} );
			
			tblhtpxxmh.on( 'select', function( e, dt, type, indexes ) {
				htpxxmh_data    = tblhtpxxmh.row( { selected: true } ).data().htpxxmh;
				id_htpxxmh      = htpxxmh_data.id;
				id_transaksi_h = id_htpxxmh; // dipakai untuk general
				is_approve     = htpxxmh_data.is_approve;
				is_nextprocess = htpxxmh_data.is_nextprocess;
				is_jurnal      = htpxxmh_data.is_jurnal;
				is_active      = htpxxmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtpxxmh);
			} );

			tblhtpxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpxxmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtpxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
