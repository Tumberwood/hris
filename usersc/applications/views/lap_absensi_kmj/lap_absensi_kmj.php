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

</style> -->

<style>

/* ============================================================
   GANTT ABSENSI KMJ V5
   CLEAN / MODERN / COMPACT
   ============================================================ */


/* ============================================================
   MAIN CONTAINER
   ============================================================ */

#gantt_absensi_kmj {

    width: 100%;

    height: 720px;

    max-height: 720px;

    position: relative;

    box-sizing: border-box;

    overflow-x: auto;

    overflow-y: auto;

    border: 1px solid #d8dce1;

    border-radius: 8px;

    background: #ffffff;

    scrollbar-width: auto;

    scrollbar-color:
        #aeb4bc
        #f1f3f5;

}


/* ============================================================
   SCROLL CONTENT
   ============================================================ */

#gantt_absensi_kmj .gantt-v5-scroll-content {

    position: relative;

    display: block;

    min-width: max-content;

    box-sizing: border-box;

}


/* ============================================================
   CHART
   ============================================================ */

#gantt_absensi_kmj_chart {

    position: relative;

    display: block;

    background: #ffffff;

}


/* ============================================================
   CHART SVG
   ============================================================ */

#gantt_absensi_kmj
.highcharts-container {

    overflow: visible !important;

}


#gantt_absensi_kmj
.highcharts-root {

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* ============================================================
   SCROLLBAR CHROME
   ============================================================ */

#gantt_absensi_kmj::-webkit-scrollbar {

    width: 11px;

    height: 11px;

}


#gantt_absensi_kmj::-webkit-scrollbar-track {

    background: #f1f3f5;

    border-radius: 6px;

}


#gantt_absensi_kmj::-webkit-scrollbar-thumb {

    background: #b7bdc5;

    border-radius: 6px;

    border: 2px solid #f1f3f5;

}


#gantt_absensi_kmj::-webkit-scrollbar-thumb:hover {

    background: #9299a3;

}


#gantt_absensi_kmj::-webkit-scrollbar-corner {

    background: #f1f3f5;

}


/* ============================================================
   TITLE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-title {

    fill: #1f2937 !important;

    color: #1f2937 !important;

    font-size: 16px !important;

    font-weight: 700 !important;

}


/* ============================================================
   SUBTITLE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-subtitle {

    fill: #8a929d !important;

    color: #8a929d !important;

    font-size: 11px !important;

}


/* ============================================================
   X AXIS
   ============================================================ */

#gantt_absensi_kmj
.highcharts-xaxis {

    overflow: visible;

}


/* ============================================================
   X AXIS LINE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-xaxis-line {

    stroke: #cfd4da;

    stroke-width: 1;

}


/* ============================================================
   X AXIS TICK
   ============================================================ */

#gantt_absensi_kmj
.highcharts-tick {

    stroke: #d9dde2;

    stroke-width: 1;

}


/* ============================================================
   TIME LABEL
   ============================================================ */

.gantt-v5-time {

    width: 95px;

    min-width: 95px;

    box-sizing: border-box;

    padding-top: 4px;

    text-align: center;

    line-height: 1.2;

}


.gantt-v5-time b {

    display: block;

    color: #68717c;

    font-size: 10px;

    font-weight: 600;

    letter-spacing: .1px;

}


/* ============================================================
   DATE HEADER
   ============================================================ */

.gantt-v5-date {

    width: 100%;

    box-sizing: border-box;

    padding: 2px 8px;

    text-align: center;

    color: #374151;

    font-size: 12px;

    font-weight: 700;

    white-space: nowrap;

}


/* ============================================================
   HIGHCHART BACKGROUND
   ============================================================ */

#gantt_absensi_kmj
.highcharts-background {

    fill: #ffffff;

}


/* ============================================================
   PLOT BACKGROUND
   ============================================================ */

#gantt_absensi_kmj
.highcharts-plot-background {

    fill: #ffffff;

}


/* ============================================================
   GRID X
   ============================================================ */

#gantt_absensi_kmj
.highcharts-grid-line {

    stroke: #e5e7eb;

    stroke-width: 1;

}


/* ============================================================
   GRID Y
   ============================================================ */

#gantt_absensi_kmj
.highcharts-grid-axis
.highcharts-grid-line {

    stroke: #e1e4e8;

    stroke-width: 1;

}


/* ============================================================
   AXIS LINE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-axis-line {

    stroke: #d5d9de;

    stroke-width: 1;

}


/* ============================================================
   Y AXIS LABEL AREA
   ============================================================ */

#gantt_absensi_kmj
.highcharts-yaxis-labels {

    overflow: visible !important;

}


/* ============================================================
   Y AXIS TITLE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-yaxis-title {

    fill: #6b7280 !important;

    color: #6b7280 !important;

    font-size: 11px !important;

    font-weight: 600 !important;

}


/* ============================================================
   PERSON CONTAINER
   ============================================================ */

.gantt-v5-person {

    display: flex;

    align-items: center;

    width: 250px;

    min-width: 250px;

    height: 38px;

    box-sizing: border-box;

    gap: 8px;

    padding: 0 8px 0 2px;

    white-space: nowrap;

    overflow: hidden;

}


/* ============================================================
   PERSON AVATAR
   ============================================================ */

.gantt-v5-avatar {

    width: 26px;

    height: 26px;

    min-width: 26px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    box-sizing: border-box;

    border-radius: 50%;

    background: #f3f4f6;

    border: 1px solid #d9dde3;

    color: #4b5563;

    font-size: 10px;

    font-weight: 700;

}


/* ============================================================
   PERSON NAME
   ============================================================ */

.gantt-v5-name {

    display: block;

    max-width: 205px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    color: #374151;

    font-size: 11px;

    font-weight: 600;

    text-align: left;

}


/* ============================================================
   GANTT ROW
   ============================================================ */

#gantt_absensi_kmj
.highcharts-grid-axis
.highcharts-grid-line {

    stroke: #e2e5e9;

}


/* ============================================================
   GANTT BAR
   ============================================================ */

#gantt_absensi_kmj
.highcharts-gantt-series
.highcharts-point {

    rx: 5px;

    ry: 5px;

    stroke-width: 0;

    opacity: .96;

    transition:
        filter .15s ease,
        opacity .15s ease;

}


/* ============================================================
   GANTT BAR HOVER
   ============================================================ */

#gantt_absensi_kmj
.highcharts-gantt-series
.highcharts-point:hover {

    opacity: 1;

    filter:
        brightness(1.04)
        drop-shadow(
            0 2px 3px
            rgba(
                0,
                0,
                0,
                .18
            )
        );

}


/* ============================================================
   DATA LABEL
   ============================================================ */

#gantt_absensi_kmj
.highcharts-data-label {

    overflow: visible !important;

}


#gantt_absensi_kmj
.highcharts-data-label text {

    font-size: 9px !important;

    font-weight: 600 !important;

    fill: #1f2937 !important;

    color: #1f2937 !important;

    text-outline: none !important;

}


/* ============================================================
   DATE PLOT BAND
   ============================================================ */

#gantt_absensi_kmj
.highcharts-plot-band {

    fill: rgba(
        248,
        250,
        252,
        .75
    );

}


/* ============================================================
   DATE BAND BORDER
   ============================================================ */

#gantt_absensi_kmj
.highcharts-plot-band-label {

    color: #374151 !important;

}


/* ============================================================
   TOOLTIP
   ============================================================ */

.gantt-v5-tooltip {

    width: 245px;

    box-sizing: border-box;

    padding: 11px 12px;

    border: 1px solid #e1e4e8;

    border-radius: 6px;

    background: #ffffff;

    color: #222;

    box-shadow:
        0 5px 18px
        rgba(
            0,
            0,
            0,
            .12
        );

}


/* ============================================================
   TOOLTIP NAME
   ============================================================ */

.gantt-v5-tooltip-name {

    margin-bottom: 8px;

    padding-bottom: 7px;

    border-bottom: 1px solid #eceff2;

    color: #1f2937;

    font-size: 13px;

    font-weight: 700;

}


/* ============================================================
   TOOLTIP ROW
   ============================================================ */

.gantt-v5-tooltip-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;

    padding: 3px 0;

    color: #374151;

    font-size: 11px;

}


/* ============================================================
   TOOLTIP LABEL
   ============================================================ */

.gantt-v5-tooltip-row span {

    color: #8a929d;

    font-weight: 500;

}


/* ============================================================
   TOOLTIP VALUE
   ============================================================ */

.gantt-v5-tooltip-row b {

    color: #374151;

    font-weight: 600;

    text-align: right;

}


/* ============================================================
   TOOLTIP DURATION
   ============================================================ */

.gantt-v5-tooltip-duration {

    margin-top: 7px;

    padding-top: 7px;

    border-top: 1px solid #eceff2;

    color: #e58a00;

    font-size: 11px;

    font-weight: 700;

    text-align: right;

}


/* ============================================================
   HIGHCHART TOOLTIP
   ============================================================ */

#gantt_absensi_kmj
.highcharts-tooltip-box {

    fill: #ffffff;

    stroke: #dfe3e8;

    stroke-width: 1;

}


/* ============================================================
   HIGHCHART CROSSHAIR
   ============================================================ */

#gantt_absensi_kmj
.highcharts-crosshair {

    stroke: #9ca3af;

    stroke-width: 1;

    stroke-dasharray: 4, 3;

}


/* ============================================================
   REMOVE EXTRA FOCUS OUTLINE
   ============================================================ */

#gantt_absensi_kmj
.highcharts-point:focus {

    outline: none;

}


/* ============================================================
   TABLE-LIKE ROW SEPARATOR
   ============================================================ */

#gantt_absensi_kmj
.highcharts-grid-line {

    shape-rendering: crispEdges;

}


/* ============================================================
   MOBILE
   ============================================================ */

@media (max-width: 768px) {

    #gantt_absensi_kmj {

        height: 600px;

        max-height: 600px;

        border-radius: 6px;

    }


    .gantt-v5-person {

        width: 210px;

        min-width: 210px;

    }


    .gantt-v5-name {

        max-width: 165px;

        font-size: 11px;

    }


    .gantt-v5-avatar {

        width: 24px;

        height: 24px;

        min-width: 24px;

    }

}


/* ============================================================
   SMALL SCREEN
   ============================================================ */

@media (max-width: 480px) {

    #gantt_absensi_kmj {

        height: 520px;

        max-height: 520px;

    }


    .gantt-v5-person {

        width: 190px;

        min-width: 190px;

    }


    .gantt-v5-name {

        max-width: 145px;

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
			generateGanttAbsensiV5(start_date, end_date);
			
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
					generateGanttAbsensiV5(start_date, end_date);

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
