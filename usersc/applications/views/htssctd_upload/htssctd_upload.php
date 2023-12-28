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
        <div class="ibox" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmperiode">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periode</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
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

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-title">
                <h5 class="text-navy">Upload Jadwal</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
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

<div class="row" id="report" style="display: none">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<h3 id="judul"></h3>
					<div class="alert alert-info alert-dismissable">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<b>Penjelasan Kode Shift:</b>
						<ul>
							<li><b>L &nbsp = &nbsp </b> OFF</li>
							<li><b>1 &nbsp = &nbsp </b> PAGI 07:00-15:00</li>
							<li><b>2 &nbsp = &nbsp </b> SORE 15:00-23:00</li>
							<li><b>3 &nbsp = &nbsp </b> MALAM 23:00-07:00</li>
						</ul>
					</div>
                	<div id="searchPanes1"></div>
					<div id="tabel_atas"></div>
				</div> <!-- end of table -->
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
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "M yyyy", 
			minViewMode: 'months' 
		});

		$('#start_date').datepicker('setDate', tanggal_hariini_dmy);

		
		$(document).ready(function() {
			start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
			generateTable(start_date);

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
								generateTable(start_date);
							},
							error: function (xhr, Status, err){
								console.log('x');
							}
						} );
					}
				}
			});
			// END upload data

			//GENERATE TABLE
			
			function generateTable(start_date) {
				$('#report').show();
				$('#tabel_atas').empty();
				$('#searchPanes1').empty();
				tanggal 		= moment($('#start_date').val()).format('MMM YYYY');
				$('#judul').html("Tabel Upload Jadwal Bulan " + tanggal);
				
				$.ajax({
					url: "../../models/htssctd_upload/htssctd_upload.php",
					dataType: 'json',
					type: 'POST',
					data: {
						start_date: start_date
					},
					success: function (json) {
						if (json.data.length > 0) {
							// Create an empty table
							var str1 = '<table id="tblhtsprrd1" class="table table-striped table-bordered table-hover nowrap">';
							str1 += '<thead>';
							str1 += '<tr>';

							// Loop through columns and add them to the table header
							$.each(json.columns, function (k, colObj) {
								str1 += '<th>';
								str1 += colObj.data + '</th>';
							});
							str1 += '</tr>';
							str1 = str1 + '</thead>';
							str1 += '<tbody>';

							// Loop through data and add rows to the table body
							$.each(json.data, function (index, rowData) {
								str1 += '<tr>';

								$.each(json.columns, function (k, colObj) {
									var columnName = colObj.data;
									var cellValue = rowData[columnName];
									// console.log(cellValue);
									if (cellValue == undefined) {
										cellValue = "-";
									}
									str1 += '<td>' + cellValue + '</td>';
								});
								str1 += '</tr>';
							});

							str1 += '</tbody>';
							str1 += '</table>';

							// Update the table element
							$('#tabel_atas').html(str1);

							// Initialize DataTable
							tblhtsprrd1 = $('#tblhtsprrd1').DataTable({
								responsive: false,
								searchPanes:{
									layout: 'columns-3',
								},
								dom: 
									"<P>"+
									"<lf>"+
									"<B>"+
									"<rt>"+
									"<'row'<'col-sm-4'i><'col-sm-8'p>>",
								columnDefs:[
									{
										searchPanes:{
											show: true,
										},
										targets: [0,1,2]
									},
									{
										searchPanes:{
											show: false,
										},
										targets: '_all'
									}
								],
							});
							tblhtsprrd1.searchPanes.container().appendTo( '#searchPanes1' );
							
						} else {
							// notifyprogress.close();
							notifyprogress = $.notify({
								message: 'Tidak ada data pada tanggal tersebut!'
							}, {
								z_index: 9999,
								allow_dismiss: false,
								type: 'danger',
								delay: 3
							});
						}
					}
				});
			}

			// FORM PERIODE
			
			$("#frmperiode").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmperiode) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					console.log(start_date);
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});
					// tblhtsprrd.destroy();
					generateTable(start_date)
					
					notifyprogress.close();
					return false; 
				}
			});
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
