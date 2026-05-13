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
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>1.</b>
								</div>

								<div>
									<b>
										Komponen per Karyawan
										(Gaji Pokok, Gaji BPJS TK, Gaji BPJS Kes,
										Tunjangan Lain-lain & Iuran SPSI)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompKaryawan">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/1. template_komp_per_karyawan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
							</div>
						</div>
					</div>
					<div class="form-group row" style='display:none;'>
						<label class="col-lg-2 col-form-label"><b>Komponen per Status (Potongan Uang Makan)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompStatus">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/template_komp_per_status.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row" style='display:none;'>
						<label class="col-lg-2 col-form-label"><b>Komponen per Tipe (Outsourcing/Organik)</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompTipe">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/template_komp_per_tipe.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>2.</b>
								</div>

								<div>
									<b>
										Komponen per Grup Jabatan, Bagian, Skala Upah, Sub Tipe, Status
										(Tunj. Jabatan)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompLevel">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/2. template_komp_tunj_jabatan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>3.</b>
								</div>

								<div>
									<b>
										Komponen per Grup Jabatan
										(Tunjangan Masa Kerja)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompgrup_jabatan">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/3. template_komp_per_grup_jabatan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Tunjangan Masa Kerja</b>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>4.</b>
								</div>

								<div>
									<b>
										Komponen Sub Tipe
										(Potongan Absen, Lembur Mati / Jam)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompSub_tipe">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/4. template_komp_per_sub_tipe.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>5.</b>
								</div>

								<div>
									<b>
										Komponen Sub Tipe
										(Potongan Uang Makan)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompPot_uang_makan">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/5. template_komp_pot_uang_makan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
							</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>6. Komponen Tunjangan Khusus</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompTj_khusus">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/6. template_komp_tj_khusus.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
							</div>
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">
							<div class="d-flex">
								<div class="mr-2">
									<b>7.</b>
								</div>

								<div>
									<b>
										Komponen per Grup Jabatan, Skala Upah, Sub Tipe, Status
										(Premi Absen)
									</b>
								</div>
							</div>
						</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputKompPremi">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/temp_komp_v2/07 template_premi absesn_per_grup jabatan.xlsx');">
									<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
								</button>
								<b class="ml-2">Insert ke Gaji Karyawan</b>
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
							url: "../../models/upload_komp_2/upload_komp_2_fn_karyawan.php",
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
							url: "../../models/upload_komp_2/upload_komp_2_fn_status.php",
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
							url: "../../models/upload_komp_2/upload_komp_2_fn_tipe.php",
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
							url: "../../models/upload_komp_2/upload_komp_2_fn_tunj_jab.php",
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
					
					//premi
					var fd_premi = new FormData();
					var premi = $('#inputKompPremi')[0].files[0];
					console.log(premi);
					if (premi != undefined) {
						fd_premi.append('filename',premi);
			
						$.ajax( {
							url: "../../models/upload_komp_2/upload_komp_2_fn_premi.php",
							type: 'POST',
							dataType: 'json',
							data: fd_premi,
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
								$("#inputKompPremi").val('');
							},
							error: function (xhr, Premi, err){
								console.log('x');
							}
						} );
					}
					
					//grup_jabatan
					var fd_grup_jabatan = new FormData();
					var grup_jabatan = $('#inputKompgrup_jabatan')[0].files[0];
					console.log(grup_jabatan);
					if (grup_jabatan != undefined) {
						fd_grup_jabatan.append('filename',grup_jabatan);
			
						$.ajax( {
							url: "../../models/upload_komp_2/upload_komp_2_fn_grup_jabatan.php",
							type: 'POST',
							dataType: 'json',
							data: fd_grup_jabatan,
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
								$("#inputKompgrup_jabatan").val('');
							},
							error: function (xhr, grup_jabatan, err){
								console.log('x');
							}
						} );
					}
					
					//sub_tipe
					var fd_sub_tipe = new FormData();
					var sub_tipe = $('#inputKompSub_tipe')[0].files[0];
					console.log(sub_tipe);
					if (sub_tipe != undefined) {
						fd_sub_tipe.append('filename',sub_tipe);
			
						$.ajax( {
							url: "../../models/upload_komp_2/upload_komp_2_fn_sub_tipe_baru.php",
							type: 'POST',
							dataType: 'json',
							data: fd_sub_tipe,
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
								$("#inputKompSub_tipe").val('');
							},
							error: function (xhr, Sub_tipe, err){
								console.log('x');
							}
						} );
					}

					//pot_uang_makan
					var fd_pot_uang_makan = new FormData();
					var pot_uang_makan = $('#inputKompPot_uang_makan')[0].files[0];
					console.log(pot_uang_makan);
					if (pot_uang_makan != undefined) {
						fd_pot_uang_makan.append('filename',pot_uang_makan);
			
						$.ajax( {
							url: "../../models/upload_komp_2/upload_komp_2_fn_pot_uang_makan_baru.php",
							type: 'POST',
							dataType: 'json',
							data: fd_pot_uang_makan,
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
								$("#inputKompPot_uang_makan").val('');
							},
							error: function (xhr, Pot_uang_makan, err){
								console.log('x');
							}
						} );
					}

					//tj_khusus
					var fd_tj_khusus = new FormData();
					var tj_khusus = $('#inputKompTj_khusus')[0].files[0];
					console.log(tj_khusus);
					if (tj_khusus != undefined) {
						fd_tj_khusus.append('filename',tj_khusus);
			
						$.ajax( {
							url: "../../models/upload_komp_2/upload_komp_2_fn_tj_khusus.php",
							type: 'POST',
							dataType: 'json',
							data: fd_tj_khusus,
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
								$("#inputKompTj_khusus").val('');
							},
							error: function (xhr, Tj_khusus, err){
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
