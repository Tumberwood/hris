<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'rencana_makan';
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
                <form class="form-horizontal" id="frmFilter">
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
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblrencana_makan" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Shift Makan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/rencana_makan/fn/rencana_makan_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtrencana_makan, tblrencana_makan, show_inactive_status_rencana_makan = 0, id_rencana_makan;
		// ------------- end of default variable
		var id_hemxxmh_old = 0;
		
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
			edtrencana_makan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/rencana_makan/rencana_makan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_rencana_makan = show_inactive_status_rencana_makan;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblrencana_makan",
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
						def: "rencana_makan",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "rencana_makan.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "rencana_makan.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "rencana_makan.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hemxxmh/hemxxmh_fn_opt_include_hl_umum.php",
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
						label: "Shift Makan <sup class='text-danger'>*<sup>",
						name: "rencana_makan.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Pagi", "value": "Pagi" },
							{ "label": "Sore", "value": "Sore" },
							{ "label": "Malam", "value": "Malam" },
						]
					},
					{
						label: "Keterangan",
						name: "rencana_makan.keterangan",
						type: "textarea"
					},
				]
			} );

			edtrencana_makan.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtrencana_makan.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblrencana_makan.rows().deselect();
				}
			});

			edtrencana_makan.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtrencana_makan.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					id_hemxxmh = edtrencana_makan.field('rencana_makan.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edtrencana_makan.field('rencana_makan.id_hemxxmh').error( 'Wajib diisi!' );
					}

					tanggal = edtrencana_makan.field('rencana_makan.tanggal').val();
					if(!tanggal || tanggal == ''){
						edtrencana_makan.field('rencana_makan.tanggal').error( 'Wajib diisi!' );
					}

					shift = edtrencana_makan.field('rencana_makan.shift').val();
					if(!shift || shift == ''){
						edtrencana_makan.field('rencana_makan.shift').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtrencana_makan.inError() ) {
					return false;
				}
			});
			
			edtrencana_makan.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtrencana_makan.field('finish_on').val(finish_on);
			});

			//start datatables
			tblrencana_makan = $('#tblrencana_makan').DataTable( {
				ajax: {
					url: "../../models/rencana_makan/rencana_makan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_rencana_makan = show_inactive_status_rencana_makan;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "rencana_makan.id",visible:false },
					{ data: "rencana_makan.tanggal" },
					{ data: "hemxxmh_data" },
					{ data: "rencana_makan.shift" },
					{ data: "rencana_makan.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_rencana_makan';
						$table       = 'tblrencana_makan';
						$edt         = 'edtrencana_makan';
						$show_status = '_rencana_makan';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.rencana_makan.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblrencana_makan.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblrencana_makan);
			} );
			
			tblrencana_makan.on( 'select', function( e, dt, type, indexes ) {
				rencana_makan_data    = tblrencana_makan.row( { selected: true } ).data().rencana_makan;
				id_rencana_makan      = rencana_makan_data.id;
				id_transaksi_h = id_rencana_makan; // dipakai untuk general
				is_approve     = rencana_makan_data.is_approve;
				is_nextprocess = rencana_makan_data.is_nextprocess;
				is_jurnal      = rencana_makan_data.is_jurnal;
				is_active      = rencana_makan_data.is_active;
				id_hemxxmh_old      = rencana_makan_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblrencana_makan);
			} );

			tblrencana_makan.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_rencana_makan = '';
				id_hemxxmh_old = 0 ;

				// atur hak akses
				CekDeselectHeaderH(tblrencana_makan);
			} );

			
			$("#frmFilter").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmFilter) {
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

					tblrencana_makan.rows().deselect();
					tblrencana_makan.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					cekApproveTanggal()
					return false; 
				}
			});
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
