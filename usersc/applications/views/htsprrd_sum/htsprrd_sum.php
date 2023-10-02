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

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<table id="tblhtsprrd" class="table table-striped table-bordered table-hover wrap" width="100%">
				</table>
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

		function generateTable() {
			
			$.ajax( {
				url: "../../models/htsprrd_sum/htsprrd_sum.php",
				dataType: 'json',
				type: 'POST',
				data: {
					start_date: start_date,
					end_date: end_date
				},
				success: function ( json ) {
					if(json.data.length > 0){
						
						if ( $.fn.dataTable.isDataTable( '#tblhtsprrd' ) ) {
							$('#tblhtsprrd').DataTable().clear();
							$('#tblhtsprrd').DataTable().destroy(); 
							$('#tblhtsprrd tbody').empty();
							$('#tblhtsprrd thead').empty();
						}

						str = '<thead>';
							// BEGIN header baris 1
							str = str + '<tr>';
							$.each(json.columns, function (k, colObj) {
								// BEGIN render column name
								if( colObj.name == 'kode_finger' ){
									str = str + '<th>Kode Finger</th>';
								}else if( colObj.name == 'hemxxmh_data' ){
									str = str + '<th>Karyawan</th>';
								}else if( colObj.name == 'hodxxmh_nama' ){
									str = str + '<th>Department</th>';
								}else if( colObj.name == 'hetxxmh_nama' ){
									str = str + '<th>Jabatan</th>';
								}else if( colObj.name == 'hr' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Hari Periode Terpilih">HR</th>';
								}else if( colObj.name == 'hk' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Hari Kerja dimana Karyawan Masuk dan Valid">HK</th>';
								}else if( colObj.name == 'st_off' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Hari Jadwal Off">OFF</th>';
								}else if( colObj.name == 'st_nj' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Hari yang belum dibuatkan Jadwal">NJ</th>';
								}else if( colObj.name == 'hl' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Libur Nasional">HL</th>';
								}else if( colObj.name == 'ct' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Cuti Tahunan">CT</th>';
								}else if( colObj.name == 'cb' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Cuti Bersama">CB</th>';
								}else if( colObj.name == 'sd' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Sakit Dengan Surat Dokter Resmi">SD</th>';
								}else if( colObj.name == 'sk' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Sakit">SK</th>';
								}else if( colObj.name == 'al' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Alpa" class="text-danger">AL</th>';
								}else if( colObj.name == 'ip' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Izin Pribadi">IP</th>';
								}else if( colObj.name == 'absen_khusus' ){
									str = str + '<th data-toggle="tooltip" data-placement="top" title="Jumlah Izin Khusus">Absen Khusus</th>';
								}
							});
							str = str + '</tr>';
							// END header baris 1
						str = str + '</thead>';

						$('#tblhtsprrd').html(str);
						
						$('#tblhtsprrd').DataTable({
							scrollX: true,
							responsive: false,
							scrollCollapse: true,
							fixedColumns:   {
								left: 3
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
										{ extend: "copy", text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', className: '',titleAttr: 'Copy'},
										{ extend: "excel", text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', className: '',titleAttr: 'Export to Excel'}
									]
								},
								{ 
									extend: 'colvis',
									columns: colvisCount,
									columnText: function ( dt, idx, title ) {
										return title;
									},
									text: '<i class="fa fa-eye-slash"></i>',
									className: 'btn btn-white',
									titleAttr: 'Show / Hide Column'
								},
							],
							rowCallback: function( row, data, index ) {
								
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
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			// generateTable();

			$("#frmhtsprrd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmhtsprrd) {
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
					// tblhtsprrd.destroy();
					generateTable();
					return false; 
				}
			});
			
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
