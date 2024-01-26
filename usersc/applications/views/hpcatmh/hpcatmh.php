<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpcatmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpcatmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kategori</th>
                                <th>Bruto (>)</th>
                                <th>Bruto (<=)</th>
                                <th>TER (%)</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpcatmh/fn/hpcatmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpcatmh, tblhpcatmh, show_inactive_status_hpcatmh = 0, id_hpcatmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpcatmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpcatmh/hpcatmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpcatmh = show_inactive_status_hpcatmh;
					}
				},
				table: "#tblhpcatmh",
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
						def: "hpcatmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpcatmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kategori <sup class='text-danger'>*<sup>",
						name: "hpcatmh.kategori",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "A", "value": "A" },
							{ "label": "B", "value": "B" },
							{ "label": "C", "value": "C" }
						]
					}, 	{
						label: "Bruto (>) <sup class='text-danger'>*<sup>",
						name: "hpcatmh.nominal_awal"
					}, 	{
						label: "Bruto (<=) <sup class='text-danger'>*<sup>",
						name: "hpcatmh.nominal_akhir"
					}, 	{
						label: "TER (%) <sup class='text-danger'>*<sup>",
						name: "hpcatmh.persen"
					}
				]
			} );

			edthpcatmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpcatmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpcatmh.rows().deselect();
				}
			});

			edthpcatmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpcatmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpcatmh.kategori
					if ( ! edthpcatmh.field('hpcatmh.kategori').isMultiValue() ) {
						kategori = edthpcatmh.field('hpcatmh.kategori').val();
						if(!kategori || kategori == ''){
							edthpcatmh.field('hpcatmh.kategori').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hpcatmh.kategori
					
					// BEGIN of validasi hpcatmh.nominal_awal
					if ( ! edthpcatmh.field('hpcatmh.nominal_awal').isMultiValue() ) {
						nominal_awal = edthpcatmh.field('hpcatmh.nominal_awal').val();
						if(!nominal_awal || nominal_awal == ''){
							edthpcatmh.field('hpcatmh.nominal_awal').error( 'Wajib diisi!' );
						}
						if(nominal_awal < 0 ){
							edthpcatmh.field('hpcatmh.nominal_awal').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(nominal_awal) ){
							edthpcatmh.field('hpcatmh.nominal_awal').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hpcatmh.nominal_awal
					
					// BEGIN of validasi hpcatmh.nominal_akhir
					if ( ! edthpcatmh.field('hpcatmh.nominal_akhir').isMultiValue() ) {
						nominal_akhir = edthpcatmh.field('hpcatmh.nominal_akhir').val();
						if(!nominal_akhir || nominal_akhir == ''){
							edthpcatmh.field('hpcatmh.nominal_akhir').error( 'Wajib diisi!' );
						}
						if(nominal_akhir < 0 ){
							edthpcatmh.field('hpcatmh.nominal_akhir').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(nominal_akhir) ){
							edthpcatmh.field('hpcatmh.nominal_akhir').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hpcatmh.nominal_akhir
					
					// BEGIN of validasi hpcatmh.persen
					if ( ! edthpcatmh.field('hpcatmh.persen').isMultiValue() ) {
						persen = edthpcatmh.field('hpcatmh.persen').val();
						if(!persen || persen == ''){
							edthpcatmh.field('hpcatmh.persen').error( 'Wajib diisi!' );
						}
						if(persen < 0 ){
							edthpcatmh.field('hpcatmh.persen').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(persen) ){
							edthpcatmh.field('hpcatmh.persen').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hpcatmh.persen
				}
				
				if ( edthpcatmh.inError() ) {
					return false;
				}
			});
			
			edthpcatmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpcatmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpcatmh = $('#tblhpcatmh').DataTable( {
				ajax: {
					url: "../../models/hpcatmh/hpcatmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpcatmh = show_inactive_status_hpcatmh;
					}
				},
				order: [[ 0, "asc" ]],
				columns: [
					{ data: "hpcatmh.id",visible:false },
					{ data: "hpcatmh.kategori" },
					{ 
						data: "hpcatmh.nominal_awal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ 
						data: "hpcatmh.nominal_akhir",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ 
						data: "hpcatmh.persen",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right" 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpcatmh';
						$table       = 'tblhpcatmh';
						$edt         = 'edthpcatmh';
						$show_status = '_hpcatmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpcatmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpcatmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpcatmh);
			} );
			
			tblhpcatmh.on( 'select', function( e, dt, type, indexes ) {
				hpcatmh_data    = tblhpcatmh.row( { selected: true } ).data().hpcatmh;
				id_hpcatmh      = hpcatmh_data.id;
				id_transaksi_h = id_hpcatmh; // dipakai untuk general
				is_approve     = hpcatmh_data.is_approve;
				is_nextprocess = hpcatmh_data.is_nextprocess;
				is_jurnal      = hpcatmh_data.is_jurnal;
				is_active      = hpcatmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhpcatmh);
			} );

			tblhpcatmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpcatmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhpcatmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
