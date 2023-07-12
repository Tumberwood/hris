<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpr_bpjs';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpr_bpjs" class="table table-striped table-bordered table-hover nowrap" width="100%">
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_bpjs/fn/htpr_bpjs_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpr_bpjs, tblhtpr_bpjs, show_inactive_status_htpr_bpjs = 0, id_htpr_bpjs;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtpr_bpjs = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_bpjs/htpr_bpjs.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_bpjs = show_inactive_status_htpr_bpjs;
					}
				},
				table: "#tblhtpr_bpjs",
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
						def: "htpr_bpjs",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_bpjs.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Efektif",
						name: "htpr_bpjs.tanggal_efektif",
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
						label: "Nominal <sup class='text-danger'>*<sup>",
						name: "htpr_bpjs.nominal"
					}, 	
					{
						label: "Keterangan",
						name: "htpr_bpjs.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtpr_bpjs.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_bpjs.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_bpjs.rows().deselect();
				}
			});

			edthtpr_bpjs.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtpr_bpjs.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpr_bpjs.tanggal_efektif
					if ( ! edthtpr_bpjs.field('htpr_bpjs.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edthtpr_bpjs.field('htpr_bpjs.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edthtpr_bpjs.field('htpr_bpjs.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpr_bpjs.tanggal_efektif

					// BEGIN of validasi htpr_bpjs.nominal
					if ( ! edthtpr_bpjs.field('htpr_bpjs.nominal').isMultiValue() ) {
						nominal = edthtpr_bpjs.field('htpr_bpjs.nominal').val();
						if(!nominal || nominal == ''){
							edthtpr_bpjs.field('htpr_bpjs.nominal').error( 'Wajib diisi!' );
						}
						if(nominal <= 0 ){
							edthtpr_bpjs.field('htpr_bpjs.nominal').error( 'Inputan harus > 0' );
						}
						if(isNaN(nominal) ){
							edthtpr_bpjs.field('htpr_bpjs.nominal').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htpr_bpjs.nominal
					
					
				}
				
				if ( edthtpr_bpjs.inError() ) {
					return false;
				}
			});
			
			edthtpr_bpjs.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_bpjs.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtpr_bpjs = $('#tblhtpr_bpjs').DataTable( {
				ajax: {
					url: "../../models/htpr_bpjs/htpr_bpjs.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_bpjs = show_inactive_status_htpr_bpjs;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htpr_bpjs.id",visible:false },
					{ data: "htpr_bpjs.tanggal_efektif" },
					{ 
						data: "htpr_bpjs.nominal" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htpr_bpjs.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_bpjs';
						$table       = 'tblhtpr_bpjs';
						$edt         = 'edthtpr_bpjs';
						$show_status = '_htpr_bpjs';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_bpjs.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtpr_bpjs.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpr_bpjs);
			} );
			
			tblhtpr_bpjs.on( 'select', function( e, dt, type, indexes ) {
				htpr_bpjs_data    = tblhtpr_bpjs.row( { selected: true } ).data().htpr_bpjs;
				id_htpr_bpjs      = htpr_bpjs_data.id;
				id_transaksi_h = id_htpr_bpjs; // dipakai untuk general
				is_approve     = htpr_bpjs_data.is_approve;
				is_nextprocess = htpr_bpjs_data.is_nextprocess;
				is_jurnal      = htpr_bpjs_data.is_jurnal;
				is_active      = htpr_bpjs_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtpr_bpjs);
			} );

			tblhtpr_bpjs.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpr_bpjs = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtpr_bpjs);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
