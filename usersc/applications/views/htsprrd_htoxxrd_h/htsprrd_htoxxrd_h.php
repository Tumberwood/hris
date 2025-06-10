<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'htsprrd_htoxxrd_h';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'htsprrd_htoxxrd_d';
?>

<!-- begin content here -->
 
<div class="modal" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated bounceInRight">
			<form class="form-horizontal" id="frmUploadMaster" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title">Upload Excel</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Excel</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="frmUploadItem">
							</div>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/Data Lembur & Makan Periode 18 12 2024 - 20 01 2025.xls');">
								<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					<button class="btn btn-primary" type="submit" id="submitUpload">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmFilter">
                    <div class="form-group row">
						<label class="col-lg-2 col-form-label">Tanggal Awal</label>
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
            </div>
        </div>
    </div>
</div>

<!-- Breakdown -->
<div class="modal fade" id="modalBreakdown" tabindex="-1" role="dialog" aria-labelledby="myModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="myModal1Label">Breakdown Potongan Makan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
		<div class="table-responsive" id="proteksi">
			<h3>Checkclock Makan</h3>
			<table id="ceklok_makan" class="table table-striped table-bordered table-hover nowrap" width="100%">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>NIP</th>
						<th>Nama</th>
						<th>Mesin</th>
						<th>Jam</th>
						<th>keterangan</th>
					</tr>
				</thead>
			</table>
			<br>
			<h3>Potongan Makan Report Presensi</h3>
			<table id="pot_makan" class="table table-striped table-bordered table-hover nowrap" width="100%">
				<thead>
					<tr>
						<th>Tanggal</th>
						<th>NIP</th>
						<th>Nama</th>
						<th>Potongan Makan</th>
					</tr>
				</thead>
			</table>
		</div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsprrd_htoxxrd_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<h5>Detail</h5>
			</div>
			<div class="ibox-content">
				<div class="row">
					<div class="col">
						<div class="ibox collapsed" id="iboxfilter">
							<div class="ibox-title p-xs">
								<h5 class="text-navy">Filter</h5>&nbsp
								<button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
							</div>
							<div class="ibox-content p-xs">
								<div class="p-xs" id="searchPanes1"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
                    <table id="tblhtsprrd_htoxxrd_d" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th rowspan="2">ID</th>
								<th rowspan="2">id_htsprrd_htoxxrd_h</th>
								<th rowspan="2">NIP</th>
								<th rowspan="2">Nama</th>
								<th rowspan="2">Status</th>
								<th class="text-center" colspan="5">HRIS</th>
								<th class="text-center" colspan="5">Excel</th>
								<th rowspan="2">Sesuai</th>
							</tr>
							<tr>
								<th>Lembur 1.5</th>
								<th>Lembur 2</th>
								<th>Lembur 3</th>
								<th>Lembur 4</th>
								<th>Makan</th>
								<th>Lembur 1.5</th>
								<th>Lembur 2</th>
								<th>Lembur 3</th>
								<th>Lembur 4</th>
								<th>Makan</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th colspan="4">Total</th>
								<th id="total_4"></th>
								<th id="total_5"></th>
								<th id="total_6"></th>
								<th id="total_7"></th>
								<th id="total_8"></th>
								<th id="total_9"></th>
								<th id="total_10"></th>
								<th id="total_11"></th>
								<th id="total_12"></th>
								<th id="total_13"></th>
								<th id="total_14"></th>
								<th></th> 
							</tr>
							<tr>
								<th colspan="15">Total Tidak Sesuai</th>
								<th id="tidak_sesuai" class="bg-success"></th> 
								<!-- <th></th> 
								<th></th> 
								<th></th> 
								<th></th> 
								<th></th> 
								<th></th> 
								<th></th> 
								<th></th>  -->
							</tr>
						</tfoot>
                    </table>
				</div>
			</div>
		</div>
	</div>

</div> <!-- end of row -->

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprrd_htoxxrd_h/fn/htsprrd_htoxxrd_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtsprrd_htoxxrd_h, tblhtsprrd_htoxxrd_h, show_inactive_status_htsprrd_htoxxrd_h = 0, id_htsprrd_htoxxrd_h;
        var edthtsprrd_htoxxrd_d, tblhtsprrd_htoxxrd_d, show_inactive_status_htsprrd_htoxxrd_d = 0, id_htsprrd_htoxxrd_d;
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

		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			//start datatables editor
			edthtsprrd_htoxxrd_h = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htsprrd_htoxxrd_h = show_inactive_status_htsprrd_htoxxrd_h;
					}
				},
				table: "#tblhtsprrd_htoxxrd_h",
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
						def: "htsprrd_htoxxrd_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsprrd_htoxxrd_h.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",  
						name: "htsprrd_htoxxrd_h.tanggal_awal",
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
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "htsprrd_htoxxrd_h.tanggal_akhir",
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
						label: "Keterangan",
						name: "htsprrd_htoxxrd_h.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtsprrd_htoxxrd_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprrd_htoxxrd_h.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtsprrd_htoxxrd_h.rows().deselect();
				}
			});

            edthtsprrd_htoxxrd_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthtsprrd_htoxxrd_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					tanggal_awal = edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_awal').val();
					if(!tanggal_awal || tanggal_awal == ''){
						edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_awal').error( 'Wajib diisi!' );
					}
					tanggal_akhir = edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_akhir').val();
					if(!tanggal_akhir || tanggal_akhir == ''){
						edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_akhir').error( 'Wajib diisi!' );
					}
					
					let tglAwal = new Date(tanggal_awal);
					let tglAkhir = new Date(tanggal_akhir);
					
					if (tglAwal > tglAkhir) {
						edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_awal').error('Tanggal awal tidak boleh lebih besar dari tanggal akhir.');
						edthtsprrd_htoxxrd_h.field('htsprrd_htoxxrd_h.tanggal_akhir').error('Tanggal akhir tidak boleh lebih kecil dari tanggal awal.');
					}
				}
				
				if ( edthtsprrd_htoxxrd_h.inError() ) {
					return false;
				}
			});

			edthtsprrd_htoxxrd_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprrd_htoxxrd_h.field('finish_on').val(finish_on);
			});
			
			edthtsprrd_htoxxrd_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtsprrd_htoxxrd_h = $('#tblhtsprrd_htoxxrd_h').DataTable( {
				ajax: {
					url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htsprrd_htoxxrd_h = show_inactive_status_htsprrd_htoxxrd_h;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htsprrd_htoxxrd_h.id",visible:false },
					{ data: "htsprrd_htoxxrd_h.tanggal_awal" },
					{ data: "htsprrd_htoxxrd_h.tanggal_akhir" },
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprrd_htoxxrd_h';
						$table       = 'tblhtsprrd_htoxxrd_h';
						$edt         = 'edthtsprrd_htoxxrd_h';
						$show_status = '_htsprrd_htoxxrd_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					{
						name: 'btnUpload',
						text: '<i class="fa fa-file-excel-o"></i>',
						className: 'btn btn-primary',
						titleAttr: 'Upload Excel',
						action: function ( e, dt, node, config ) {
							$('#modalUpload').modal('toggle');
						}
					}
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsprrd_htoxxrd_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			tblhtsprrd_htoxxrd_h.button('btnUpload:name').disable();
			
			tblhtsprrd_htoxxrd_h.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhtsprrd_htoxxrd_d];
				CekInitHeaderHD(tblhtsprrd_htoxxrd_h, tbl_details);
			} );
			
			tblhtsprrd_htoxxrd_h.on( 'select', function( e, dt, type, indexes ) {
				data_htsprrd_htoxxrd_h = tblhtsprrd_htoxxrd_h.row( { selected: true } ).data().htsprrd_htoxxrd_h;
				id_htsprrd_htoxxrd_h  = data_htsprrd_htoxxrd_h.id;
				id_transaksi_h   = id_htsprrd_htoxxrd_h; // dipakai untuk general
				is_approve       = data_htsprrd_htoxxrd_h.is_approve;
				is_nextprocess   = data_htsprrd_htoxxrd_h.is_nextprocess;
				is_jurnal        = data_htsprrd_htoxxrd_h.is_jurnal;
				is_active        = data_htsprrd_htoxxrd_h.is_active;
				
				// atur hak akses
				tbl_details = [tblhtsprrd_htoxxrd_d];
				CekSelectHeaderHD(tblhtsprrd_htoxxrd_h, tbl_details);
				cekDetail();
				
				if (c_id > 0) {
					tblhtsprrd_htoxxrd_h.button('btnUpload:name').disable();
				} else {
					tblhtsprrd_htoxxrd_h.button('btnUpload:name').enable();
				}
			} );
			
			tblhtsprrd_htoxxrd_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htsprrd_htoxxrd_h = '';

				// atur hak akses
				tbl_details = [tblhtsprrd_htoxxrd_d];
				CekDeselectHeaderHD(tblhtsprrd_htoxxrd_h, tbl_details);
				tblhtsprrd_htoxxrd_h.button('btnUpload:name').disable();
				tblhtsprrd_htoxxrd_d.button('btnBreakdown:name').disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables
			tblhtsprrd_htoxxrd_d = $('#tblhtsprrd_htoxxrd_d').DataTable( {
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
					url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_d.php",
					type: 'POST',
					data: function (d){
						d.id_htsprrd_htoxxrd_h = id_htsprrd_htoxxrd_h;
					},
					dataSrc: 'data.lembur'
				},
				fixedColumns: {
                    leftColumns: 2 // Freeze column 0 and 1
                },
				responsive: false,
				order: [[ 2, "asc" ]],
				columns: [
					{ data: "id",visible:false },
					{ data: "id_htsprrd_htoxxrd_h",visible:false },
					{ 
					data: "kode",
						render: function(data, type, row) {
							return '<a target="_blank" href="../htsprrd_overtime_jam/htsprrd_overtime_jam.php?id_hemxxmh=' + row.id_hemxxmh + 
								'&start_date=' + row.start_date + 
								'&end_date=' + row.end_date + '">' + data + '</a>';
						}
					},
					{ 
					data: "nama",
						render: function(data, type, row) {
							return '<a target="_blank" href="../htsprrd_overtime_jam/htsprrd_overtime_jam.php?id_hemxxmh=' + row.id_hemxxmh + 
								'&start_date=' + row.start_date + 
								'&end_date=' + row.end_date + '">' + data + '</a>';
						}
					},

					{ data: "status" },
					{ data: "lembur15_db" },
					{ data: "lembur2_db" },
					{ data: "lembur3_db" },
					{ data: "lembur4_db" },
					{ 
						data: "makan_db",
						render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
						class: "text-right"
					},
					{ data: "lembur15_xl" },
					{ data: "lembur2_xl" },
					{ data: "lembur3_xl" },
					{ data: "lembur4_xl" },
					{ data: "makan_xl" },
					{
						data: "is_tidak_sesuai",
						render: function (data, type, row) {
							if (data == 0	) {
								return `<span class="badge bg-primary">Ya</span>`;
							} else {
								return `<span class="badge bg-danger">Tidak</span>`;
							}
						}
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprrd_htoxxrd_d';
						$table       = 'tblhtsprrd_htoxxrd_d';
						$edt         = 'edthtsprrd_htoxxrd_d';
						$show_status = '_htsprrd_htoxxrd_d';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-list"></i> Pot Makan',
						name: 'btnBreakdown',
						className: 'btn btn-outline',
						titleAttr: 'Breakdown Pot Makan',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							$('#modalBreakdown').modal('show');
						}
					}
				],
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				},
				rowCallback: function(row, data) {
					if (data.is_tidak_sesuai == 1) {
						$(row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 

					for (var i = 5; i <= 14; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#total_' + columnIndex).html(numFormat(sum_all));
					}
					var tidak_sesuai = api.column(15).data().sum();
					$('#tidak_sesuai').html(numFormat(tidak_sesuai));
				},
				columnDefs: [
					{ targets: [5, 6, 7, 8, 9,10,11,12,13,14], className: "text-right" },
					{
						searchPanes:{
							show: true,
						},
						targets: [4,15]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				]
			} );
			
			tblhtsprrd_htoxxrd_d.searchPanes.container().appendTo( '#searchPanes1' );
			tblhtsprrd_htoxxrd_d.button('btnBreakdown:name').disable();
			tblhtsprrd_htoxxrd_d.on( 'select', function( e, dt, type, indexes ) {
				data_htsprrd_htoxxrd_d = tblhtsprrd_htoxxrd_d.row( { selected: true } ).data();
				id_hemxxmh       = data_htsprrd_htoxxrd_d.id_hemxxmh;
				start_date       = data_htsprrd_htoxxrd_d.start_date;
				end_date       = data_htsprrd_htoxxrd_d.end_date;
				kode       = data_htsprrd_htoxxrd_d.kode;
				kode_finger = kode.slice(-4);
				tblhtsprrd_htoxxrd_d.button('btnBreakdown:name').enable();
				breakdownMakan(id_hemxxmh, kode_finger, start_date, end_date);
			} );
			
			tblhtsprrd_htoxxrd_d.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hemxxmh = 0;
				start_date = '';
				end_date = '';
				kode_finger = '';
				tblhtsprrd_htoxxrd_d.button('btnBreakdown:name').disable();
			} );

// --------- end _detail --------------- //		
			
			$("#frmFilter").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmFilter) {
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

					tblhtsprrd_htoxxrd_h.rows().deselect();
					tblhtsprrd_htoxxrd_d.rows().deselect();
					tblhtsprrd_htoxxrd_h.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});

			
			var frmUploadMaster = $("#frmUploadMaster").submit(function(e) {
				e.preventDefault();
				// $('#submit_ceklok').hide();
			}).validate({
				rules: {
					filename: "required"
				},
				messages: {
					filename: "Pilih file yang akan di-upload!"
				},
				submitHandler: function(form) { 
					$('#submitUpload').hide();
					let notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0,
						element: 'body',
					});

					//item
					var fd_item = new FormData();
					var item = $('#frmUploadItem')[0].files[0];
					if (item != undefined) {
						fd_item.append('filename',item);
						fd_item.append('id_htsprrd_htoxxrd_h',id_htsprrd_htoxxrd_h);
			
						$.ajax( {
							url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_upload.php",
							type: 'POST',
							dataType: 'json',
							data: fd_item,
							contentType: false,
							processData: false,
							success: function ( json ) {
								notifyprogress.close();

								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message
								});

								$("#frmUploadItem").val('');
								tblhtsprrd_htoxxrd_h.ajax.reload(null,false);
								tblhtsprrd_htoxxrd_d.ajax.reload(null,false);
								$('#modalUpload').modal('toggle'); 
								$('#submitUpload').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
