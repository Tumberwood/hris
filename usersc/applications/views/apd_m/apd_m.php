<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'apd_m';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblapd_m" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Efektif</th>
                                <th>Nominal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/apd_m/fn/apd_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtapd_m, tblapd_m, show_inactive_status_apd_m = 0, id_apd_m;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtapd_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/apd_m/apd_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_apd_m = show_inactive_status_apd_m;
					}
				},
				table: "#tblapd_m",
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
						def: "apd_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "apd_m.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Nominal <sup class='text-danger'>*<sup>",
						name: "apd_m.nominal"
					}, 
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "apd_m.tanggal_efektif",
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
						name: "apd_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtapd_m.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtapd_m.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblapd_m.rows().deselect();
				}
			});

			edtapd_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtapd_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi apd_m.tanggal_efektif
					if ( ! edtapd_m.field('apd_m.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edtapd_m.field('apd_m.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edtapd_m.field('apd_m.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}

					// BEGIN of validasi apd_m.nominal
					if ( ! edtapd_m.field('apd_m.nominal').isMultiValue() ) {
						nominal = edtapd_m.field('apd_m.nominal').val();
						if(!nominal || nominal == ''){
							edtapd_m.field('apd_m.nominal').error( 'Wajib diisi!' );
						}
						if(nominal <= 0 ){
							edtapd_m.field('apd_m.nominal').error( 'Inputan harus > 0' );
						}
						if(isNaN(nominal) ){
							edtapd_m.field('apd_m.nominal').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi apd_m.nominal

				}
				
				if ( edtapd_m.inError() ) {
					return false;
				}
			});
			
			edtapd_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtapd_m.field('finish_on').val(finish_on);
			});

			//start datatables
			tblapd_m = $('#tblapd_m').DataTable( {
				ajax: {
					url: "../../models/apd_m/apd_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_apd_m = show_inactive_status_apd_m;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "apd_m.id",visible:false },
					{ data: "apd_m.tanggal_efektif" },
					{ 
						data: "apd_m.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "apd_m.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_apd_m';
						$table       = 'tblapd_m';
						$edt         = 'edtapd_m';
						$show_status = '_apd_m';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.apd_m.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblapd_m.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblapd_m);
			} );
			
			tblapd_m.on( 'select', function( e, dt, type, indexes ) {
				apd_m_data    = tblapd_m.row( { selected: true } ).data().apd_m;
				id_apd_m      = apd_m_data.id;
				id_transaksi_h = id_apd_m; // dipakai untuk general
				is_approve     = apd_m_data.is_approve;
				is_nextprocess = apd_m_data.is_nextprocess;
				is_jurnal      = apd_m_data.is_jurnal;
				is_active      = apd_m_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblapd_m);
			} );

			tblapd_m.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_apd_m = '';

				// atur hak akses
				CekDeselectHeaderH(tblapd_m);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
