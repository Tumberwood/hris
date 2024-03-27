<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpr_ti';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpr_ti" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Efektif</th>
                                <th>Jenis</th>
                                <th>Menit Toleransi Istirahat</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_ti/fn/htpr_ti_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpr_ti, tblhtpr_ti, show_inactive_status_htpr_ti = 0, id_htpr_ti;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthtpr_ti = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_ti/htpr_ti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_ti = show_inactive_status_htpr_ti;
					}
				},
				table: "#tblhtpr_ti",
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
						def: "htpr_ti",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_ti.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "htpr_ti.nama",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Toleransi TI", "value": "Toleransi TI" },
							{ "label": "Toleransi Keluar Istirahat", "value": "Toleransi Keluar Istirahat" },
						]
					},
					{
						label: "Menit Toleransi Istirahat <sup class='text-danger'>*<sup>",
						name: "htpr_ti.menit"
					}, 
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "htpr_ti.tanggal_efektif",
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
						name: "htpr_ti.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtpr_ti.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_ti.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_ti.rows().deselect();
				}
			});

			edthtpr_ti.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtpr_ti.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htpr_ti.nama
					if ( ! edthtpr_ti.field('htpr_ti.nama').isMultiValue() ) {
						nama = edthtpr_ti.field('htpr_ti.nama').val();
						if(!nama || nama == ''){
							edthtpr_ti.field('htpr_ti.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpr_ti.persen_perusahaan
					
					// BEGIN of validasi htpr_ti.tanggal_efektif
					if ( ! edthtpr_ti.field('htpr_ti.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edthtpr_ti.field('htpr_ti.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edthtpr_ti.field('htpr_ti.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpr_ti.persen_perusahaan
					

					// BEGIN of validasi htpr_ti.menit
					if ( ! edthtpr_ti.field('htpr_ti.menit').isMultiValue() ) {
						menit = edthtpr_ti.field('htpr_ti.menit').val();
						if(!menit || menit == ''){
							edthtpr_ti.field('htpr_ti.menit').error( 'Wajib diisi!' );
						}
						if(menit <= 0 ){
							edthtpr_ti.field('htpr_ti.menit').error( 'Inputan harus > 0' );
						}
						if(isNaN(menit) ){
							edthtpr_ti.field('htpr_ti.menit').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htpr_ti.menit

				}
				
				if ( edthtpr_ti.inError() ) {
					return false;
				}
			});
			
			edthtpr_ti.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_ti.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtpr_ti = $('#tblhtpr_ti').DataTable( {
				ajax: {
					url: "../../models/htpr_ti/htpr_ti.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_ti = show_inactive_status_htpr_ti;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "htpr_ti.id",visible:false },
					{ data: "htpr_ti.tanggal_efektif" },
					{ data: "htpr_ti.nama" },
					{ 
						data: "htpr_ti.menit" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htpr_ti.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_ti';
						$table       = 'tblhtpr_ti';
						$edt         = 'edthtpr_ti';
						$show_status = '_htpr_ti';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_ti.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtpr_ti.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpr_ti);
			} );
			
			tblhtpr_ti.on( 'select', function( e, dt, type, indexes ) {
				htpr_ti_data    = tblhtpr_ti.row( { selected: true } ).data().htpr_ti;
				id_htpr_ti      = htpr_ti_data.id;
				id_transaksi_h = id_htpr_ti; // dipakai untuk general
				is_approve     = htpr_ti_data.is_approve;
				is_nextprocess = htpr_ti_data.is_nextprocess;
				is_jurnal      = htpr_ti_data.is_jurnal;
				is_active      = htpr_ti_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtpr_ti);
			} );

			tblhtpr_ti.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpr_ti = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtpr_ti);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
