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
								<th rowspan=2>Tanggal</th>
								<th rowspan=2>Nama</th>
								<th rowspan=2>Department</th>
								<th rowspan=2>Jabatan</th>
								<th rowspan=2>Jenis</th>
								<th colspan=2>Lembur Libur</th>
								<th colspan=2>Lembur Awal</th>
								<th colspan=2>Lembur Akhir</th>
								<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
								<th class="text-center" colspan=3>Potongan Jam Overtime</th>
								<th class="text-center" colspan=4>Jam Lembur</th>

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
								<th>Lembur 1.5</th>
								<th>Lembur 2</th>
								<th>Lembur 3</th>
								<th>Lembur 4</th>
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
								<th>Total</th>

								<th id="s_12"></th>
								<th id="s_13"></th>
								<th id="s_14"></th>
								<th id="s_15"></th>
								<th id="s_16"></th>
								<th id="s_17"></th>
								<th id="s_18"></th>
								<th id="s_19"></th>
								<th id="s_20"></th>
								<th id="s_21"></th>
								<th id="s_22"></th>
								<th id="s_23"></th>
								<th id="s_24"></th>
								<th id="s_25"></th>

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

		var id_hem_get = <?php echo $id_hemxxmh ?>;

		if (id_hem_get != '') {
			var id_hemxxmh_old = id_hem_get;
		} else {
			var id_hemxxmh_old = 0;
		}

		var id_hemxxmh = 0;
		var tanggal_awal = "<?php echo $awal ?>";
		var tanggal_akhir = "<?php echo $akhir ?>";

		console.log(tanggal_awal);
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		// $('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		// $('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		
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
			
			if ($('#select_hemxxmh').val() > 0) {
				id_hemxxmh = $('#select_hemxxmh').val();
			} else {
				if (id_hem_get != 0) {
					id_hemxxmh = id_hem_get;
				} else {
					id_hemxxmh = $('#select_hemxxmh').val();
				}
			}

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
						targets: [1,2,3,5]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [4,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21, 22, 23, 24,25]
					}
				],
				ajax: {
					url: "../../models/htsprrd_overtime/htsprrd.php",
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
						data: "htsprrd.tanggal"
						// ,visible:false 
					},
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
						data: "heyxxmh.nama"
						// ,visible:false 
					},
					{ data: "htsprrd.jam_awal_lembur_libur" },//6
					{ data: "htsprrd.jam_akhir_lembur_libur" },
					{ data: "htsprrd.jam_awal_lembur_awal" },
					{ data: "htsprrd.jam_akhir_lembur_awal" },
					{ data: "htsprrd.jam_awal_lembur_akhir" },
					{ data: "htsprrd.jam_akhir_lembur_akhir" },
					{ 
						data: "htsprrd.durasi_lembur_libur",
						class: "text-right"
					}, //12
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
					}, //18
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
						data: "htsprrd.lembur15",
						class: "text-right"
					},
					{ 
						data: "htsprrd.lembur2",
						class: "text-right"
					},
					{ 
						data: "htsprrd.lembur3",
						class: "text-right"
					},
					{ 
						data: "htsprrd.lembur4",
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
				],
				rowCallback: function( row, data, index ) {
					if ( data.htsprrd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
					
					for (var i = 12; i <= 25; i++) {
						var columnIndex = i;
						var sum = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						// var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#s_' + columnIndex).html(numFormat(sum));
					}
				},
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
					if ($('#select_hemxxmh').val() > 0) {
						id_hemxxmh = $('#select_hemxxmh').val();
					} else {
						if (id_hem_get != 0) {
							id_hemxxmh = id_hem_get;
						} else {
							id_hemxxmh = $('#select_hemxxmh').val();
						}
					}

					console.log(id_hemxxmh);
					
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
