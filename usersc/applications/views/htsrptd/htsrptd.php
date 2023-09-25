<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsrptd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsrptd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Tanggal</th>
                                <th>Pengaju</th>
                                <th>Pengganti</th>
                                <th>Keterangan</th>
                                <th>Appr</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsrptd/fn/htsrptd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsrptd, tblhtsrptd, show_inactive_status_htsrptd = 0, id_htsrptd;
		// ------------- end of default variable

		is_need_approval = 1, is_need_generate_kode = 1;

		var id_hemxxmh_pengaju_old = 0, id_hemxxmh_pengganti_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthtsrptd = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/htsrptd/htsrptd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsrptd = show_inactive_status_htsrptd;
					}
				},
				table: "#tblhtsrptd",
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
						def: "htsrptd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsrptd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htsrptd.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Pengaju <sup class='text-danger'>*<sup>",
						name: "htsrptd.id_hemxxmh_pengaju",
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
										id_hemxxmh_old: id_hemxxmh_pengaju_old,
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
						label: "htsrptd.id_htsxxmh_pengaju",
						name: "htsrptd.id_htsxxmh_pengaju",
						type: "hidden"
					}, 	{
						label: "Shift Pengaju <sup class='text-danger'>*<sup>",
						name: "htsxxmh_pengaju_data",
						type: "readonly"
					}, 	{
						label: "Pengganti <sup class='text-danger'>*<sup>",
						name: "htsrptd.id_hemxxmh_pengganti",
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
										id_hemxxmh_old: id_hemxxmh_pengganti_old,
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
						label: "htsrptd.id_htsxxmh_pengganti",
						name: "htsrptd.id_htsxxmh_pengganti",
						type: "hidden"
					}, 	{
						label: "Shift Pengganti <sup class='text-danger'>*<sup>",
						name: "htsxxmh_pengganti_data",
						type: "readonly"
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htsrptd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtsrptd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsrptd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsrptd.rows().deselect();
				}
			});

			edthtsrptd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtsrptd.dependent( 'htsrptd.tanggal', function ( val, data, callback ) {
				tanggal = edthtsrptd.field('htsrptd.tanggal').val();
				id_hemxxmh_pengaju = edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').val();
				id_hemxxmh_pengganti = edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').val();
				if(tanggal != '' && id_hemxxmh_pengaju > 0){
					get_htsxxmh_pengaju();
				}
				if(tanggal != '' && id_hemxxmh_pengganti > 0){
					get_htsxxmh_pengganti();
				}
				return {}
			}, {event: 'keyup change'});

			edthtsrptd.dependent( 'htsrptd.id_hemxxmh_pengaju', function ( val, data, callback ) {
				tanggal = edthtsrptd.field('htsrptd.tanggal').val();
				id_hemxxmh_pengaju = edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').val();
				if(tanggal != '' && id_hemxxmh_pengaju > 0){
					get_htsxxmh_pengaju();
				}
				return {}
			}, {event: 'keyup change'});

			edthtsrptd.dependent( 'htsrptd.id_hemxxmh_pengganti', function ( val, data, callback ) {
				tanggal = edthtsrptd.field('htsrptd.tanggal').val();
				id_hemxxmh_pengganti = edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').val();
				if(tanggal != '' && id_hemxxmh_pengganti > 0){
					get_htsxxmh_pengganti();
				}
				return {}
			}, {event: 'keyup change'});

            edthtsrptd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi htsrptd.tanggal
					if ( ! edthtsrptd.field('htsrptd.tanggal').isMultiValue() ) {
						tanggal = edthtsrptd.field('htsrptd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtsrptd.field('htsrptd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsrptd.tanggal

					// BEGIN of validasi htsrptd.id_hemxxmh_pengaju
					if ( ! edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').isMultiValue() ) {
						id_hemxxmh_pengaju = edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').val();
						if(!id_hemxxmh_pengaju || id_hemxxmh_pengaju == ''){
							edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsrptd.id_hemxxmh_pengaju

					// BEGIN of validasi htsrptd.id_hemxxmh_pengganti
					if ( ! edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').isMultiValue() ) {
						id_hemxxmh_pengganti = edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').val();
						if(!id_hemxxmh_pengganti || id_hemxxmh_pengganti == ''){
							edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsrptd.id_hemxxmh_pengganti

					// BEGIN of validasi htsrptd.keterangan
					if ( ! edthtsrptd.field('htsrptd.keterangan').isMultiValue() ) {
						keterangan = edthtsrptd.field('htsrptd.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtsrptd.field('htsrptd.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsrptd.keterangan

					// BEGIN validasi pengaju dan pengganti tidak boleh sama
					if(id_hemxxmh_pengaju == id_hemxxmh_pengganti) {
						edthtsrptd.field('htsrptd.id_hemxxmh_pengaju').error( 'Pengaju dan Pengganti Tidak Boleh Sama!' );
						edthtsrptd.field('htsrptd.id_hemxxmh_pengganti').error( 'Pengaju dan Pengganti Tidak Boleh Sama!' );
					}
					// END validasi pengaju dan pengganti tidak boleh sama
				}
				
				if ( edthtsrptd.inError() ) {
					return false;
				}
			});
			
			edthtsrptd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsrptd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtsrptd = $('#tblhtsrptd').DataTable( {
				ajax: {
					url: "../../models/htsrptd/htsrptd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsrptd = show_inactive_status_htsrptd;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "htsrptd.id",visible:false },
					{ data: "htsrptd.kode" },
					{ data: "htsrptd.tanggal" },
					{ data: "hemxxmh_pengaju" },
					{ data: "hemxxmh_pengganti" },
					{ data: "htsrptd.keterangan" },
					{ 
						data: "htsrptd.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							}
						}
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsrptd';
						$table       = 'tblhtsrptd';
						$edt         = 'edthtsrptd';
						$show_status = '_htsrptd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsrptd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtsrptd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtsrptd);
			} );
			
			tblhtsrptd.on( 'select', function( e, dt, type, indexes ) {
				htsrptd_data    = tblhtsrptd.row( { selected: true } ).data().htsrptd;
				id_htsrptd      = htsrptd_data.id;
				id_transaksi_h = id_htsrptd; // dipakai untuk general
				is_approve     = htsrptd_data.is_approve;
				is_nextprocess = htsrptd_data.is_nextprocess;
				is_jurnal      = htsrptd_data.is_jurnal;
				is_active      = htsrptd_data.is_active;

				id_hemxxmh_pengaju_old    = htsrptd_data.id_hemxxmh_pengaju;
				id_hemxxmh_pengganti_old  = htsrptd_data.id_hemxxmh_pengganti;

				// atur hak akses
				CekSelectHeaderH(tblhtsrptd);
			} );

			tblhtsrptd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsrptd = 0;

				id_hemxxmh_pengaju_old    = 0;
				id_hemxxmh_pengganti_old  = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtsrptd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
