<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpy_piutang_lunas';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpy_piutang_lunas" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Komponen</th>
                                <th>Nominal</th>
                                <th>Tanggal Pelunasan</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpy_piutang_lunas/fn/hpy_piutang_lunas_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpy_piutang_lunas, tblhpy_piutang_lunas, show_inactive_status_hpy_piutang_lunas = 0, id_hpy_piutang_lunas;
		var id_hemxxmh_old = 0;
		var id_hpcxxmh_old = 0;
		var id_hpy_piutang_h_old = 0;
		is_need_approval = 1;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpy_piutang_lunas = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpy_piutang_lunas/hpy_piutang_lunas.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_lunas = show_inactive_status_hpy_piutang_lunas;
					}
				},
				table: "#tblhpy_piutang_lunas",
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
						def: "hpy_piutang_lunas",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpy_piutang_lunas.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Tanggal Pelunasan<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_lunas.tanggal_mulai",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY',
					},
					{
						label: "Piutang Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_lunas.id_hpy_piutang_h",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hpy_piutang_h/hpy_piutang_h_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hpy_piutang_h_old: id_hpy_piutang_h_old,
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
					},
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_lunas.id_hemxxmh",
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
					},
					{
						label: "Komponen",
						name: "hpy_piutang_lunas.id_hpcxxmh",
						def: 105,
						type: "hidden",
					},
					{
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_lunas.nominal"
					}, 	
					{
						label: "Keterangan<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_lunas.keterangan",
						type: "textarea"
					}
				]
			} );
			edthpy_piutang_lunas.field('hpy_piutang_lunas.nominal').input().addClass('text-right');

			edthpy_piutang_lunas.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_lunas.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpy_piutang_lunas.rows().deselect();
				}
			});

			edthpy_piutang_lunas.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpy_piutang_lunas.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpy_piutang_lunas.id_hpy_piutang_h 
					id_hpy_piutang_h = edthpy_piutang_lunas.field('hpy_piutang_lunas.id_hpy_piutang_h').val();
					if(!id_hpy_piutang_h || id_hpy_piutang_h == ''){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.id_hpy_piutang_h').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_lunas.id_hpy_piutang_h 
					
					
					// BEGIN of validasi hpy_piutang_lunas.id_hemxxmh 
					id_hemxxmh = edthpy_piutang_lunas.field('hpy_piutang_lunas.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_lunas.id_hemxxmh 
					
					// BEGIN of validasi hpy_piutang_lunas.keterangan 
					keterangan = edthpy_piutang_lunas.field('hpy_piutang_lunas.keterangan').val();
					if(!keterangan || keterangan == ''){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.keterangan').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_lunas.keterangan 
					
					// BEGIN of validasi hpy_piutang_lunas.tanggal_mulai 
					tanggal_mulai = edthpy_piutang_lunas.field('hpy_piutang_lunas.tanggal_mulai').val();
					if(!tanggal_mulai || tanggal_mulai == ''){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.tanggal_mulai').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_lunas.tanggal_mulai 
					
					// BEGIN of validasi hpy_piutang_lunas.nominal 
					nominal = edthpy_piutang_lunas.field('hpy_piutang_lunas.nominal').val();
					
					// validasi min atau max angka
					if(nominal <= 0 ){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.nominal').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal) ){
						edthpy_piutang_lunas.field('hpy_piutang_lunas.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_piutang_lunas.nominal 
					
				}
				
				if ( edthpy_piutang_lunas.inError() ) {
					return false;
				}
			});
			
			edthpy_piutang_lunas.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_lunas.field('finish_on').val(finish_on);
			});
			
			edthpy_piutang_lunas.on( 'close', function (e, json, data, action, xhr) {
				edthpy_piutang_lunas.enable();
			} );

			//start datatables
			tblhpy_piutang_lunas = $('#tblhpy_piutang_lunas').DataTable( {
				ajax: {
					url: "../../models/hpy_piutang_lunas/hpy_piutang_lunas.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_lunas = show_inactive_status_hpy_piutang_lunas;
					}
				},
				responsive: false,
				scrollX: true,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hpy_piutang_lunas.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpcxxmh.nama" },
					{ 
						data: "hpy_piutang_lunas.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ data: "hpy_piutang_lunas.tanggal_mulai" },
					{ data: "hpy_piutang_lunas.keterangan" },
					{ 
						data: "hpy_piutang_lunas.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							} else {
								return '';
							}
						} 
					} 
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpy_piutang_lunas';
						$table       = 'tblhpy_piutang_lunas';
						$edt         = 'edthpy_piutang_lunas';
						$show_status = '_hpy_piutang_lunas';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve', 'cancel_approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpy_piutang_lunas.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpy_piutang_lunas.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpy_piutang_lunas);
			} );
			
			tblhpy_piutang_lunas.on( 'select', function( e, dt, type, indexes ) {
				hpy_piutang_lunas_data    = tblhpy_piutang_lunas.row( { selected: true } ).data().hpy_piutang_lunas;
				id_hpy_piutang_lunas      = hpy_piutang_lunas_data.id;
				id_transaksi_h = id_hpy_piutang_lunas; // dipakai untuk general
				is_approve     = hpy_piutang_lunas_data.is_approve;
				is_nextprocess = hpy_piutang_lunas_data.is_nextprocess;
				is_jurnal      = hpy_piutang_lunas_data.is_jurnal;
				is_active      = hpy_piutang_lunas_data.is_active;
				id_hemxxmh_old      = hpy_piutang_lunas_data.id_hemxxmh;
				id_hpcxxmh_old      = hpy_piutang_lunas_data.id_hpcxxmh;
				id_hpy_piutang_h_old      = hpy_piutang_lunas_data.id_hpy_piutang_h;

				// atur hak akses
				CekSelectHeaderH(tblhpy_piutang_lunas);
			} );

			tblhpy_piutang_lunas.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpy_piutang_lunas = '';
				id_hemxxmh_old = 0;
				id_hpcxxmh_old = 0;
				id_hpy_piutang_h_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpy_piutang_lunas);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
