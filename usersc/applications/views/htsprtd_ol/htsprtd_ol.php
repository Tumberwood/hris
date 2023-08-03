<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsprtd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsprtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
								<th>Nama</th>
								<th>Tanggal</th>
								<th>Jam</th>
								<th>Lokasi</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprtd_ol/fn/htsprtd_ol_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsprtd, tblhtsprtd, show_inactive_status_htsprtd = 0, id_htsprtd;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtsprtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsprtd_ol/htsprtd_ol.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprtd = show_inactive_status_htsprtd;
					}
				},
				table: "#tblhtsprtd",
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
						def: "htsprtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsprtd.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Lat",
						name: "htsprtd.lat",
						// type: "hidden"
						type: "readonly",
						fieldInfo: "Jika tidak ada datanya, maka lokasi tidak terdeteksi<br/>Pastikan akses lokasi sudah aktif!"
					}, 	
					{
						label: "Long",
						name: "htsprtd.lng",
						// type: "hidden",
						type: "readonly",
						fieldInfo: "Jika tidak ada datanya, maka lokasi tidak terdeteksi<br/>Pastikan akses lokasi sudah aktif!"
					}, 
					{
						label: "Foto <sup class='text-danger'>*<sup>",
						name: "htsprtd.id_files_foto",
						type: "upload",
						display: function(id) {
							if (id > 0) {
								return '<img src="' + edthtsprtd.file('files', id).web_path + '"/>';
							} else {
								return 'Belum ada gambar';
							}
						},
						noFileText: 'Belum ada gambar',
						attr: {
							type: 'file',
							accept: 'image/*',
							capture: 'camera'
						}
					}, 
					// {
					// 	label: "Foto",
					// 	name: "temp_id_files",
					// 	type: "hidden"
					// },
					{
						label: "Tanggal",
						name: "htsprtd.tanggal",
						type: "readonly",
						fieldInfo: "Data akan otomatis terisi saat submit"
					},
					{
						label: "Jam",
						name: "htsprtd.jam",
						type: "readonly",
						fieldInfo: "Data akan otomatis terisi saat submit"
					},
					{
						label: "Keterangan",
						name: "htsprtd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtsprtd.on( 'preOpen', function( e, mode, action ) {
				getLocation();
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsprtd.rows().deselect();
				}
			});

			edthtsprtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtsprtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthtsprtd.inError() ) {
					return false;
				}
			});
			
			edthtsprtd.on('initSubmit', function(e, action) {
				tanggal = moment().format('DD MMM YYYY');
				edthtsprtd.field('htsprtd.tanggal').val(tanggal);
				jam = moment().format('HH:mm:ss');
				edthtsprtd.field('htsprtd.jam').val(jam);

				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtsprtd = $('#tblhtsprtd').DataTable( {
				ajax: {
					url: "../../models/htsprtd_ol/htsprtd_ol.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprtd = show_inactive_status_htsprtd;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htsprtd.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "htsprtd.tanggal" },
					{ data: "htsprtd.jam" },
					{ 
                        data: null ,
                        render: function (data, type, row) {
							if(row.htsprtd.lat && row.htsprtd.lng){
                                koordinat = row.htsprtd.lat + ','+row.htsprtd.lng;
                                return '<a href="https://www.google.com/maps/search/?api=1&query='+koordinat+'&z=15" target="_blank=">'+row.htsprtd.lat + ','+row.htsprtd.lng+'</a>';
							}
					   	}
                    },
					{ data: "htsprtd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprtd';
						$table       = 'tblhtsprtd';
						$edt         = 'edthtsprtd';
						$show_status = '_htsprtd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsprtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsprtd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtsprtd);
			} );
			
			tblhtsprtd.on( 'select', function( e, dt, type, indexes ) {
				htsprtd_data    = tblhtsprtd.row( { selected: true } ).data().htsprtd;
				id_htsprtd      = htsprtd_data.id;
				id_transaksi_h = id_htsprtd; // dipakai untuk general
				is_approve     = htsprtd_data.is_approve;
				is_nextprocess = htsprtd_data.is_nextprocess;
				is_jurnal      = htsprtd_data.is_jurnal;
				is_active      = htsprtd_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtsprtd);
			} );

			tblhtsprtd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsprtd = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtsprtd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
