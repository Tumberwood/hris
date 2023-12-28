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
				<form id="frmUploadKompKaryawan" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Komponen per Karyawan (Gaji Pokok & Gaji BPJS)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompKaryawan">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_komp_per_karyawan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Komponen per Status (Potongan Uang Makan)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompStatus">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_komp_per_status.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Komponen per Tipe (Outsourcing/Organik)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompTipe">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_komp_per_tipe.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Komponen per Level (Premi Absen & Tunj. Jabatan)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompLevel">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_komp_per_level.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Komponen per Grup Level (Tunjangan Masa Kerja)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompGrup_Level">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_komp_per_grup_level.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
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
			var frmUploadKompKaryawan = $("#frmUploadKompKaryawan").submit(function(e) {
				e.preventDefault();
			}).validate({

				submitHandler: function(form) { 
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0,
						element: 'body'
					});

					var notifyprogress;
					
					//karyawan
					var fd_karyawan = new FormData();
					var karyawan = $('#inputKompKaryawan')[0].files[0];
					console.log(karyawan);
					if (karyawan != undefined) {
						fd_karyawan.append('filename',karyawan);
			
						$.ajax( {
							url: "../../models/upload_komp/upload_komp_fn_karyawan.php",
							type: 'POST',
							dataType: 'json',
							data: fd_karyawan,
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
								$("#inputKompKaryawan").val('');
							},
							error: function (xhr, Status, err){
								console.log('x');
							}
						} );
					}
					
					//status
					var fd_status = new FormData();
					var status = $('#inputKompStatus')[0].files[0];
					console.log(status);
					if (status != undefined) {
						fd_status.append('filename',status);
			
						$.ajax( {
							url: "../../models/upload_komp/upload_komp_fn_status.php",
							type: 'POST',
							dataType: 'json',
							data: fd_status,
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
								$("#inputKompStatus").val('');
							},
							error: function (xhr, Status, err){
								console.log('x');
							}
						} );
					}
					
					//tipe
					var fd_tipe = new FormData();
					var tipe = $('#inputKompTipe')[0].files[0];
					console.log(tipe);
					if (tipe != undefined) {
						fd_tipe.append('filename',tipe);
			
						$.ajax( {
							url: "../../models/upload_komp/upload_komp_fn_tipe.php",
							type: 'POST',
							dataType: 'json',
							data: fd_tipe,
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
								$("#inputKompTipe").val('');
							},
							error: function (xhr, Tipe, err){
								console.log('x');
							}
						} );
					}
					
					//level
					var fd_level = new FormData();
					var level = $('#inputKompLevel')[0].files[0];
					console.log(level);
					if (level != undefined) {
						fd_level.append('filename',level);
			
						$.ajax( {
							url: "../../models/upload_komp/upload_komp_fn_level.php",
							type: 'POST',
							dataType: 'json',
							data: fd_level,
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
								$("#inputKompLevel").val('');
							},
							error: function (xhr, Level, err){
								console.log('x');
							}
						} );
					}
					
					//grup_level
					var fd_grup_level = new FormData();
					var grup_level = $('#inputKompGrup_Level')[0].files[0];
					console.log(grup_level);
					if (grup_level != undefined) {
						fd_grup_level.append('filename',grup_level);
			
						$.ajax( {
							url: "../../models/upload_komp/upload_komp_fn_grup_level.php",
							type: 'POST',
							dataType: 'json',
							data: fd_grup_level,
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
								$("#inputKompGrup_Level").val('');
							},
							error: function (xhr, Grup_Level, err){
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
