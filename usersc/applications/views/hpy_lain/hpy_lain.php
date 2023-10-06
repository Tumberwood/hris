<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hpy_lain';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhpy_lain" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
								<th>Jenis</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpy_lain/fn/hpy_lain_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpy_lain, tblhpy_lain, show_inactive_status_hpy_lain = 0, id_hpy_lain;
		var id_hemxxmh_old = 0;
		is_need_approval = 1;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edthpy_lain = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpy_lain/hpy_lain.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_lain = show_inactive_status_hpy_lain;
					}
				},
				table: "#tblhpy_lain",
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
						def: "hpy_lain",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpy_lain.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "hpy_lain.id_hemxxmh",
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
						name: "hpy_lain.jenis",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Tambahan/Bonus", "value": "Tambahan" },
							{ "label": "Potongan", "value": "Potongan" }
						]
					},	{
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "hpy_lain.nominal"
					}, 	{
						label: "Mulai Tanggal<sup class='text-danger'>*<sup>",
						name: "hpy_lain.tanggal",
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
						name: "hpy_lain.keterangan",
						type: "textarea"
					}
				]
			} );
			edthpy_lain.field('hpy_lain.nominal').input().addClass('text-right');

			edthpy_lain.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_lain.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpy_lain.rows().deselect();
				}
			});

			edthpy_lain.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthpy_lain.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hpy_lain.id_hemxxmh 
					id_hemxxmh = edthpy_lain.field('hpy_lain.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthpy_lain.field('hpy_lain.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_lain.id_hemxxmh 
					
					// BEGIN of validasi hpy_lain.jenis 
					jenis = edthpy_lain.field('hpy_lain.jenis').val();
					if(!jenis || jenis == ''){
						edthpy_lain.field('hpy_lain.jenis').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_lain.jenis 
					
					// BEGIN of validasi hpy_lain.keterangan 
					keterangan = edthpy_lain.field('hpy_lain.keterangan').val();
					if(!keterangan || keterangan == ''){
						edthpy_lain.field('hpy_lain.keterangan').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_lain.keterangan 
					
					// BEGIN of validasi hpy_lain.tanggal 
					tanggal = edthpy_lain.field('hpy_lain.tanggal').val();
					if(!tanggal || tanggal == ''){
						edthpy_lain.field('hpy_lain.tanggal').error( 'Wajib diisi!' );
					}
					// END of validasi hpy_lain.tanggal 
					
					// BEGIN of validasi hpy_lain.nominal 
					nominal = edthpy_lain.field('hpy_lain.nominal').val();
					
					// validasi min atau max angka
					if(nominal <= 0 ){
						edthpy_lain.field('hpy_lain.nominal').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal) ){
						edthpy_lain.field('hpy_lain.nominal').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hpy_lain.nominal 
				}
				
				if ( edthpy_lain.inError() ) {
					return false;
				}
			});
			
			edthpy_lain.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpy_lain.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhpy_lain = $('#tblhpy_lain').DataTable( {
				ajax: {
					url: "../../models/hpy_lain/hpy_lain.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpy_lain = show_inactive_status_hpy_lain;
					}
				},
				responsive: false,
				scrollX: true,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hpy_lain.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpy_lain.tanggal" },
					{ 
						data: "hpy_lain.nominal",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					},
					{ data: "hpy_lain.jenis" },
					{ data: "hpy_lain.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpy_lain';
						$table       = 'tblhpy_lain';
						$edt         = 'edthpy_lain';
						$show_status = '_hpy_lain';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpy_lain.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpy_lain.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhpy_lain);
			} );
			
			tblhpy_lain.on( 'select', function( e, dt, type, indexes ) {
				hpy_lain_data    = tblhpy_lain.row( { selected: true } ).data().hpy_lain;
				id_hpy_lain      = hpy_lain_data.id;
				id_transaksi_h = id_hpy_lain; // dipakai untuk general
				is_approve     = hpy_lain_data.is_approve;
				is_nextprocess = hpy_lain_data.is_nextprocess;
				is_jurnal      = hpy_lain_data.is_jurnal;
				is_active      = hpy_lain_data.is_active;
				id_hemxxmh_old      = hpy_lain_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhpy_lain);
			} );

			tblhpy_lain.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpy_lain = '';
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhpy_lain);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
