<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hibksmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhibksmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>% Perusahaan</th>
                                <th>% Karyawan</th>
                                <th>Gaji Maksimal</th>
                                <th>Tanggal Efektif</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hibksmh/fn/hibksmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthibksmh, tblhibksmh, show_inactive_status_hibksmh = 0, id_hibksmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthibksmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hibksmh/hibksmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hibksmh = show_inactive_status_hibksmh;
					}
				},
				table: "#tblhibksmh",
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
						def: "hibksmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hibksmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "% Perusahaan <sup class='text-danger'>*<sup>",
						name: "hibksmh.persen_perusahaan"
					
					}, 	{
						label: "% Karyawan <sup class='text-danger'>*<sup>",
						name: "hibksmh.persen_karyawan"
					}, 	
					{
						label: "Gaji Maksimal <sup class='text-danger'>*<sup>",
						name: "hibksmh.gaji_max"
					}, 
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "hibksmh.tanggal_efektif",
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
						name: "hibksmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthibksmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthibksmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhibksmh.rows().deselect();
				}
			});

			edthibksmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthibksmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hibksmh.tanggal_efektif
					if ( ! edthibksmh.field('hibksmh.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edthibksmh.field('hibksmh.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edthibksmh.field('hibksmh.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hibksmh.persen_perusahaan
					
					// BEGIN of validasi hibksmh.persen_perusahaan
					if ( ! edthibksmh.field('hibksmh.persen_perusahaan').isMultiValue() ) {
						persen_perusahaan = edthibksmh.field('hibksmh.persen_perusahaan').val();
						if(!persen_perusahaan || persen_perusahaan == ''){
							edthibksmh.field('hibksmh.persen_perusahaan').error( 'Wajib diisi!' );
						}
						if(persen_perusahaan <= 0 ){
							edthibksmh.field('hibksmh.persen_perusahaan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_perusahaan) ){
							edthibksmh.field('hibksmh.persen_perusahaan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibksmh.persen_perusahaan

					// BEGIN of validasi hibksmh.persen_karyawan
					if ( ! edthibksmh.field('hibksmh.persen_karyawan').isMultiValue() ) {
						persen_karyawan = edthibksmh.field('hibksmh.persen_karyawan').val();
						if(!persen_karyawan || persen_karyawan == ''){
							edthibksmh.field('hibksmh.persen_karyawan').error( 'Wajib diisi!' );
						}
						if(persen_karyawan <= 0 ){
							edthibksmh.field('hibksmh.persen_karyawan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_karyawan) ){
							edthibksmh.field('hibksmh.persen_karyawan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibksmh.persen_karyawan

					// BEGIN of validasi hibksmh.gaji_max
					if ( ! edthibksmh.field('hibksmh.gaji_max').isMultiValue() ) {
						gaji_max = edthibksmh.field('hibksmh.gaji_max').val();
						if(!gaji_max || gaji_max == ''){
							edthibksmh.field('hibksmh.gaji_max').error( 'Wajib diisi!' );
						}
						if(gaji_max <= 0 ){
							edthibksmh.field('hibksmh.gaji_max').error( 'Inputan harus > 0' );
						}
						if(isNaN(gaji_max) ){
							edthibksmh.field('hibksmh.gaji_max').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibksmh.gaji_max

				}
				
				if ( edthibksmh.inError() ) {
					return false;
				}
			});
			
			edthibksmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthibksmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhibksmh = $('#tblhibksmh').DataTable( {
				ajax: {
					url: "../../models/hibksmh/hibksmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hibksmh = show_inactive_status_hibksmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hibksmh.id",visible:false },
					{ 
						data: "hibksmh.persen_perusahaan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibksmh.persen_karyawan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibksmh.gaji_max" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "hibksmh.tanggal_efektif" },
					{ data: "hibksmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hibksmh';
						$table       = 'tblhibksmh';
						$edt         = 'edthibksmh';
						$show_status = '_hibksmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hibksmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhibksmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhibksmh);
			} );
			
			tblhibksmh.on( 'select', function( e, dt, type, indexes ) {
				hibksmh_data    = tblhibksmh.row( { selected: true } ).data().hibksmh;
				id_hibksmh      = hibksmh_data.id;
				id_transaksi_h = id_hibksmh; // dipakai untuk general
				is_approve     = hibksmh_data.is_approve;
				is_nextprocess = hibksmh_data.is_nextprocess;
				is_jurnal      = hibksmh_data.is_jurnal;
				is_active      = hibksmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhibksmh);
			} );

			tblhibksmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hibksmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhibksmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
