<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>
<link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/plugins/pivottable/pivot.min.css" rel="stylesheet">

<?php
	$nama_tabel    = 'htsprrd';
	$nama_tabels_d = [];

	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= $_GET['id_hemxxmh'];
	} else {
		$id_hemxxmh		= 0;
	}
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
?>

<!-- begin content here -->

<div class="row">
    <div class="col">
        <div class="ibox" id="iboxfilter">
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
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Employee</label>
						<div class="col-sm-4">
							<div class="input-group">
								<div class="row">
									<div class="col-md-10">
										<select class="form-control" id="select_hemxxmh" name="select_hemxxmh"></select>
									</div>
									<div class="col-md-2">
										<button class="btn btn-danger" id="clearSelect"><i class="fa fa-times"></i></button>
									</div>
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

<div class="row" id="report" style="display: none;">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<h3 class="text-center" id="nama_peg"></h3>
				<br>
                <div class="row" id="konten">
					<div class="col-md-1">
                       <h4>Tanggal</h4> 
                       <h4>Jadwal</h4> 
                       <h4>Keterangan</h4> 
                    </div>
                    <div class="col-md-4" >
                        <h3 id="tanggal"></h3>
                        <div id="edit_jadwal"></div>
                        <!-- <h3 id="edit_jadwal"><a href="#" id="jadwal" data-editor-id=""></a></h3> -->
                        <h3 id="keterangan"></h3>
                    </div>
					<div class="col-md-1"></div>
                    <div class="col-md-1.5">
                       <h4>Department</h4> 
                       <h4>Sub Tipe</h4> 
                       <h4>Kelompok</h4> 
                    </div>
                    <div class="col-md-2">
                        <h4 id="dep"></h4>
                        <h4 id="kmj"></h4>
                        <h4 id="kelompok"></h4>
                    </div>
                    <div class="col-md-1.5">
                       <h4>Status</h4> 
                       <h4>Divisi</h4> 
                       <h4>Level</h4> 
                    </div>
                    <div class="col-md-2">
                        <h4 id="stat"></h4>
                        <h4 id="STATUS"></h4>
                        <h4 id="lev"></h4>
                    </div>
                </div>
				<button id="del_cuti_holiday" class="btn btn-danger fa fa-trash" style="display: none"> Hapus Cuti & Public Holiday</button>
				<br>
				<div id="tabel_atas"></div>
				<div style="margin-top: -40px" id="tabel_bawah"></div>
				<div class="row">
					<div class="col-md-4">
                        <h3 id="h3_riwayat"></h3>
						<div id="tabel_riwayat"></div>
					</div>
					<div class="col-md-4">
                        <h3 id="h3_istirahat"></h3>
						<div id="tabel_istirahat"></div>
					</div>
					<div class="col-md-4">
                        <h3 id="h3_makan"></h3>
						<div id="tabel_makan"></div>
					</div>
				</div>
				<div id="tabel_cek_finger"></div>
				<div class="row">
					<div class="col-md-6">
						<button id="prevButton" class="btn btn-primary">Previous</button>
					</div>
					<div class="col-md-5">
						<h5 id="paging"></h5>
					</div>
					<div class="col-md-1">
						<button id="nextButton" class="btn btn-primary">Next</button>
					</div>
				</div>
			</div>
		</div>
    </div>
	</div>
</div>

<div class="ibox"  id="no_jadwal" style="display: none;">
	<div class="ibox-content">
		<h1 class="text-center text-danger text-bold"> Data Tidak Valid !!! </h1>
	</div>
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>
<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/training_m/fn/training_m_fn.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htssctd/fn/htssctd_fn.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/dashboard/fn/d_hr_report_presensi_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tblhtsprrd, show_inactive_status_htsprrd = 0;
		var edthtssctd, tblhtssctd, show_inactive_status_htssctd = 0, id_htssctd;
		var edthtsprtd, tblhtsprtd, show_inactive_status_htsprtd = 0, id_htsprtd;
		// ------------- end of default variable
		var notifyprogress = '';
		var id_hemxxmh_old = 0;
		var id_hemxxmh = null;
		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var tanggal_get = "<?php echo $awal ?>";
		var id_htsxxmh_old = 0;
		var id_hemxxmh_filter = 0, tanggal_old = '';
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		if (tanggal_get === '') {
			$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_get));
		}
        // END datepicker init
        
        // BEGIN select2 init
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			ajax: {
				url: "../../models/hemxxmh/hemxxmh_fn_opt_all.php",
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
					var options = data.results.map(function (result) {
						return {
							id: result.id,
							text: result.text
						};
					});

					//add by ferry agar auto select 07 sep 23
					if (params.page && params.page === 1) {
						$('#select_hemxxmh').empty().select2({ data: options });
					} else {
						$('#select_hemxxmh').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
					}

					return {
						results: options,
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

		function generateTable(counter) {
			if ($('#select_hemxxmh').val() > 0) {
				id_hemxxmh = $('#select_hemxxmh').val();
			} else {
				if (id_hem_get != 0) {
					id_hemxxmh = id_hem_get;
				} else {
					id_hemxxmh = $('#select_hemxxmh').val();
				}
			}
			console.log(tanggal_old);
			
			var buttons = [
				{
					extend: 'collection',
					text: '<i class="fa fa-wrench"></i>',
					className: 'btn btn-white',
					autoClose: true,
					buttons: [
						{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
						{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
					]
				}
			];
			
			// console.log('tanggal_old = '+tanggal_old);
			// console.log('start_date = '+start_date);
			if (tanggal_old != start_date) {
				$.ajax( {
					url: "../../models/dashboard/d_hr_report_presensi_cek_finger.php",
					dataType: 'json',
					type: 'POST',
					data: {
						start_date: start_date
					},
					success: function ( json ) {
						// console.log(json);
						var str9 = `
							<h3>Tabel Cek Finger</h3>
							<table id="tblhtsprrd9" class="table table-striped table-bordered" width="100%">`
						;
						
						if ($.fn.dataTable.isDataTable('#tblhtsprrd9')) {
							$('#tblhtsprrd9').DataTable().clear();
							$('#tblhtsprrd9').DataTable().destroy();
							$('#tblhtsprrd9 tbody').empty();
							$('#tblhtsprrd9 thead').empty();
						}
						str9 += '<thead>';
							str9 += '<tr>';
							$.each(json.columns9, function (k, colObj9) {
								// BEGIN render column name
								if (colObj9.name == 'kode') {
									str9 += '<th class="text-center">NIP</th>';
								} else if (colObj9.name == 'nama') {
									str9 += '<th class="text-center">Nama</th>';
								} else if (colObj9.name == 'dept') {
									str9 += '<th class="text-center">Departemen</th>';
								} else if (colObj9.name == 'jabatan') {
									str9 += '<th class="text-center">Jabatan</th>';
								} else if (colObj9.name == 'area') {
									str9 += '<th class="text-center">Area</th>';
								} else if (colObj9.name == 'ceklok_in') {
									str9 += '<th class="text-center">Check In Hadir</th>';
								} else if (colObj9.name == 'ceklok_out') {
									str9 += '<th class="text-center">Check Out Hadir</th>';
								} else if (colObj9.name == 'break_in_gedung3') {
									str9 += '<th class="text-center">Break In Gedung 3</th>';
								} else if (colObj9.name == 'break_out_gedung3') {
									str9 += '<th class="text-center">Break Out Gedung 3</th>';
								} else if (colObj9.name == 'break_in_luar_gedung3') {
									str9 += '<th class="text-center">Break In 2</th>';
								} else if (colObj9.name == 'break_out_luar_gedung3') {
									str9 += '<th class="text-center">Break Out 2</th>';
								}
							});
							str9 += '</tr>';
							// END header baris 9
						str9 = str9 + '</thead>';

						$('#tabel_cek_finger').html(str9);

						$('#tblhtsprrd9').DataTable({
							lengthChange: false,
							responsive: false,
							data: json.data9,
							columns: json.columns9,
							buttons: buttons,
							rowCallback: function (row, data, index) {

							}
						});
					}
				} );
			}

			$.ajax( {
				url: "../../models/dashboard/d_hr_report_presensi.php",
				dataType: 'json',
				type: 'POST',
				data: {
					tanggal_old: tanggal_old,
					start_date: start_date,
					counter: counter,
					id_hemxxmh: id_hemxxmh
				},
				success: function ( json ) {
					if (json.data7 && Object.keys(json.data7).length > 0) {
						tanggal_old = start_date;
						// console.log(tanggal_old);
						$('#edit_jadwal').empty();
						$('#jadwal').empty();
						
						$('#report').show();
						$('#no_jadwal').hide();

						var page_now = parseInt(counter) + 1; 
						var page_total = parseInt(json.data5) + 1; 
						if ($('#select_hemxxmh').val() > 0) {
							id_hemxxmh = $('#select_hemxxmh').val();
						} else {
							if (id_hem_get != 0) {
								id_hemxxmh = id_hem_get;
							} else {
								id_hemxxmh = $('#select_hemxxmh').val();
							}
						}

						// console.log('id_hemxxmh'+id_hemxxmh);
						
						var button_add = [
							{
								extend: 'collection',
								text: '<i class="fa fa-wrench"></i>',
								className: 'btn btn-white',
								autoClose: true,
								buttons: [
									{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
									{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
								]
							}
						];

						// hitung counter	
						if (id_hemxxmh != null) {
							$('#prevButton').hide();
							$('#nextButton').hide();
							$('#paging').hide();
						} else {
							$('#paging').show();

							if (counter == 0) {
								$('#prevButton').hide();
							} else {
								$('#prevButton').show();
							}
							console.log(json.data5);
							if (counter == json.data5) {
								$('#nextButton').hide();
							} else {
								$('#nextButton').show();
							}
						}

						button_add.push({
							extend: 'create',
							name: 'btnCreateCeklok',
							id: 'addCeklok',
							editor: edthtsprtd,
							text: '<i class="fa fa-plus"></i>',
							className: 'btn btn-outline',
							titleAttr: 'New',
							key: {
								key: 'n',
								ctrlKey: true,
								altKey: true
							}
						});
						
						var id = json.data8.id_jadwal;
						// console.log(id);
						// console.log('row_'+id);
						// console.log(json.data8.id_jadwal);
						if(json.data8.id_jadwal > 0){
							$('#edit_jadwal').attr('data-editor-id', 'row_'+id);
							
							var h3Element = $('<h3>');

							keterangan_jadwal = '';
							if (json.data8.keterangan != null) {
								keterangan_jadwal = `(${json.data8.keterangan})`;
							}

							console.log(json.data8.is_cuti_holiday);

							if (json.data8.is_cuti_holiday != 0) {
								$('#del_cuti_holiday').show();

								$("#del_cuti_holiday").click(function () {
									$('#del_cuti_holiday').hide();
									$.ajax( {
										url: '../../models/hthhdth/fn_hapus_cuti_holiday.php',
										dataType: 'json',
										type: 'POST',
										data: {
											tanggal: json.data7.tanggal,
											id_hemxxmh: json.data7.id_hemxxmh
										},
										success: function ( json ) {
											$.notify({
												message: json.data.message
											},{
												type: json.data.type_message
											});
											generateTable(counter);
										}
									});
									console.log(json.data7.id_hemxxmh);
								});
							} else {
								$('#del_cuti_holiday').hide();
							}

							// Create the anchor element with the specified attributes
							var anchorElement = $('<a>')
								.attr('href', '#')
								.attr('id', 'jadwal')
								.attr('data-id',id)
								.attr('data-empsjadwal',json.data7.nama)
								.attr('data-id_jadwal',json.data8.id_jadwal)
								.html(` :  ${json.data8.st_jadwal} ${keterangan_jadwal}`);

							// Append the anchor element to the h3 element
							h3Element.append(anchorElement);
							$('#edit_jadwal').append(h3Element);
						} else {
							var h3Element = $('<h3>');

							// Create the anchor element with the specified attributes
							var anchorElement = $('<a>')
								.attr('href', '#')
								.attr('id', 'buat_jadwal')
								.html(" : Jadwal Belum Dibuat");

							// Append the anchor element to the h3 element
							h3Element.append(anchorElement);
							$('#edit_jadwal').append(h3Element);
						}

						$('#tanggal').html(" : " + json.data7.tanggal);
						

						$('#dep').html(" : " + json.data7.dep);
						$('#kmj').html(" : " + json.data7.kmj);
						$('#kelompok').html(" : " + json.data7.kelompok);
						$('#stat').html(" : " + json.data7.stat);
						$('#STATUS').html(" : " + json.data7.STATUS);
						$('#lev').html(" : " + json.data7.lev);

						$('#nama_peg').text(json.data7.nama);
						$('#paging').text( page_now + " / " + page_total);
						$('#h3_riwayat').text("Riwayat Checkclock");

						if(json.data.length > 0 && json.data[0].id_hemxxmh != null){
							$('#keterangan').html(" : " + json.data[0].keterangan);
							var str1 = '<table id="tblhtsprrd1" class="table table-striped table-bordered">';
							
							if ($.fn.dataTable.isDataTable('#tblhtsprrd1')) {
								$('#tblhtsprrd1').DataTable().clear();
								$('#tblhtsprrd1').DataTable().destroy();
								$('#tblhtsprrd1 tbody').empty();
								$('#tblhtsprrd1 thead').empty();
							}
							str1 += '<thead>';
								// BEGIN header baris 1
								str1 += '<tr>';
								if (json.data[0].cek == 0) {
									str1 += '<th class="text-center" style="color: blue; vertical-align: middle;" colspan="2" rowspan="2" >Clock Finger</th>';
								} else {
									str1 += '<th class="text-center text-danger" colspan="2" rowspan="2" style="vertical-align: middle;">Clock Finger</th>';
								}
								
								var id_hemxxmh = json.data[0].id_hemxxmh;
								var tanggal = json.data[0].tanggal;
								var kondite = json.data[0].kondite;
								var kode_spkl = json.data[0].kode_spkl;

								str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;">Status IN</th>';
								str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;" >Status OUT</th>';

								var linkUrl = "../htlxxrh/htlxxrh.php?id_hemxxmh=" + id_hemxxmh + "&start_date=" + tanggal;
								
								str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;">Kondite</th>';

								str1 += '<th class="text-center" colspan="2" rowspan="1">SPKL</th>';
								str1 += '</tr>';

								var url = "../htoxxth/htoxxth.php?kode_hto=" + json.data[0].kode_spkl + "&start_date=" + tanggal;
								
								if (kode_spkl == '-') {
									str1 += '<tr>';
									str1 += '<th class="text-center" colspan="2" rowspan="1">' + json.data[0].kode_spkl + '</th>';
									str1 += '</tr>';
								} else {
									str1 += '<tr>';
									str1 += '<th class="text-center" colspan="2" rowspan="1">';
									str1 += '<a href="' + url + '" target="_blank">' + json.data[0].kode_spkl + '</a>';
									str1 += '</th>';
									str1 += '</tr>';
								}

								str1 += '<tr>';
								$.each(json.columns, function (k, colObj) {
									// BEGIN render column name
									if (colObj.name == 'clock_in') {
										str1 += '<th class="text-center">IN</th>';
									} else if (colObj.name == 'clock_out') {
										str1 += '<th class="text-center">OUT</th>';
									} else if (colObj.name == 'jam_awal') {
										str1 += '<th class="text-center">Jam Awal</th>';
									} else if (colObj.name == 'jam_akhir') {
										str1 += '<th class="text-center">Jam Akhir</th>';
									}
								});
								str1 += '</tr>';
								// END header baris 1
							str1 = str1 + '</thead>';

							$('#tabel_atas').html(str1);

							$('#tblhtsprrd1').DataTable({
								paging: false,          // Disable pagination
								searching: false,       // Disable search
								info: false,            // Disable "Showing X of Y entries" information
								lengthChange: false,    // Disable "Show X entries" dropdown
								responsive: false,
								fixedHeader: {
									header: false,
									// footer: true
								},
								data: json.data,
								columns: json.columns,
								buttons: buttons,
								columnDefs: [
									{
										targets: [4],
										render: function (data, type, row, meta) {
											var id_hemxxmh = row.id_hemxxmh;
											var tanggal = row.tanggal;
											var kondite = row.kondite;

											if (kondite !== '') {
												var linkUrlKondite = "../htlxxrh/htlxxrh.php?id_hemxxmh=" + id_hemxxmh + "&start_date=" + tanggal;
												return '<a href="' + linkUrlKondite + '" target="_blank">' + kondite + '</a>';
											} else {
												return data;
											}
										},
									},
								],
								rowCallback: function (row, data, index) {

								}
							});

							
							var str5 = '<table id="tblhtsprrd5" class="table table-striped table-bordered">';
							
							if ($.fn.dataTable.isDataTable('#tblhtsprrd5')) {
								$('#tblhtsprrd5').DataTable().clear();
								$('#tblhtsprrd5').DataTable().destroy();
								$('#tblhtsprrd5 tbody').empty();
								$('#tblhtsprrd5 thead').empty();
							}
							str5 += '<thead>';
								str5 += '<tr>';
								$.each(json.columns5, function (k, colObj5) {
									// BEGIN render column name
									if (colObj5.name == 'jam_kerja') {
										str5 += '<th class="text-center">Jam Kerja</th>';
									} else if (colObj5.name == 'jam_wajib') {
										str5 += '<th class="text-center">Jam Wajib</th>';
									} else if (colObj5.name == 'potong') {
										str5 += '<th class="text-center">Potong</th>';
									} else if (colObj5.name == 'ti') {
										str5 += '<th class="text-center">TI</th>';
									} else if (colObj5.name == 'makan') {
										str5 += '<th class="text-center">Makan</th>';
									} else if (colObj5.name == 'is_pot_upah') {
										str5 += '<th class="text-center">Pot Upah</th>';
									} else if (colObj5.name == 'is_pot_premi') {
										str5 += '<th class="text-center">Pot Premi</th>';
									} else if (colObj5.name == 'lembur') {
										str5 += '<th class="text-center">Lembur</th>';
									} else if (colObj5.name == 'lembur15') {
										str5 += '<th class="text-center">x1.5</th>';
									} else if (colObj5.name == 'lembur2') {
										str5 += '<th class="text-center">x2</th>';
									} else if (colObj5.name == 'lembur3') {
										str5 += '<th class="text-center">x3</th>';
									} else if (colObj5.name == 'lembur4') {
										str5 += '<th class="text-center">x4</th>';
									}
								});
								str5 += '</tr>';
								// END header baris 5
							str5 = str5 + '</thead>';

							$('#tabel_bawah').html(str5);
							// $('#h3_bawah').text("Checkclock bawah");

							$('#tblhtsprrd5').DataTable({
								paging: false,          // Disable pagination
								searching: false,       // Disable search
								info: false,            // Disable "Showing X of Y entries" information
								lengthChange: false,    // Disable "Show X entries" dropdown
								responsive: false,
								scrollCollapse: true,
								data: json.data,
								columns: json.columns5,
								columnDefs: [
									{ targets: '_all', className: 'text-right' } // Apply text-right class to all columns
									,{
										targets: [10,11],
										className: 'text-center',
										render: function (data){
											if (data == 0){
												return '<i class="fa fa-remove text-danger"></i>';
											}else if(data == 1){
												return '<i class="fa fa-check text-navy"></i>';
											}
										}
									},
								],
								buttons: [
								],
								rowCallback: function (row, data, index) {

								}
							});
						} else {
							$('#tabel_atas').html('<h1 style="color: red; text-align: center;"><b>REPORT PRESENSI BELUM DI GENERATE!!!<b></h1><br><br><br>');
						}

						var str2 = '<table id="tblhtsprrd2" class="table table-striped table-bordered">';
						
						if ($.fn.dataTable.isDataTable('#tblhtsprrd2')) {
							$('#tblhtsprrd2').DataTable().clear();
							$('#tblhtsprrd2').DataTable().destroy();
							$('#tblhtsprrd2 tbody').empty();
							$('#tblhtsprrd2 thead').empty();
						}
						str2 += '<thead>';
							str2 += '<tr>';
							$.each(json.columns2, function (k, colObj2) {
								// BEGIN render column name
								if (colObj2.name == 'jam') {
									str2 += '<th class="text-center">Jam</th>';
								} else if (colObj2.name == 'tanggal') {
									str2 += '<th class="text-center">Tanggal</th>';
								} else if (colObj2.name == 'mesin') {
									str2 += '<th class="text-center">Mesin</th>';
								}
							});
							str2 += '</tr>';
							// END header baris 2
						str2 = str2 + '</thead>';

						$('#tabel_riwayat').html(str2);
						$('#h3_riwayat').text("Riwayat Checkclock");

						$('#tblhtsprrd2').DataTable({
							paging: false,
							searching: false,
							info: false,
							lengthChange: false,
							responsive: false,
							scrollY: '125px',
							scrollCollapse: true,
							data: json.data2,
							columns: json.columns2,
							buttons: button_add,
							rowCallback: function (row, data, index) {
								// Your row callback code here
							}
						});
						// console.log('id_hemxxmh_old'+json.data[0].id_hemxxmh); 
						id_hemxxmh_old = json.data7.id_hemxxmh;
						edthtsprtd.field('htsprtd.id_hemxxmh').val(id_hemxxmh_old);

						var str3 = '<table id="tblhtsprrd3" class="table table-striped table-bordered">';
						
						if ($.fn.dataTable.isDataTable('#tblhtsprrd3')) {
							$('#tblhtsprrd3').DataTable().clear();
							$('#tblhtsprrd3').DataTable().destroy();
							$('#tblhtsprrd3 tbody').empty();
							$('#tblhtsprrd3 thead').empty();
						}
						str3 += '<thead>';
							str3 += '<tr>';
							$.each(json.columns3, function (k, colObj3) {
								// BEGIN render column name
								if (colObj3.name == 'jam') {
									str3 += '<th class="text-center">Jam</th>';
								} else if (colObj3.name == 'tanggal') {
									str3 += '<th class="text-center">Tanggal</th>';
								} else if (colObj3.name == 'mesin') {
									str3 += '<th class="text-center">Mesin</th>';
								}
							});
							str3 += '</tr>';
							// END header baris 3
						str3 = str3 + '</thead>';

						$('#tabel_makan').html(str3);
						$('#h3_makan').text("Checkclock Makan");

						$('#tblhtsprrd3').DataTable({
							paging: false,
							searching: false,
							info: false,
							lengthChange: false,
							responsive: false,
							scrollY: '125px',
							scrollCollapse: true,
							data: json.data3,
							columns: json.columns3,
							buttons: buttons,
							rowCallback: function (row, data, index) {

							}
						});

						var str4 = '<table id="tblhtsprrd4" class="table table-striped table-bordered">';
						
						if ($.fn.dataTable.isDataTable('#tblhtsprrd4')) {
							$('#tblhtsprrd4').DataTable().clear();
							$('#tblhtsprrd4').DataTable().destroy();
							$('#tblhtsprrd4 tbody').empty();
							$('#tblhtsprrd4 thead').empty();
						}
						str4 += '<thead>';
							str4 += '<tr>';
							$.each(json.columns4, function (k, colObj4) {
								// BEGIN render column name
								if (colObj4.name == 'jam') {
									str4 += '<th class="text-center">Jam</th>';
								} else if (colObj4.name == 'tanggal') {
									str4 += '<th class="text-center">Tanggal</th>';
								} else if (colObj4.name == 'mesin') {
									str4 += '<th class="text-center">Mesin</th>';
								}
							});
							str4 += '</tr>';
							// END header baris 4
						str4 = str4 + '</thead>';

						$('#tabel_istirahat').html(str4);
						$('#h3_istirahat').text("Checkclock Istirahat");

						$('#tblhtsprrd4').DataTable({
							paging: false,          // Disable pagination
							searching: false,       // Disable search
							info: false,            // Disable "Showing X of Y entries" information
							lengthChange: false,    // Disable "console.log X entries" dropdown
							responsive: false,
							scrollCollapse: true,
							data: json.data4,
							columns: json.columns4,
							buttons: buttons,
							rowCallback: function (row, data, index) {

							}
						});

						if(notifyprogress != ''){
							notifyprogress.close();
						}
					} else {
						$('#report').hide();
						$('#no_jadwal').show();
						if(notifyprogress != ''){
							notifyprogress.close();
						}
					}
				}
			} );
		}

		$(document).ready(function() {
			$('#prevButton').hide();
			$('#nextButton').hide();
			$('#report').hide();
			
			const prevButton = document.getElementById("prevButton");
        	const nextButton = document.getElementById("nextButton");
			id_hemxxmh_old = id_hem_get;
			// console.log(id_hemxxmh_old);
			
            $('#report').hide();

			$('#select_hemxxmh').select2('open');

			setTimeout(function() {
				$('#select_hemxxmh').select2('close');
			}, 5);

			$("#clearSelect").click(function () {
				$("#select_hemxxmh").val(null).trigger('change');
				id_hem_get = 0;
			});

			let counter = 0;

			nextButton.addEventListener("click", function() {
				counter++;
				$("#frmhtsprrd").submit();
			});

			prevButton.addEventListener("click", function() {
				counter--;
				$("#frmhtsprrd").submit();
			});

			$("#frmhtsprrd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
	
				},
				submitHandler: function(frmhtsprrd) {
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
					
					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					}, {
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					generateTable(counter);
					// addCeklok();
					return false;
				}
			});

			if (tanggal_get != '' && id_hem_get != 0) {
				id_hemxxmh = id_hem_get;
				start_date = tanggal_get;
				generateTable(counter);
			}
			// addCeklok();
        
///////// START EDIT SCHEDULE ////////////////
			//start datatables editor
			edthtssctd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htssctd/htssctd_presensi.php",
					type: 'POST',
					data: function (d){
					}
				},
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
					},	
					{
						label: "Nama",
						name: "emp_jadwal",
						type: "readonly"
					},	
					{
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
					},	
					{
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
					},	
					{
						label: "Awal T1 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_t1",
						type: "datetime",
						def: function () {  
							const currentDate = new Date();
							currentDate.setHours(currentDate.getHours() - 2);
							return currentDate;
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Awal <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal",
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
						label: "Awal T2 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_t2",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Akhir T1 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_t1",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Akhir <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir",
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
						label: "Akhir T2 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_t2",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Tanggal Awal Istirahat <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_istirahat",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Tanggal Akhir Istirahat <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_istirahat",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Keterangan",
						name: "htssctd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtssctd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd.field('start_on').val(start_on);

			});

			edthtssctd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtssctd.dependent( 'htssctd.tanggal', function ( val, data, callback ) {
				id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
				
				shift(edthtssctd);
				
				if (id_htsxxmh == 1) {
					var tanggal = edthtssctd.field('htssctd.tanggal').val();
					edthtssctd.field('htssctd.tanggaljam_awal_t1').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_awal_t2').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_akhir_t1').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_akhir_t2').val(tanggal+' 00:00');
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd.dependent( 'htssctd.id_htsxxmh', function ( val, data, callback ) {
				shift(edthtssctd);
				
				if (val == 1) {
					var tanggal = edthtssctd.field('htssctd.tanggal').val();
					edthtssctd.field('htssctd.tanggaljam_awal_t1').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_awal_t2').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_akhir_t1').val(tanggal+' 00:00');
					edthtssctd.field('htssctd.tanggaljam_akhir_t2').val(tanggal+' 00:00');
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd.dependent( 'htssctd.tanggaljam_awal', function ( val, data, callback ) {
				var tanggal_awal = edthtssctd.field('htssctd.tanggaljam_awal').val();
      		 	id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
				   
					
				if (id_htsxxmh != 1) {
					akhir = moment(tanggal_awal).add(2, 'hour').format('DD MMM YYYY HH:mm');
					awal = moment(tanggal_awal).subtract(2, 'hour').format('DD MMM YYYY HH:mm');
					
					edthtssctd.field('htssctd.tanggaljam_awal_t1').val(awal);
					edthtssctd.field('htssctd.tanggaljam_awal_t2').val(akhir);
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd.dependent( 'htssctd.tanggaljam_akhir', function ( val, data, callback ) {
				var tanggal_akhir = edthtssctd.field('htssctd.tanggaljam_akhir').val();
				id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
				// tanggaljam_akhir_t2 = edthtssctd.field('htssctd.tanggaljam_akhir_t2').val();
				
				if (id_htsxxmh != 1) {
						akhir = moment(tanggal_akhir).add(5, 'hour').format('DD MMM YYYY HH:mm');
						awal = moment(tanggal_akhir).subtract(5, 'hour').format('DD MMM YYYY HH:mm');
						edthtssctd.field('htssctd.tanggaljam_akhir_t1').val(awal);
						edthtssctd.field('htssctd.tanggaljam_akhir_t2').val(akhir);
				}
				return {}
			}, {event: 'keyup change'});

            edthtssctd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					
					// BEGIN of validasi htssctd.id_htsxxmh
					if ( ! edthtssctd.field('htssctd.id_htsxxmh').isMultiValue() ) {
						id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
						if(!id_htsxxmh || id_htsxxmh == ''){
							edthtssctd.field('htssctd.id_htsxxmh').error( 'Wajib diisi!' );
						}
					}

					// BEGIN of validasi htssctd.tanggal
					if ( ! edthtssctd.field('htssctd.tanggal').isMultiValue() ) {
						tanggal = edthtssctd.field('htssctd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtssctd.field('htssctd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggal

					// BEGIN of validasi htssctd.tanggaljam_awal_istirahat
					if ( ! edthtssctd.field('htssctd.tanggaljam_awal_istirahat').isMultiValue() ) {
						tanggaljam_awal_istirahat = edthtssctd.field('htssctd.tanggaljam_awal_istirahat').val();
						if(!tanggaljam_awal_istirahat || tanggaljam_awal_istirahat == ''){
							edthtssctd.field('htssctd.tanggaljam_awal_istirahat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggaljam_awal_istirahat

					// BEGIN of validasi htssctd.tanggaljam_akhir_istirahat
					if ( ! edthtssctd.field('htssctd.tanggaljam_akhir_istirahat').isMultiValue() ) {
						tanggaljam_akhir_istirahat = edthtssctd.field('htssctd.tanggaljam_akhir_istirahat').val();
						if(!tanggaljam_akhir_istirahat || tanggaljam_akhir_istirahat == ''){
							edthtssctd.field('htssctd.tanggaljam_akhir_istirahat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggaljam_akhir_istirahat
					
				}
				
				if ( edthtssctd.inError() ) {
					return false;
				}
			});
			
			edthtssctd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd.field('finish_on').val(finish_on);
			});

			$('#edit_jadwal').on('click', '#jadwal', function (e) {
				e.preventDefault();
				var id = $(this).data('id');
				var empsjadwal = $(this).data('empsjadwal');
				// console.log('row_ = '+id);
				val_edit('htssctd', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edthtssctd.on( 'preOpen', function( e, mode, action ) {
					// console.log('peg = '+empsjadwal);
					edthtssctd.field('emp_jadwal').val(empsjadwal);
					id_htsxxmh_old = edit_val.id_htsxxmh;
					edthtssctd.field('htssctd.id_htsxxmh').val(id_htsxxmh_old);
					// console.log(edit_val.id_htsxxmh);
					edthtssctd.field('htssctd.tanggal').val(moment(edit_val.tanggal).format('DD MMM YYYY') );
					edthtssctd.field('htssctd.tanggaljam_akhir_t2').val(moment(edit_val.tanggaljam_akhir_t2).format('DD MMM YYYY HH:mm') );
				});

				edthtssctd.title('Edit Schedule').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
							generateTable(counter);
						}
					}
				).edit(id);
			});
///////////// END OF JADWAL //////////////


/////////// START OF CEKLOK ////////////

			edthtsprtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsprtd/htsprtd_report.php",
					type: 'POST',
					data: function (d){
					}
				},
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
							{ "label": "Istirahat Manual", "value": "istirahat manual" },
							// { "label": "Makan", "value": "makan" },
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
						id: "peg_ceklok",
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
									var options = data.results.map(function (result) {
										return {
											id: result.id,
											text: result.text
										};
									});

									//add by ferry agar auto select 07 sep 23
									if (params.page && params.page === 1) {
										$('#peg_ceklok').empty().select2({ data: options });
									} else {
                                        $('#peg_ceklok').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
									}

									return {
										results: options,
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
							return moment($('#start_date').val()).format('DD MMM YYYY'); 
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
				 console.log(id_hemxxmh_old);
				edthtsprtd.field('htsprtd.id_hemxxmh').val(id_hemxxmh_old);
				edthtsprtd.field('htsprtd.id_hemxxmh').disable();
				
			});

			edthtsprtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				$('#peg_ceklok').select2('open');

				setTimeout(function() {
					$('#peg_ceklok').select2('close');
				}, 5);
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
					// unikMakan(jam);
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
				// console.log(kode_finger);
				edthtsprtd.field('htsprtd.kode').val(kode_finger);

				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('finish_on').val(finish_on);
			});

			edthtsprtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				generateTable(counter);
			});
//////////// END OF CEKLOK /////////////

/////////// START OF CEKLOK ////////////

			edthtssctd_add = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htssctd/htssctd_presensi.php",
					type: 'POST',
					data: function (d){
					}
				},
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
					},	
					{
						label: "htssctd.kode",
						name: "htssctd.kode",
                        type: "hidden"
					},
					{
						label: "Employee <sup class='text-danger'>*<sup>",
						name: "htssctd.id_hemxxmh",
						type: "select2",
						id: "peg_ceklok",
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
									var options = data.results.map(function (result) {
										return {
											id: result.id,
											text: result.text
										};
									});

									//add by ferry agar auto select 07 sep 23
									if (params.page && params.page === 1) {
										$('#peg_ceklok').empty().select2({ data: options });
									} else {
                                        $('#peg_ceklok').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
									}

									return {
										results: options,
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
						name: "htssctd.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#start_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
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
					},	
					{
						label: "Awal T1 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_t1",
						type: "datetime",
						def: function () {  
							const currentDate = new Date();
							currentDate.setHours(currentDate.getHours() - 2);
							return currentDate;
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Awal <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal",
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
						label: "Awal T2 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_t2",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Akhir T1 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_t1",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Akhir <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir",
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
						label: "Akhir T2 <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_t2",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Tanggal Awal Istirahat <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_awal_istirahat",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Tanggal Akhir Istirahat <sup class='text-danger'>*<sup>",
						name: "htssctd.tanggaljam_akhir_istirahat",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY HH:mm'
					},
					{
						label: "Keterangan",
						name: "htssctd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtssctd_add.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtssctd_add.field('start_on').val(start_on);
				 console.log(id_hemxxmh_old);
				edthtssctd_add.field('htssctd.id_hemxxmh').val(id_hemxxmh_old);
				edthtssctd_add.field('htssctd.id_hemxxmh').disable();
				
			});

			edthtssctd_add.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				$('#peg_ceklok').select2('open');

				setTimeout(function() {
					$('#peg_ceklok').select2('close');
				}, 5);
			});

			edthtssctd_add.dependent( 'htssctd.tanggal', function ( val, data, callback ) {
				id_htsxxmh = edthtssctd_add.field('htssctd.id_htsxxmh').val();
				
				shift(edthtssctd_add);
				
				if (id_htsxxmh == 1) {
					var tanggal = edthtssctd_add.field('htssctd.tanggal').val();
					edthtssctd_add.field('htssctd.tanggaljam_awal_t1').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_awal_t2').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_akhir_t1').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_akhir_t2').val(tanggal+' 00:00');
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd_add.dependent( 'htssctd.id_htsxxmh', function ( val, data, callback ) {
				shift(edthtssctd_add);
				
				if (val == 1) {
					var tanggal = edthtssctd_add.field('htssctd.tanggal').val();
					edthtssctd_add.field('htssctd.tanggaljam_awal_t1').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_awal_t2').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_akhir_t1').val(tanggal+' 00:00');
					edthtssctd_add.field('htssctd.tanggaljam_akhir_t2').val(tanggal+' 00:00');
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd_add.dependent( 'htssctd.tanggaljam_awal', function ( val, data, callback ) {
				var tanggal_awal = edthtssctd_add.field('htssctd.tanggaljam_awal').val();
      		 	id_htsxxmh = edthtssctd_add.field('htssctd.id_htsxxmh').val();
				   
					
				if (id_htsxxmh != 1) {
					akhir = moment(tanggal_awal).add(2, 'hour').format('DD MMM YYYY HH:mm');
					awal = moment(tanggal_awal).subtract(2, 'hour').format('DD MMM YYYY HH:mm');
					
					edthtssctd_add.field('htssctd.tanggaljam_awal_t1').val(awal);
					edthtssctd_add.field('htssctd.tanggaljam_awal_t2').val(akhir);
				}
				return {}
			}, {event: 'keyup change'});

			edthtssctd_add.dependent( 'htssctd.tanggaljam_akhir', function ( val, data, callback ) {
				var tanggal_akhir = edthtssctd_add.field('htssctd.tanggaljam_akhir').val();
				id_htsxxmh = edthtssctd_add.field('htssctd.id_htsxxmh').val();
				// tanggaljam_akhir_t2 = edthtssctd_add.field('htssctd.tanggaljam_akhir_t2').val();
				
				if (id_htsxxmh != 1) {
						akhir = moment(tanggal_akhir).add(5, 'hour').format('DD MMM YYYY HH:mm');
						awal = moment(tanggal_akhir).subtract(5, 'hour').format('DD MMM YYYY HH:mm');
						edthtssctd_add.field('htssctd.tanggaljam_akhir_t1').val(awal);
						edthtssctd_add.field('htssctd.tanggaljam_akhir_t2').val(akhir);
				}
				return {}
			}, {event: 'keyup change'});

            edthtssctd_add.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					
					// BEGIN of validasi htssctd.id_htsxxmh
					if ( ! edthtssctd_add.field('htssctd.id_htsxxmh').isMultiValue() ) {
						id_htsxxmh = edthtssctd_add.field('htssctd.id_htsxxmh').val();
						if(!id_htsxxmh || id_htsxxmh == ''){
							edthtssctd_add.field('htssctd.id_htsxxmh').error( 'Wajib diisi!' );
						}
					}

					// BEGIN of validasi htssctd.tanggal
					if ( ! edthtssctd_add.field('htssctd.tanggal').isMultiValue() ) {
						tanggal = edthtssctd_add.field('htssctd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtssctd_add.field('htssctd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggal

					// BEGIN of validasi htssctd.tanggaljam_awal_istirahat
					if ( ! edthtssctd_add.field('htssctd.tanggaljam_awal_istirahat').isMultiValue() ) {
						tanggaljam_awal_istirahat = edthtssctd_add.field('htssctd.tanggaljam_awal_istirahat').val();
						if(!tanggaljam_awal_istirahat || tanggaljam_awal_istirahat == ''){
							edthtssctd_add.field('htssctd.tanggaljam_awal_istirahat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggaljam_awal_istirahat

					// BEGIN of validasi htssctd.tanggaljam_akhir_istirahat
					if ( ! edthtssctd_add.field('htssctd.tanggaljam_akhir_istirahat').isMultiValue() ) {
						tanggaljam_akhir_istirahat = edthtssctd_add.field('htssctd.tanggaljam_akhir_istirahat').val();
						if(!tanggaljam_akhir_istirahat || tanggaljam_akhir_istirahat == ''){
							edthtssctd_add.field('htssctd.tanggaljam_akhir_istirahat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htssctd.tanggaljam_akhir_istirahat
					
				}
				
				if ( edthtssctd_add.inError() ) {
					return false;
				}
			});

			edthtssctd_add.on( 'postSubmit', function (e, json, data, action, xhr) {
				generateTable(counter);
			});
			
			$('#edit_jadwal').on('click', '#buat_jadwal', function (e) {
				e.preventDefault();
				edthtssctd_add.title('Create Schedule').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
							generateTable(counter);
						}
					}
				).create();
			});
//////////// END OF CEKLOK /////////////
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
