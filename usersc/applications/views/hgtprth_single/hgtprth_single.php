<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hgtprth_single';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmhgtprth">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periode</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
                                <span class="input-group-addon">to</span>
                                <input type="text" id="end_date" class="form-control">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary" type="submit" id="go">Submit</button>
                        </div>
                    </div>
                </form>
                <div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					Sebelum melakukan generate presensi, pastikan data-data ini sudah diinput
					<ul>
						<li>Jadwal & Check clock</li>
						<li>Izin, Absensi, Overtime, Cuti Bersama, Libur Nasional, Tukar Jadwal, Replacement, Tukar Hari (Wajib Approve)</li>
					</ul>
				</div>
				<div class="table-responsive">
                    <table id="tblhgtprth_single" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Tanggal Jam Generate</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgtprth_single/fn/hgtprth_single_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgtprth_single, tblhgtprth_single, show_inactive_status_hgtprth_single = 0, id_hgtprth_single;
		// ------------- end of default variable

		var id_hemxxmh_old = 0;
		var tanggal_select = 0;
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		$('#start_date').datepicker('setDate', awal_bulan_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			//start datatables editor
			edthgtprth_single = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/hgtprth_single/hgtprth_single.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgtprth_single = show_inactive_status_hgtprth_single;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblhgtprth_single",
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
						def: "hgtprth_single",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgtprth_single.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "hgtprth_single.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Nama<sup class='text-danger'>*<sup>",
						name: "hgtprth_single.id_hemxxmh",
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
						name: "hgtprth_single.keterangan",
						type: "textarea"
					}
				]
			} );

			edthgtprth_single.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgtprth_single.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgtprth_single.rows().deselect();
				}
			});

			edthgtprth_single.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthgtprth_single.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hgtprth_single.tanggal
					//edit by ferry, hapus is multi value karena tidak diperlukan.
					tanggal = edthgtprth_single.field('hgtprth_single.tanggal').val();
					if(!tanggal || tanggal == ''){
						edthgtprth_single.field('hgtprth_single.tanggal').error( 'Wajib diisi!' );
					}else{
						tanggal_ymd = moment(tanggal).format('YYYY-MM-DD');
					}
					// END of validasi hgtprth_single.tanggal

					// BEGIN of validasi hgtprth_single.id_hemxxmh
					
					//edit by ferry, hapus is multi value karena tidak diperlukan. Jika jika pakai is multi value, maka validasi tidak berjalan dengan baik
					id_hemxxmh = edthgtprth_single.field('hgtprth_single.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthgtprth_single.field('hgtprth_single.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hgtprth_single.id_hemxxmh

					// BEGIN of cek unik hgtprth_single.id_hemxxmh dan hgtprth_single.tanggal
					if(action == 'create'){
						id_hgtprth_single = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'hgtprth_single',
							nama_field: 'tanggal,id_hemxxmh',
							nama_field_value: '"'+tanggal_ymd+'",' + id_hemxxmh,
							id_transaksi: id_hgtprth_single
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthgtprth_single.field('hgtprth_single.id_hemxxmh').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik hgtprth_single.id_hemxxmh dan hgtprth_single.tanggal
				}
				
				if ( edthgtprth_single.inError() ) {
					return false;
				}
			});
			
			edthgtprth_single.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgtprth_single.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhgtprth_single = $('#tblhgtprth_single').DataTable( {
				ajax: {
					url: "../../models/hgtprth_single/hgtprth_single.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgtprth_single = show_inactive_status_hgtprth_single;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hgtprth_single.id",visible:false },
					{ data: "hgtprth_single.tanggal" },
					{ data: "nama" },
					{ data: "hgtprth_single.keterangan" },
					{ data: "hgtprth_single.generated_on" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgtprth_single';
						$table       = 'tblhgtprth_single';
						$edt         = 'edthgtprth_single';
						$show_status = '_hgtprth_single';
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
							var date = new Date().getTime();
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hgtprth_single/gen_presensi_single_v3.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hgtprth_single: id_hgtprth_single,
									tanggal_select: tanggal_select,
									id_hemxxmh_select: id_hemxxmh_select,
									timestamp: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhgtprth_single.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgtprth_single.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhgtprth_single.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhgtprth_single);
				tblhgtprth_single.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			tblhgtprth_single.on( 'select', function( e, dt, type, indexes ) {
				hgtprth_single_data    = tblhgtprth_single.row( { selected: true } ).data().hgtprth_single;
				id_hgtprth_single      = hgtprth_single_data.id;
				id_transaksi_h = id_hgtprth_single; // dipakai untuk general
				is_approve     = hgtprth_single_data.is_approve;
				is_nextprocess = hgtprth_single_data.is_nextprocess;
				is_jurnal      = hgtprth_single_data.is_jurnal;
				is_active      = hgtprth_single_data.is_active;
				tanggal_select      = hgtprth_single_data.tanggal;
				id_hemxxmh_select      = hgtprth_single_data.id_hemxxmh;

				id_hemxxmh_old = hgtprth_single_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhgtprth_single);
				
				cariApprove();
				if (total_approve > 0) {
					tblhgtprth_single.button( 'btnGeneratePresensi:name' ).disable();
				} else {
					tblhgtprth_single.button( 'btnGeneratePresensi:name' ).enable();
				}

			} );

			tblhgtprth_single.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgtprth_single = 0;
				id_hemxxmh_old = 0;
				id_hemxxmh = 0;
				tanggal_select = 0;
				id_hemxxmh_select = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhgtprth_single);
				tblhgtprth_single.button( 'btnGeneratePresensi:name' ).disable();
			} );
			
			$("#frmhgtprth").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhgtprth) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhgtprth_single.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
