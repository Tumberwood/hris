<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpy_piutang_d';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpy_piutang_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Tanggal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpy_piutang_d/fn/hpy_piutang_d_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpy_piutang_d, tblhpy_piutang_d, show_inactive_status_hpy_piutang_d = 0, id_hpy_piutang_d;
		var id_hemxxmh_old = 0;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpy_piutang_d = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpy_piutang_d/hpy_piutang_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_d = show_inactive_status_hpy_piutang_d;
					}
				},
				table: "#tblhpy_piutang_d",
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
						def: "hpy_piutang_d",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpy_piutang_d.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.id_hemxxmh",
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
						label: "Jenis<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.jenis",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Klaim", "value": "Klaim" },
							{ "label": "Pinjaman", "value": "Pinjaman" }
						]
					},	{
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.nominal"
					}, 	{
						label: "Mulai Tanggal<sup class='text-danger'>*<sup>",
						name: "hpy_piutang_d.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}
				]
			} );
			edthpy_piutang_d.field('hpy_piutang_d.nominal').input().addClass('text-right');

			edthpy_piutang_d.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_d.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpy_piutang_d.rows().deselect();
				}
			});

			edthpy_piutang_d.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpy_piutang_d.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpy_piutang_d.id_hemxxmh 
					id_hemxxmh = edthpy_piutang_d.field('hpy_piutang_d.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthpy_piutang_d.field('hpy_piutang_d.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.id_hemxxmh 
					
					// BEGIN of validasi hpy_piutang_d.jenis 
					jenis = edthpy_piutang_d.field('hpy_piutang_d.jenis').val();
					if(!jenis || jenis == ''){
						edthpy_piutang_d.field('hpy_piutang_d.jenis').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.jenis 
					
					// BEGIN of validasi hpy_piutang_d.tanggal 
					tanggal = edthpy_piutang_d.field('hpy_piutang_d.tanggal').val();
					if(!tanggal || tanggal == ''){
						edthpy_piutang_d.field('hpy_piutang_d.tanggal').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_piutang_d.tanggal 
					
					// BEGIN of validasi hpy_piutang_d.nominal 
					nominal = edthpy_piutang_d.field('hpy_piutang_d.nominal').val();
					
					// validasi min atau max angka
					if(nominal <= 0 ){
						edthpy_piutang_d.field('hpy_piutang_d.nominal').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal) ){
						edthpy_piutang_d.field('hpy_piutang_d.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_piutang_d.nominal 
				}
				
				if ( edthpy_piutang_d.inError() ) {
					return false;
				}
			});
			
			edthpy_piutang_d.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_piutang_d.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpy_piutang_d = $('#tblhpy_piutang_d').DataTable( {
				ajax: {
					url: "../../models/hpy_piutang_d/hpy_piutang_d.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_piutang_d = show_inactive_status_hpy_piutang_d;
					}
				},
				responsive: false,
				scrollX: true,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hpy_piutang_d.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpy_piutang_d.jenis" },
					{ 
						data: "hpy_piutang_d.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ data: "hpy_piutang_d.tanggal" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpy_piutang_d';
						$table       = 'tblhpy_piutang_d';
						$edt         = 'edthpy_piutang_d';
						$show_status = '_hpy_piutang_d';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpy_piutang_d.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpy_piutang_d.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpy_piutang_d);
			} );
			
			tblhpy_piutang_d.on( 'select', function( e, dt, type, indexes ) {
				hpy_piutang_d_data    = tblhpy_piutang_d.row( { selected: true } ).data().hpy_piutang_d;
				id_hpy_piutang_d      = hpy_piutang_d_data.id;
				id_transaksi_h = id_hpy_piutang_d; // dipakai untuk general
				is_approve     = hpy_piutang_d_data.is_approve;
				is_nextprocess = hpy_piutang_d_data.is_nextprocess;
				is_jurnal      = hpy_piutang_d_data.is_jurnal;
				is_active      = hpy_piutang_d_data.is_active;
				id_hemxxmh_old      = hpy_piutang_d_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhpy_piutang_d);
			} );

			tblhpy_piutang_d.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpy_piutang_d = '';
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpy_piutang_d);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
