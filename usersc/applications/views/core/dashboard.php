<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel   = '';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col=lg-4 col-md-4 col-sm-12 col-xs-12">
		<div class="profile-image">
			<img src="../../../files/uploads/def_male.png" class="rounded-circle circle-border m-b-md" alt="profile">
		</div>
		<div class="profile-info">
			<div class="">
				<div>
					<h2 class="no-margins">
						Howdy, 
						<br><strong><span id="hemxxmh_nama"></span></strong>
					</h2>
					<h4 id="namamhjabatan">
						<br><span id="hodxxmh_nama"></span>
						<br><span id="hetxxmh_nama"></span>
						<br><span class="fa fa-calendar">&nbsp</span><span id="masakerja"></span> Tahun
					</h4>
					<i id="icon_anniv" class=""></i>
				</div>
			</div>
		</div>
	</div>
	<div class="col=lg-4 col-md-4 col-sm-12 col-xs-12">
		<h3>Kehadiran</h3>
		<table class="table m-b-xs">
			<tbody>
				<tr>
					<td> <strong><span id="">3 </span>Cuti</strong></td>
					<td class="text-danger"> <strong><span id="">1 </span>Alpa</strong></td>
				</tr>
				<tr>
					<td> <strong><span id="">0 </span>Sakit</strong></td>
					<td class=""> <strong><span id="">1 </span>Surat Dokter</strong></td>
				</tr>
				<tr>
					<td> <strong><span id="">3 </span>Terlambat</strong></td>
					<td class=""> <strong><span id="">1 </span>Pulang Awal</strong></td>
				</tr>
				<tr>
					<td> <strong><span id="">0 </span>Meninggalkan Kerja</strong></td>
					<td class=""> <strong><span id=""></span></strong></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col=lg-4 col-md-4 col-sm-12 col-xs-12">
		<h3>Kedisiplinan</h3>
		<ul class="list-group clear-list m-t">
			<li class="list-group-item fist-item">
				<span class="label label-warning" id="c_teguran">1</span>&nbsp Teguran
			</li>
			<li class="list-group-item">
				<span class="label label-danger" id="c_peringatan">0</span>&nbsp Peringatan
			</li>
			<li class="list-group-item">
				<span class="label label-danger" id="c_skorsing">0</span>&nbsp Skorsing
			</li>
		</ul>
	</div>
</div>

<!-- <div class="row">
	<div class="col=lg-2 col-md-2 col-sm-12 col-xs-12">
			<div id="widgetKPISkor" class="widget p-lg text-center">
			<div class="m-b-md">
				<h1 id="AvgKPISkor" class="m-xs"></h1>
				<h3 class="font-bold no-margins">
					KPI SKOR
				</h3>
				<small>average</small>
			</div>
		</div>
	</div>
</div> -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/core/fn/dashboard_fn.php'; ?>

<script type="text/javascript">
	$(document).ready(function() {
		loadProfile();
	} );// end of document.ready
</script>

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
