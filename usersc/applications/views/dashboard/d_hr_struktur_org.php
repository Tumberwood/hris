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

<!-- OrgChart CSS -->
<link rel="stylesheet" href="https://dabeng.github.io/OrgChart/css/jquery.orgchart.css">
<style>
	#chart-container {
	width: 100%;
	min-height: 600px;
	background: #ffffff; /* putih polos */
	text-align: center;
	overflow: auto;
	padding: 20px;
	}

	/* Override background grid bawaan orgchart */
	.orgchart {
	background: #ffffff !important;  /* ganti putih atau transparent */
	}
</style>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
              <div class="card-body">
                <div id="chart-container"></div>
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

<!-- OrgChart JS -->
<script src="https://dabeng.github.io/OrgChart/js/jquery.orgchart.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		

		$(document).ready(function() {

			
			$.ajax({
				url: "../../models/dashboard/d_hr_struktur_org.php",
				dataType: 'json',
				type: 'POST',
				data: {
				},
				success: function (json) {
					var struktur_org = json.data.struktur_org;

					var finalData = {
						name: "ROOT",
						title: "ROOT",
						children: struktur_org
					};

					$('#chart-container').orgchart({
						data: finalData,
						nodeContent: 'title',
						pan: false,
						zoom: false
					});
				}
			});
						
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
