<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hppphmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhppphmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>PKP ></th>
                                <th>PKP <=</th>
                                <th>% Pajak</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hppphmh/fn/hppphmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthppphmh, tblhppphmh, show_inactive_status_hppphmh = 0, id_hppphmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthppphmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hppphmh/hppphmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hppphmh = show_inactive_status_hppphmh;
					}
				},
				table: "#tblhppphmh",
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
						def: "hppphmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hppphmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "PKP > <sup class='text-danger'>*<sup>",
						name: "hppphmh.pkp_awal"
					
					}, 	
					{ 	
						label: "PKP <= <sup class='text-danger'>*<sup>",
						name: "hppphmh.pkp_akhir"
					
					},
					{
						label: "% Pajak <sup class='text-danger'>*<sup>",
						name: "hppphmh.pajak"
					}
				]
			} );

			edthppphmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthppphmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhppphmh.rows().deselect();
				}
			});

			edthppphmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthppphmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// END of validasi hppphmh.pkp_awal
					
					// BEGIN of validasi hppphmh.pkp_awal
					if ( ! edthppphmh.field('hppphmh.pkp_awal').isMultiValue() ) {
						pkp_awal = edthppphmh.field('hppphmh.pkp_awal').val();
						if(!pkp_awal || pkp_awal == ''){
							edthppphmh.field('hppphmh.pkp_awal').error( 'Wajib diisi!' );
						}
						if(isNaN(pkp_awal) ){
							edthppphmh.field('hppphmh.pkp_awal').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hppphmh.pkp_awal

					// BEGIN of validasi hppphmh.pajak
					if ( ! edthppphmh.field('hppphmh.pajak').isMultiValue() ) {
						pajak = edthppphmh.field('hppphmh.pajak').val();
						if(!pajak || pajak == ''){
							edthppphmh.field('hppphmh.pajak').error( 'Wajib diisi!' );
						}
						if(pajak <= 0 ){
							edthppphmh.field('hppphmh.pajak').error( 'Inputan harus > 0' );
						}
						if(isNaN(pajak) ){
							edthppphmh.field('hppphmh.pajak').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hppphmh.pajak

					// BEGIN of validasi hppphmh.pkp_akhir
					if ( ! edthppphmh.field('hppphmh.pkp_akhir').isMultiValue() ) {
						pkp_akhir = edthppphmh.field('hppphmh.pkp_akhir').val();
						if(!pkp_akhir || pkp_akhir == ''){
							edthppphmh.field('hppphmh.pkp_akhir').error( 'Wajib diisi!' );
						}
						if(isNaN(pkp_akhir) ){
							edthppphmh.field('hppphmh.pkp_akhir').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hppphmh.pkp_akhir

				}
				
				if ( edthppphmh.inError() ) {
					return false;
				}
			});
			
			edthppphmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthppphmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhppphmh = $('#tblhppphmh').DataTable( {
				ajax: {
					url: "../../models/hppphmh/hppphmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hppphmh = show_inactive_status_hppphmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hppphmh.id",visible:false },
					{ 
						data: "hppphmh.pkp_awal" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hppphmh.pkp_akhir" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ 
						data: "hppphmh.pajak" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hppphmh';
						$table       = 'tblhppphmh';
						$edt         = 'edthppphmh';
						$show_status = '_hppphmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hppphmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhppphmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhppphmh);
			} );
			
			tblhppphmh.on( 'select', function( e, dt, type, indexes ) {
				hppphmh_data    = tblhppphmh.row( { selected: true } ).data().hppphmh;
				id_hppphmh      = hppphmh_data.id;
				id_transaksi_h = id_hppphmh; // dipakai untuk general
				is_approve     = hppphmh_data.is_approve;
				is_nextprocess = hppphmh_data.is_nextprocess;
				is_jurnal      = hppphmh_data.is_jurnal;
				is_active      = hppphmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhppphmh);
			} );

			tblhppphmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hppphmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhppphmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
