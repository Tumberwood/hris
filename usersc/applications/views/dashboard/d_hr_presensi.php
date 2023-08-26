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
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmpresensi">
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

<!-- <div class="row">
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
									<div class="col-6">
										<i class="fa fa-male fa-3x"></i>
									</div>
									<div class="col-6">
									<i class="fa fa-female fa-3x"></i>
									</div>
								</div>
								<div class="row">
									<div class="col-6">
										<h2 class="font-bold m-b-xxs" id="c_laki"></h2>
										<small class="font-bold text-success" id="p_laki"></small>
									</div>
									<div class="col-6">
										<h2 class="font-bold m-b-xxs" id="c_perempuan"></h2>
										<small class="font-bold text-success" id="p_perempuan"></small>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div id="chartEmpType"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
								<div id="chartEmpStatus"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-content">
								<div id="chartEmpLevel"></div>
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div> -->
<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="row">
					<div class="col-lg-6">
						<div class="" id="chartEmpIzin"></div>
					</div>
					<div class="col-lg-6">
						<div class="" id="chartEmpAbsen"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
    
</div>chartEmpAbsen

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>

<!-- load highcharts -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/drilldown.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts-more.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/exporting.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/no-data-to-display.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/dashboard/fn/d_hr_presensi_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		
        start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
        end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

        
		$(document).ready(function() {
			$("#frmpresensi").submit(function(e) {
                e.preventDefault();
            }).validate({
                rules: {
                    // Define your validation rules here
                },
                submitHandler: function(frmpresensi) {
                    start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
                    end_date = moment($('#end_date').val()).format('YYYY-MM-DD');

                    notifyprogress = $.notify({
                        message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
                    }, {
                        z_index: 9999,
                        allow_dismiss: false,
                        type: 'info',
                        delay: 0
                    });

                    chartEmpIzin();
                    chartEmpAbsen();
                    notifyprogress.close();
                    return false; 
                }
            });

			// boxEmpGender();
			// chartEmpType();
			// chartEmpStatus();
			// chartEmpLevel();
			// chartEmpDept();
			chartEmpIzin();
			chartEmpAbsen();
						
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>

