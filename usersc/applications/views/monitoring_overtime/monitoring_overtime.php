<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hesxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<h3 class="text-info">Format file harus dalam .xlsx dan disesuaikan dengan format template</h3>
				<hr>
				<form id="frmUpload" enctype="multipart/form-data">
					<div class="form-group row">
                        <label class="col-lg-2 col-form-label"><b>Periode</b></label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" name="start_date" class="form-control">
                                <span class="input-group-addon">to</span>
                                <input type="text" id="end_date" name="end_date" class="form-control">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="form-group row">
						<label class="col-lg-2 col-form-label"><b>Upload Excel Lembur</b></label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputLembur">
							</div>
						</div>
						<div class="col-sm-4">
							<div>
								<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/Data Lembur & Makan Periode 18 12 2024 - 20 01 2025.xls');">
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

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
				<div id="resultTable"></div>

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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/monitoring_overtime/fn/monitoring_overtime_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxmh, tblhesxxmh, show_inactive_status_hesxxmh = 0, id_hesxxmh;
		// ------------- end of default variable
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		
		$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			// BEGIN upload data
			var frmUpload = $("#frmUpload").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					start_date: "required",
					end_date: "required",
					filename: "required"
				},
				messages: {
					start_date: "Pilih start date!",
					end_date: "Pilih end date!",
					filename: "Pilih file yang akan di-upload!"
				},
				submitHandler: function(form) { 
					let start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
					let end_date = moment($('#end_date').val()).format('YYYY-MM-DD');

					let notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0,
						element: 'body'
					});

					let fd_status = new FormData();
					let fileInput = document.getElementById("inputLembur");
					let status = fileInput.files[0];

					if (status !== undefined) {
						fd_status.append('filename', status);
						fd_status.append('start_date', start_date);
						fd_status.append('end_date', end_date);
						$.ajax({
							url: "../../models/monitoring_overtime/monitoring_overtime.php",
							type: "POST",
							data: fd_status,
							processData: false,
							contentType: false,
							dataType: "json",
							success: function(response) {
								notifyprogress.close();

								$.notify({
									message: response.data.message
								},{
									type: response.data.type_message
								});

								if (response.data.type_message == "success") {
									let tableHtml = `
										<div class="table-responsive">
											<table id="tblhemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
												<thead>
													<tr>
														<th rowspan=2>Kode</th>
														<th rowspan=2>Nama</th>
														<th class="text-center" colspan=4>HRIS</th>
														<th class="text-center" colspan=4>Excel</th>
														<th rowspan=2>Sesuai</th>
													</tr>
													<tr>
														<th>Lembur 1.5</th>
														<th>Lembur 2</th>
														<th>Lembur 3</th>
														<th>Lembur 4</th>

														<th>Lembur 1.5</th>
														<th>Lembur 2</th>
														<th>Lembur 3</th>
														<th>Lembur 4</th>
													</tr>
												</thead>
												<tbody></tbody>
												<tfoot>
													<tr>
														<th colspan="2">Total</th>
														<th id="total_2"></th>
														<th id="total_3"></th>
														<th id="total_4"></th>
														<th id="total_5"></th>

														<th id="total_6"></th>
														<th id="total_7"></th>
														<th id="total_8"></th>
														<th id="total_9"></th>
														<th></th>
													</tr>
												</tfoot>
											</table>
										</div>`
									;

									// Tampilkan tabel di div dengan id resultTable
									$("#resultTable").html(tableHtml);

									// Inisialisasi DataTables
									$("#tblhemxxmh").DataTable({
										responsive: false,
										data: response.data.lembur,
										columns: [
											{ data: "kode" },
											{ data: "nama" },
											{ data: "lembur15_db" },
											{ data: "lembur2_db" },
											{ data: "lembur3_db" },
											{ data: "lembur4_db" },
											{ data: "lembur15_xl" },
											{ data: "lembur2_xl" },
											{ data: "lembur3_xl" },
											{ data: "lembur4_xl" },
											{
												data: "total_db",
												render: function (data, type, row) {
													if (data == row.total_xl) {
														return `<span class="badge bg-primary">Ya</span>`;
													} else {
														return `<span class="badge bg-danger">Tidak</span>`;
													}
												}
											}
										],
										rowCallback: function(row, data) {
											if (data.total_xl !== data.total_db) {
												$(row).addClass('text-danger');
											}
										},
										footerCallback: function ( row, data, start, end, display ) {
											var api = this.api();
											var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 

											for (var i = 2; i <= 9; i++) {
												var columnIndex = i;
												var sum_all = api.column(columnIndex).data().sum();
												var sum = api.column(columnIndex, { page: 'current' }).data().sum();
												$('#total_' + columnIndex).html(numFormat(sum_all));
											}
										},
										columnDefs: [
											{ targets: [2, 3, 4, 5, 6, 7, 8, 9], className: "text-right" }
										]
									});
								}
							},
							error: function(xhr) {
								notifyprogress.close();
								console.error("Upload gagal:", xhr.responseText);
							}
						});
					} else {
						notifyprogress.close();
						alert("Harap pilih file sebelum mengupload!");
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
