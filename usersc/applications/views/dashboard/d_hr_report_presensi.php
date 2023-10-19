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
                            <select class="form-control" id="select_hemxxmh" name="select_hemxxmh"></select>
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

<div class="row" id="report">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<h3 class="text-center" id="nama_peg"></h3>
				<br>
                <div class="row">
                    <div class="col-md-5">
                        <h3 id="tanggal"></h3>
                        <h3 id="jadwal"></h3>
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

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tblhtsprrd, show_inactive_status_htsprrd = 0;
		// ------------- end of default variable
		var notifyprogress = '';
		var id_hemxxmh_old = 0;
		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var tanggal_get = "<?php echo $awal ?>";
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

		function generateTable(counter) {
            $('#report').show();
			$.ajax( {
				url: "../../models/dashboard/d_hr_report_presensi.php",
				dataType: 'json',
				type: 'POST',
				data: {
					start_date: start_date,
					counter: counter,
					id_hemxxmh: id_hemxxmh
				},
				success: function ( json ) {
					if(json.data.length > 0 && json.data[0].id_hemxxmh != null){
						
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
							// console.log(json.data5);
							if (counter == json.data5) {
								$('#nextButton').hide();
							} else {
								$('#nextButton').show();
							}
						}

						var page_now = parseInt(counter) + 1; 
						var page_total = parseInt(json.data5) + 1; 


						$('#tanggal').html("Tanggal &nbsp; &nbsp; &nbsp; &nbsp;: " + json.data[0].tanggal);
						$('#jadwal').html("Jadwal &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: " + json.data[0].st_jadwal);
						$('#keterangan').html("Keterangan : " + json.data[0].keterangan);

						$('#dep').html(" : " + json.data7.dep);
						$('#kmj').html(" : " + json.data7.kmj);
						$('#kelompok').html(" : " + json.data7.kelompok);
						$('#stat').html(" : " + json.data7.stat);
						$('#STATUS').html(" : " + json.data7.STATUS);
						$('#lev').html(" : " + json.data7.lev);
						// $('#jadwal').html("Jadwal &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: " + json.data7[0].st_jadwal);
						// $('#keterangan').html("Keterangan : " + json.data7[0].keterangan);

						$('#nama_peg').text(json.data7.nama);
						$('#paging').text( page_now + " / " + page_total);
						$('#h3_riwayat').text("Riwayat Checkclock");

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
                            str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;">Status IN</th>';
                            str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;" >Status OUT</th>';
                            str1 += '<th class="text-center" rowspan="3" style="vertical-align: middle;" >Kondite</th>';
                            str1 += '<th class="text-center" colspan="2" rowspan="1">SPKL</th>';
                            str1 += '</tr>';

                            str1 += '<tr>';
                            str1 += '<th class="text-center" colspan="2" rowspan="1">' + json.data[0].kode_spkl + '</th>';
                            str1 += '</tr>';

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
							buttons: [
								{
									extend: 'collection',
									text: '<i class="fa  fa-wrench">',
									className: 'btn btn-white',
									autoClose: true,
									buttons: [
										{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
										{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
									]
								},
								{
									extend: 'colvis',
									columns: colvisCount,
									columnText: function (dt, idx, title) {
										return title;
									},
									text: '<i class="fa fa-eye-slash"></i>',
									className: 'btn btn-white',
									titleAttr: 'Show / Hide Column'
								},
							],
							rowCallback: function (row, data, index) {

							}
						});

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
								paging: false,          // Disable pagination
								searching: false,       // Disable search
								info: false,            // Disable "Showing X of Y entries" information
								lengthChange: false,    // Disable "Show X entries" dropdown
								responsive: false,
								scrollY: '125px',       // Set the scrollY option
								scrollCollapse: true,
								data: json.data2,
								columns: json.columns2,
								buttons: [
								{
									extend: 'collection',
									text: '<i class="fa  fa-wrench">',
									className: 'btn btn-white',
									autoClose: true,
									buttons: [
										{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
										{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
									]
								}
							],
							rowCallback: function (row, data, index) {

							}
						});

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
							paging: false,          // Disable pagination
							searching: false,       // Disable search
							info: false,            // Disable "Showing X of Y entries" information
							lengthChange: false,    // Disable "Show X entries" dropdown
							responsive: false,
							scrollCollapse: true,
							data: json.data3,
							columns: json.columns3,
							buttons: [
								{
									extend: 'collection',
									text: '<i class="fa  fa-wrench">',
									className: 'btn btn-white',
									autoClose: true,
									buttons: [
										{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
										{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
									]
								}
							],
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
							lengthChange: false,    // Disable "Show X entries" dropdown
							responsive: false,
							scrollCollapse: true,
							data: json.data4,
							columns: json.columns4,
							buttons: [
								{
									extend: 'collection',
									text: '<i class="fa  fa-wrench">',
									className: 'btn btn-white',
									autoClose: true,
									buttons: [
										{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '', titleAttr: 'Copy' },
										{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '', titleAttr: 'Export to Excel' }
									]
								}
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
							],
							buttons: [
							],
							rowCallback: function (row, data, index) {

							}
						});

						if(notifyprogress != ''){
							notifyprogress.close();
						}

					}else{
						notifyprogress.close();
						notifyprogress = $.notify({
							message: 'Tidak ada data pada tanggal tersebut!'
						},{
							z_index: 9999,
							allow_dismiss: false,
							type: 'danger',
							delay: 3
						});
					}
				}
			} );
		}

		$(document).ready(function() {
			const prevButton = document.getElementById("prevButton");
        	const nextButton = document.getElementById("nextButton");

            $('#report').hide();
			
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
					return false;
				}
			});

			if (tanggal_get != '' && id_hem_get != 0) {
				$("#frmhtsprrd").submit();
			}
			
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
