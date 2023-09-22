<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsprtd';
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
                <form class="form-horizontal" id="frmhtsprtd">
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
                        <label class="col-sm-2 col-form-label">Employee</label>
                        <div class="col-sm-4">
                            <select class="form-control" id="select_hemxxmh" name="select_hemxxmh"></select>
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
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsprtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>ID Checkclock</th>
                                <th>Nama</th>
                                <th>Mesin</th>
                                <th>Jam</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprtd/fn/htsprtd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsprtd, tblhtsprtd, show_inactive_status_htsprtd = 0, id_htsprtd;
		// ------------- end of default variable

		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var id_hemxxmh_old_select = 0;
		//UPDATE BY FERRY , BUG FILTER 14 SEP 2023
		var select_hemxxmh = 0;
		var kode_finger;

		id_heyxxmh = "<?php echo $_SESSION['str_arr_ha_heyxxmh']; ?>";
		console.log(id_heyxxmh);

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
        // END datepicker init

        // BEGIN select2 init
		
		//UPDATE BY FERRY , BUG FILTER 14 SEP 2023
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_hemxxmh_old: id_hemxxmh_old_select,
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
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			edthtsprtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsprtd/htsprtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprtd = show_inactive_status_htsprtd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.select_hemxxmh = select_hemxxmh;
					}
				},
				table: "#tblhtsprtd",
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
						def: "htsprtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsprtd.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "htsprtd.kode",
						name: "htsprtd.kode",
                        type: "hidden"
					},
					{
						label: "Mesin <sup class='text-danger'>*<sup>",
						name: "htsprtd.nama",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Istirahat", "value": "istirahat" },
							{ "label": "Makan", "value": "makan" },
							{ "label": "Makan Manual", "value": "makan manual" },
							{ "label": "Outsourcing", "value": "os" },
							{ "label": "PMI", "value": "pmi" },
							{ "label": "Staff", "value": "staff" }
						]
					},
					{
						label: "Employee <sup class='text-danger'>*<sup>",
						name: "htsprtd.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
										id_heyxxmh: id_heyxxmh,
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
							},
						}
					},
					{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htsprtd.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",
						name: "htsprtd.jam",
						type: "datetime",
						format: 'HH:mm'
					},
					{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htsprtd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtsprtd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtsprtd.rows().deselect();
				}
			});

			edthtsprtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtsprtd.dependent( 'htsprtd.nama', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
				}
				return {}
			}, {event: 'keyup change'});

			edthtsprtd.dependent( 'htsprtd.id_hemxxmh', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
					edthtsprtd.field('htsprtd.jam').val('');
				}
				return {}
			}, {event: 'keyup change'});

			edthtsprtd.dependent( 'htsprtd.tanggal', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
					edthtsprtd.field('htsprtd.jam').val('');
				}
				return {}
			}, {event: 'keyup change'});

            edthtsprtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsprtd.nama
					nama = edthtsprtd.field('htsprtd.nama').val();
					if(!nama || nama == ''){
						edthtsprtd.field('htsprtd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi htsprtd.nama

					// BEGIN of validasi htsprtd.id_hemxxmh
					id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
					if ( ! edthtsprtd.field('htsprtd.id_hemxxmh').isMultiValue() ) {
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtsprtd.field('htsprtd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.id_hemxxmh

					// BEGIN of validasi htsprtd.tanggal
					if ( ! edthtsprtd.field('htsprtd.tanggal').isMultiValue() ) {
						tanggal = edthtsprtd.field('htsprtd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtsprtd.field('htsprtd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.tanggal

					jam = edthtsprtd.field('htsprtd.jam').val();
					unikMakan(jam);
					if (nama != "makan manual") {
						// BEGIN of validasi htsprtd.jam
						if ( ! edthtsprtd.field('htsprtd.jam').isMultiValue() ) {
							if(!jam || jam == ''){
								edthtsprtd.field('htsprtd.jam').error( 'Wajib diisi!' );
							}
						}
						// END of validasi htsprtd.jam
					} else {
						if (jam == '' || jam == null) {
							edthtsprtd.field('htsprtd.jam').error( 'Jam Kosong Karena Jadwal Belum Dibuat!' );
						}
					}

					// BEGIN of validasi htsprtd.keterangan
					if ( ! edthtsprtd.field('htsprtd.keterangan').isMultiValue() ) {
						keterangan = edthtsprtd.field('htsprtd.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtsprtd.field('htsprtd.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.keterangan
				}
				
				if ( edthtsprtd.inError() ) {
					return false;
				}
			});
			
			edthtsprtd.on('initSubmit', function(e, action) {
				// update kode finger
				id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
				htsprtd_get_hemxxmh_kode();
				console.log(kode_finger);
				edthtsprtd.field('htsprtd.kode').val(kode_finger);

				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('finish_on').val(finish_on);
			});

			edthtsprtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				
				tblhtsprtd.ajax.reload(null, false);
			});
			
			//start datatables
			tblhtsprtd = $('#tblhtsprtd').DataTable( {
				searchPanes:{
					layout: 'columns-4',
				},
				dom:
					"<'row'<'col-lg-4 col-md-4 col-sm-12 col-xs-12'l><'col-lg-8 col-md-8 col-sm-12 col-xs-12'f>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'B>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
					"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true,
						},
						targets: [1,2,3,4]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,5,6]
					}
				],
				ajax: {
					url: "../../models/htsprtd/htsprtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprtd = show_inactive_status_htsprtd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.select_hemxxmh = select_hemxxmh;
					}
				},
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "htsprtd.id",visible:false },
					{ data: "htsprtd.tanggal" },
					{ data: "htsprtd.kode" },
					{ data: "hemxxmh_data" },
					{ data: "htsprtd.nama" },
					{ data: "htsprtd.jam" },
					{ data: "htsprtd.keterangan" }
				],
				buttons: [	
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprtd';
						$table       = 'tblhtsprtd';
						$edt         = 'edthtsprtd';
						$show_status = '_htsprtd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'remove'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsprtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtsprtd.searchPanes.container().appendTo( '#searchPanes1' );

			$("#frmhtsprtd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtsprtd) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					
					//UPDATE BY FERRY , BUG FILTER 14 SEP 2023
					select_hemxxmh 	= $('#select_hemxxmh').val();
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhtsprtd.ajax.reload(function ( json ) {
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
