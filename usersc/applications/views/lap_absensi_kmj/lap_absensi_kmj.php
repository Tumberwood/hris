<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'lap_absensi_kmj';
	$nama_tabels_d = [];
	
	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= $_GET['id_hemxxmh'];
	} else {
		$id_hemxxmh		= 0;
	}
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
?>

<!-- <style>

	.gantt-v2-wrapper {

		width: 100%;

		overflow-x: auto;

		border: 1px solid #000;

	}


	.gantt-v2-table {

		border-collapse: collapse;

		min-width: max-content;

		width: 100%;

		font-size: 13px;

	}


	.gantt-v2-table th,
	.gantt-v2-table td {

		border: 1px solid #000;

		padding: 0;

		vertical-align: middle;

	}


	/*
	|--------------------------------------------------------------------------
	| HEADER TANGGAL
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-date {

		height: 32px;

		min-width: 270px;

		text-align: center;

		background: #f8f8f8;

		font-weight: bold;

	}


	/*
	|--------------------------------------------------------------------------
	| HEADER SHIFT
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-shift {

		width: 90px;

		min-width: 90px;

		height: 30px;

		text-align: center;

		background: #fff;

		font-weight: bold;

	}


	/*
	|--------------------------------------------------------------------------
	| NIK
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-nik {

		width: 90px;

		min-width: 90px;

		text-align: center;

	}


	.gantt-v2-nik-cell {

		padding: 5px 8px !important;

		white-space: nowrap;

		text-align: center;

	}


	/*
	|--------------------------------------------------------------------------
	| NAMA
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-nama {

		width: 260px;

		min-width: 260px;

	}


	.gantt-v2-nama-cell {

		width: 260px;

		min-width: 260px;

		padding: 5px 8px !important;

		white-space: nowrap;

	}


	/*
	|--------------------------------------------------------------------------
	| CELL BAR
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-bar-cell {

		width: 90px;

		min-width: 90px;

		height: 32px;

		padding: 0 !important;

		position: relative;

	}


	/*
	|--------------------------------------------------------------------------
	| TRACK
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-track {

		position: relative;

		width: 100%;

		height: 30px;

		background: #fff;

		overflow: hidden;

	}


	/*
	|--------------------------------------------------------------------------
	| BAR
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-bar {

		position: absolute;

		top: 5px;

		height: 20px;

		background: #ffc107;

		border: 1px solid #e0a800;

		box-sizing: border-box;

		display: flex;

		align-items: center;

		justify-content: center;

		white-space: nowrap;

		overflow: hidden;

		font-size: 10px;

		font-weight: bold;

		color: #000;

	}


	/*
	|--------------------------------------------------------------------------
	| EMPTY
	|--------------------------------------------------------------------------
	*/

	.gantt-v2-empty {

		width: 100%;

		height: 30px;

		background: #fff;

	}

</style> -->

<style>

	#gantt_absensi_kmj {

		width: 100%;

		border: 1px solid #dee2e6;

		border-radius: 8px;

		overflow: hidden;

		background: #fff;

	}


	/*
	|--------------------------------------------------------------------------
	| AXIS HEADER
	|--------------------------------------------------------------------------
	*/

	.gantt-v4-axis {

		text-align: center;

		min-width: 85px;

		line-height: 1.25;

	}


	.gantt-v4-axis b {

		display: block;

		font-size: 11px;

		color: #212529;

	}


	.gantt-v4-axis span {

		display: block;

		margin-top: 3px;

		font-size: 10px;

		font-weight: 700;

		color: #ff9800;

	}


	.gantt-v4-axis small {

		display: block;

		margin-top: 2px;

		font-size: 9px;

		color: #868e96;

	}


	/*
	|--------------------------------------------------------------------------
	| PERSON
	|--------------------------------------------------------------------------
	*/

	.gantt-v4-person {

		display: flex;

		align-items: center;

		gap: 7px;

		white-space: nowrap;
		text-align: left;

	}


	.gantt-v4-avatar {

		width: 25px;

		height: 25px;

		min-width: 25px;

		border-radius: 50%;

		display: inline-flex;

		align-items: center;

		justify-content: center;

		background: #f1f3f5;

		color: #495057;

		font-size: 10px;

		font-weight: 700;

	}


	/*
	|--------------------------------------------------------------------------
	| TOOLTIP
	|--------------------------------------------------------------------------
	*/

	.gantt-v4-tooltip {

		width: 240px;

		padding: 10px;

	}


	.gantt-v4-tooltip-name {

		margin-bottom: 8px;

		padding-bottom: 7px;

		border-bottom: 1px solid #eee;

		font-size: 13px;

		font-weight: 700;

	}


	.gantt-v4-tooltip-row {

		display: flex;

		justify-content: space-between;

		gap: 15px;

		padding: 3px 0;

	}


	.gantt-v4-tooltip-row span {

		color: #888;

	}


	.gantt-v4-tooltip-row b {

		text-align: right;

	}


	.gantt-v4-tooltip-duration {

		margin-top: 7px;

		padding-top: 7px;

		border-top: 1px solid #eee;

		color: #ff9800;

		font-weight: 700;

	}


	/*
	|--------------------------------------------------------------------------
	| BAR
	|--------------------------------------------------------------------------
	*/

	.highcharts-gantt-series
	.highcharts-point {

		rx: 5;

		ry: 5;

		stroke-width: 0;

		transition:
			filter .15s ease;

	}


	.highcharts-gantt-series
	.highcharts-point:hover {

		filter:
			brightness(1.08)
			drop-shadow(
				0 4px 5px rgba(
					0,
					0,
					0,
					.22
				)
			);

	}


	/*
	|--------------------------------------------------------------------------
	| MOBILE
	|--------------------------------------------------------------------------
	*/

	@media (max-width: 768px) {

		#gantt_absensi_kmj {

			min-width: 850px;

		}

	}

</style>
<!-- begin content here -->

<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmlap_absensi_kmj">
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
                        <label class="col-sm-2 col-form-label">Periode Payroll</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="select_periode_payroll" name="select_periode_payroll"></select>
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

<!-- PIVOT -->
<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<h3>Resume</h3>
					<div id="tabel_atas"></div>

					<div id="gantt_absensi_kmj" style="margin-top:20px;"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<h3>List</h3>
                    <table id="tbllap_absensi_kmj" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>NIK</th>
								<th>Nama</th>
								<th>Sub Tipe</th>
								<th>Tanggal</th>
								<th>Shift</th>
								<th>Check In</th>
								<th>Check Out</th>
								<th>Durasi (Jam)</th>
								<th>Count orang</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/lap_absensi_kmj/fn/lap_absensi_kmj_fn.php'; ?>
<script src="https://code.highcharts.com/gantt/highcharts-gantt.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tbllap_absensi_kmj, show_inactive_status_lap_absensi_kmj = 0;
		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var tanggal_get = "<?php echo $awal ?>";

		// console.log(id_hem_get);
		// console.log(tanggal_get);
		// ------------- end of default variable

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		
		if (tanggal_get === '') {
			$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
			$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_get));
			$('#end_date').datepicker('setDate', new Date(tanggal_get));
		}
        // END datepicker init

		//Select2 init
        $("#select_periode_payroll").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/periode_payroll/periode_payroll_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_periode_payroll_old: 0,
						search: params.term || '',
						page: params.page || 1
					}
						return query;
				},
				processResults: function (data, params) {
					if (id_hem_get > 0) {
						var options = data.results.map(function (result) {
							return {
								id: result.id,
								text: result.text
							};
						});

						//add by ferry agar auto select 07 sep 23
						if (params.page && params.page === 1) {
							$('#select_periode_payroll').empty().select2({ data: options });
						} else {
							$('#select_periode_payroll').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
						}

						return {
							results: options,
							pagination: {
								more: true
							}
						};
					} else {
						return {
							results: data.results,
							pagination: {
								more: true
							}
						};
					}
				},
				cache: true,
				minimumInputLength: 1,
				maximum: 10,
				delay: 500,
				maximumSelectionLength: 5,
				minimumResultsForSearch: -1,
			}
			
		});
        // END select2 init

		// Override tanggal ketika periode payroll dipilih
		$('#select_periode_payroll').on('select2:select', async function (e) {
			const val = $(this).val();

			await autofillField(
				'periode_payroll',
				val,
				'DATE_FORMAT(tanggal_awal, "%d %b %Y") AS tanggal_awal, DATE_FORMAT(tanggal_akhir, "%d %b %Y") AS tanggal_akhir'
			);

			const tanggal_awal = autofillData.tanggal_awal;
			const tanggal_akhir = autofillData.tanggal_akhir;

			$('#start_date').datepicker('setDate', tanggal_awal);
			$('#end_date').datepicker('setDate', tanggal_akhir);

		});
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			generateTable(start_date, end_date);
			// generateGanttAbsensiV2(start_date, end_date);
			generateGanttAbsensiV4(start_date, end_date);
			
			id_hemxxmh_old = id_hem_get;
			
			$('#select_periode_payroll').select2('open');

			setTimeout(function() {
				$('#select_periode_payroll').select2('close');
			}, 5);

			//start datatables
			tbllap_absensi_kmj = $('#tbllap_absensi_kmj').DataTable( {
				searchPanes:{
					layout: 'columns-2',
				},
				dom: 
					"<P>"+
					"<lf>"+
					"<B>"+
					"<rt>"+
					"<'row'<'col-sm-4'i><'col-sm-8'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true,
						},
						targets: [3,4]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				],
				ajax: {
					url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
					},
					dataSrc: 'data.htsprrd'
				},
				order: [[3, "asc"], [ 0, "asc" ]],
				responsive: false,
				columns: [
					{ 
						data: "NIK",
						render: function(data, type, row) {
							var id_hemxxmh = row.id_hemxxmh;
							var tanggal = row.tanggal;
							var url = "../dashboard/d_hr_report_presensi.php?id_hemxxmh=" + id_hemxxmh + "&start_date=" + tanggal;
							var link = '<a href="' + url + '" target="_blank"> ' + data + ' </a>';
							return link;
						}
					},
					{ data: "Nama" },
					{ data: "Sub Tipe" },
					{ data: "tanggal" },
					{ data: "Shift" },
					{ data: "Check In" },
					{ data: "Check Out" },
					{ data: "Durasi (Jam)" },
					{ data: "Count orang" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_lap_absensi_kmj';
						$table       = 'tbllap_absensi_kmj';
						$edt         = 'edtlap_absensi_kmj';
						$show_status = '_lap_absensi_kmj';
						$table_name  = $nama_tabel;

						$arr_buttons_tools = ['copy','excel','colvis'];
						$arr_buttons_action = [];
						$arr_buttons_approve = [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				},
			} );

			tbllap_absensi_kmj.searchPanes.container().appendTo( '#searchPanes1' );

			$("#frmlap_absensi_kmj").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmlap_absensi_kmj) {
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

					generateTable(start_date, end_date);
					// generateGanttAbsensiV2(start_date, end_date);
					generateGanttAbsensiV4(start_date, end_date);

					tbllap_absensi_kmj.rows().deselect();
					tbllap_absensi_kmj.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});
			
			if (id_hem_get > 0) {
				$("#frmlap_absensi_kmj").submit();
			}
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
