<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'makan_catering_spv_koor';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-title">
                <h5 class="text-navy">Upload Makan Catering SPV dan Koordinator</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
			<div class="ibox-content">
				<h3 class="text-info">Format file harus dalam .xlsx dan disesuaikan dengan format template</h3>
				<hr>
				<div>
					<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_makan_catering_spv_koor.xlsx');">
						<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
					</button>
				</div>
				<form id="frmUploadthimportmakan" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Makan Catering SPV dan Koordinator</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="inputfilethimportmakan">
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
                    <table id="tblmakan_catering_spv_koor" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Jabatan</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/makan_catering_spv_koor/fn/makan_catering_spv_koor_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmakan_catering_spv_koor, tblmakan_catering_spv_koor, show_inactive_status_makan_catering_spv_koor = 0, id_makan_catering_spv_koor;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtmakan_catering_spv_koor = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/makan_catering_spv_koor/makan_catering_spv_koor.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_spv_koor = show_inactive_status_makan_catering_spv_koor;
					}
				},
				table: "#tblmakan_catering_spv_koor",
				fields: [ 
					{
						label: "start_on",
						name: "start_on",
						type: "hidden"
					},	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
						label: "nama_tabel",
						name: "nama_tabel",
						def: "makan_catering_spv_koor",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_catering_spv_koor.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal <sup class='text-danger'>*<sup>", 
						name: "makan_catering_spv_koor.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Shift <sup class='text-danger'>*<sup>", 
						name: "makan_catering_spv_koor.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": "1" },
							{ "label": "2", "value": "2" },
							{ "label": "3", "value": "3" },
						]
					},	
					{
						label: "Jabatan <sup class='text-danger'>*<sup>", 
						name: "makan_catering_spv_koor.nama",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Supervisor", "value": "Supervisor" },
							{ "label": "Koordinator", "value": "Koordinator" },
						]
					},	
					{
						label: "Jumlah <sup class='text-danger'>*<sup>",
						name: "makan_catering_spv_koor.jumlah"
					}, 	
					{
						label: "Keterangan",
						name: "makan_catering_spv_koor.keterangan",
						type: "textarea"
					},
				]
			} );

			edtmakan_catering_spv_koor.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_spv_koor.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_catering_spv_koor.rows().deselect();
				}
			});

			edtmakan_catering_spv_koor.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtmakan_catering_spv_koor.on( 'preSubmit', function (e, data, action) {
				if (action != 'remove') {

				tanggal = edtmakan_catering_spv_koor.field('makan_catering_spv_koor.tanggal').val();
				nama    = edtmakan_catering_spv_koor.field('makan_catering_spv_koor.nama').val();
				shift   = edtmakan_catering_spv_koor.field('makan_catering_spv_koor.shift').val();
				jumlah  = edtmakan_catering_spv_koor.field('makan_catering_spv_koor.jumlah').val();

				// Validasi wajib
				if (!tanggal) edtmakan_catering_spv_koor.field('makan_catering_spv_koor.tanggal').error('Wajib diisi!');
				if (!nama) edtmakan_catering_spv_koor.field('makan_catering_spv_koor.nama').error('Wajib diisi!');
				if (!shift) edtmakan_catering_spv_koor.field('makan_catering_spv_koor.shift').error('Wajib diisi!');
				if (!jumlah) edtmakan_catering_spv_koor.field('makan_catering_spv_koor.jumlah').error('Wajib diisi!');

				// Validasi jumlah
				if (jumlah !== '' && isNaN(jumlah)) {
					edtmakan_catering_spv_koor.field('makan_catering_spv_koor.jumlah').error('Inputan harus berupa angka!');
				} else if (jumlah !== '' && Number(jumlah) <= 0) {
					edtmakan_catering_spv_koor.field('makan_catering_spv_koor.jumlah').error('Inputan harus > 0');
				}

				// Cek kombinasi unik
				if (tanggal && nama && shift) {

					if (action == 'create') id_makan_catering_spv_koor = 0;

					$.ajax({
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'makan_catering_spv_koor',
							nama_field: 'tanggal,shift,nama',
							nama_field_value: [
								'"' + moment(tanggal).format('YYYY-MM-DD') + '"',
								'"' + String(shift) + '"',
								'"' + String(nama) + '"'
							].join(','),
							id_transaksi: id_makan_catering_spv_koor
						},
						success: function(json) {
							if (json.data.count > 0) {
								var pesan = 'Kombinasi Tanggal, Shift, dan Nama sudah ada!';

								edtmakan_catering_spv_koor.field('makan_catering_spv_koor.tanggal').error(pesan);
								edtmakan_catering_spv_koor.field('makan_catering_spv_koor.shift').error(pesan);
								edtmakan_catering_spv_koor.field('makan_catering_spv_koor.nama').error(pesan);
							}
						}
					});
				}
			}
				
				if ( edtmakan_catering_spv_koor.inError() ) {
					return false;
				}
			});
			
			edtmakan_catering_spv_koor.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_spv_koor.field('finish_on').val(finish_on);
			});

			//start datatables
			tblmakan_catering_spv_koor = $('#tblmakan_catering_spv_koor').DataTable( {
				ajax: {
					url: "../../models/makan_catering_spv_koor/makan_catering_spv_koor.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_spv_koor = show_inactive_status_makan_catering_spv_koor;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_catering_spv_koor.id",visible:false },
					{ data: "makan_catering_spv_koor.tanggal" },
					{ data: "makan_catering_spv_koor.shift" },
					{ data: "makan_catering_spv_koor.nama" },
					{ data: "makan_catering_spv_koor.jumlah" },
					{ data: "makan_catering_spv_koor.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_catering_spv_koor';
						$table       = 'tblmakan_catering_spv_koor';
						$edt         = 'edtmakan_catering_spv_koor';
						$show_status = '_makan_catering_spv_koor';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_catering_spv_koor.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblmakan_catering_spv_koor.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblmakan_catering_spv_koor);
			} );
			
			tblmakan_catering_spv_koor.on( 'select', function( e, dt, type, indexes ) {
				makan_catering_spv_koor_data    = tblmakan_catering_spv_koor.row( { selected: true } ).data().makan_catering_spv_koor;
				id_makan_catering_spv_koor      = makan_catering_spv_koor_data.id;
				id_transaksi_h = id_makan_catering_spv_koor; // dipakai untuk general
				is_approve     = makan_catering_spv_koor_data.is_approve;
				is_nextprocess = makan_catering_spv_koor_data.is_nextprocess;
				is_jurnal      = makan_catering_spv_koor_data.is_jurnal;
				is_active      = makan_catering_spv_koor_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblmakan_catering_spv_koor);
			} );

			tblmakan_catering_spv_koor.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_catering_spv_koor = '';

				// atur hak akses
				CekDeselectHeaderH(tblmakan_catering_spv_koor);
			} );
			
			// BEGIN upload data
			var frmUploadthimportmakan = $("#frmUploadthimportmakan").submit(function(e) {
				e.preventDefault();
			}).validate({

				submitHandler: function(form) { 
					
					var notifyprogress;

					var fd_makan = new FormData();
					var makan = $('#inputfilethimportmakan')[0].files[0];
					// console.log(makan);
					if (makan != undefined) {
						fd_makan.append('filename',makan);

						notifyprogress = $.notify({
							message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
						},{
							allow_dismiss: false,
							type: 'danger',
							delay: 0,
							element: 'body'
						});
						
						$.ajax( {
							url: "../../models/makan_catering_spv_koor/makan_catering_spv_koor_fn_upload.php",
							type: 'POST',
							dataType: 'json',
							data: fd_makan,
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
								$("#inputfilethimportmakan").val('');
								tblmakan_catering_spv_koor.rows().deselect();
								tblmakan_catering_spv_koor.ajax.reload(function ( json ) {
									notifyprogress.close();
								}, false);
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
