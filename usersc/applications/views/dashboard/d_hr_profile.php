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
						
                        <h3 id='usia'>Table Usia</h3>
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table id="tblusia" class="table table-striped table-bordered table-hover nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Departemen</th>
                                                <th>Unit Kerja</th>
                                                <th>Jabatan</th>
                                                <th>Tanggal Join</th>
                                                <th>Usia</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
					</div>
					<div class="col-lg-6">
						<div class="" id="chartEmpMK"></div>
						<h3 id="total_mk"  style="text-align:center;"></h3>
						
                        <h3 id='mk'>Table Masa Kerja</h3>
                        <div class="ibox ">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table id="tblmk" class="table table-striped table-bordered table-hover nowrap" width="100%">
                                        <thead>
                                            <tr>
                                                <th>NIK</th>
                                                <th>Nama</th>
                                                <th>Departemen</th>
                                                <th>Unit Kerja</th>
                                                <th>Jabatan</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Masa Kerja</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
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
				<h3>Table Divisi</h3>
				<div class="table-responsive">
					<table id="tblhovxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th width="40%">Divisi</th>
								<th>Jumlah Organik</th>
								<th>Jumlah Outsourcing</th>
								<th>Total</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Total</th>
								<th class="text-right bg-success" id="hovxxmh_org"></th>
								<th class="text-right bg-warning" id="hovxxmh_outs"></th>
								<th class="text-right bg-primary" id="hovxxmh_total"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<h3>Table Departemen</h3>
				<div class="table-responsive">
					<table id="tblhodxxmh" class="table table-striped table-bordered table-hoder nowrap" width="100%">
						<thead>
							<tr>
								<th width="40%">Departemen</th>
								<th>Jumlah Organik</th>
								<th>Jumlah Outsourcing</th>
								<th>Total</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Total</th>
								<th class="text-right bg-success" id="hodxxmh_org"></th>
								<th class="text-right bg-warning" id="hodxxmh_outs"></th>
								<th class="text-right bg-primary" id="hodxxmh_total"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<h3>Table Bagian</h3>
				<div class="table-responsive">
					<table id="tblhobxxmh" class="table table-striped table-bordered table-hober nowrap" width="100%">
						<thead>
							<tr>
								<th width="40%">Bagian</th>
								<th>Jumlah Organik</th>
								<th>Jumlah Outsourcing</th>
								<th>Total</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Total</th>
								<th class="text-right bg-success" id="hobxxmh_org"></th>
								<th class="text-right bg-warning" id="hobxxmh_outs"></th>
								<th class="text-right bg-primary" id="hobxxmh_total"></th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-12">
		<div class="ibox ">
			<div class="ibox-content">
				<h3>Table Unit Kerja</h3>
				<div class="table-responsive">
					<table id="tblhtlxxrh" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th width="40%">Unit Kerja</th>
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

			hovxxmh();
			hodxxmh();
			hobxxmh();
			htlxxrh();
						
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
