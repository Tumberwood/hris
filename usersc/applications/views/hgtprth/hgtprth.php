<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hgtprth';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhgtprth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgtprth/fn/hgtprth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgtprth, tblhgtprth, show_inactive_status_hgtprth = 0, id_hgtprth;
		// ------------- end of default variable

		var id_heyxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthgtprth = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/hgtprth/hgtprth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgtprth = show_inactive_status_hgtprth;
					}
				},
				table: "#tblhgtprth",
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
						def: "hgtprth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgtprth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "hgtprth.tanggal",
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
						label: "Jenis",
						name: "hgtprth.id_heyxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh_old: id_heyxxmh_old,
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
						name: "hgtprth.keterangan",
						type: "textarea"
					}
				]
			} );

			edthgtprth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgtprth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgtprth.rows().deselect();
				}
			});

			edthgtprth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthgtprth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hgtprth.tanggal
					if ( ! edthgtprth.field('hgtprth.tanggal').isMultiValue() ) {
						tanggal = edthgtprth.field('hgtprth.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthgtprth.field('hgtprth.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgtprth.tanggal
				}
				
				if ( edthgtprth.inError() ) {
					return false;
				}
			});
			
			edthgtprth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgtprth.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhgtprth = $('#tblhgtprth').DataTable( {
				ajax: {
					url: "../../models/hgtprth/hgtprth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgtprth = show_inactive_status_hgtprth;
					}
				},
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hgtprth.id",visible:false },
					{ data: "hgtprth.tanggal" },
					{ data: "heyxxmh.nama" },
					{ data: "hgtprth.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgtprth';
						$table       = 'tblhgtprth';
						$edt         = 'edthgtprth';
						$show_status = '_hgtprth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensi',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hgtprth/hgtprth_fn_gen_presensi.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_transaksi_h: id_transaksi_h
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhgtprth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgtprth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhgtprth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhgtprth);
				tblhgtprth.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			tblhgtprth.on( 'select', function( e, dt, type, indexes ) {
				hgtprth_data    = tblhgtprth.row( { selected: true } ).data().hgtprth;
				id_hgtprth      = hgtprth_data.id;
				id_transaksi_h = id_hgtprth; // dipakai untuk general
				is_approve     = hgtprth_data.is_approve;
				is_nextprocess = hgtprth_data.is_nextprocess;
				is_jurnal      = hgtprth_data.is_jurnal;
				is_active      = hgtprth_data.is_active;

				id_heyxxmh_old = hgtprth_data.id_heyxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhgtprth);
				tblhgtprth.button( 'btnGeneratePresensi:name' ).enable();
			} );

			tblhgtprth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgtprth = 0;
				id_heyxxmh_old = 0;
				id_heyxxmh = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhgtprth);
				tblhgtprth.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
