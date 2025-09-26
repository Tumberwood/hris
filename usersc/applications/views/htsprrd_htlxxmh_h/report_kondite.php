<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htsprrd';
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
                <form class="form-horizontal" id="frmhtsprrd">
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
				<div class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					Apabila data presensi sudah final pada satu tanggal, mohon lakukan approval untuk mengunci data yang ada. Pastikan hanya memilih satu tanggal saja.
				</div>
				<div class="table-responsive">
                    <table id="tblhtsprrd" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>NIP</th>
								<th>Nama</th>
								<th>Sub Tipe</th>
								<th>Status</th>
								<th>AL</th>
								<th>S3</th>
								<th>IT</th>
								<th>LB</th>
								<th >Total</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Total</th>
								<th id="total_1"></th>
								<th id="total_2"></th>
								<th id="total_3"></th>
								<th id="total_4"></th>
								<th id="total_5"></th>
								<th id="total_6"></th>
								<th id="total_7"></th>
								<th id="total_8"></th>
							</tr>
						</tfoot>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprrd_overtime/fn/htsprrd_fn.php'; ?>
<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tblhtsprrd, show_inactive_status_htsprrd = 0;
		// ------------- end of default variable

		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;

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
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
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
			}
			
		});
        // END select2 init
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			//start datatables
			tblhtsprrd = $('#tblhtsprrd').DataTable( {
				searchPanes:{
					layout: 'columns-2',
				},
				dom: 
					"<P>"+
					"<lf>"+
					"<B>"+
					"<rt>"+
					"<'row'<'col-sm-4'i><'col-sm-8'p>>",
				ajax: {
					url: "../../models/report_kondite/report_kondite.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
					},
					dataSrc: 'data.lembur'
				},
				fixedColumns: {
                    leftColumns: 2 // Freeze column 0 and 1
                },
				responsive: false,
				order: [[ 2, "asc" ]],
				columns: [
					{ data: "kode" },
					{ data: "nama" },
					{ data: "sub_tipe" },
					{ data: "status" },
					{ data: "al" },
					{ data: "s3" },
					{ data: "it" },
					{ data: "lb" },
					{ data: "total_kondite" },
				],
				buttons: [	
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprrd';
						$table       = 'tblhtsprrd';
						$edt         = 'edthtsprrd';
						$show_status = '_htsprrd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 

					for (var i = 4; i <= 8; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#total_' + columnIndex).html(numFormat(sum_all));
					}
					var tidak_sesuai = api.column(8).data().sum();
					$('#tidak_sesuai').html(numFormat(tidak_sesuai));
				},
				columnDefs: [
					{ targets: [5, 6, 7, 8, 8], className: "text-right" },
					{
						searchPanes:{
							show: true,
						},
						targets: [2,3]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				],
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );

			tblhtsprrd.searchPanes.container().appendTo( '#searchPanes1' );

			tblhtsprrd.on( 'select', function( e, dt, type, indexes ) {
				htsprrd_data    = tblhtsprrd.row( { selected: true } ).data().htsprrd;
				id_htsprrd      = htsprrd_data.id;
				status_presensi_in      = htsprrd_data.status_presensi_in;
				status_presensi_out      = htsprrd_data.status_presensi_out;
				st_clock_in      = htsprrd_data.st_clock_in;
				st_clock_out      = htsprrd_data.st_clock_out;
				id_hemxxmh_select      = htsprrd_data.id_hemxxmh;
				htlxxrh_kode      = htsprrd_data.htlxxrh_kode;
				tanggal      = htsprrd_data.tanggal;
				cek      = htsprrd_data.cek;
				htlxxrh_kode      = htsprrd_data.htlxxrh_kode;
				
			} );
			
			tblhtsprrd.on( 'deselect', function () {
				tblhtsprrd.button('btncekNol:name').disable();
				tblhtsprrd.button('btnPresensiOK:name').disable();
			} );
				
			$("#frmhtsprrd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtsprrd) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					id_hemxxmh = $('#select_hemxxmh').val();
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 8888,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblhtsprrd.ajax.reload(function ( json ) {
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
