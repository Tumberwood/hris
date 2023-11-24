<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hibtkmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
			<div id="frmhibtkmh">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jht_perusahaan"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jht_karyawan"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jkk"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jkm"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jp_perusahaan"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hibtkmh.persen_jp_karyawan"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hibtkmh.gaji_max"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hibtkmh.keterangan"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hibtkmh.tanggal_efektif"></editor-field>
							</div>
						</div>
					</div>
				</div>

				<div class="table-responsive">
                    <table id="tblhibtkmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
							<tr>
                                <th rowspan="2">ID</th>
                                <th colspan="2">JHT</th>
                                <th rowspan="2">JKK</th>
                                <th rowspan="2">JKM</th>
                                <th colspan="2">JP</th>
                                <th rowspan="2">Gaji Maksimal</th>
                                <th rowspan="2">Tanggal Efektif</th>
                                <th rowspan="2">Keterangan</th>
                            </tr>
                            <tr>
                                <th>Perusahaan</th>
                                <th>Karyawan</th>
                                <th>Perusahaan</th>
                                <th>Karyawan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hibtkmh/fn/hibtkmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthibtkmh, tblhibtkmh, show_inactive_status_hibtkmh = 0, id_hibtkmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthibtkmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hibtkmh/hibtkmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hibtkmh = show_inactive_status_hibtkmh;
					}
				},
				table: "#tblhibtkmh",
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
						def: "hibtkmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hibtkmh.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "JHT Ditanggung Perusahaan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jht_perusahaan"
					}, 	
					{
						label: "JHT Ditanggung Karyawan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jht_karyawan"
					}, 	
					{
						label: "JKK Ditanggung Perusahaan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jkk"
					}, 	
					{
						label: "JKM Ditanggung Perusahaan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jkm"
					}, 	
					{
						label: "JP Ditanggung Perusahaan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jp_perusahaan"
					}, 	
					{
						label: "JP Ditanggung Karyawan <sup class='text-danger'>*<sup>",
						name: "hibtkmh.persen_jp_karyawan"
					}, 	
					{
						label: "Gaji Maksimal <sup class='text-danger'>*<sup>",
						name: "hibtkmh.gaji_max"
					}, 	
					{
						label: "Tanggal Efektif <sup class='text-danger'>*<sup>",
						name: "hibtkmh.tanggal_efektif",
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
						name: "hibtkmh.keterangan",
						type: "textarea"
					}
				]
			} );

			edthibtkmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthibtkmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhibtkmh.rows().deselect();
				}
			});

			edthibtkmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthibtkmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hibtkmh.tanggal_efektif
					if ( ! edthibtkmh.field('hibtkmh.tanggal_efektif').isMultiValue() ) {
						tanggal_efektif = edthibtkmh.field('hibtkmh.tanggal_efektif').val();
						if(!tanggal_efektif || tanggal_efektif == ''){
							edthibtkmh.field('hibtkmh.tanggal_efektif').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hibtkmh.persen_jht_perusahaan

					// BEGIN of validasi hibtkmh.persen_jht_perusahaan
					if ( ! edthibtkmh.field('hibtkmh.persen_jht_perusahaan').isMultiValue() ) {
						persen_jht_perusahaan = edthibtkmh.field('hibtkmh.persen_jht_perusahaan').val();
						if(!persen_jht_perusahaan || persen_jht_perusahaan == ''){
							edthibtkmh.field('hibtkmh.persen_jht_perusahaan').error( 'Wajib diisi!' );
						}
						if(persen_jht_perusahaan <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jht_perusahaan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jht_perusahaan) ){
							edthibtkmh.field('hibtkmh.persen_jht_perusahaan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jht_perusahaan

					// BEGIN of validasi hibtkmh.persen_jht_karyawan
					if ( ! edthibtkmh.field('hibtkmh.persen_jht_karyawan').isMultiValue() ) {
						persen_jht_karyawan = edthibtkmh.field('hibtkmh.persen_jht_karyawan').val();
						if(!persen_jht_karyawan || persen_jht_karyawan == ''){
							edthibtkmh.field('hibtkmh.persen_jht_karyawan').error( 'Wajib diisi!' );
						}
						if(persen_jht_karyawan <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jht_karyawan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jht_karyawan) ){
							edthibtkmh.field('hibtkmh.persen_jht_karyawan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jht_karyawan

					// BEGIN of validasi hibtkmh.persen_jkk
					if ( ! edthibtkmh.field('hibtkmh.persen_jkk').isMultiValue() ) {
						persen_jkk = edthibtkmh.field('hibtkmh.persen_jkk').val();
						if(!persen_jkk || persen_jkk == ''){
							edthibtkmh.field('hibtkmh.persen_jkk').error( 'Wajib diisi!' );
						}
						if(persen_jkk <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jkk').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jkk) ){
							edthibtkmh.field('hibtkmh.persen_jkk').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jkk

					// BEGIN of validasi hibtkmh.persen_jkm
					if ( ! edthibtkmh.field('hibtkmh.persen_jkm').isMultiValue() ) {
						persen_jkm = edthibtkmh.field('hibtkmh.persen_jkm').val();
						if(!persen_jkm || persen_jkm == ''){
							edthibtkmh.field('hibtkmh.persen_jkm').error( 'Wajib diisi!' );
						}
						if(persen_jkm <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jkm').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jkm) ){
							edthibtkmh.field('hibtkmh.persen_jkm').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jkm

					// BEGIN of validasi hibtkmh.persen_jp_perusahaan
					if ( ! edthibtkmh.field('hibtkmh.persen_jp_perusahaan').isMultiValue() ) {
						persen_jp_perusahaan = edthibtkmh.field('hibtkmh.persen_jp_perusahaan').val();
						if(!persen_jp_perusahaan || persen_jp_perusahaan == ''){
							edthibtkmh.field('hibtkmh.persen_jp_perusahaan').error( 'Wajib diisi!' );
						}
						if(persen_jp_perusahaan <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jp_perusahaan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jp_perusahaan) ){
							edthibtkmh.field('hibtkmh.persen_jp_perusahaan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jp_perusahaan

					// BEGIN of validasi hibtkmh.persen_jp_karyawan
					if ( ! edthibtkmh.field('hibtkmh.persen_jp_karyawan').isMultiValue() ) {
						persen_jp_karyawan = edthibtkmh.field('hibtkmh.persen_jp_karyawan').val();
						if(!persen_jp_karyawan || persen_jp_karyawan == ''){
							edthibtkmh.field('hibtkmh.persen_jp_karyawan').error( 'Wajib diisi!' );
						}
						if(persen_jp_karyawan <= 0 ){
							edthibtkmh.field('hibtkmh.persen_jp_karyawan').error( 'Inputan harus > 0' );
						}
						if(isNaN(persen_jp_karyawan) ){
							edthibtkmh.field('hibtkmh.persen_jp_karyawan').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.persen_jp_karyawan

					// BEGIN of validasi hibtkmh.gaji_max
					if ( ! edthibtkmh.field('hibtkmh.gaji_max').isMultiValue() ) {
						gaji_max = edthibtkmh.field('hibtkmh.gaji_max').val();
						if(!gaji_max || gaji_max == ''){
							edthibtkmh.field('hibtkmh.gaji_max').error( 'Wajib diisi!' );
						}
						if(gaji_max <= 0 ){
							edthibtkmh.field('hibtkmh.gaji_max').error( 'Inputan harus > 0' );
						}
						if(isNaN(gaji_max) ){
							edthibtkmh.field('hibtkmh.gaji_max').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hibtkmh.gaji_max

				}
				
				if ( edthibtkmh.inError() ) {
					return false;
				}
			});
			
			edthibtkmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthibtkmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhibtkmh = $('#tblhibtkmh').DataTable( {
				ajax: {
					url: "../../models/hibtkmh/hibtkmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hibtkmh = show_inactive_status_hibtkmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hibtkmh.id",visible:false },
					{ 
						data: "hibtkmh.persen_jht_perusahaan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.persen_jht_karyawan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.persen_jkk" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.persen_jkm" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.persen_jp_perusahaan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.persen_jp_karyawan" ,
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ 
						data: "hibtkmh.gaji_max" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "hibtkmh.tanggal_efektif" },
					{ data: "hibtkmh.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hibtkmh';
						$table       = 'tblhibtkmh';
						$edt         = 'edthibtkmh';
						$show_status = '_hibtkmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hibtkmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhibtkmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhibtkmh);
			} );
			
			tblhibtkmh.on( 'select', function( e, dt, type, indexes ) {
				hibtkmh_data    = tblhibtkmh.row( { selected: true } ).data().hibtkmh;
				id_hibtkmh      = hibtkmh_data.id;
				id_transaksi_h = id_hibtkmh; // dipakai untuk general
				is_approve     = hibtkmh_data.is_approve;
				is_nextprocess = hibtkmh_data.is_nextprocess;
				is_jurnal      = hibtkmh_data.is_jurnal;
				is_active      = hibtkmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhibtkmh);
			} );

			tblhibtkmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hibtkmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblhibtkmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
