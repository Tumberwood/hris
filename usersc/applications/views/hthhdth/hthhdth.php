<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hthhdth';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhthhdth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hthhdth/fn/hthhdth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edththhdth, tblhthhdth, show_inactive_status_hthhdth = 0, id_hthhdth;
		// ------------- end of default variable

		var is_need_approval = 1;
		
		$(document).ready(function() {
			//start datatables editor
			edththhdth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hthhdth/hthhdth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hthhdth = show_inactive_status_hthhdth;
					}
				},
				table: "#tblhthhdth",
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
						def: "hthhdth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hthhdth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "hthhdth.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Hari Libur <sup class='text-danger'>*<sup>",
						name: "hthhdth.nama"
					}
				]
			} );

			edththhdth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edththhdth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhthhdth.rows().deselect();
				}
			});

			edththhdth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edththhdth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hthhdth.tanggal
					if ( ! edththhdth.field('hthhdth.tanggal').isMultiValue() ) {
						tanggal = edththhdth.field('hthhdth.tanggal').val();
						if(!tanggal || tanggal == ''){
							edththhdth.field('hthhdth.tanggal').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik hthhdth.tanggal
						if(action == 'create'){
							id_hthhdth = 0;
						}
						
						tanggal_ymd = moment(tanggal).format('YYYY-MM-DD');
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'hthhdth',
								nama_field: 'tanggal',
								nama_field_value: '"'+tanggal_ymd+'"',
								id_transaksi: id_hthhdth
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edththhdth.field('hthhdth.tanggal').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik hthhdth.tanggal
					}
					// END of validasi hthhdth.tanggal
					
					if ( ! edththhdth.field('hthhdth.tanggal').isMultiValue() ) {
						// BEGIN of validasi hthhdth.nama 
						hthhdth_nama = edththhdth.field('hthhdth.nama').val();
						if(!hthhdth_nama || hthhdth_nama == ''){
							edththhdth.field('hthhdth.nama').error( 'Wajib diisi!' );
						}
						// END of validasi hthhdth.nama 	
					}
				}
				
				if ( edththhdth.inError() ) {
					return false;
				}
			});
			
			edththhdth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edththhdth.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhthhdth = $('#tblhthhdth').DataTable( {
				ajax: {
					url: "../../models/hthhdth/hthhdth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hthhdth = show_inactive_status_hthhdth;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hthhdth.id",visible:false },
					{ data: "hthhdth.tanggal" },
					{ data: "hthhdth.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hthhdth';
						$table       = 'tblhthhdth';
						$edt         = 'edththhdth';
						$show_status = '_hthhdth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hthhdth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhthhdth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhthhdth);
			} );
			
			tblhthhdth.on( 'select', function( e, dt, type, indexes ) {
				hthhdth_data    = tblhthhdth.row( { selected: true } ).data().hthhdth;
				id_hthhdth      = hthhdth_data.id;
				id_transaksi_h = id_hthhdth; // dipakai untuk general
				is_approve     = hthhdth_data.is_approve;
				is_nextprocess = hthhdth_data.is_nextprocess;
				is_jurnal      = hthhdth_data.is_jurnal;
				is_active      = hthhdth_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhthhdth);
			} );

			tblhthhdth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hthhdth = '';

				// atur hak akses
				CekDeselectHeaderH(tblhthhdth);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
