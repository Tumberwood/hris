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
                                <input type="text" id="start_date" class="form-control" hidden>
                                <span class="input-group-addon" hidden>to</span>
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
				<div class="table-responsive">
                    <table id="tblhgtprth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Ceklok Min</th>
                                <th>Ceklok Max</th>
                                <th>Ceklok Istirahat</th>
                                <th>Ceklok Makan</th>
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
			
			//start datatables
			tblhgtprth = $('#tblhgtprth').DataTable( {
				ajax: {
					url: "../../models/jadwal_dan_ceklok/jadwal_dan_ceklok.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
					},
  					dataSrc: 'data.result'
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				columns: [

					{
						data: "nik"
					},
					{
						data: "nama"
					},
					{
						data: "tanggal",
						render: function(data) {
							return data ? moment(data).format('DD MMM YYYY') : '';
						}
					},
					{
						data: "shift",
					},
					{
						data: "cek_in",
						render: function(data) {
							return data ? moment(data).format('DD MMM YYYY HH:mm:ss') : '';
						}
					},
					{
						data: "cek_out",
						render: function(data) {
							return data ? moment(data).format('DD MMM YYYY HH:mm:ss') : '';
						}
					},
					{
						data: "cek_break",
						render: function(data) {
							return data ? moment(data).format('DD MMM YYYY HH:mm:ss') : '';
						}
					},
					{
						data: "cek_makan",
						render: function(data) {
							return data ? moment(data).format('DD MMM YYYY HH:mm:ss') : '';
						}
					},
					{
						data: "status_cek_in",
						className: "text-center",
						render: function(data) {
							if (data === "OK") {
								return '<span class="badge badge-success">OK</span>';
							} else if (data === "NO CI") {
								return '<span class="badge badge-danger">NO CI</span>';
							} else if (data === "TIDAK SESUAI") {
								return '<span class="badge badge-warning">TIDAK SESUAI</span>';
							}
							return data || '';
						}
					}
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
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
				],
				rowCallback: function( row, data, index ) {
				}
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

					tblhgtprth.ajax.reload(function ( json ) {
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
