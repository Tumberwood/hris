<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htsptth_new';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'pola_shift_new';
    $nama_tabels_d[1] = 'htststd_new';
    $nama_tabels_d[2] = 'htsemtd_new';
?>

<!-- begin content here -->

<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsptth_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jumlah Grup</th>
                                <th>Jadwal Terakhir</th>
                                <th>Keterangan</th>
                                <th>Tukar Jadwal</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="tabs-container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a class="nav-link active" data-toggle="tab" href="#tabpola_shift_new"> Pola Shift</a></li>
				<li><a class="nav-link" data-toggle="tab" href="#tabhtststd_new"> Shift</a></li>
				<li><a class="nav-link" data-toggle="tab" href="#tabhtsemtd_new"> Pegawai</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" id="tabpola_shift_new" class="tab-pane active">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblpola_shift_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_htsptth_new</th>
										<th>Grup</th>
										<th>Shift</th>
									</tr>
								</thead>
							</table>
						</div> <!-- end of table -->
					</div>
				</div>

				<div role="tabpanel" id="tabhtststd_new" class="tab-pane">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblhtststd_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_htsptth_new</th>
										<th>Urutan</th>
										<th>Jam</th>
										<th>Shift</th>
									</tr>
								</thead>
							</table>
						</div> <!-- end of table -->
					</div>
				</div>

				<div role="tabpanel" id="tabhtsemtd_new" class="tab-pane">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblhtsemtd_new" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_htsptth_new</th>
										<th>Grup</th>
										<th>Nama</th>
										<th>Department</th>
										<th>Jabatan</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

</div> <!-- end of row -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsptth_new/fn/htsptth_new_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsptth_new, tblhtsptth_new, show_inactive_status_htsptth_new = 0, id_htsptth_new;
        var edtpola_shift_new, tblpola_shift_new, show_inactive_status_pola_shift_new = 0, id_pola_shift_new;
        var edthtststd_new, tblhtststd_new, show_inactive_status_htststd_new = 0, id_htststd_new;
        var edthtsemtd_new, tblhtsemtd_new, show_inactive_status_htsemtd_new = 0, id_htsemtd_new;
		// ------------- end of default variable

		var id_htsxxmh_old = 0;
		var id_hemxxmh_old = 0;
		var jumlah_grup;
		
		$(document).ready(function() {
			//start datatables editor
			edthtsptth_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_new/htsptth_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth_new = show_inactive_status_htsptth_new;
					}
				},
				table: "#tblhtsptth_new",
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
						def: "htsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsptth_new.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsptth_new.nama"
					}, 	{
						label: "Jumlah Grup <sup class='text-danger'>*<sup>",
						name: "htsptth_new.jumlah_grup"
					}, 	{
						label: "Keterangan",
						name: "htsptth_new.keterangan",
						type: "textarea"
					}, {
						label: "Tukar Jadwal",
						name: "htsptth_new.is_tukar",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 0 }
						]
					},
				]
			} );
			
			edthtsptth_new.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth_new.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtsptth_new.rows().deselect();
				}
			});

            edthtsptth_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsptth_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsptth_new.nama 
					nama = edthtsptth_new.field('htsptth_new.nama').val();
					if(!nama || nama == ''){
						edthtsptth_new.field('htsptth_new.nama').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik htsptth_new.nama 
					if(action == 'create'){
						id_htsptth_new = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name       : 'htsptth_new',
							nama_field       : 'nama',
							nama_field_value : '"' + nama + '"',
							id_transaksi     : id_htsptth_new
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtsptth_new.field('htsptth_new.nama').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					
					// END of cek unik htsptth_new.nama 
					// END of validasi htsptth_new.nama 
					
					// BEGIN of validasi htsptth_new.jumlah_grup 
					jumlah_grup = edthtsptth_new.field('htsptth_new.jumlah_grup').val();
					if(!jumlah_grup || jumlah_grup == ''){
						edthtsptth_new.field('htsptth_new.jumlah_grup').error( 'Wajib diisi!' );
					}
					if(jumlah_grup <= 0 ){
						edthtsptth_new.field('htsptth_new.jumlah_grup').error( 'Inputan harus > 0' );
					}
					if(isNaN(jumlah_grup) ){
						edthtsptth_new.field('htsptth_new.jumlah_grup').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htsptth_new.jumlah_grup 
				}
				
				if ( edthtsptth_new.inError() ) {
					return false;
				}
			});

			edthtsptth_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth_new.field('finish_on').val(finish_on);
			});
			
			edthtsptth_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsptth_new = $('#tblhtsptth_new').DataTable( {
				ajax: {
					url: "../../models/htsptth_new/htsptth_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth_new = show_inactive_status_htsptth_new;
					}
				},
				order: [[ 1, "desc" ]],
				responsive: false,
				columns: [
					{ data: "htsptth_new.id",visible:false },
					{ data: "htsptth_new.nama" },
					{ 
						data: "htsptth_new.jumlah_grup" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htsptth_new.generated_on" },
					{ data: "htsptth_new.keterangan" },
					{ 
						data: "htsptth_new.is_tukar",
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}
						} 
					}
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsptth_new';
						$table       = 'tblhtsptth_new';
						$edt         = 'edthtsptth_new';
						$show_status = '_htsptth_new';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsptth_new.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsptth_new.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblpola_shift_new, tblhtsemtd_new, tblhtststd_new];
				CekInitHeaderHD(tblhtsptth_new, tbl_details);
			} );
			
			tblhtsptth_new.on( 'select', function( e, dt, type, indexes ) {
				data_htsptth_new = tblhtsptth_new.row( { selected: true } ).data().htsptth_new;
				id_htsptth_new  = data_htsptth_new.id;
				id_transaksi_h   = id_htsptth_new; // dipakai untuk general
				is_approve       = data_htsptth_new.is_approve;
				is_nextprocess   = data_htsptth_new.is_nextprocess;
				is_jurnal        = data_htsptth_new.is_jurnal;
				is_active        = data_htsptth_new.is_active;

				jumlah_grup = data_htsptth_new.jumlah_grup;
				
				// atur hak akses
				tbl_details = [tblpola_shift_new, tblhtsemtd_new, tblhtststd_new];
				CekSelectHeaderHD(tblhtsptth_new, tbl_details);

			} );
			
			tblhtsptth_new.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsptth_new = '';

				// atur hak akses
				tbl_details = [tblpola_shift_new, tblhtsemtd_new, tblhtststd_new];
				CekDeselectHeaderHD(tblhtsptth_new, tbl_details);
			} );

// --------- start _detail --------------- //

			//start datatables editor
			edtpola_shift_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_new/pola_shift_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pola_shift_new = show_inactive_status_pola_shift_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				table: "#tblpola_shift_new",
				formOptions: {
					main: {
						focus: 3
					}
				},
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
						def: "pola_shift_new",
						type: "hidden"
					},	{
						label: "id_htsptth_new",
						name: "pola_shift_new.id_htsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "pola_shift_new.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Grup <sup class='text-danger'>*<sup>",
						name: "pola_shift_new.grup"
					}, 	{
						label: "Shift <sup class='text-danger'>*<sup>",
						name: "pola_shift_new.shift",
						def: 0
					}
				]
			} );
			
			edtpola_shift_new.on( 'preOpen', function( e, mode, action ) {
				edtpola_shift_new.field('pola_shift_new.id_htsptth_new').val(id_htsptth_new);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpola_shift_new.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblpola_shift_new.rows().deselect();
				}
			});

            edtpola_shift_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtpola_shift_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi pola_shift_new.grup 
					grup = edtpola_shift_new.field('pola_shift_new.grup').val();
					if(!grup || grup == ''){
						edtpola_shift_new.field('pola_shift_new.grup').error( 'Wajib diisi!' );
					}
					if(grup <= 0 ){
						edtpola_shift_new.field('pola_shift_new.grup').error( 'Inputan harus > 0' );
					}
					if(isNaN(grup) ){
						edtpola_shift_new.field('pola_shift_new.grup').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi pola_shift_new.grup 
					
					// BEGIN of validasi pola_shift_new.shift 
					shift = edtpola_shift_new.field('pola_shift_new.shift').val();
					if(!shift || shift == ''){
						edtpola_shift_new.field('pola_shift_new.shift').error( 'Wajib diisi!' );
					}
					if(shift <= 0 ){
						edtpola_shift_new.field('pola_shift_new.shift').error( 'Inputan harus > 0' );
					}
					if(isNaN(shift) ){
						edtpola_shift_new.field('pola_shift_new.shift').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi pola_shift_new.shift 

				}
				
				if ( edtpola_shift_new.inError() ) {
					return false;
				}
			});

			edtpola_shift_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpola_shift_new.field('finish_on').val(finish_on);
			});

			
			edtpola_shift_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblpola_shift_new = $('#tblpola_shift_new').DataTable( {
				ajax: {
					url: "../../models/htsptth_new/pola_shift_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_pola_shift_new = show_inactive_status_pola_shift_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				order: [[ 2, "asc" ]],
				columns: [
					{ data: "pola_shift_new.id",visible:false },
					{ data: "pola_shift_new.id_htsptth_new",visible:false },
					{ 
						data: "pola_shift_new.grup" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ 
						data: "pola_shift_new.shift" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_pola_shift_new';
						$table       = 'tblpola_shift_new';
						$edt         = 'edtpola_shift_new';
						$show_status = '_pola_shift_new';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.pola_shift_new.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblpola_shift_new.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth_new, tblpola_shift_new, 'pola_shift_new' );
				CekDrawDetailHDFinal(tblhtsptth_new);
			} );

			tblpola_shift_new.on( 'select', function( e, dt, type, indexes ) {
				data_pola_shift_new = tblpola_shift_new.row( { selected: true } ).data().pola_shift_new;
				id_pola_shift_new   = data_pola_shift_new.id;
				id_transaksi_d    = id_pola_shift_new; // dipakai untuk general
				is_active_d       = data_pola_shift_new.is_active;

				id_htsxxmh_old = data_pola_shift_new.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth_new, tblpola_shift_new );
			} );

			tblpola_shift_new.on( 'deselect', function() {
				id_pola_shift_new = 0;
				is_active_d = 0;
				id_htsxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtsptth_new, tblpola_shift_new );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthtststd_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_new/htststd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htststd_new = show_inactive_status_htststd_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				table: "#tblhtststd_new",
				formOptions: {
					main: {
						focus: 3
					}
				},
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
						def: "htststd_new",
						type: "hidden"
					},	{
						label: "id_htsptth_new",
						name: "htststd_new.id_htsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htststd_new.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Urutan <sup class='text-danger'>*<sup>",
						name: "htststd_new.urutan"
					}, 	{
						label: "Jam <sup class='text-danger'>*<sup>",
						name: "htststd_new.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									return {
										results: data.results,
										pagination: {
											more: true
										}
									};
								},
								cache: true,
								minimumInputLength: 1,
								maximum: 10,
								delay: 500,
								maximumSelectionLength: 5,
								minimumResultsForSearch: -1,
							},
						}
					}, 	{
						label: "Shift <sup class='text-danger'>*<sup>",
						name: "htststd_new.shift",
						def: 0
					}
				]
			} );
			
			edthtststd_new.on( 'preOpen', function( e, mode, action ) {
				edthtststd_new.field('htststd_new.id_htsptth_new').val(id_htsptth_new);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtststd_new.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtststd_new.rows().deselect();
				}
			});

            edthtststd_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtststd_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htststd_new.urutan 
					urutan = edthtststd_new.field('htststd_new.urutan').val();
					if(!urutan || urutan == ''){
						edthtststd_new.field('htststd_new.urutan').error( 'Wajib diisi!' );
					}
					if(urutan <= 0 ){
						edthtststd_new.field('htststd_new.urutan').error( 'Inputan harus > 0' );
					}
					if(isNaN(urutan) ){
						edthtststd_new.field('htststd_new.urutan').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htststd_new.urutan 

					// BEGIN of validasi htststd_new.id_htsxxmh 
					id_htsxxmh = edthtststd_new.field('htststd_new.id_htsxxmh').val();
					if(!id_htsxxmh || id_htsxxmh == ''){
						edthtststd_new.field('htststd_new.id_htsxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htststd_new.id_htsxxmh 

					// BEGIN of validasi htststd_new.shift 
					shift = edthtststd_new.field('htststd_new.shift').val();
					if(!shift || shift == ''){
						edthtststd_new.field('htststd_new.shift').error( 'Wajib diisi!' );
					}
					if(shift <= 0 ){
						edthtststd_new.field('htststd_new.shift').error( 'Inputan Harus Lebih dari 0!' );
					}
					if(isNaN(shift) ){
						edthtststd_new.field('htststd_new.shift').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htststd_new.shift 
				}
				
				if ( edthtststd_new.inError() ) {
					return false;
				}
			});

			edthtststd_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtststd_new.field('finish_on').val(finish_on);
			});

			
			edthtststd_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtststd_new = $('#tblhtststd_new').DataTable( {
				ajax: {
					url: "../../models/htsptth_new/htststd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htststd_new = show_inactive_status_htststd_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				order: [[ 0, "asc" ]],
				columns: [
					{ data: "htststd_new.id",visible:false },
					{ data: "htststd_new.id_htsptth_new",visible:false },
					{ 
						data: "htststd_new.urutan" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htsxxmh.kode" },
					{ 
						data: "htststd_new.shift" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htststd_new';
						$table       = 'tblhtststd_new';
						$edt         = 'edthtststd_new';
						$show_status = '_htststd_new';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htststd_new.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtststd_new.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth_new, tblhtststd_new, 'htststd_new' );
				CekDrawDetailHDFinal(tblhtsptth_new);
			} );

			tblhtststd_new.on( 'select', function( e, dt, type, indexes ) {
				data_htststd_new = tblhtststd_new.row( { selected: true } ).data().htststd_new;
				id_htststd_new   = data_htststd_new.id;
				id_transaksi_d    = id_htststd_new; // dipakai untuk general
				is_active_d       = data_htststd_new.is_active;

				id_htsxxmh_old = data_htststd_new.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth_new, tblhtststd_new );
			} );

			tblhtststd_new.on( 'deselect', function() {
				id_htststd_new = 0;
				is_active_d = 0;
				id_htsxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtsptth_new, tblhtststd_new );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthtsemtd_new = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_new/htsemtd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsemtd_new = show_inactive_status_htsemtd_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				table: "#tblhtsemtd_new",
				formOptions: {
					main: {
						focus: 3
					}
				},
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
						def: "htsemtd_new",
						type: "hidden"
					},	{
						label: "id_htsptth_new",
						name: "htsemtd_new.id_htsptth_new",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsemtd_new.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Grup <sup class='text-danger'>*<sup>",
						name: "htsemtd_new.grup"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsemtd_new.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									return {
										results: data.results,
										pagination: {
											more: true
										}
									};
								},
								cache: true,
								minimumInputLength: 1,
								maximum: 10,
								delay: 500,
								maximumSelectionLength: 5,
								minimumResultsForSearch: -1,
							},
						}
					}
				]
			} );
			
			edthtsemtd_new.on( 'preOpen', function( e, mode, action ) {
				edthtsemtd_new.field('htsemtd_new.id_htsptth_new').val(id_htsptth_new);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsemtd_new.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsemtd_new.rows().deselect();
				}
			});

            edthtsemtd_new.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsemtd_new.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htsemtd_new.grup 
					grup = edthtsemtd_new.field('htsemtd_new.grup').val();
					if(!grup || grup == ''){
						edthtsemtd_new.field('htsemtd_new.grup').error( 'Wajib diisi!' );
					}
					if(grup <= 0 ){
						edthtsemtd_new.field('htsemtd_new.grup').error( 'Inputan harus > 0' );
					}
					if(isNaN(grup) ){
						edthtsemtd_new.field('htsemtd_new.grup').error( 'Inputan harus berupa Angka!' );
					}
					if(grup > jumlah_grup){
						edthtsemtd_new.field('htsemtd_new.grup').error( 'Maksimal Jumlah Grup adalah ' + jumlah_grup);
					}
					// END of validasi htsemtd_new.grup 

					// BEGIN of validasi htsemtd_new.id_hemxxmh 
					id_hemxxmh = edthtsemtd_new.field('htsemtd_new.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthtsemtd_new.field('htsemtd_new.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htsemtd_new.id_hemxxmh 
				}
				
				if ( edthtsemtd_new.inError() ) {
					return false;
				}
			});

			edthtsemtd_new.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsemtd_new.field('finish_on').val(finish_on);
			});

			
			edthtsemtd_new.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsemtd_new = $('#tblhtsemtd_new').DataTable( {
				ajax: {
					url: "../../models/htsptth_new/htsemtd_new.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsemtd_new = show_inactive_status_htsemtd_new;
						d.id_htsptth_new = id_htsptth_new;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "htsemtd_new.id",visible:false },
					{ data: "htsemtd_new.id_htsptth_new",visible:false },
					{ data: "htsemtd_new.grup" },
					{ data: "hemxxmh_data" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsemtd_new';
						$table       = 'tblhtsemtd_new';
						$edt         = 'edthtsemtd_new';
						$show_status = '_htsemtd_new';
						$table_name  = $nama_tabels_d[2];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsemtd_new.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtsemtd_new.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth_new, tblhtsemtd_new, 'htsemtd_new' );
				CekDrawDetailHDFinal(tblhtsptth_new);
			} );

			tblhtsemtd_new.on( 'select', function( e, dt, type, indexes ) {
				data_htsemtd_new = tblhtsemtd_new.row( { selected: true } ).data().htsemtd_new;
				id_htsemtd_new   = data_htsemtd_new.id;
				id_transaksi_d    = id_htsemtd_new; // dipakai untuk general
				is_active_d       = data_htsemtd_new.is_active;

				id_hemxxmh_old = data_htsemtd_new.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth_new, tblhtsemtd_new );
			} );

			tblhtsemtd_new.on( 'deselect', function() {
				id_htsemtd_new = 0;
				is_active_d = 0;
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectDetailHD(tblhtsptth_new, tblhtsemtd_new );
			} );

// --------- end _detail --------------- //		
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
