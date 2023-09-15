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
					<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_jadwal_satpam.xlsx');">
						<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
					</button>
				</div>
				<form id="frmUploadthimportcheckclock" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Jadwal Satpam</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportjadwal_satpam">
							</div>
						</div>
					</div>
					<span class="input-group-append"> 
						<button type="submit" class="btn btn-primary">Import</button>
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
			// BEGIN upload data
			var frmUploadthimportcheckclock = $("#frmUploadthimportcheckclock").submit(function(e) {
				e.preventDefault();
			}).validate({

				submitHandler: function(form) { 
					
					var notifyprogress;
					//STAFF
					var fd_staff = new FormData();
					var staff = $('#inputfilethimportjadwal_satpam')[0].files[0];
					console.log(staff);
					if (staff != undefined) {
						fd_staff.append('filename',staff);

						notifyprogress = $.notify({
							message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
						},{
							allow_dismiss: false,
							type: 'danger',
							delay: 0,
							element: 'body'
						});
						
						$.ajax( {
							url: "../../models/htssctd_upload/htssctd_upload_fn_jadwal_satpam.php",
							type: 'POST',
							dataType: 'json',
							data: fd_staff,
							async: false,
							contentType: false,
							processData: false,
							success: function ( json ) {
								notifyprogress.close();
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
								$("#inputfilethimportjadwal_satpam").val('');
							},
							error: function (xhr, Status, err){
								console.log('x');
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
