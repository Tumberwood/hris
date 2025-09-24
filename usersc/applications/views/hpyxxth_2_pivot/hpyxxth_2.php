<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpyxxth_2';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					Sebelum melakukan Generate Payroll, pastikan sudah melakukan Approve data-data pada menu berikut ini!!!
					<ul>
						<li>Report Presensi</li>
						<li>Payroll Lain-lain</li>
					</ul>
				</div>
				<div class="table-responsive">
					<table id="tblhpyxxth_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Periode</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Generated On</th>
                            </tr>
                        </thead>
                    </table>
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhpyemtd_2"> All</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd_2" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<div id="tabel_atas"></div>
									</div> <!-- end of table -->
								</div>
							</div>
						</div>

					</div>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth_2/fn/hpyxxth_2_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth_2, tblhpyxxth_2, show_inactive_status_hpyxxth_2 = 0, id_hpyxxth_2;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpyxxth_2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyxxth_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_2 = show_inactive_status_hpyxxth_2;
					}
				},
				table: "#tblhpyxxth_2",
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
						def: "hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyxxth_2.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hpyxxth_2.tanggal_awal",
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
						name: "hpyxxth_2.tanggal_akhir",
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
						label: "Keterangan",
						name: "hpyxxth_2.keterangan",
						type: "textarea"
					}
				]
			} );

			edthpyxxth_2.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyxxth_2.rows().deselect();
				}
			});

			edthpyxxth_2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpyxxth_2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpyxxth_2.tanggal_awal
					if ( ! edthpyxxth_2.field('hpyxxth_2.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthpyxxth_2.field('hpyxxth_2.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthpyxxth_2.field('hpyxxth_2.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_2.tanggal_awal

					// BEGIN of validasi hpyxxth_2.tanggal_akhir
					if ( ! edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_2.tanggal_akhir

				}
				
				if ( edthpyxxth_2.inError() ) {
					return false;
				}
			});
			
			edthpyxxth_2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_2.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpyxxth_2 = $('#tblhpyxxth_2').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyxxth_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_2 = show_inactive_status_hpyxxth_2;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hpyxxth_2.id",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth_2.tanggal_awal + " - " + row.hpyxxth_2.tanggal_akhir;
					   	}
					},
					{ data: "heyxxmh.nama",visible:false },
					{ data: "hpyxxth_2.keterangan" },
					{ data: "hpyxxth_2.generated_on" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyxxth_2';
						$table       = 'tblhpyxxth_2';
						$edt         = 'edthpyxxth_2';
						$show_status = '_hpyxxth_2';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensi',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hpyxxth_2/hpyxxth_2_fn_gen_payroll_pivot.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hpyxxth_2		: id_hpyxxth_2,
									tanggal_awal	: tanggal_awal_select,
									tanggal_akhir	: tanggal_akhir_select,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhpyxxth_2.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);

									generateTable(id_hpyxxth_2);
								}
							} );
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth_2.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpyxxth_2.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpyxxth_2);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			tblhpyxxth_2.on( 'select', function( e, dt, type, indexes ) {
				hpyxxth_2_data    = tblhpyxxth_2.row( { selected: true } ).data().hpyxxth_2;
				id_hpyxxth_2      = hpyxxth_2_data.id;
				id_transaksi_h = id_hpyxxth_2; // dipakai untuk general
				is_approve     = hpyxxth_2_data.is_approve;
				is_nextprocess = hpyxxth_2_data.is_nextprocess;
				is_jurnal      = hpyxxth_2_data.is_jurnal;
				is_active      = hpyxxth_2_data.is_active;
				tanggal_awal_select        = hpyxxth_2_data.tanggal_awal;
				tanggal_akhir_select        = hpyxxth_2_data.tanggal_akhir;
				id_heyxxmh_select        = hpyxxth_2_data.id_heyxxmh;

				id_heyxxmh_old = hpyxxth_2_data.id_heyxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhpyxxth_2);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).enable();
				generateTable(id_hpyxxth_2);
			} );

			tblhpyxxth_2.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth_2 = '';
				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpyxxth_2);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).disable();
        		$('#tabel_atas').empty();	
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
