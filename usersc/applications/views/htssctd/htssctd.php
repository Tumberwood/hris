<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htssctd';
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
                <form class="form-horizontal" id="frmhtssctd">
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
						<label class="col-lg-2 col-md-2">Pegawai</label>
						<div class="col-lg-4 col-sm-4">
							<select class="form-control" id="selectPegawai" name="selectPegawai" multiple="multiple"></select>
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
                    <table id="tblhtssctd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Hari</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Department</th>
                                <th>Shift</th>
                                <th>Jam Awal</th>
                                <th>Jam Akhir</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htssctd/fn/htssctd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtssctd, tblhtssctd, show_inactive_status_htssctd = 0, id_htssctd;
		// ------------- end of default variable

		var id_htsxxmh_old = 0, id_hemxxmh_old = 0, id_hemxxmh_filter= 0;

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
		
		$("#selectPegawai").select2({
			placeholder : "Select",
			allowClear: true,
			multiple: false,
			ajax: {
				url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_hemxxmh_old: id_hemxxmh_filter,
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
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			//start datatables editor
			edthtssctd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htssctd/htssctd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htssctd = show_inactive_status_htssctd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh_filter = id_hemxxmh_filter;
					}
				},
				table: "#tblhtssctd",
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
						def: "htssctd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htssctd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htssctd.id_hemxxmh",
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
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Shift <sup class='text-danger'>*<sup>",
						name: "htssctd.id_htsxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsxxmh/htsxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsxxmh_old: id_htsxxmh_old,
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
					},	{
						label: "Keterangan",
						name: "htssctd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtssctd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtssctd.rows().deselect();
				}
			});

			edthtssctd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtssctd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi htssctd.id_hemxxmh
					if ( ! edthtssctd.field('htssctd.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edthtssctd.field('htssctd.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtssctd.field('htssctd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.id_hemxxmh

					// BEGIN of validasi htssctd.tanggal
					if ( ! edthtssctd.field('htssctd.tanggal').isMultiValue() ) {
						tanggal = edthtssctd.field('htssctd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtssctd.field('htssctd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggal

					// BEGIN of validasi htssctd.id_htsxxmh
					if ( ! edthtssctd.field('htssctd.id_htsxxmh').isMultiValue() ) {
						id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
						if(!id_htsxxmh || id_htsxxmh == ''){
							edthtssctd.field('htssctd.id_htsxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.id_htsxxmh
						
					// BEGIN of cek unik 
					if(action == 'create'){
						id_htssctd = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name       : 'htssctd',
							nama_field       : 'id_hemxxmh, tanggal, id_htsxxmh',
							nama_field_value : id_hemxxmh + ',"' + moment(tanggal).format('YYYY-MM-DD') + '",' + id_htsxxmh,
							id_transaksi     : id_htssctd
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthtssctd.field('htssctd.id_hemxxmh').error( 'Data tidak boleh kembar!' );
								edthtssctd.field('htssctd.tanggal').error( 'Data tidak boleh kembar!' );
								edthtssctd.field('htssctd.id_htsxxmh').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik 
					
				}
				
				if ( edthtssctd.inError() ) {
					return false;
				}
			});
			
			edthtssctd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtssctd = $('#tblhtssctd').DataTable( {
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
						targets: [2,3,4,5]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,1,,6,7,8,9]
					}
				],
				ajax: {
					url: "../../models/htssctd/htssctd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htssctd = show_inactive_status_htssctd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh_filter = id_hemxxmh_filter;
					}
				},
				order: [[ 2, "desc" ],[3, "asc"]],
				columns: [
					{ data: "htssctd.id",visible:false },
					{ 
						data: null,
						render: function (data, type, row) {
							if(row.htssctd.tanggal){
								hari = moment(row.htssctd.tanggal, 'DD-MMM-YYYY').locale("id").format("dddd")
								return hari;
							}
					   	}
						
					},
					{ data: "htssctd.tanggal" },
					{ data: "hemxxmh_data" },
					{ data: "hetxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "htsxxmh.kode" },
					{ data: "htsxxmh.jam_awal" },
					{ data: "htsxxmh.jam_akhir" },
					{ data: "htssctd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htssctd';
						$table       = 'tblhtssctd';
						$edt         = 'edthtssctd';
						$show_status = '_htssctd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htssctd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblhtssctd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtssctd);
			} );
			
			tblhtssctd.on( 'select', function( e, dt, type, indexes ) {
				htssctd_data    = tblhtssctd.row( { selected: true } ).data().htssctd;
				id_htssctd      = htssctd_data.id;
				id_transaksi_h = id_htssctd; // dipakai untuk general
				is_approve     = htssctd_data.is_approve;
				is_nextprocess = htssctd_data.is_nextprocess;
				is_jurnal      = htssctd_data.is_jurnal;
				is_active      = htssctd_data.is_active;

				id_hemxxmh_old = htssctd_data.id_hemxxmh;
				id_htsxxmh_old = htssctd_data.id_htsxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhtssctd);
			} );

			tblhtssctd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htssctd = 0;
				id_hemxxmh_old = 0;
				id_htsxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtssctd);
			} );

			tblhtssctd.searchPanes.container().appendTo( '#searchPanes1' );

			$("#frmhtssctd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtssctd) {
					
					if( $('#selectPegawai').val() > 0){
						id_hemxxmh_filter  = $('#selectPegawai').val();
					}else{
						id_hemxxmh_filter = '';
					}

					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhtssctd.ajax.reload(function ( json ) {
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
