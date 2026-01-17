<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'service_request';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblservice_request" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Requester</th>
                                <th>Tgl Request</th>
                                <th>Pekerjaan</th>
                                <th>Keterangan</th>
                                <th>Teknisi</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/service_request/fn/service_request_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtservice_request, tblservice_request, show_inactive_status_service_request = 0, id_service_request;
		// ------------- end of default variable
		var id_pekerjaan_m_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtservice_request = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/service_request/ambil_request.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_service_request = show_inactive_status_service_request;
					}
				},
				table: "#tblservice_request",
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
						def: "service_request",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "service_request.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Request",
						name: "service_request.tglrequest",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Pekerjaan <sup class='text-danger'>*<sup>",
						name: "service_request.id_pekerjaan_m",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/pekerjaan_m/pekerjaan_m_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_pekerjaan_m_old: id_pekerjaan_m_old,
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
								minimumResultsForSearch: -1
							}
						}
					},
					{
						label: "Keterangan",
						name: "service_request.keterangan",
						type: "textarea"
					}
				]
			} );

			edtservice_request.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtservice_request.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblservice_request.rows().deselect();
				}
			});

			edtservice_request.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtservice_request.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					tglrequest = edtservice_request.field('service_request.tglrequest').val();
					if(!tglrequest || tglrequest == ''){
						edtservice_request.field('service_request.tglrequest').error( 'Wajib diisi!' );
					}
					
					id_pekerjaan_m = edtservice_request.field('service_request.id_pekerjaan_m').val();
					if(!id_pekerjaan_m || id_pekerjaan_m == ''){
						edtservice_request.field('service_request.id_pekerjaan_m').error( 'Wajib diisi!' );
					}
				}
				
				if ( edtservice_request.inError() ) {
					return false;
				}
			});
			
			edtservice_request.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtservice_request.field('finish_on').val(finish_on);
			});

			//start datatables
			tblservice_request = $('#tblservice_request').DataTable( {
				ajax: {
					url: "../../models/service_request/ambil_request.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_service_request = show_inactive_status_service_request;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "service_request.id",visible:false },
					{ data: "pembuat" },
					{ data: "service_request.tglrequest" },
					{ data: "pekerjaan_m.nama" },
					{ data: "service_request.keterangan" },
					{ data: "teknisi" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_service_request';
						$table       = 'tblservice_request';
						$edt         = 'edtservice_request';
						$show_status = '_service_request';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: 'Ambil Request',
						name: 'btnAmbil',
						className: 'btn btn-outline',
						titleAttr: 'klik ambil request untuk mengerjakan',
						action: function (e, dt, node, config) {
							e.preventDefault();

							if (confirm('Yakin ingin Ambil Request?')) {
								$.ajax({
									url: "../../models/service_request/ambil_request_update_teknisi.php",
									type: "POST",
									dataType: "json",
									data: {
										id_service_request: id_service_request 
									},
									success: function (json) {
										if (json.data.type_message == 'success') {
											$.notify({
												message: json.data.message
											}, {
												type: json.data.type_message,
											});
											
											tblservice_request.ajax.reload(); 
										} else {
											alert('Gagal approve: ' + json.message);
										}
									},
									error: function (xhr, status, error) {
										console.error('Error AJAX:', error);
										alert('Terjadi kesalahan saat Ambil Request.');
									}
								});
							}
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.service_request.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblservice_request.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblservice_request);
				tblservice_request.button('btnAmbil:name').disable();
			} );
			
			tblservice_request.on( 'select', function( e, dt, type, indexes ) {
				service_request_data    = tblservice_request.row( { selected: true } ).data().service_request;
				id_service_request      = service_request_data.id;
				id_transaksi_h = id_service_request; // dipakai untuk general
				is_approve     = service_request_data.is_approve;
				is_nextprocess = service_request_data.is_nextprocess;
				is_jurnal      = service_request_data.is_jurnal;
				is_active      = service_request_data.is_active;
				id_pekerjaan_m_old      = service_request_data.id_pekerjaan_m;

				// atur hak akses
				CekSelectHeaderH(tblservice_request);
				tblservice_request.button('btnAmbil:name').enable();
			} );

			tblservice_request.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_service_request = '';
				id_pekerjaan_m_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblservice_request);
				tblservice_request.button('btnAmbil:name').disable();
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
