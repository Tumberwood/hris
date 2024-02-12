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
				<form id="frmUploadthimportcheckclock" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Staff</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_staff">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock PMI</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_pmi">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Outsourcing</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_os">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Istirahat</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_istirahat">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Makan</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_makan">
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Checkclock Makan Manual</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportcheckclock_makan_manual">
							</div>
						</div>
					</div>

					<span class="input-group-append"> 
						<button type="submit" id="submit_ceklok" class="btn btn-primary">Import</button>
					</span>
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

			//Edit by Ferry, revisi dijadikan 1 button untuk semua inputan
			var frmUploadthimportcheckclock = $("#frmUploadthimportcheckclock").submit(function(e) {
				e.preventDefault();
				$('#submit_ceklok').hide();
			}).validate({

				submitHandler: function(form) { 
					
					var notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'danger',
						delay: 0
					});

					
					//STAFF
					var fd_staff = new FormData();
					var staff = $('#inputfilethimportcheckclock_staff')[0].files[0];
					if (staff != undefined) {
						fd_staff.append('filename',staff);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_staff.php",
							type: 'POST',
							dataType: 'json',
							data: fd_staff,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_staff").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}
					
					//PMI
					var fd_pmi = new FormData();
					var pmi = $('#inputfilethimportcheckclock_pmi')[0].files[0];
					// console.log(pmi);
					fd_pmi.append('filename',pmi);
					
					if (pmi != undefined) {
						fd_pmi.append('filename',pmi);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_pmi.php",
							type: 'POST',
							dataType: 'json',
							data: fd_pmi,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_pmi").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}
					
					//os
					var fd_os = new FormData();
					var os = $('#inputfilethimportcheckclock_os')[0].files[0];
					// console.log(os);
					fd_os.append('filename',os);
					
					if (os != undefined) {
						fd_os.append('filename',os);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_os.php",
							type: 'POST',
							dataType: 'json',
							data: fd_os,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_os").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}

					//istirahat
					var fd_istirahat = new FormData();
					var istirahat = $('#inputfilethimportcheckclock_istirahat')[0].files[0];
					// console.log(istirahat);
					fd_istirahat.append('filename',istirahat);
					
					if (istirahat != undefined) {
						fd_istirahat.append('filename',istirahat);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_istirahat.php",
							type: 'POST',
							dataType: 'json',
							data: fd_istirahat,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_istirahat").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}

					
					//makan
					var fd_makan = new FormData();
					var makan = $('#inputfilethimportcheckclock_makan')[0].files[0];
					// console.log(makan);
					fd_makan.append('filename',makan);
					
					if (makan != undefined) {
						fd_makan.append('filename',makan);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_makan.php",
							type: 'POST',
							dataType: 'json',
							data: fd_makan,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_makan").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}

					
					//makan_manual
					var fd_makan_manual = new FormData();
					var makan_manual = $('#inputfilethimportcheckclock_makan_manual')[0].files[0];
					// console.log(makan_manual);
					fd_makan_manual.append('filename',makan_manual);
					
					if (makan_manual != undefined) {
						fd_makan_manual.append('filename',makan_manual);
			
						$.ajax( {
							url: "../../models/gipxxsh/gipxxsh_fn_checkclock_makan_manual.php",
							type: 'POST',
							dataType: 'json',
							data: fd_makan_manual,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								
								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message,
									delay: 0,
									showProgressbar: true, // To show a progress bar
									template: 
										'<div class="alert alert-{0} alert-dismissible" role="alert">' +
											'<button type="button" class="close" data-notify="dismiss">×</button>' +
											'<div data-notify="message">{2}</div>' +
										'</div>'
								});
								$("#inputfilethimportcheckclock_makan_manual").val('');
								notifyprogress.close();
								$('#submit_ceklok').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}
				}
			});
			// END upload data

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
