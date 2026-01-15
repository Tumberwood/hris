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

						/* =========================
						BARIS HEADER KE-1
						========================= */
						str += '<tr>';

						// kolom normal (rowspan 2)
						const normalCols = [
							'kode_finger','hemxxmh_data','hodxxmh_nama','hetxxmh_nama',
							'hr','hari_kerja_efektif','hk','persen',
							'hk_tok','late_1','st_off','st_nj','hl','ct','cb','sd','kk','al','it'
						];

						const colsMeta = {
							kode_finger: { label: 'Kode Finger', tooltip: 'Kode absensi karyawan' },
							hemxxmh_data: { label: 'Karyawan', tooltip: 'Kode dan nama karyawan' },
							hodxxmh_nama: { label: 'Department', tooltip: 'Departemen karyawan' },
							hetxxmh_nama: { label: 'Jabatan', tooltip: 'Jabatan karyawan' },
							hr: { label: 'Hari Kalender', tooltip: 'Jumlah hari kalender pada periode terpilih' },
							hari_kerja_efektif: { label: 'Hari Kerja Efektif', tooltip: 'hari kerja efektif' },
							persen: { label: 'Persen Kerja Efektif', tooltip: 'Persentase kehadiran' },
							hk: { label: 'Hari Hadir', tooltip: 'HK + Late 1' },
							hk_tok: { label: 'HK', tooltip: 'Hadir kerja normal' },
							late_1: { label: 'Late 1', tooltip: 'Terlambat tidak kena potongan' },
							st_off: { label: 'OFF', tooltip: 'Hari libur / off' },
							st_nj: { label: 'NJ', tooltip: 'Jadwal Belum Dibuat' },
							hl: { label: 'HL', tooltip: 'Hari Libur Nasional' },
							ct: { label: 'CT', tooltip: 'Cuti Tahunan' },
							cb: { label: 'CB', tooltip: 'Cuti Bersama' },
							sd: { label: 'SD', tooltip: 'Sakit dengan surat dokter' },
							kk: { label: 'KK', tooltip: 'Kecelakaan Kerja' },
							al: { label: 'AL', tooltip: 'Alpa / tanpa izin' },
							it: { label: 'IT', tooltip: 'Izin terlambat' }
						};

						$.each(json.columns, function (k, colObj) {

							if (normalCols.includes(colObj.name)) {

								const meta = colsMeta[colObj.name] || {
								label: colObj.name,
								tooltip: null
								};

								const tooltipAttr = meta.tooltip
								? `data-toggle="tooltip" data-placement="top" title="${meta.tooltip}"`
								: '';

								str += `<th rowspan="2" ${tooltipAttr}>${meta.label}</th>`;
							}

							// GROUP HEADER
							if (colObj.name === 'SK') {
								str += '<th colspan="6" class="text-center">Absen Khusus</th>';
							}

							if (colObj.name === 'CK') {
								str += '<th colspan="8" class="text-center">Absen Lain</th>';
							}
						});

						str += '</tr>';

						/* =========================
						BARIS HEADER KE-2
						========================= */
						str += '<tr>';

						const absenKhusus = ['SK','SPSI','DL','S3','LB','LR'];
						const absenLain   = ['CK','KAK','KOT','PS','IMG','PKB','KKR','KM'];

						$.each(json.columns, function (k, colObj) {

							if (absenKhusus.includes(colObj.name) || absenLain.includes(colObj.name)) {
								str += `<th>${colObj.name}</th>`;
							}
						});

						str += '</tr>';
						str += '</thead>';

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
							columnDefs: [
								{
									targets: '_all', // Apply to all columns
									render: function (data, type, row, meta) {
										if (![0, 1, 2, 3].includes(meta.col)) {
											return type === 'display' ? Math.floor(data) : data;
										}
										return data;									
									}
								}
							],
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
			$('[data-toggle="tooltip"]').tooltip();
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
