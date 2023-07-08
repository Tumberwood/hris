<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsprtd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<form>
						<div class="form-group">
							<label>Foto</label>
							<input id="foto" type="file" accept="image/*" class="form-control">
						</div>
						
						<div class="form-group">
							<label>Lat</label>
							<input id="lat" name="lat" type="text" class="form-control">
						</div>
						<div class="form-group">
							<label>Long</label>
							<input id="lng" name="lng" type="text" class="form-control">
						</div>
						<button id="checkin" class="btn btn-primary">Check In</button>
						<button id="checkout" class="btn btn-danger">Check Out</button>
					</form>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprtd_ol/fn/htsprtd_ol_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsprtd, tblhtsprtd, show_inactive_status_htsprtd = 0, id_htsprtd;
		// ------------- end of default variable
		
		$(document).ready(function() {
			
			

			$( "#checkin" ).on( "click", function() {
				event.preventDefault();

				const fileInput = document.getElementById('file-input');
				fileInput.addEventListener('change', (e) =>
					doSomethingWithFiles(e.target.files),
				);

				getLocation();
				nama = 'online';
				// save ke database
			});
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
