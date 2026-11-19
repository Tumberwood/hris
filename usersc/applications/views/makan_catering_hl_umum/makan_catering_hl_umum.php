<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'makan_catering_hl_umum';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmmakan_catering_hl_umum">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periode</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
                                <span class="input-group-addon">to</span>
                                <input type="text" id="end_date" class="form-control">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">												
                        <label class="col-sm-2 col-form-label">Periode Payroll</label>
                        <div class="col-sm-5">
                            <select class="form-control" id="select_periode_payroll" name="select_periode_payroll"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary" type="submit" id="go">Submit</button>
                        </div>
                    </div>
                </form>
                <div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-title">
                <h5 class="text-navy">Upload Makan Catering HL Umum</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
			<div class="ibox-content">
				<h3 class="text-info">Format file harus dalam .xlsx dan disesuaikan dengan format template</h3>
				<hr>
				<div>
					<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_makan_catering_hl_umum.xlsx');">
						<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
					</button>
				</div>
				<form id="frmUploadthimportmakan" enctype="multipart/form-data">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Makan Catering HL Umum</label>
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
                    <table id="tblmakan_catering_hl_umum" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/makan_catering_hl_umum/fn/makan_catering_hl_umum_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmakan_catering_hl_umum, tblmakan_catering_hl_umum, show_inactive_status_makan_catering_hl_umum = 0, id_makan_catering_hl_umum;
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
		
		$('#start_date').datepicker('setDate', awal_bulan_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
        // END datepicker init
		
		//Select2 init
        $("#select_periode_payroll").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/periode_payroll/periode_payroll_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_periode_payroll_old: 0,
						search: params.term || '',
						page: params.page || 1
					}
						return query;
				},
				processResults: function (data, params) {
					return {
						results: data.results,
						pagination: {
							more: true
						}
					};
				},
				cache: true,
				minimumInputLength: 1,
				maximum: 10,
				delay: 500,
				maximumSelectionLength: 5,
				minimumResultsForSearch: -1,
			}
			
		});
        // END select2 init

		// Override tanggal ketika periode payroll dipilih
		$('#select_periode_payroll').on('select2:select', async function (e) {
			const val = $(this).val();

			await autofillField(
				'periode_payroll',
				val,
				'DATE_FORMAT(tanggal_awal, "%d %b %Y") AS tanggal_awal, DATE_FORMAT(tanggal_akhir, "%d %b %Y") AS tanggal_akhir'
			);

			const tanggal_awal = autofillData.tanggal_awal;
			const tanggal_akhir = autofillData.tanggal_akhir;

			$('#start_date').datepicker('setDate', tanggal_awal);
			$('#end_date').datepicker('setDate', tanggal_akhir);

		});
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			id_periode_payroll_old = 0;
			
			$('#select_periode_payroll').select2('open');

			setTimeout(function() {
				$('#select_periode_payroll').select2('close');
			}, 5);

			edtmakan_catering_hl_umum = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/makan_catering_hl_umum/makan_catering_hl_umum.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_hl_umum = show_inactive_status_makan_catering_hl_umum;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				table: "#tblmakan_catering_hl_umum",
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
						def: "makan_catering_hl_umum",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_catering_hl_umum.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Tanggal <sup class='text-danger'>*<sup>", 
						name: "makan_catering_hl_umum.tanggal",
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
						name: "makan_catering_hl_umum.shift",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "1", "value": "1" },
							{ "label": "2", "value": "2" },
							{ "label": "3", "value": "3" },
						]
					},	
					{
						label: "Jumlah <sup class='text-danger'>*<sup>",
						name: "makan_catering_hl_umum.jumlah"
					}, 	
					{
						label: "Keterangan",
						name: "makan_catering_hl_umum.keterangan",
						type: "textarea"
					},
				]
			} );

			edtmakan_catering_hl_umum.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_hl_umum.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_catering_hl_umum.rows().deselect();
				}
			});

			edtmakan_catering_hl_umum.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtmakan_catering_hl_umum.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					tanggal = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.tanggal').val();
					if(!tanggal || tanggal == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.tanggal').error( 'Wajib diisi!' );
					}

					shift = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.shift').val();
					if(!shift || shift == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.shift').error( 'Wajib diisi!' );
					}

					// BEGIN of validasi makan_catering_hl_umum.jumlah 
					jumlah = edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').val();
					if(!jumlah || jumlah == ''){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(jumlah <= 0 ){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah) ){
						edtmakan_catering_hl_umum.field('makan_catering_hl_umum.jumlah').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi makan_catering_hl_umum.jumlah 

					
				}
				
				if ( edtmakan_catering_hl_umum.inError() ) {
					return false;
				}
			});
			
			edtmakan_catering_hl_umum.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_catering_hl_umum.field('finish_on').val(finish_on);
			});

			//start datatables
			tblmakan_catering_hl_umum = $('#tblmakan_catering_hl_umum').DataTable( {
				ajax: {
					url: "../../models/makan_catering_hl_umum/makan_catering_hl_umum.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_makan_catering_hl_umum = show_inactive_status_makan_catering_hl_umum;
						d.start_date = start_date;
						d.end_date = end_date;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_catering_hl_umum.id",visible:false },
					{ data: "makan_catering_hl_umum.tanggal" },
					{ data: "makan_catering_hl_umum.shift" },
					{ data: "makan_catering_hl_umum.jumlah" },
					{ data: "makan_catering_hl_umum.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_catering_hl_umum';
						$table       = 'tblmakan_catering_hl_umum';
						$edt         = 'edtmakan_catering_hl_umum';
						$show_status = '_makan_catering_hl_umum';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_catering_hl_umum.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblmakan_catering_hl_umum.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblmakan_catering_hl_umum);
			} );
			
			tblmakan_catering_hl_umum.on( 'select', function( e, dt, type, indexes ) {
				makan_catering_hl_umum_data    = tblmakan_catering_hl_umum.row( { selected: true } ).data().makan_catering_hl_umum;
				id_makan_catering_hl_umum      = makan_catering_hl_umum_data.id;
				id_transaksi_h = id_makan_catering_hl_umum; // dipakai untuk general
				is_approve     = makan_catering_hl_umum_data.is_approve;
				is_nextprocess = makan_catering_hl_umum_data.is_nextprocess;
				is_jurnal      = makan_catering_hl_umum_data.is_jurnal;
				is_active      = makan_catering_hl_umum_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblmakan_catering_hl_umum);
			} );

			tblmakan_catering_hl_umum.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_catering_hl_umum = '';

				// atur hak akses
				CekDeselectHeaderH(tblmakan_catering_hl_umum);
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
							url: "../../models/makan_catering_hl_umum/makan_catering_hl_umum_fn_upload.php",
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
								tblmakan_catering_hl_umum.rows().deselect();
								tblmakan_catering_hl_umum.ajax.reload(function ( json ) {
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
			
			$("#frmmakan_catering_hl_umum").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmmakan_catering_hl_umum) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');

					notifyLoadingDomba();

					tblmakan_catering_hl_umum.rows().deselect();
					tblmakan_catering_hl_umum.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
