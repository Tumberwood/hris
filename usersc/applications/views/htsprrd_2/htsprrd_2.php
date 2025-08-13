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
	
	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= ($_GET['id_hemxxmh']);
	} else {
		$id_hemxxmh = 0;
	}
	
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
	
	if (isset($_GET['end_date'])){
		$akhir		= ($_GET['end_date']);
	} else {
		$akhir = null;
	}
?>

<!-- begin content here -->

<div class="row" id="filterPresensi">
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
						<label class="col-sm-2 col-form-label">Sub Type</label>
                        <div class="col-sm-4">
							<select class="form-control" id="select_heyxxmd" name="select_heyxxmd"></select>
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

<div class="modal fade" id="modalQ">
	<div class="modal-dialog">
		<div class="modal-content animated bounceInRight">
			<div class="modal-header">
				<h4 class="modal-title">Advanced Filter</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<!-- pane8 -->
			<div class="modal-body">
				<div id="sb"></div> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
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
				<div class="tabs-container">
					<ul class="nav nav-tabs" role="tablist">
						<li><a class="nav-link active" data-toggle="tab" href="#tabhtsprrd"> Detail</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" id="tabhtsprrd" class="tab-pane active">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblhtsprrd" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th rowspan=2>ID</th>
												<th rowspan=2>Nama</th>
												<th rowspan=2>Link</th>
												<th rowspan=2>Department</th>
												<th rowspan=2>Jabatan</th>
												<th rowspan=2>Area Kerja</th>
												<th rowspan=2>Tanggal</th>
												<th rowspan=2>Cek</th>
												<th rowspan=2>Shift In</th>
												<th rowspan=2>Shift Out</th>
												<th rowspan=2>Jadwal</th>
												<th rowspan=2>Clock In</th>
												<th rowspan=2>Clock Out</th>
												<th rowspan=2>Break In</th>
												<th rowspan=2>Break Out</th>
												<th rowspan=2>Cek CI</th>
												<th rowspan=2>Cek CO</th>
												
												<th rowspan=2>Status In</th>
												<th rowspan=2>Status Out</th>

												<th rowspan=2>Keterangan</th>
												<!-- //pot jam dihapus (16) --> 
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>
												<th rowspan=2>Pot Upah</th>
												<th rowspan=2>Pot Premi</th>

											</tr>
											<tr>
												<th>Awal</th>
												<th>Akhir</th>
												<th>Awal</th>
												<th>Akhir</th>
												<th>Awal</th>
												<th>Akhir</th>

												<th data-toggle="tooltip" data-placement="top" title="Lembur Hari Libur">LB</th>
												<th data-toggle="tooltip" data-placement="top" title="Lembur Awal">AW</th>
												<th data-toggle="tooltip" data-placement="top" title="Lembur Akhir">AK</th>
												<th data-toggle="tooltip" data-placement="top" title="Lembur Istirahat Pagi">I1</th>
												<th data-toggle="tooltip" data-placement="top" title="Lembur Istirahat Sore">I2</th>
												<th data-toggle="tooltip" data-placement="top" title="Lembur Istirahat Malam">I3</th>
												<th data-toggle="tooltip" data-placement="top" title="Total Lembur">Total</th>

												<th class="text-danger">Pot TI</th>
												<th class="text-danger">Pot Overtime</th>
												<th>Overtime Final</th>
												<th class="text-danger">Pot Jam Kerja</th>
												<th class="text-danger">Pot Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th>Total</th>
												<th id="all_makan"></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												
												<th id="all_lb"></th>
												<th id="all_aw"></th>
												<th id="all_ak"></th>
												<th id="all_i1"></th>
												<th id="all_i2"></th>
												<th id="all_i3"></th>
												<th id="all_tl"></th>
												
												<th id="all_pot_ti"></th>
												<th id="all_pot_overtime"></th>
												<th id="all_overtime"></th>
												<th id="all_pot_hk"></th>
												<th id="all_pot_jam"></th>
												<!-- <th id="s_hk"></th> -->

											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprrd/fn/htsprrd_fn.php'; ?>
<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tblhtsprrd, show_inactive_status_htsprrd = 0;
		// ------------- end of default variable

		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var id_heyxxmd = 0;
		var id_heyxxmd_old = 0;
		var user_id = <?php echo $_SESSION['user'] ?>;
		var str_arr_ha_heyxxmh = <?php echo "'" . $_SESSION['str_arr_ha_heyxxmh'] . "'" ?>;
		
		var tanggal_awal = "<?php echo $awal ?>";
		var tanggal_akhir = "<?php echo $akhir ?>";

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});

		if (tanggal_awal === '') {
			$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_awal));
		}
		
		if (tanggal_akhir === '') {
			$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#end_date').datepicker('setDate', new Date(tanggal_akhir));
		}
        // END datepicker init
		
        // BEGIN select2 init
        $("#select_heyxxmd").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/heyxxmd/heyxxmd_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_heyxxmd_old: id_heyxxmd_old,
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

        // BEGIN select2 init
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			allowClear: true,
			ajax: {
				url: "../../models/hemxxmh/hemxxmh_heyxxmd_fn_opt.php",
				dataType: 'json',
				data: function (params) {
					id_heyxxmd = $('#select_heyxxmd').val();
					
					var query = {
						id_hemxxmh_old: id_hemxxmh_old,
						id_heyxxmd: id_heyxxmd,
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
			
			if ($('#select_hemxxmh').val() > 0) {
				id_hemxxmh = $('#select_hemxxmh').val();
			} else {
				if (id_hem_get != 0) {
					id_hemxxmh = id_hem_get;
				} else {
					id_hemxxmh = $('#select_hemxxmh').val();
				}
			}

			id_hemxxmh_old = id_hem_get;
			id_heyxxmd = $('#select_heyxxmd').val();
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			if (user_id > 100) {
				if (str_arr_ha_heyxxmh == '1,2') {
					$('#kbm').show();
					$('#kmj').show();
					$('#karyawan').show();
					$('#freelance').show();
				}
				if (str_arr_ha_heyxxmh == '1') {
					$('#karyawan').show();
					$('#freelance').show();

					$('#kbm').hide();
					$('#kmj').hide();
				}
				if (str_arr_ha_heyxxmh == '2') {
					$('#kbm').show();

					$('#kmj').hide();
					$('#karyawan').hide();
					$('#freelance').hide();
				}
			} else {
				$('#kbm').show();
				$('#kmj').show();
				$('#karyawan').show();
				$('#freelance').show();
			}
			
		///start datatables
			tblhtsprrd = $('#tblhtsprrd').DataTable( {
				// select: {
				// 	style: 'multi', // 'single', 'multi', 'os', 'multi+shift'
				// },
				searchPanes:{
					layout: 'columns-4'
				},
				// dom:
				// 	"<'row'<'col-lg-4 col-md-4 col-sm-12 col-xs-12'l><'col-lg-8 col-md-8 col-sm-12 col-xs-12'f>>" +
				// 	"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'B>>" +
				// 	"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
				// 	"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
				dom:
				"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'P>>" +
				"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'Q>>" +
				"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'l>>" +
				"<'row'<'col-lg-6 col-md-12 col-sm-12 col-xs-12'B><'col-lg-6 col-md-6 col-sm-12 col-xs-12'f>>" +
				"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
				"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true
						},
						targets: [1,3,5,6,7,15,16,17,18]
					},
					{
						searchPanes:{
							show: false
						},
						targets: "_all"
					}
				],
				fixedColumns: {
                    leftColumns: 1 // Freeze column 0 and 1
                },
				ajax: {
					url: "../../models/htsprrd/htsprrd_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
						d.id_heyxxmd = id_heyxxmd;
					}
				},
				order: [[1, "asc"]],
				// scrollX: true,
				responsive: false,
				// rowGroup: {
				// 	dataSrc: function (row) {
				// 		return row.hemxxmh_data + ' - ' + row.hodxxmh.nama + ' - ' + row.hetxxmh.nama + ' (' + row.htsprrd.tanggal + ')';
				// 	}
				// },
				columns: [
					{ data: "htsprrd.id",visible:false },
					{ 
						data: "hemxxmh_data"
						// ,visible:false 
					},
					{ 
						data: null ,
						render: function (data, type, row) {
							var id_hemxxmh = row.htsprrd.id_hemxxmh;
							var tanggal = row.htsprrd.tanggal;
							var url = "../dashboard/d_hr_report_presensi.php?id_hemxxmh=" + id_hemxxmh + "&start_date=" + tanggal;
							var link = '<a href="' + url + '" target="_blank">Link Text</a>';
							return link;
						}
					},
					{ 
						data: "hodxxmh.nama"
						// ,visible:false 
					},
					{ 
						data: "hetxxmh.nama"
						// ,visible:false 
					},
					{ 
						data: "holxxmd_2.nama"
					},
					{ 
						data: "htsprrd.tanggal"
						// ,visible:false 
					},
					{ 
						data: "htsprrd.cek" ,
						render: function (data, type, row) {
							var cek = 0;
							if(row.htsprrd.cek == null) {
								// console.log(row.htsprrd.status_presensi_in);
								status_presensi_in = row.htsprrd.status_presensi_in;
								status_presensi_out = row.htsprrd.status_presensi_out;
								st_clock_in = row.htsprrd.st_clock_in;
								if(
									status_presensi_in == 'NJ' || 
									status_presensi_in == 'AL' || 
									status_presensi_in == 'Belum ada Izin' || 
									status_presensi_in == 'Belum ada Absen' || 
									status_presensi_in == 'Izin Belum Disetujui' || 
									status_presensi_in == 'No CI' || 
									status_presensi_out == 'Belum ada Absen' || 
									status_presensi_out == 'Belum ada Izin' || 
									status_presensi_out == 'Izin Belum Disetujui' || 
									status_presensi_out == 'No CO'
									){
									cek = cek + 1;
								}

								if (st_clock_in == "LATE 1") {
									return 0;
								} else {
									if(cek > 0){
										return '<span class="text-danger">' + cek + '</span>';
									}else{
										return cek;
									}
								}
								return row.htsprrd.status_presensi_in;
							} else {
								return row.htsprrd.cek
							}
					   	},
						class: "text-right"
					},
					{ 
						data: "htsprrd.shift_in",
						visible: false 
					},
					{ 
						data: "htsprrd.shift_out" ,
						visible: false
					},
					{ data: "htsprrd.st_jadwal" }, //8
					{ data: "htsprrd.clock_in" },
					{ data: "htsprrd.clock_out" },
					{ data: "htsprrd.break_in" },
					{ data: "htsprrd.break_out" },
					{ data: "htsprrd.st_clock_in" },
					{ data: "htsprrd.st_clock_out" },//12
					{ data: "htsprrd.status_presensi_in" },
					{ data: "htsprrd.status_presensi_out" },
					{ data: "htsprrd.htlxxrh_kode" }, //15
					{ 
						data: "htsprrd.is_makan",
						render: function (data){
							if (data > 0){
								return data;
							}else {
								return '';
							}
						},
						class: "text-right"
					}, //17 jadi 16
					{ data: "htsprrd.jam_awal_lembur_libur" },//18
					{ data: "htsprrd.jam_akhir_lembur_libur" },
					{ data: "htsprrd.jam_awal_lembur_awal" },
					{ data: "htsprrd.jam_akhir_lembur_awal" },
					{ data: "htsprrd.jam_awal_lembur_akhir" },
					{ data: "htsprrd.jam_akhir_lembur_akhir" }, //23
					{ 
						data: "htsprrd.durasi_lembur_libur",
						class: "text-right"
					}, //24
					{ 
						data: "htsprrd.durasi_lembur_awal" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_akhir" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_istirahat1" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_istirahat2" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_istirahat3" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_total_jam" ,
						class: "text-right"
					}, //30
					{ 
						data: "htsprrd.pot_ti" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.pot_overtime" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.durasi_lembur_final",
						class: "text-right"
					},
					{ 
						data: "htsprrd.pot_hk" ,
						class: "text-right"
					},
					{ 
						data: "htsprrd.pot_jam",
						class: "text-right"
					},
					{ 
						data: "htsprrd.is_pot_upah",
						render: function (data, type, row) {
							if (data == 1	) {
								return `<span class="badge bg-primary">Ya</span>`;
							} else {
								return `<span class="badge bg-danger">Tidak</span>`;
							}
						}
					},
					{ 
						data: "htsprrd.is_pot_premi",
						render: function (data, type, row) {
							if (data == 1	) {
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
					,{
						extend: 'collection',
						name: 'btnSetApprovePresensi',
						id: 'btnSetApprovePresensi',
						text: 'Approval Presensi',
						className: 'btn btn-outline',
						autoClose: true,
						buttons: [
							{ 
								text: '<span class="fa fa-check">&nbsp &nbsp Approve Presensi</span>', 
								name: 'btnApprovePresensi',
								className: 'btn btn-primary',
								titleAttr: 'Approve',
								action: function ( e, dt, node, config ) {
									var approve_presensi = 1;
									$.ajax( {
										url: '../../models/htsprrd/fn_approve_presensi.php',
										dataType: 'json',
										type: 'POST',
										data: {
											start_date: start_date,
											approve_presensi: approve_presensi
										},
										success: function ( json ) {
											$.notify({
												message: json.message
											},{
												type: json.type_message
											});
											tblhtsprrd.ajax.reload(null,false);
											cariApprove();
										}
									});
								}
							},
							{ 
								text: '<span class="fa fa-undo">&nbsp &nbsp Cancel Approve Presensi</span>', 
								name: 'btnCancelApprovePresensi',
								className: 'btn btn-outline',
								titleAttr: 'Cancel Approve',
								action: function ( e, dt, node, config ) {
									var approve_presensi = 0;
									$.ajax( {
										url: '../../models/htsprrd/fn_approve_presensi.php',
										dataType: 'json',
										type: 'POST',
										data: {
											start_date: start_date,
											approve_presensi: approve_presensi
										},
										success: function ( json ) {
											$.notify({
												message: json.message
											},{
												type: json.type_message
											});
											tblhtsprrd.ajax.reload(null,false);
											cariApprove();
										}
									});
								}
							},
						]
					},
					{ 
						// text: '<i class="fa fa-exchange" aria-hidden="true"></i>', 
						text: 'AL', 
						name: 'btncekNol',
						className: 'btn btn-danger',
						titleAttr: 'Meng Alpha-kan',
						action: function ( e, dt, node, config ) {
							$.ajax( {
								url: '../../models/htsprrd/fn_ganti_alpha_multi.php',
								dataType: 'json',
								type: 'POST',
								data: {
									id_htsprrd: id_htsprrd
								},
								success: function ( json ) {
									$.notify({
										message: json.message
									},{
										type: json.type_message
									});
									tblhtsprrd.ajax.reload(null,false);
									id_htsprrd = [];
								}
							});
						}
					},
					{ 
						text: '<i class="fa fa-exchange" aria-hidden="true"></i>',  
						name: 'btnPresensiOK',
						className: 'btn btn-primary',
						titleAttr: 'Presensi OK',
						action: function ( e, dt, node, config ) {
							$.ajax( {
								url: '../../models/htsprrd/fn_presensi_ok_multi.php',
								dataType: 'json',
								type: 'POST',
								data: {
									id_htsprrd: id_htsprrd
								},
								success: function ( json ) {
									$.notify({
										message: json.message
									},{
										type: json.type_message
									});
									tblhtsprrd.ajax.reload(null,false);
									id_htsprrd = [];
								}
							});
						}
					},
					{ 
						text: '<i class="fa fa-filter" aria-hidden="true"></i>',  
						name: 'btnFilterAdvanced',
						className: 'btn btn-primary',
						titleAttr: 'Advanced Filter',
						action: function ( e, dt, node, config ) {
							$('#modalQ').modal('show'); 
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsprrd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 

					all_makan = api.column(20).data().sum();
					all_lb = api.column(27).data().sum();
					all_aw = api.column(28).data().sum();
					all_ak = api.column(29).data().sum();
					all_i1 = api.column(30).data().sum();
					all_i2 = api.column(31).data().sum();
					all_i3 = api.column(32).data().sum();
					all_tl = api.column(33).data().sum();

					all_pot_ti = api.column(34).data().sum();
					all_pot_overtime = api.column(35).data().sum();
					all_overtime = api.column(36).data().sum();
					all_pot_hk = api.column(37).data().sum();
					all_pot_jam = api.column(38).data().sum();

					$( '#all_makan' ).html( numFormat(all_makan) );
					$( '#all_lb' ).html( numFormat(all_lb) );
					$( '#all_aw' ).html( numFormat(all_aw) );
					$( '#all_ak' ).html( numFormat(all_ak) );
					$( '#all_i1' ).html( numFormat(all_i1) );
					$( '#all_i2' ).html( numFormat(all_i2) );
					$( '#all_i3' ).html( numFormat(all_i3) );
					$( '#all_tl' ).html( numFormat(all_tl) );

					$( '#all_pot_ti' ).html( numFormat(all_pot_ti) );
					$( '#all_pot_overtime' ).html( numFormat(all_pot_overtime) );
					$( '#all_overtime' ).html( numFormat(all_overtime) );
					$( '#all_pot_hk' ).html( numFormat(all_pot_hk) );
					$( '#all_pot_jam' ).html( numFormat(all_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhtsprrd.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd.button('btncekNol:name').disable();
			tblhtsprrd.button('btnPresensiOK:name').disable();

			tblhtsprrd.searchPanes.container().appendTo( '#searchPanes1' );
			tblhtsprrd.searchBuilder.container().appendTo( '#sb' );

			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				var activeTabId = $(e.target).attr('href').substring(1);
				// console.log('Active Tab ID: ' + activeTabId);
				if (activeTabId == 'tabhtsprrd') {
					tblhtsprrd.searchPanes.container().appendTo( '#searchPanes1' );
					
				} else {
					tblhtsprrd.searchPanes.container().detach();
				}
			});

			tblhtsprrd.on( 'select', function( e, dt, type, indexes ) {
				updateSelectedData();
			} );
			
			tblhtsprrd.on( 'deselect', function () {
				updateSelectedData();
			} );

			function updateSelectedData() {

				var data_multi = tblhtsprrd.rows({ selected: true }).data().toArray();
				id_htsprrd = data_multi.map(row => row.htsprrd.id);
				status_presensi_in = data_multi.map(row => row.htsprrd.status_presensi_in);
				status_presensi_out = data_multi.map(row => row.htsprrd.status_presensi_out);
				st_clock_in = data_multi.map(row => row.htsprrd.st_clock_in);
				st_clock_out = data_multi.map(row => row.htsprrd.st_clock_out);
				htlxxrh_kode = data_multi.map(row => row.htsprrd.htlxxrh_kode);
				tanggal = data_multi.map(row => row.htsprrd.tanggal);
				cek = data_multi.map(row => row.htsprrd.cek);
				id_heyxxmd = data_multi.map(row => row.hemjbmh.id_heyxxmd);
				id_hemxxmh_select = data_multi.map(row => row.htsprrd.id_hemxxmh);
				// console.log(id_heyxxmd);
				
				var btncekNol = data_multi.every(row =>
					(row.htsprrd.status_presensi_in === "AL" && row.htsprrd.status_presensi_out === "AL") ||
					(row.htsprrd.status_presensi_in === "Jadwal Salah" && row.htsprrd.status_presensi_out === "Jadwal Salah")
				);

				tblhtsprrd.button('btncekNol:name').enable(btncekNol);
				
				htlxxrh_kode.forEach(value => {
					if (value.includes("KD/")) {
						kode = "KD"; // Log 1 if the string contains "KD/"
					} else {
						kode = "NO"
					}
				});

				// console.log(st_clock_in);
				// console.log(status_presensi_in);
				// console.log(cek);

				var btnPresensiOK = data_multi.every(row =>
    				(kode == "KD" && row.htsprrd.cek == "1") || 
					(row.htsprrd.status_presensi_out == "Belum ada Izin") ||
					(row.htsprrd.status_presensi_in == "Belum ada Izin") ||
					// (row.htsprrd.status_presensi_in == "TL 1" && row.htsprrd.status_presensi_out == "Belum ada Izin") ||
					// (row.htsprrd.st_clock_in == "Late" && row.htsprrd.status_presensi_in == "Belum ada Izin") ||
					// (row.htsprrd.st_clock_out == "EARLY" && row.htsprrd.status_presensi_out == "Belum ada Izin") ||
					// (row.htsprrd.st_clock_in == "HK" && row.htsprrd.status_presensi_in == "Belum ada Izin") ||
					// (row.htsprrd.st_clock_out == "HK" && row.htsprrd.status_presensi_out == "Belum ada Izin") ||
					(row.hemjbmh.id_heyxxmd == "4" && row.htsprrd.cek == "1") ||
					(row.htsprrd.status_presensi_in == "Jadwal Salah") ||
					(row.htsprrd.id_hemxxmh_select == "130" || row.htsprrd.id_hemxxmh_select == "134")
				);

				// tblhtsprrd.button('btnPresensiOK:name').enable(btnPresensiOK);
				tblhtsprrd.button('btnPresensiOK:name').enable();
				
				if (id_htsprrd.length === 0) {
					tblhtsprrd.button('btncekNol:name').disable();
					tblhtsprrd.button('btnPresensiOK:name').disable();
				}
			}

		///// end datatables

			$("#frmhtsprrd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtsprrd) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					
					if ($('#select_hemxxmh').val() > 0) {
						id_hemxxmh = $('#select_hemxxmh').val();
					} else {
						if (id_hem_get != 0) {
							id_hemxxmh = id_hem_get;
						} else {
							id_hemxxmh = $('#select_hemxxmh').val();
						}
					}
					
					id_heyxxmd = $('#select_heyxxmd').val();

					console.log('id_heyxxmd ='+id_heyxxmd);

					cariApprove();
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
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
