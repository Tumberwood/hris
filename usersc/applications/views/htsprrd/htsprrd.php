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
				<div class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					Apabila data presensi sudah final pada satu tanggal, mohon lakukan approval untuk mengunci data yang ada. Pastikan hanya memilih satu tanggal saja.
				</div>
				<div class="table-responsive">
                    <table id="tblhtsprrd" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th rowspan=2>ID</th>
								<th rowspan=2>Nama</th>
								<th rowspan=2>Department</th>
								<th rowspan=2>Jabatan</th>
								<th rowspan=2>Tanggal</th>
								<th rowspan=2>Cek</th>
								<th rowspan=2>Shift In</th>
								<th rowspan=2>Shift Out</th>
								<th rowspan=2>Jadwal</th>
								<th rowspan=2>Clock In</th>
								<th rowspan=2>Clock Out</th>
								<th rowspan=2>Cek CI</th>
								<th rowspan=2>Cek CO</th>
								
								<th rowspan=2>Status In</th>
								<th rowspan=2>Status Out</th>

								<th rowspan=2>Keterangan</th>
								 <!-- //pot jam dihapus (16) --> 
								<th rowspan=2>Potongan Makan</th>
								
								<th colspan=2>Lembur Libur</th>
								<th colspan=2>Lembur Awal</th>
								<th colspan=2>Lembur Akhir</th>
								<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
								<th class="text-center" colspan=3>Potongan Jam Overtime</th>
								<th class="text-center" colspan=2>Potongan Jam</th>

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

								<th>Pot TI</th>
								<th>Pot Overtime</th>
								<th>Overtime Final</th>
								<th>Pot Hari Kerja</th>
								<th>Pot Total</th>
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
								<th>Total</th>
								<th id="s_makan"></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								
								<th id="s_lb"></th>
								<th id="s_aw"></th>
								<th id="s_ak"></th>
								<th id="s_i1"></th>
								<th id="s_i2"></th>
								<th id="s_i3"></th>
								<th id="s_tl"></th>
								
								<th id="s_pot_ti"></th>
								<th id="s_pot_overtime"></th>
								<th id="s_overtime"></th>
								<th id="s_pot_hk"></th>
								<th id="s_pot_jam"></th>
								<!-- <th id="s_hk"></th> -->

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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htsprrd/fn/htsprrd_fn.php'; ?>
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
					layout: 'columns-4'
				},
				dom:
					"<'row'<'col-lg-4 col-md-4 col-sm-12 col-xs-12'l><'col-lg-8 col-md-8 col-sm-12 col-xs-12'f>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'B>>" +
					"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
					"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true
						},
						targets: [1,2,4,5,11,12,13,14]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,3,5,6,7,8,9,10,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34]
					}
				],
				ajax: {
					url: "../../models/htsprrd/htsprrd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 4, "asc" ],[1, "asc"]],
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
						data: "hodxxmh.nama"
						// ,visible:false 
					},
					{ 
						data: "hetxxmh.nama"
						// ,visible:false 
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
					}
					
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
								url: '../../models/htsprrd/fn_ganti_alpha.php',
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
								url: '../../models/htsprrd/fn_presensi_ok.php',
								dataType: 'json',
								type: 'POST',
								data: {
									id_htsprrd: id_htsprrd,
									id_hemxxmh_select: id_hemxxmh_select,
									tanggal: tanggal,
									htlxxrh_kode: htlxxrh_kode,
								},
								success: function ( json ) {
									$.notify({
										message: json.message
									},{
										type: json.type_message
									});
									tblhtsprrd.ajax.reload(null,false);
								}
							});
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
					
					// s_pot_jam = api.column( 16 ).data().sum();
					s_makan = api.column( 16 ).data().sum();
					s_lb = api.column( 23 ).data().sum();
					s_aw = api.column( 24 ).data().sum();
					s_ak = api.column( 25 ).data().sum();
					s_i1 = api.column( 26 ).data().sum();
					s_i2 = api.column( 27 ).data().sum();
					s_i3 = api.column( 28 ).data().sum();
					s_tl = api.column( 29 ).data().sum();

					s_pot_ti = api.column( 30 ).data().sum();
					s_pot_overtime = api.column( 31 ).data().sum();
					s_overtime = api.column( 32 ).data().sum();
					s_pot_hk = api.column( 33 ).data().sum();
					s_pot_jam = api.column( 34 ).data().sum();

					$( '#s_makan' ).html( numFormat(s_makan) );
					$( '#s_lb' ).html( numFormat(s_lb) );
					$( '#s_aw' ).html( numFormat(s_aw) );
					$( '#s_ak' ).html( numFormat(s_ak) );
					$( '#s_i1' ).html( numFormat(s_i1) );
					$( '#s_i2' ).html( numFormat(s_i2) );
					$( '#s_i3' ).html( numFormat(s_i3) );
					$( '#s_tl' ).html( numFormat(s_tl) );

					$( '#s_pot_ti' ).html( numFormat(s_pot_ti) );
					$( '#s_pot_overtime' ).html( numFormat(s_pot_overtime) );
					$( '#s_overtime' ).html( numFormat(s_overtime) );
					$( '#s_pot_hk' ).html( numFormat(s_pot_hk) );
					$( '#s_pot_jam' ).html( numFormat(s_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhtsprrd.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd.button('btncekNol:name').disable();
			tblhtsprrd.button('btnPresensiOK:name').disable();

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
				
				if (status_presensi_in == "AL" && status_presensi_out == "AL" || status_presensi_in == "Jadwal Salah" && status_presensi_out == "Jadwal Salah") {
					tblhtsprrd.button('btncekNol:name').enable();
				} else {
					tblhtsprrd.button('btncekNol:name').disable();
				}
				// if (status_presensi_in == "" && status_presensi_out == "") {
				// 	if (st_clock_in == "No CI" && st_clock_out == "No CO") {
				// 	tblhtsprrd.button('btncekNol:name').enable();
				// 	} else {
				// 		tblhtsprrd.button('btncekNol:name').disable();
				// 	}
				// }

				cariKMJ();
				// console.log(htlxxrh_kode);
				//Cek Apakah mengandung Kode Absen KD
				if (htlxxrh_kode.includes("KD/") && cek == 1) {
				    tblhtsprrd.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_in == "Late" && status_presensi_in == "Belum ada Izin") {
				    tblhtsprrd.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_out == "EARLY" && status_presensi_out == "Belum ada Izin") {
				    tblhtsprrd.button('btnPresensiOK:name').enable();
                }
				// console.log(htsprrd_data.status_presensi_in);
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
