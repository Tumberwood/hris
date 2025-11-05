<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = '';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="row m-b-n">
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <div class="ibox-tools">
                                    <span class="label label-success float-right">Current</span>
                                </div>
                                <h5>by Gender</h5>
                            </div>
                            <div class="ibox-content">
								<div class="row">
									<div class="col-4">
										<i class="fa fa-male fa-3x"></i>
									</div>
									<div class="col-4">
									<i class="fa fa-female fa-3x"></i>
									</div>
									<div class="col-4">
										<h3>TOTAL</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-4">
										<h2 class="font-bold m-b-xxs" id="c_laki"></h2>
										<small class="font-bold text-success" id="p_laki"></small>
									</div>
									<div class="col-4">
										<h2 class="font-bold m-b-xxs" id="c_perempuan"></h2>
										<small class="font-bold text-success" id="p_perempuan"></small>
									</div>
									<div class="col-4">
										<h2 class="font-bold m-b-xxs" id="c_total"></h2>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div id="chartEmpType"></div>
								<h3 id="total_type" style="text-align:center;"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
								<div id="chartEmpStatus"></div>
								<h3 id="total_status"  style="text-align:center;"></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
								<div id="chartEmpLevel"></div>
								<h3 id="total_level"  style="text-align:center;"></h3>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="row">
					<div class="col-lg-12">
						<div class="" id="chartEmpDept"></div>
						<h3 id="total_dept"  style="text-align:center;"></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="row">
					<div class="col-lg-6">
						<div class="" id="chartEmpAge"></div>
						<h3 id="total_age"  style="text-align:center;"></h3>
					</div>
					<div class="col-lg-6">
						<div class="" id="chartEmpMK"></div>
						<h3 id="total_mk"  style="text-align:center;"></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<h3>Table Bagian</h3>
				<div class="table-responsive">
					<table id="tblhtlxxrh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Bagian</th>
								<th>Jumlah Organik</th>
								<th>Jumlah Outsourcing</th>
								<th>Total</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Total</th>
								<th class="text-right bg-success" id="s_org"></th>
								<th class="text-right bg-warning" id="s_outs"></th>
								<th class="text-right bg-primary" id="s_total"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>

<!-- load highcharts -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts-more.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/exporting.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/no-data-to-display.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/dashboard/fn/d_hr_profile_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		

		$(document).ready(function() {
			boxEmpGender();
			chartEmpType();
			chartEmpStatus();
			chartEmpLevel();
			chartEmpDept();
			chartEmpAge();
			chartEmpMK();

			
			$.ajax({
				url: "../../models/dashboard/d_hr_profile_bagian.php",
				dataType: 'json',
				type: 'POST',
				data: {
				},
				success: function (json) {
					// kalau table sudah ada → reset dulu
					if ($.fn.dataTable.isDataTable('#tblhtlxxrh')) {
						$('#tblhtlxxrh').DataTable().clear().destroy();
						$('#tblhtlxxrh tbody').empty();
					}

					// build DataTable baru
					$('#tblhtlxxrh').DataTable({
						data: json.data.result, // dari fn_ajax_results.php otomatis "data"
						columns: [
							{ data: "bagian" },
							{ data: "c_pmi", class: "text-right" },
							{ data: "c_os", class: "text-right" },
							{ data: "c_total", class: "text-right" },
						],
						destroy: true,
						responsive: false,
						scrollX: true,
						footerCallback: function ( row, data, start, end, display ) {
							var api       = this.api(), data;
							var numFormat1 = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
							var numFormat0 = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
							// hitung jumlah 
							s_org = api.column( 1 ).data().sum();
							s_outs = api.column( 2 ).data().sum();
							s_total = api.column( 3 ).data().sum();
							

							$( '#s_org' ).html( numFormat1(s_org) );
							$( '#s_outs' ).html( numFormat1(s_outs) );
							$( '#s_total' ).html( numFormat1(s_total) );
						}
					});
				}
			});
						
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
