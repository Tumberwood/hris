<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'makan_catering_hl_umum';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblmakan_catering_hl_umum" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Jumlah</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/makan_catering_hl_umum/fn/makan_catering_hl_umum_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmakan_catering_hl_umum, tblmakan_catering_hl_umum, show_inactive_status_makan_catering_hl_umum = 0, id_makan_catering_hl_umum;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtmakan_catering_hl_umum = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/makan_catering_hl_umum/makan_catering_hl_umum.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_hl_umum = show_inactive_status_makan_catering_hl_umum;
					}
				},
				table: "#tblmakan_catering_hl_umum",
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
						def: "makan_catering_hl_umum",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_catering_hl_umum.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal <sup class='text-danger'>*<sup>", 
						name: "makan_catering_hl_umum.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Shift <sup class='text-danger'>*<sup>", 
						name: "makan_catering_hl_umum.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Shift 1", "value": "Shift 1" },
							{ "label": "Shift 2", "value": "Shift 2" },
							{ "label": "Shift 3", "value": "Shift 3" },
						]
					},	
					{
						label: "Jumlah <sup class='text-danger'>*<sup>",
						name: "makan_catering_hl_umum.jumlah"
					}, 	
					{
						label: "Keterangan",
						name: "makan_catering_hl_umum.keterangan",
						type: "textarea"
					},
				]
			} );

			edtmakan_catering_hl_umum.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_hl_umum.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_catering_hl_umum.rows().deselect();
				}
			});

			edtmakan_catering_hl_umum.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtmakan_catering_hl_umum.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					tanggal = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.tanggal').val();
					if(!tanggal || tanggal == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.tanggal').error( 'Wajib diisi!' );
					}

					shift = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.shift').val();
					if(!shift || shift == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.shift').error( 'Wajib diisi!' );
					}

					// BEGIN of validasi makan_catering_hl_umum.jumlah 
					jumlah = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').val();
					if(!jumlah || jumlah == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(jumlah <= 0 ){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah) ){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi makan_catering_hl_umum.jumlah 

					
				}
				
				if ( edtmakan_catering_hl_umum.inError() ) {
					return false;
				}
			});
			
			edtmakan_catering_hl_umum.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_hl_umum.field('finish_on').val(finish_on);
			});

			//start datatables
			tblmakan_catering_hl_umum = $('#tblmakan_catering_hl_umum').DataTable( {
				ajax: {
					url: "../../models/makan_catering_hl_umum/makan_catering_hl_umum.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_hl_umum = show_inactive_status_makan_catering_hl_umum;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_catering_hl_umum.id",visible:false },
					{ data: "makan_catering_hl_umum.tanggal" },
					{ data: "makan_catering_hl_umum.shift" },
					{ data: "makan_catering_hl_umum.jumlah" },
					{ data: "makan_catering_hl_umum.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_catering_hl_umum';
						$table       = 'tblmakan_catering_hl_umum';
						$edt         = 'edtmakan_catering_hl_umum';
						$show_status = '_makan_catering_hl_umum';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_catering_hl_umum.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblmakan_catering_hl_umum.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblmakan_catering_hl_umum);
			} );
			
			tblmakan_catering_hl_umum.on( 'select', function( e, dt, type, indexes ) {
				makan_catering_hl_umum_data    = tblmakan_catering_hl_umum.row( { selected: true } ).data().makan_catering_hl_umum;
				id_makan_catering_hl_umum      = makan_catering_hl_umum_data.id;
				id_transaksi_h = id_makan_catering_hl_umum; // dipakai untuk general
				is_approve     = makan_catering_hl_umum_data.is_approve;
				is_nextprocess = makan_catering_hl_umum_data.is_nextprocess;
				is_jurnal      = makan_catering_hl_umum_data.is_jurnal;
				is_active      = makan_catering_hl_umum_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblmakan_catering_hl_umum);
			} );

			tblmakan_catering_hl_umum.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_catering_hl_umum = '';

				// atur hak akses
				CekDeselectHeaderH(tblmakan_catering_hl_umum);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
