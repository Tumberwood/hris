<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = '_blank';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<h3 class="text-info">Format file harus dalam .xlsx dan disesuaikan dengan format template</h3>
				<hr>
				<div>
					<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_checkclock.xls');">
						<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
					</button>
				</div>
				<form id="frmUploadthimportcheckclock_staff" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Staff</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_staff">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<form id="frmUploadthimportcheckclock_pmi" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock PMI</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_pmi">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<form id="frmUploadthimportcheckclock_os" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Outsourcing</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_os">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<form id="frmUploadthimportcheckclock_istirahat" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Istirahat</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_istirahat">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<form id="frmUploadthimportcheckclock_makan" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Makan</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_makan">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
				<hr>
				<form id="frmUploadthimportcheckclock_makan_manual" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Makan Manual</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_makan_manual">
								<span class="input-group-append"> 
									<button type="submit" class="btn btn-primary">Import</button>
								</span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		
		$(document).ready(function() {

			// BEGIN upload data finger staff
			var frmUploadthimportcheckclock_staff = $("#frmUploadthimportcheckclock_staff").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_staff	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_staff')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_staff.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;
				}
			});
			// END upload data finger staff

			// BEGIN upload data finger pmi
			var frmUploadthimportcheckclock_pmi = $("#frmUploadthimportcheckclock_pmi").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_pmi	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_pmi')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_pmi.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;  //This doesn't prevent the form from submitting.
				}
			});
			// END upload data finger pmi

			// BEGIN upload data finger os
			var frmUploadthimportcheckclock_os = $("#frmUploadthimportcheckclock_os").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_os	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_os')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_os.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;  //This doesn't prevent the form from submitting.
				}
			});
			// END upload data finger os

			// BEGIN upload data finger istirahat
			var frmUploadthimportcheckclock_istirahat = $("#frmUploadthimportcheckclock_istirahat").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_istirahat	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_istirahat')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_istirahat.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;  //This doesn't prevent the form from submitting.
				}
			});
			// END upload data finger istirahat

			// BEGIN upload data finger makan
			var frmUploadthimportcheckclock_makan = $("#frmUploadthimportcheckclock_makan").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_makan	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_makan')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_makan.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;  //This doesn't prevent the form from submitting.
				}
			});
			// END upload data finger makan

			// BEGIN upload data finger makan_manual
			var frmUploadthimportcheckclock_makan_manual = $("#frmUploadthimportcheckclock_makan_manual").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					inputfilethimportcheckclock_makan_manual	: {required: true}
				},
				submitHandler: function(form) { 
					
					var notifyprogress;
					var fd = new FormData();
					var files = $('#inputfilethimportcheckclock_makan_manual')[0].files[0];
					fd.append('filename',files);

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});
					
					$.ajax( {
						url: "../../models/gipxxsh/gipxxsh_fn_checkclock_makan_manual.php",
						type: 'POST',
						dataType: 'json',
						data: fd,
						async: false,
						contentType: false,
						processData: false,
						success: function ( json ) {
							notifyprogress.close();
							$.notify({
								message: json.data.message
							},{
								type: json.data.type_message
							});
							$("#frmUploadthimportcheckclock_staff")[0].reset();
						},
						error: function (xhr, Status, err){
							console.log('x');
						}
					} );
					return false;  //This doesn't prevent the form from submitting.
				}
			});
			// END upload data finger makan_manual

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
