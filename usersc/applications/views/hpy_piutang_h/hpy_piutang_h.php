<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpy_piutang_h';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpy_piutang_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Tenor</th>
                                <th>Cicilan per Bulan</th>
                                <th>Cicilan Terakhir</th>
                                <th>Mulai Tanggal</th>
                                <th>Berakhir Tanggal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpy_piutang_h/fn/hpy_piutang_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpy_piutang_h, tblhpy_piutang_h, show_inactive_status_hpy_piutang_h = 0, id_hpy_piutang_h;
		var id_hemxxmh_old = 0;
		var id_hpcxxmh_old = 0;
		is_need_approval = 1;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpy_piutang_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpy_piutang_h/hpy_piutang_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_h = show_inactive_status_hpy_piutang_h;
					}
				},
				table: "#tblhpy_piutang_h",
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
						def: "hpy_piutang_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpy_piutang_h.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.id_hemxxmh",
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
						label: "jenis <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.id_hpcxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hpcxxmh/hpcxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hpcxxmh_old: id_hpcxxmh_old,
										is_denda: 1,
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
					// {
					// 	label: "Jenis<sup class='text-danger'>*<sup>",
					// 	name: "hpy_piutang_h.id_hpcxxmh",
					// 	type: "select",
					// 	placeholder : "Select",
					// 	options: [
					// 		{ "label": "Klaim", "value": "Klaim" },
					// 		{ "label": "Pinjaman", "value": "Pinjaman" }
					// 	]
					// },	
					{
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.nominal"
					}, 	{
						label: "Tenor<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.tenor"
					}, 	{
						label: "Cicilan per Bulan",
						name: "hpy_piutang_h.cicilan_per_bulan",
						type: "readonly"
					}, 	{
						label: "Cicilan Terakhir",
						name: "hpy_piutang_h.cicilan_terakhir",
						type: "readonly"
					}, 	{
						label: "Mulai Tanggal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.tanggal_mulai",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	 	{
						label: "Berakhir Tanggal",
						name: "hpy_piutang_h.tanggal_akhir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Keterangan<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_h.keterangan",
						type: "textarea"
					}
				]
			} );
			edthpy_piutang_h.field('hpy_piutang_h.tenor').input().addClass('text-right');
			edthpy_piutang_h.field('hpy_piutang_h.nominal').input().addClass('text-right');
			edthpy_piutang_h.field('hpy_piutang_h.cicilan_per_bulan').input().addClass('text-right');
			edthpy_piutang_h.field('hpy_piutang_h.cicilan_terakhir').input().addClass('text-right');

			edthpy_piutang_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_h.field('start_on').val(start_on);
				edthpy_piutang_h.field('hpy_piutang_h.tanggal_akhir').disable();
				
				if(action == 'create'){
					tblhpy_piutang_h.rows().deselect();
				}
			});

			edthpy_piutang_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthpy_piutang_h.dependent( 'hpy_piutang_h.nominal', function ( val, data, callback ) {
				if (val > 0) {
					hitung_cicilan();
				}
				return {}
			}, {event: 'keyup change'});

			edthpy_piutang_h.dependent( 'hpy_piutang_h.tenor', function ( val, data, callback ) {
				if (val > 0) {
					hitung_cicilan();
					hitung_tanggal_akhir();
				}
				return {}
			}, {event: 'keyup change'});

			edthpy_piutang_h.dependent( 'hpy_piutang_h.tanggal_mulai', function ( val, data, callback ) {
				if (val != null) {
					hitung_tanggal_akhir();
				}
				return {}
			}, {event: 'keyup change'});

            edthpy_piutang_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpy_piutang_h.id_hemxxmh 
					id_hemxxmh = edthpy_piutang_h.field('hpy_piutang_h.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthpy_piutang_h.field('hpy_piutang_h.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_h.id_hemxxmh 
					
					// BEGIN of validasi hpy_piutang_h.id_hpcxxmh 
					jenis = edthpy_piutang_h.field('hpy_piutang_h.id_hpcxxmh').val();
					if(!jenis || jenis == ''){
						edthpy_piutang_h.field('hpy_piutang_h.id_hpcxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_h.id_hpcxxmh 
					
					// BEGIN of validasi hpy_piutang_h.keterangan 
					keterangan = edthpy_piutang_h.field('hpy_piutang_h.keterangan').val();
					if(!keterangan || keterangan == ''){
						edthpy_piutang_h.field('hpy_piutang_h.keterangan').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_h.keterangan 
					
					// BEGIN of validasi hpy_piutang_h.tanggal_mulai 
					tanggal_mulai = edthpy_piutang_h.field('hpy_piutang_h.tanggal_mulai').val();
					if(!tanggal_mulai || tanggal_mulai == ''){
						edthpy_piutang_h.field('hpy_piutang_h.tanggal_mulai').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_h.tanggal_mulai 
					
					// BEGIN of validasi hpy_piutang_h.tenor 
					tenor = edthpy_piutang_h.field('hpy_piutang_h.tenor').val();
					
					// validasi min atau max angka
					if(tenor <= 0 ){
						edthpy_piutang_h.field('hpy_piutang_h.tenor').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(tenor) ){
						edthpy_piutang_h.field('hpy_piutang_h.tenor').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_piutang_h.tenor 
					
					// BEGIN of validasi hpy_piutang_h.nominal 
					nominal = edthpy_piutang_h.field('hpy_piutang_h.nominal').val();
					
					// validasi min atau max angka
					if(nominal <= 0 ){
						edthpy_piutang_h.field('hpy_piutang_h.nominal').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal) ){
						edthpy_piutang_h.field('hpy_piutang_h.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_piutang_h.nominal 
				}
				
				if ( edthpy_piutang_h.inError() ) {
					return false;
				}
			});
			
			edthpy_piutang_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_h.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpy_piutang_h = $('#tblhpy_piutang_h').DataTable( {
				ajax: {
					url: "../../models/hpy_piutang_h/hpy_piutang_h.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_h = show_inactive_status_hpy_piutang_h;
					}
				},
				responsive: false,
				scrollX: true,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hpy_piutang_h.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpcxxmh.nama" },
					{ 
						data: "hpy_piutang_h.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ 
						data: "hpy_piutang_h.tenor",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ 
						data: "hpy_piutang_h.cicilan_per_bulan",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ 
						data: "hpy_piutang_h.cicilan_terakhir",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ data: "hpy_piutang_h.tanggal_mulai" },
					{ data: "hpy_piutang_h.tanggal_akhir" },
					{ data: "hpy_piutang_h.keterangan" },
					{ 
						data: "hpy_piutang_h.is_approve" ,
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
						$id_table    = 'id_hpy_piutang_h';
						$table       = 'tblhpy_piutang_h';
						$edt         = 'edthpy_piutang_h';
						$show_status = '_hpy_piutang_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpy_piutang_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpy_piutang_h.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpy_piutang_h);
			} );
			
			tblhpy_piutang_h.on( 'select', function( e, dt, type, indexes ) {
				hpy_piutang_h_data    = tblhpy_piutang_h.row( { selected: true } ).data().hpy_piutang_h;
				id_hpy_piutang_h      = hpy_piutang_h_data.id;
				id_transaksi_h = id_hpy_piutang_h; // dipakai untuk general
				is_approve     = hpy_piutang_h_data.is_approve;
				is_nextprocess = hpy_piutang_h_data.is_nextprocess;
				is_jurnal      = hpy_piutang_h_data.is_jurnal;
				is_active      = hpy_piutang_h_data.is_active;
				id_hemxxmh_old      = hpy_piutang_h_data.id_hemxxmh;
				id_hpcxxmh_old      = hpy_piutang_h_data.id_hpcxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhpy_piutang_h);
			} );

			tblhpy_piutang_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpy_piutang_h = '';
				id_hemxxmh_old = 0;
				id_hpcxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpy_piutang_h);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
