<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htsptth';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htststd';
    $nama_tabels_d[1] = 'htsemtd';
?>

<!-- begin content here -->

<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsptth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jumlah Grup</th>
                                <th>Keterangan</th>
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
				<li><a class="nav-link active" data-toggle="tab" href="#tabhtststd"> Shift</a></li>
				<li><a class="nav-link" data-toggle="tab" href="#tabhtsemtd"> Pegawai</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" id="tabhtststd" class="tab-pane active">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblhtststd" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_htsptth</th>
										<th>Urutan</th>
										<th>Shift</th>
										<th>Mulai Grup ke</th>
										<th>Keterangan</th>
									</tr>
								</thead>
							</table>
						</div> <!-- end of table -->
					</div>
				</div>

				<div role="tabpanel" id="tabhtsemtd" class="tab-pane">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tblhtsemtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id_htsptth</th>
										<th>Grup ke</th>
										<th>Nama</th>
										<th>Department</th>
										<th>Jabatan</th>
										<th>Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsptth/fn/htsptth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsptth, tblhtsptth, show_inactive_status_htsptth = 0, id_htsptth;
        var edthtststd, tblhtststd, show_inactive_status_htststd = 0, id_htststd;
        var edthtsemtd, tblhtsemtd, show_inactive_status_htsemtd = 0, id_htsemtd;
		// ------------- end of default variable

		var id_htsxxmh_old = 0;
		var id_hemxxmh_old = 0;
		var jumlah_grup;
		
		$(document).ready(function() {
			//start datatables editor
			edthtsptth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth/htsptth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth = show_inactive_status_htsptth;
					}
				},
				table: "#tblhtsptth",
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
						def: "htsptth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsptth.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsptth.nama"
					}, 	{
						label: "Jumlah Grup <sup class='text-danger'>*<sup>",
						name: "htsptth.jumlah_grup"
					}, 	{
						label: "Keterangan",
						name: "htsptth.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtsptth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtsptth.rows().deselect();
				}
			});

            edthtsptth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsptth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsptth.nama 
					nama = edthtsptth.field('htsptth.nama').val();
					if(!nama || nama == ''){
						edthtsptth.field('htsptth.nama').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik htsptth.nama 
					if(action == 'create'){
						id_htsptth = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name       : 'htsptth',
							nama_field       : 'nama',
							nama_field_value : '"' + nama + '"',
							id_transaksi     : id_htsptth
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtsptth.field('htsptth.nama').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					
					// END of cek unik htsptth.nama 
					// END of validasi htsptth.nama 
					
					// BEGIN of validasi htsptth.jumlah_grup 
					jumlah_grup = edthtsptth.field('htsptth.jumlah_grup').val();
					if(!jumlah_grup || jumlah_grup == ''){
						edthtsptth.field('htsptth.jumlah_grup').error( 'Wajib diisi!' );
					}
					if(jumlah_grup <= 0 ){
						edthtsptth.field('htsptth.jumlah_grup').error( 'Inputan harus > 0' );
					}
					if(isNaN(jumlah_grup) ){
						edthtsptth.field('htsptth.jumlah_grup').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htsptth.jumlah_grup 
				}
				
				if ( edthtsptth.inError() ) {
					return false;
				}
			});

			edthtsptth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth.field('finish_on').val(finish_on);
			});
			
			edthtsptth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsptth = $('#tblhtsptth').DataTable( {
				ajax: {
					url: "../../models/htsptth/htsptth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth = show_inactive_status_htsptth;
					}
				},
				order: [[ 1, "desc" ]],
				responsive: false,
				columns: [
					{ data: "htsptth.id",visible:false },
					{ data: "htsptth.nama" },
					{ 
						data: "htsptth.jumlah_grup" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htsptth.keterangan" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsptth';
						$table       = 'tblhtsptth';
						$edt         = 'edthtsptth';
						$show_status = '_htsptth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsptth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsptth.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtsemtd, tblhtststd];
				CekInitHeaderHD(tblhtsptth, tbl_details);
			} );
			
			tblhtsptth.on( 'select', function( e, dt, type, indexes ) {
				data_htsptth = tblhtsptth.row( { selected: true } ).data().htsptth;
				id_htsptth  = data_htsptth.id;
				id_transaksi_h   = id_htsptth; // dipakai untuk general
				is_approve       = data_htsptth.is_approve;
				is_nextprocess   = data_htsptth.is_nextprocess;
				is_jurnal        = data_htsptth.is_jurnal;
				is_active        = data_htsptth.is_active;

				jumlah_grup = data_htsptth.jumlah_grup;
				
				// atur hak akses
				tbl_details = [tblhtsemtd, tblhtststd];
				CekSelectHeaderHD(tblhtsptth, tbl_details);

			} );
			
			tblhtsptth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsptth = '';

				// atur hak akses
				tbl_details = [tblhtsemtd, tblhtststd];
				CekDeselectHeaderHD(tblhtsptth, tbl_details);
			} );

// --------- start _detail --------------- //

			//start datatables editor
			edthtststd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth/htststd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htststd = show_inactive_status_htststd;
						d.id_htsptth = id_htsptth;
					}
				},
				table: "#tblhtststd",
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
						def: "htststd",
						type: "hidden"
					},	{
						label: "id_htsptth",
						name: "htststd.id_htsptth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htststd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Urutan <sup class='text-danger'>*<sup>",
						name: "htststd.urutan"
					}, 	{
						label: "Shift <sup class='text-danger'>*<sup>",
						name: "htststd.id_htsxxmh",
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
						label: "Mulai Grup ke <sup class='text-danger'>*<sup>",
						name: "htststd.mulai_grup",
						def: 0
					}, 	{
						label: "Keterangan",
						name: "htststd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtststd.on( 'preOpen', function( e, mode, action ) {
				edthtststd.field('htststd.id_htsptth').val(id_htsptth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtststd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtststd.rows().deselect();
				}
			});

            edthtststd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtststd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htststd.urutan 
					urutan = edthtststd.field('htststd.urutan').val();
					if(!urutan || urutan == ''){
						edthtststd.field('htststd.urutan').error( 'Wajib diisi!' );
					}
					if(urutan <= 0 ){
						edthtststd.field('htststd.urutan').error( 'Inputan harus > 0' );
					}
					if(isNaN(urutan) ){
						edthtststd.field('htststd.urutan').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htststd.urutan 

					// BEGIN of validasi htststd.id_htsxxmh 
					id_htsxxmh = edthtststd.field('htststd.id_htsxxmh').val();
					if(!id_htsxxmh || id_htsxxmh == ''){
						edthtststd.field('htststd.id_htsxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htststd.id_htsxxmh 

					// BEGIN of validasi htststd.mulai_grup 
					mulai_grup = edthtststd.field('htststd.mulai_grup').val();
					if(!mulai_grup || mulai_grup == ''){
						edthtststd.field('htststd.mulai_grup').error( 'Wajib diisi!' );
					}
					if(mulai_grup < 0 ){
						edthtststd.field('htststd.mulai_grup').error( 'Inputan Minimal 0!' );
					}
					if(isNaN(mulai_grup) ){
						edthtststd.field('htststd.mulai_grup').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htststd.mulai_grup 
				}
				
				if ( edthtststd.inError() ) {
					return false;
				}
			});

			edthtststd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtststd.field('finish_on').val(finish_on);
			});

			
			edthtststd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtststd = $('#tblhtststd').DataTable( {
				ajax: {
					url: "../../models/htsptth/htststd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htststd = show_inactive_status_htststd;
						d.id_htsptth = id_htsptth;
					}
				},
				order: [[ 2, "asc" ]],
				columns: [
					{ data: "htststd.id",visible:false },
					{ data: "htststd.id_htsptth",visible:false },
					{ 
						data: "htststd.urutan" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htsxxmh.kode" },
					{ 
						data: "htststd.mulai_grup" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htststd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htststd';
						$table       = 'tblhtststd';
						$edt         = 'edthtststd';
						$show_status = '_htststd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htststd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtststd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth, tblhtststd, 'htststd' );
				CekDrawDetailHDFinal(tblhtsptth);
			} );

			tblhtststd.on( 'select', function( e, dt, type, indexes ) {
				data_htststd = tblhtststd.row( { selected: true } ).data().htststd;
				id_htststd   = data_htststd.id;
				id_transaksi_d    = id_htststd; // dipakai untuk general
				is_active_d       = data_htststd.is_active;

				id_htsxxmh_old = data_htststd.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth, tblhtststd );
			} );

			tblhtststd.on( 'deselect', function() {
				id_htststd = 0;
				is_active_d = 0;
				id_htsxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtsptth, tblhtststd );
			} );

// --------- end _detail --------------- //		

// --------- start _detail --------------- //

			//start datatables editor
			edthtsemtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth/htsemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsemtd = show_inactive_status_htsemtd;
						d.id_htsptth = id_htsptth;
					}
				},
				table: "#tblhtsemtd",
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
						def: "htsemtd",
						type: "hidden"
					},	{
						label: "id_htsptth",
						name: "htsemtd.id_htsptth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsemtd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Grup ke <sup class='text-danger'>*<sup>",
						name: "htsemtd.grup_ke"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsemtd.id_hemxxmh",
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
					},	{
						label: "Keterangan",
						name: "htsemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtsemtd.on( 'preOpen', function( e, mode, action ) {
				edthtsemtd.field('htsemtd.id_htsptth').val(id_htsptth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsemtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsemtd.rows().deselect();
				}
			});

            edthtsemtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsemtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htsemtd.grup_ke 
					grup_ke = edthtsemtd.field('htsemtd.grup_ke').val();
					if(!grup_ke || grup_ke == ''){
						edthtsemtd.field('htsemtd.grup_ke').error( 'Wajib diisi!' );
					}
					if(grup_ke <= 0 ){
						edthtsemtd.field('htsemtd.grup_ke').error( 'Inputan harus > 0' );
					}
					if(isNaN(grup_ke) ){
						edthtsemtd.field('htsemtd.grup_ke').error( 'Inputan harus berupa Angka!' );
					}
					if(grup_ke > jumlah_grup){
						edthtsemtd.field('htsemtd.grup_ke').error( 'Maksimal Jumlah Grup adalah ' + jumlah_grup);
					}
					// END of validasi htsemtd.grup_ke 

					// BEGIN of validasi htsemtd.id_hemxxmh 
					id_hemxxmh = edthtsemtd.field('htsemtd.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthtsemtd.field('htsemtd.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htsemtd.id_hemxxmh 
				}
				
				if ( edthtsemtd.inError() ) {
					return false;
				}
			});

			edthtsemtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsemtd.field('finish_on').val(finish_on);
			});

			
			edthtsemtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsemtd = $('#tblhtsemtd').DataTable( {
				ajax: {
					url: "../../models/htsptth/htsemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsemtd = show_inactive_status_htsemtd;
						d.id_htsptth = id_htsptth;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "htsemtd.id",visible:false },
					{ data: "htsemtd.id_htsptth",visible:false },
					{ data: "htsemtd.grup_ke" },
					{ data: "hemxxmh_data" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "htsemtd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsemtd';
						$table       = 'tblhtsemtd';
						$edt         = 'edthtsemtd';
						$show_status = '_htsemtd';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsemtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtsemtd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth, tblhtsemtd, 'htsemtd' );
				CekDrawDetailHDFinal(tblhtsptth);
			} );

			tblhtsemtd.on( 'select', function( e, dt, type, indexes ) {
				data_htsemtd = tblhtsemtd.row( { selected: true } ).data().htsemtd;
				id_htsemtd   = data_htsemtd.id;
				id_transaksi_d    = id_htsemtd; // dipakai untuk general
				is_active_d       = data_htsemtd.is_active;

				id_hemxxmh_old = data_htsemtd.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth, tblhtsemtd );
			} );

			tblhtsemtd.on( 'deselect', function() {
				id_htsemtd = 0;
				is_active_d = 0;
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectDetailHD(tblhtsptth, tblhtsemtd );
			} );

// --------- end _detail --------------- //		
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
