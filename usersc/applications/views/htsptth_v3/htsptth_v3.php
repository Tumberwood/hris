<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htsptth_v3';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htspttd_v3';
?>

<!-- begin content here -->

<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsptth_v3" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jumlah Grup</th>
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
	<div class="ibox ">
		<div class="ibox-content">
			<div class="table-responsive">
				<div class="table-responsive">
					<table id="tblhtspttd_v3" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>id_htsptth_v3</th>
								<th>Shift Ke</th>
								<th>Jam</th>
							</tr>
						</thead>
					</table>
				</div> <!-- end of table -->
			</div>
		</div>
	</div>

</div> <!-- end of row -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsptth_v3/fn/htsptth_v3_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsptth_v3, tblhtsptth_v3, show_inactive_status_htsptth_v3 = 0, id_htsptth_v3;
        var edthtspttd_v3, tblhtspttd_v3, show_inactive_status_htspttd_v3 = 0, id_htspttd_v3;
		// ------------- end of default variable

		var id_htsxxmh_old = 0;
		var id_hemxxmh_old = 0;
		var jumlah_grup;
		
		$(document).ready(function() {
			//start datatables editor
			edthtsptth_v3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_v3/htsptth_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth_v3 = show_inactive_status_htsptth_v3;
					}
				},
				table: "#tblhtsptth_v3",
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
						def: "htsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsptth_v3.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htsptth_v3.nama"
					}, 	{
						label: "Jumlah Grup <sup class='text-danger'>*<sup>",
						name: "htsptth_v3.jumlah_grup"
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htsptth_v3.keterangan",
						type: "textarea"
					}, {
						label: "Tukar Jadwal",
						name: "htsptth_v3.is_tukar",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 0 }
						]
					},
				]
			} );
			
			edthtsptth_v3.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth_v3.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtsptth_v3.rows().deselect();
				}
			});

            edthtsptth_v3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsptth_v3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsptth_v3.nama 
					nama = edthtsptth_v3.field('htsptth_v3.nama').val();
					if(!nama || nama == ''){
						edthtsptth_v3.field('htsptth_v3.nama').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik htsptth_v3.nama 
					if(action == 'create'){
						id_htsptth_v3 = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name       : 'htsptth_v3',
							nama_field       : 'nama',
							nama_field_value : '"' + nama + '"',
							id_transaksi     : id_htsptth_v3
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtsptth_v3.field('htsptth_v3.nama').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					
					// END of cek unik htsptth_v3.nama 
					// END of validasi htsptth_v3.nama 
					
					keterangan = edthtsptth_v3.field('htsptth_v3.keterangan').val();
					if(!keterangan || keterangan == ''){
						edthtsptth_v3.field('htsptth_v3.keterangan').error( 'Wajib diisi!' );
					}

					jumlah_grup = edthtsptth_v3.field('htsptth_v3.jumlah_grup').val();
					if(!jumlah_grup || jumlah_grup == ''){
						edthtsptth_v3.field('htsptth_v3.jumlah_grup').error( 'Wajib diisi!' );
					}
					if(jumlah_grup <= 0 ){
						edthtsptth_v3.field('htsptth_v3.jumlah_grup').error( 'Inputan harus > 0' );
					}
					if(isNaN(jumlah_grup) ){
						edthtsptth_v3.field('htsptth_v3.jumlah_grup').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htsptth_v3.jumlah_grup 
				}
				
				if ( edthtsptth_v3.inError() ) {
					return false;
				}
			});

			edthtsptth_v3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsptth_v3.field('finish_on').val(finish_on);
			});
			
			edthtsptth_v3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsptth_v3 = $('#tblhtsptth_v3').DataTable( {
				ajax: {
					url: "../../models/htsptth_v3/htsptth_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsptth_v3 = show_inactive_status_htsptth_v3;
					}
				},
				order: [[ 1, "desc" ]],
				responsive: false,
				columns: [
					{ data: "htsptth_v3.id",visible:false },
					{ data: "htsptth_v3.nama" },
					{ 
						data: "htsptth_v3.jumlah_grup" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htsptth_v3.keterangan" },
					{ 
						data: "htsptth_v3.is_tukar",
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
						$id_table    = 'id_htsptth_v3';
						$table       = 'tblhtsptth_v3';
						$edt         = 'edthtsptth_v3';
						$show_status = '_htsptth_v3';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsptth_v3.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsptth_v3.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtspttd_v3];
				CekInitHeaderHD(tblhtsptth_v3, tbl_details);
			} );
			
			tblhtsptth_v3.on( 'select', function( e, dt, type, indexes ) {
				data_htsptth_v3 = tblhtsptth_v3.row( { selected: true } ).data().htsptth_v3;
				id_htsptth_v3  = data_htsptth_v3.id;
				id_transaksi_h   = id_htsptth_v3; // dipakai untuk general
				is_approve       = data_htsptth_v3.is_approve;
				is_nextprocess   = data_htsptth_v3.is_nextprocess;
				is_jurnal        = data_htsptth_v3.is_jurnal;
				is_active        = data_htsptth_v3.is_active;

				jumlah_grup = data_htsptth_v3.jumlah_grup;
				
				// atur hak akses
				tbl_details = [tblhtspttd_v3];
				CekSelectHeaderHD(tblhtsptth_v3, tbl_details);

			} );
			
			tblhtsptth_v3.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsptth_v3 = '';

				// atur hak akses
				tbl_details = [tblhtspttd_v3];
				CekDeselectHeaderHD(tblhtsptth_v3, tbl_details);
			} );

// --------- start _detail --------------- //

			//start datatables editor
			edthtspttd_v3 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsptth_v3/htspttd_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htspttd_v3 = show_inactive_status_htspttd_v3;
						d.id_htsptth_v3 = id_htsptth_v3;
					}
				},
				table: "#tblhtspttd_v3",
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
						def: "htspttd_v3",
						type: "hidden"
					},	{
						label: "id_htsptth_v3",
						name: "htspttd_v3.id_htsptth_v3",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htspttd_v3.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Shift Ke <sup class='text-danger'>*<sup>",
						name: "htspttd_v3.shift"
					}, 	{
						label: "Jam <sup class='text-danger'>*<sup>",
						name: "htspttd_v3.id_htsxxmh",
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
					}
				]
			} );
			
			edthtspttd_v3.on( 'preOpen', function( e, mode, action ) {
				edthtspttd_v3.field('htspttd_v3.id_htsptth_v3').val(id_htsptth_v3);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtspttd_v3.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtspttd_v3.rows().deselect();
				}
			});

            edthtspttd_v3.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtspttd_v3.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htspttd_v3.id_htsxxmh 
					id_htsxxmh = edthtspttd_v3.field('htspttd_v3.id_htsxxmh').val();
					if(!id_htsxxmh || id_htsxxmh == ''){
						edthtspttd_v3.field('htspttd_v3.id_htsxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi htspttd_v3.id_htsxxmh 
					
					// BEGIN of validasi htspttd_v3.shift 
					shift = edthtspttd_v3.field('htspttd_v3.shift').val();
					if(!shift || shift == ''){
						edthtspttd_v3.field('htspttd_v3.shift').error( 'Wajib diisi!' );
					}
					if(shift <= 0 ){
						edthtspttd_v3.field('htspttd_v3.shift').error( 'Inputan harus > 0' );
					}
					if(shift > jumlah_grup ){
						edthtspttd_v3.field('htspttd_v3.shift').error( 'Inputan Shift Tidak Boleh Melebihi Jumlah Grup ('+jumlah_grup+ ')' );
					}
					if(isNaN(shift) ){
						edthtspttd_v3.field('htspttd_v3.shift').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi htspttd_v3.shift 

				}
				
				if ( edthtspttd_v3.inError() ) {
					return false;
				}
			});

			edthtspttd_v3.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtspttd_v3.field('finish_on').val(finish_on);
			});

			
			edthtspttd_v3.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtspttd_v3 = $('#tblhtspttd_v3').DataTable( {
				ajax: {
					url: "../../models/htsptth_v3/htspttd_v3.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htspttd_v3 = show_inactive_status_htspttd_v3;
						d.id_htsptth_v3 = id_htsptth_v3;
					}
				},
				order: [[ 2, "asc" ]],
				columns: [
					{ data: "htspttd_v3.id",visible:false },
					{ data: "htspttd_v3.id_htsptth_v3",visible:false },
					{ 
						data: "htspttd_v3.shift" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ 
						data: "htsxxmh.kode"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htspttd_v3';
						$table       = 'tblhtspttd_v3';
						$edt         = 'edthtspttd_v3';
						$show_status = '_htspttd_v3';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htspttd_v3.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtspttd_v3.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhtsptth_v3, tblhtspttd_v3, 'htspttd_v3' );
				CekDrawDetailHDFinal(tblhtsptth_v3);
			} );

			tblhtspttd_v3.on( 'select', function( e, dt, type, indexes ) {
				data_htspttd_v3 = tblhtspttd_v3.row( { selected: true } ).data().htspttd_v3;
				id_htspttd_v3   = data_htspttd_v3.id;
				id_transaksi_d    = id_htspttd_v3; // dipakai untuk general
				is_active_d       = data_htspttd_v3.is_active;

				id_htsxxmh_old = data_htspttd_v3.id_htsxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhtsptth_v3, tblhtspttd_v3 );
			} );

			tblhtspttd_v3.on( 'deselect', function() {
				id_htspttd_v3 = 0;
				is_active_d = 0;
				id_htsxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhtsptth_v3, tblhtspttd_v3 );
			} );

// --------- end _detail --------------- //	
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
