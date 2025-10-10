<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'periode_payroll';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblperiode_payroll" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
								<th>Tanggal Awal</th>
								<th>Tanggal Akhir</th>
                                <th>Status</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/periode_payroll/fn/periode_payroll_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtperiode_payroll, tblperiode_payroll, show_inactive_status_periode_payroll = 0, id_periode_payroll;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtperiode_payroll = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/periode_payroll/periode_payroll.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_periode_payroll = show_inactive_status_periode_payroll;
					}
				},
				table: "#tblperiode_payroll",
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
						def: "periode_payroll",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "periode_payroll.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "periode_payroll.tanggal_awal",
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
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "periode_payroll.tanggal_akhir",
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
						label: "Status",
						name: "periode_payroll.status",
						type: "select",
						placeholder : "Select",
						def: "Dibuka",
						options: [
							{ "label": "Ditutup", "value":  "Ditutup" },
							{ "label": "Dibuka", "value":  "Dibuka" },
						]
					},
					{
						label: "Keterangan",
						name: "periode_payroll.keterangan",
						type: "textarea"
					}
				]
			} );

			edtperiode_payroll.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtperiode_payroll.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblperiode_payroll.rows().deselect();
				}
			});

			edtperiode_payroll.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtperiode_payroll.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi periode_payroll.tanggal_awal
					if ( ! edtperiode_payroll.field('periode_payroll.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edtperiode_payroll.field('periode_payroll.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edtperiode_payroll.field('periode_payroll.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi periode_payroll.tanggal_awal

					// BEGIN of validasi periode_payroll.tanggal_akhir
					if ( ! edtperiode_payroll.field('periode_payroll.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edtperiode_payroll.field('periode_payroll.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edtperiode_payroll.field('periode_payroll.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi periode_payroll.tanggal_akhir
					
				}
				
				if ( edtperiode_payroll.inError() ) {
					return false;
				}
			});
			
			edtperiode_payroll.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtperiode_payroll.field('finish_on').val(finish_on);
			});

			//start datatables
			tblperiode_payroll = $('#tblperiode_payroll').DataTable( {
				ajax: {
					url: "../../models/periode_payroll/periode_payroll.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_periode_payroll = show_inactive_status_periode_payroll;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "periode_payroll.id",visible:false },
					{ data: "periode_payroll.tanggal_awal" },
					{ data: "periode_payroll.tanggal_akhir" },
					{ data: "periode_payroll.status" },
					{ data: "periode_payroll.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_periode_payroll';
						$table       = 'tblperiode_payroll';
						$edt         = 'edtperiode_payroll';
						$show_status = '_periode_payroll';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.periode_payroll.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblperiode_payroll.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblperiode_payroll);
			} );
			
			tblperiode_payroll.on( 'select', function( e, dt, type, indexes ) {
				periode_payroll_data    = tblperiode_payroll.row( { selected: true } ).data().periode_payroll;
				id_periode_payroll      = periode_payroll_data.id;
				id_transaksi_h = id_periode_payroll; // dipakai untuk general
				is_approve     = periode_payroll_data.is_approve;
				is_nextprocess = periode_payroll_data.is_nextprocess;
				is_jurnal      = periode_payroll_data.is_jurnal;
				is_active      = periode_payroll_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblperiode_payroll);
			} );

			tblperiode_payroll.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_periode_payroll = '';

				// atur hak akses
				CekDeselectHeaderH(tblperiode_payroll);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
