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
				<div class="tabs-container">
					<ul class="nav nav-tabs" role="tablist">
						<li><a class="nav-link active" data-toggle="tab" href="#tabhtsprrd"> All</a></li>
						<li id="kbm"><a class="nav-link" data-toggle="tab" href="#tabhtsprrd_kbm"> KBM</a></li>
						<li id="karyawan"><a class="nav-link" data-toggle="tab" href="#tabhtsprrd_karyawan"> Karyawan</a></li>
						<li id="kmj"><a class="nav-link" data-toggle="tab" href="#tabhtsprrd_kmj"> KMJ</a></li>
						<li id="freelance"><a class="nav-link" data-toggle="tab" href="#tabhtsprrd_freelance"> Freelance</a></li>
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
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>

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
												<th class="text-danger">Pot Hari Kerja</th>
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
						<div role="tabpanel" id="tabhtsprrd_kbm" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblhtsprrd_kbm" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th rowspan=2>ID</th>
												<th rowspan=2>Nama</th>
												<th rowspan=2>Link</th>
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
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>

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
												<th class="text-danger">Pot Hari Kerja</th>
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
												<th>Total</th>
												<th id="kbm_makan"></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												
												<th id="kbm_lb"></th>
												<th id="kbm_aw"></th>
												<th id="kbm_ak"></th>
												<th id="kbm_i1"></th>
												<th id="kbm_i2"></th>
												<th id="kbm_i3"></th>
												<th id="kbm_tl"></th>
												
												<th id="kbm_pot_ti"></th>
												<th id="kbm_pot_overtime"></th>
												<th id="kbm_overtime"></th>
												<th id="kbm_pot_hk"></th>
												<th id="kbm_pot_jam"></th>
												<!-- <th id="s_hk"></th> -->

											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabhtsprrd_karyawan" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblhtsprrd_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th rowspan=2>ID</th>
												<th rowspan=2>Nama</th>
												<th rowspan=2>Link</th>
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
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>

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
												<th class="text-danger">Pot Hari Kerja</th>
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
												<th>Total</th>
												<th id="karyawan_makan"></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												
												<th id="karyawan_lb"></th>
												<th id="karyawan_aw"></th>
												<th id="karyawan_ak"></th>
												<th id="karyawan_i1"></th>
												<th id="karyawan_i2"></th>
												<th id="karyawan_i3"></th>
												<th id="karyawan_tl"></th>
												
												<th id="karyawan_pot_ti"></th>
												<th id="karyawan_pot_overtime"></th>
												<th id="karyawan_overtime"></th>
												<th id="karyawan_pot_hk"></th>
												<th id="karyawan_pot_jam"></th>
												<!-- <th id="s_hk"></th> -->

											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabhtsprrd_kmj" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblhtsprrd_kmj" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th rowspan=2>ID</th>
												<th rowspan=2>Nama</th>
												<th rowspan=2>Link</th>
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
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>

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
												<th class="text-danger">Pot Hari Kerja</th>
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
												<th>Total</th>
												<th id="kmj_makan"></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												
												<th id="kmj_lb"></th>
												<th id="kmj_aw"></th>
												<th id="kmj_ak"></th>
												<th id="kmj_i1"></th>
												<th id="kmj_i2"></th>
												<th id="kmj_i3"></th>
												<th id="kmj_tl"></th>
												
												<th id="kmj_pot_ti"></th>
												<th id="kmj_pot_overtime"></th>
												<th id="kmj_overtime"></th>
												<th id="kmj_pot_hk"></th>
												<th id="kmj_pot_jam"></th>
												<!-- <th id="s_hk"></th> -->

											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabhtsprrd_freelance" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblhtsprrd_freelance" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th rowspan=2>ID</th>
												<th rowspan=2>Nama</th>
												<th rowspan=2>Link</th>
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
												<th rowspan=2 class="text-danger">Potongan Makan</th>
												
												<th colspan=2>Lembur Libur</th>
												<th colspan=2>Lembur Awal</th>
												<th colspan=2>Lembur Akhir</th>
												<th class="text-center" colspan=7>Durasi Lembur (Jam)</th>
												<th class="text-center text-danger" colspan=3>Potongan Jam Overtime</th>
												<th class="text-center text-danger" colspan=2>Potongan Jam</th>

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
												<th class="text-danger">Pot Hari Kerja</th>
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
												<th>Total</th>
												<th id="freelance_makan"></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												
												<th id="freelance_lb"></th>
												<th id="freelance_aw"></th>
												<th id="freelance_ak"></th>
												<th id="freelance_i1"></th>
												<th id="freelance_i2"></th>
												<th id="freelance_i3"></th>
												<th id="freelance_tl"></th>
												
												<th id="freelance_pot_ti"></th>
												<th id="freelance_pot_overtime"></th>
												<th id="freelance_overtime"></th>
												<th id="freelance_pot_hk"></th>
												<th id="freelance_pot_jam"></th>
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

		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var user_id = <?php echo $_SESSION['user'] ?>;
		var str_arr_ha_heyxxmh = <?php echo "'" . $_SESSION['str_arr_ha_heyxxmh'] . "'" ?>;
		$('.collapse-link').prop('disabled', true);

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		// $('#start_date').datepicker('setDate', "15 Dec 2023");
		// $('#end_date').datepicker('setDate', "15 Dec 2023");
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
			$('.collapse-link').prop('disabled', true);
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
			// var arr_ha_heyxxmh = str_arr_ha_heyxxmh.split(',').map(Number);
			// if (arr_ha_heyxxmh.includes(1) && arr_ha_heyxxmh.includes(2)) {
			// 	// Tidak melakukan hide karena berisi kedua angka
			// } else {
			// 	if (arr_ha_heyxxmh.includes(1)) {
			// 		$('#kbm').hide();
			// 		$('#kmj').hide();

			// 		$('#karyawan').show();
			// 		$('#freelance').show();
			// 	}

			// 	if (arr_ha_heyxxmh.includes(1)) {
			// 		$('#karyawan').hide();
			// 		$('#freelance').hide();

			// 		$('#kbm').show();
			// 		$('#kmj').show();
			// 	}
			// }
			
		///start datatables
			tblhtsprrd = $('#tblhtsprrd').DataTable( {
				// select: {
				// 	style: 'multi', // 'single', 'multi', 'os', 'multi+shift'
				// },
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
						targets: [1,3,5,6,12,13,14,15]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,2,4,7,8,9,10,11,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
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
									tblhtsprrd_kbm.ajax.reload(null,false);
									tblhtsprrd_karyawan.ajax.reload(null,false);
									tblhtsprrd_kmj.ajax.reload(null,false);
									tblhtsprrd_freelance.ajax.reload(null,false);
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
									tblhtsprrd_kbm.ajax.reload(null,false);
									tblhtsprrd_karyawan.ajax.reload(null,false);
									tblhtsprrd_kmj.ajax.reload(null,false);
									tblhtsprrd_freelance.ajax.reload(null,false);
									id_htsprrd = [];
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
					all_makan = api.column( 17 ).data().sum();
					all_lb = api.column( 24 ).data().sum();
					all_aw = api.column( 25 ).data().sum();
					all_ak = api.column( 26 ).data().sum();
					all_i1 = api.column( 27 ).data().sum();
					all_i2 = api.column( 28 ).data().sum();
					all_i3 = api.column( 29 ).data().sum();
					all_tl = api.column( 30 ).data().sum();

					all_pot_ti = api.column( 31 ).data().sum();
					all_pot_overtime = api.column( 32 ).data().sum();
					all_overtime = api.column( 33 ).data().sum();
					all_pot_hk = api.column( 34 ).data().sum();
					all_pot_jam = api.column( 35 ).data().sum();

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

				// console.log(kode);
				// console.log(cek);

				var btnPresensiOK = data_multi.every(row =>
    				(kode == "KD" && row.htsprrd.cek == "1") || 
					(row.htsprrd.st_clock_in === "Late 1" && row.htsprrd.status_presensi_in === "Belum ada Izin") ||
					(row.htsprrd.st_clock_in === "Late" && row.htsprrd.status_presensi_in === "Belum ada Izin") ||
					(row.htsprrd.st_clock_out === "EARLY" && row.htsprrd.status_presensi_out === "Belum ada Izin") ||
					(row.htsprrd.st_clock_in === "HK" && row.htsprrd.status_presensi_in === "Belum ada Izin") ||
					(row.htsprrd.st_clock_out === "HK" && row.htsprrd.status_presensi_out === "Belum ada Izin") ||
					(row.hemjbmh.id_heyxxmd == "4" && row.htsprrd.cek == "1") ||
					(row.htsprrd.status_presensi_in === "Jadwal Salah") ||
					(row.htsprrd.id_hemxxmh_select == "130" || row.htsprrd.id_hemxxmh_select == "134")
				);

				tblhtsprrd.button('btnPresensiOK:name').enable(btnPresensiOK);
				
				if (id_htsprrd.length === 0) {
					tblhtsprrd.button('btncekNol:name').disable();
					tblhtsprrd.button('btnPresensiOK:name').disable();
				}
			}

		///// end datatables
			
		///start datatables kbm
			tblhtsprrd_kbm = $('#tblhtsprrd_kbm').DataTable( {
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
						targets: [1,3,5,6,12,13,14,15]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,2,4,7,8,9,10,11,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
					}
				],
				ajax: {
					url: "../../models/htsprrd/htsprrd_kbm.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
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
						$table       = 'tblhtsprrd_kbm';
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
											tblhtsprrd_kbm.ajax.reload(null,false);
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
											tblhtsprrd_kbm.ajax.reload(null,false);
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
									tblhtsprrd_kbm.ajax.reload(null,false);
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
									tblhtsprrd_kbm.ajax.reload(null,false);
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
					kbm_makan = api.column( 17 ).data().sum();
					kbm_lb = api.column( 24 ).data().sum();
					kbm_aw = api.column( 25 ).data().sum();
					kbm_ak = api.column( 26 ).data().sum();
					kbm_i1 = api.column( 27 ).data().sum();
					kbm_i2 = api.column( 28 ).data().sum();
					kbm_i3 = api.column( 29 ).data().sum();
					kbm_tl = api.column( 30 ).data().sum();

					kbm_pot_ti = api.column( 31 ).data().sum();
					kbm_pot_overtime = api.column( 32 ).data().sum();
					kbm_overtime = api.column( 33 ).data().sum();
					kbm_pot_hk = api.column( 34 ).data().sum();
					kbm_pot_jam = api.column( 35 ).data().sum();

					$( '#kbm_makan' ).html( numFormat(kbm_makan) );
					$( '#kbm_lb' ).html( numFormat(kbm_lb) );
					$( '#kbm_aw' ).html( numFormat(kbm_aw) );
					$( '#kbm_ak' ).html( numFormat(kbm_ak) );
					$( '#kbm_i1' ).html( numFormat(kbm_i1) );
					$( '#kbm_i2' ).html( numFormat(kbm_i2) );
					$( '#kbm_i3' ).html( numFormat(kbm_i3) );
					$( '#kbm_tl' ).html( numFormat(kbm_tl) );

					$( '#kbm_pot_ti' ).html( numFormat(kbm_pot_ti) );
					$( '#kbm_pot_overtime' ).html( numFormat(kbm_pot_overtime) );
					$( '#kbm_overtime' ).html( numFormat(kbm_overtime) );
					$( '#kbm_pot_hk' ).html( numFormat(kbm_pot_hk) );
					$( '#kbm_pot_jam' ).html( numFormat(kbm_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhtsprrd_kbm.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd_kbm.button('btncekNol:name').disable();
			tblhtsprrd_kbm.button('btnPresensiOK:name').disable();

			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				var activeTabId = $(e.target).attr('href').substring(1);
				// console.log('Active Tab ID: ' + activeTabId);
				if (activeTabId == 'tabhtsprrd_kbm') {
					tblhtsprrd_kbm.searchPanes.container().appendTo( '#searchPanes1' );
					
				} else {
					tblhtsprrd_kbm.searchPanes.container().detach();
				}
			});


			tblhtsprrd_kbm.on( 'select', function( e, dt, type, indexes ) {
				htsprrd_data    = tblhtsprrd_kbm.row( { selected: true } ).data().htsprrd;
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
					tblhtsprrd_kbm.button('btncekNol:name').enable();
				} else {
					tblhtsprrd_kbm.button('btncekNol:name').disable();
				}
				// if (status_presensi_in == "" && status_presensi_out == "") {
				// 	if (st_clock_in == "No CI" && st_clock_out == "No CO") {
				// 	tblhtsprrd_kbm.button('btncekNol:name').enable();
				// 	} else {
				// 		tblhtsprrd_kbm.button('btncekNol:name').disable();
				// 	}
				// }

				cariKMJ(tblhtsprrd_kbm);
				// console.log(htlxxrh_kode);
				//Cek Apakah mengandung Kode Absen KD
				if (htlxxrh_kode.includes("KD/") && cek == 1) {
				    tblhtsprrd_kbm.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_in == "Late" && status_presensi_in == "Belum ada Izin") {
				    tblhtsprrd_kbm.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_out == "EARLY" && status_presensi_out == "Belum ada Izin") {
				    tblhtsprrd_kbm.button('btnPresensiOK:name').enable();
                }

				if (status_presensi_in == "Jadwal Salah") {
				    tblhtsprrd_kbm.button('btnPresensiOK:name').enable();
                }

				if (id_hemxxmh_select == 130 || id_hemxxmh_select == 134) {
				    tblhtsprrd_kbm.button('btnPresensiOK:name').enable();
                }
				// console.log(htsprrd_data.status_presensi_in);
			} );
			
			tblhtsprrd_kbm.on( 'deselect', function () {
				tblhtsprrd_kbm.button('btncekNol:name').disable();
				tblhtsprrd_kbm.button('btnPresensiOK:name').disable();
			} );
		///// end datatables
			
		///start datatables karyawan
			tblhtsprrd_karyawan = $('#tblhtsprrd_karyawan').DataTable( {
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
						targets: [1,3,5,6,12,13,14,15]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,2,4,7,8,9,10,11,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
					}
				],
				ajax: {
					url: "../../models/htsprrd/htsprrd_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
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
						$table       = 'tblhtsprrd_karyawan';
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
											tblhtsprrd_karyawan.ajax.reload(null,false);
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
											tblhtsprrd_karyawan.ajax.reload(null,false);
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
									tblhtsprrd_karyawan.ajax.reload(null,false);
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
									tblhtsprrd_karyawan.ajax.reload(null,false);
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
					karyawan_makan = api.column( 17 ).data().sum();
					karyawan_lb = api.column( 24 ).data().sum();
					karyawan_aw = api.column( 25 ).data().sum();
					karyawan_ak = api.column( 26 ).data().sum();
					karyawan_i1 = api.column( 27 ).data().sum();
					karyawan_i2 = api.column( 28 ).data().sum();
					karyawan_i3 = api.column( 29 ).data().sum();
					karyawan_tl = api.column( 30 ).data().sum();

					karyawan_pot_ti = api.column( 31 ).data().sum();
					karyawan_pot_overtime = api.column( 32 ).data().sum();
					karyawan_overtime = api.column( 33 ).data().sum();
					karyawan_pot_hk = api.column( 34 ).data().sum();
					karyawan_pot_jam = api.column( 35 ).data().sum();

					$( '#karyawan_makan' ).html( numFormat(karyawan_makan) );
					$( '#karyawan_lb' ).html( numFormat(karyawan_lb) );
					$( '#karyawan_aw' ).html( numFormat(karyawan_aw) );
					$( '#karyawan_ak' ).html( numFormat(karyawan_ak) );
					$( '#karyawan_i1' ).html( numFormat(karyawan_i1) );
					$( '#karyawan_i2' ).html( numFormat(karyawan_i2) );
					$( '#karyawan_i3' ).html( numFormat(karyawan_i3) );
					$( '#karyawan_tl' ).html( numFormat(karyawan_tl) );

					$( '#karyawan_pot_ti' ).html( numFormat(karyawan_pot_ti) );
					$( '#karyawan_pot_overtime' ).html( numFormat(karyawan_pot_overtime) );
					$( '#karyawan_overtime' ).html( numFormat(karyawan_overtime) );
					$( '#karyawan_pot_hk' ).html( numFormat(karyawan_pot_hk) );
					$( '#karyawan_pot_jam' ).html( numFormat(karyawan_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhtsprrd_karyawan.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd_karyawan.button('btncekNol:name').disable();
			tblhtsprrd_karyawan.button('btnPresensiOK:name').disable();

			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				var activeTabId = $(e.target).attr('href').substring(1);
				// console.log('Active Tab ID: ' + activeTabId);
				if (activeTabId == 'tabhtsprrd_karyawan') {
					tblhtsprrd_karyawan.searchPanes.container().appendTo( '#searchPanes1' );
					
				} else {
					tblhtsprrd_karyawan.searchPanes.container().detach();
				}
			});


			tblhtsprrd_karyawan.on( 'select', function( e, dt, type, indexes ) {
				htsprrd_data    = tblhtsprrd_karyawan.row( { selected: true } ).data().htsprrd;
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
					tblhtsprrd_karyawan.button('btncekNol:name').enable();
				} else {
					tblhtsprrd_karyawan.button('btncekNol:name').disable();
				}
				// if (status_presensi_in == "" && status_presensi_out == "") {
				// 	if (st_clock_in == "No CI" && st_clock_out == "No CO") {
				// 	tblhtsprrd_karyawan.button('btncekNol:name').enable();
				// 	} else {
				// 		tblhtsprrd_karyawan.button('btncekNol:name').disable();
				// 	}
				// }

				cariKMJ(tblhtsprrd_karyawan);
				// console.log(htlxxrh_kode);
				//Cek Apakah mengandung Kode Absen KD
				if (htlxxrh_kode.includes("KD/") && cek == 1) {
				    tblhtsprrd_karyawan.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_in == "Late" && status_presensi_in == "Belum ada Izin") {
				    tblhtsprrd_karyawan.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_out == "EARLY" && status_presensi_out == "Belum ada Izin") {
				    tblhtsprrd_karyawan.button('btnPresensiOK:name').enable();
                }

				if (status_presensi_in == "Jadwal Salah") {
				    tblhtsprrd_karyawan.button('btnPresensiOK:name').enable();
                }

				if (id_hemxxmh_select == 130 || id_hemxxmh_select == 134) {
				    tblhtsprrd_karyawan.button('btnPresensiOK:name').enable();
                }
				// console.log(htsprrd_data.status_presensi_in);
			} );
			
			tblhtsprrd_karyawan.on( 'deselect', function () {
				tblhtsprrd_karyawan.button('btncekNol:name').disable();
				tblhtsprrd_karyawan.button('btnPresensiOK:name').disable();
			} );
		///// end datatables
			
		///start datatables kmj
			tblhtsprrd_kmj = $('#tblhtsprrd_kmj').DataTable( {
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
						targets: [1,3,5,6,12,13,14,15]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,2,4,7,8,9,10,11,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
					}
				],
				ajax: {
					url: "../../models/htsprrd/htsprrd_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
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
						$table       = 'tblhtsprrd_kmj';
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
											tblhtsprrd_kmj.ajax.reload(null,false);
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
											tblhtsprrd_kmj.ajax.reload(null,false);
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
									tblhtsprrd_kmj.ajax.reload(null,false);
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
									tblhtsprrd_kmj.ajax.reload(null,false);
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
					kmj_makan = api.column( 17 ).data().sum();
					kmj_lb = api.column( 24 ).data().sum();
					kmj_aw = api.column( 25 ).data().sum();
					kmj_ak = api.column( 26 ).data().sum();
					kmj_i1 = api.column( 27 ).data().sum();
					kmj_i2 = api.column( 28 ).data().sum();
					kmj_i3 = api.column( 29 ).data().sum();
					kmj_tl = api.column( 30 ).data().sum();

					kmj_pot_ti = api.column( 31 ).data().sum();
					kmj_pot_overtime = api.column( 32 ).data().sum();
					kmj_overtime = api.column( 33 ).data().sum();
					kmj_pot_hk = api.column( 34 ).data().sum();
					kmj_pot_jam = api.column( 35 ).data().sum();

					$( '#kmj_makan' ).html( numFormat(kmj_makan) );
					$( '#kmj_lb' ).html( numFormat(kmj_lb) );
					$( '#kmj_aw' ).html( numFormat(kmj_aw) );
					$( '#kmj_ak' ).html( numFormat(kmj_ak) );
					$( '#kmj_i1' ).html( numFormat(kmj_i1) );
					$( '#kmj_i2' ).html( numFormat(kmj_i2) );
					$( '#kmj_i3' ).html( numFormat(kmj_i3) );
					$( '#kmj_tl' ).html( numFormat(kmj_tl) );

					$( '#kmj_pot_ti' ).html( numFormat(kmj_pot_ti) );
					$( '#kmj_pot_overtime' ).html( numFormat(kmj_pot_overtime) );
					$( '#kmj_overtime' ).html( numFormat(kmj_overtime) );
					$( '#kmj_pot_hk' ).html( numFormat(kmj_pot_hk) );
					$( '#kmj_pot_jam' ).html( numFormat(kmj_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
					$('.collapse-link').prop('disabled', false);
				}
			} );
			tblhtsprrd_kmj.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd_kmj.button('btncekNol:name').disable();
			tblhtsprrd_kmj.button('btnPresensiOK:name').disable();

			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				var activeTabId = $(e.target).attr('href').substring(1);
				// console.log('Active Tab ID: ' + activeTabId);
				if (activeTabId == 'tabhtsprrd_kmj') {
					tblhtsprrd_kmj.searchPanes.container().appendTo( '#searchPanes1' );
					
				} else {
					tblhtsprrd_kmj.searchPanes.container().detach();
				}
			});


			tblhtsprrd_kmj.on( 'select', function( e, dt, type, indexes ) {
				htsprrd_data    = tblhtsprrd_kmj.row( { selected: true } ).data().htsprrd;
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
					tblhtsprrd_kmj.button('btncekNol:name').enable();
				} else {
					tblhtsprrd_kmj.button('btncekNol:name').disable();
				}
				// if (status_presensi_in == "" && status_presensi_out == "") {
				// 	if (st_clock_in == "No CI" && st_clock_out == "No CO") {
				// 	tblhtsprrd_kmj.button('btncekNol:name').enable();
				// 	} else {
				// 		tblhtsprrd_kmj.button('btncekNol:name').disable();
				// 	}
				// }

				cariKMJ(tblhtsprrd_kmj);
				// console.log(htlxxrh_kode);
				//Cek Apakah mengandung Kode Absen KD
				if (htlxxrh_kode.includes("KD/") && cek == 1) {
				    tblhtsprrd_kmj.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_in == "Late" && status_presensi_in == "Belum ada Izin") {
				    tblhtsprrd_kmj.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_out == "EARLY" && status_presensi_out == "Belum ada Izin") {
				    tblhtsprrd_kmj.button('btnPresensiOK:name').enable();
                }

				if (status_presensi_in == "Jadwal Salah") {
				    tblhtsprrd_kmj.button('btnPresensiOK:name').enable();
                }

				if (id_hemxxmh_select == 130 || id_hemxxmh_select == 134) {
				    tblhtsprrd_kmj.button('btnPresensiOK:name').enable();
                }
				// console.log(htsprrd_data.status_presensi_in);
			} );
			
			tblhtsprrd_kmj.on( 'deselect', function () {
				tblhtsprrd_kmj.button('btncekNol:name').disable();
				tblhtsprrd_kmj.button('btnPresensiOK:name').disable();
			} );
		///// end datatables
			
		///start datatables freelance
			tblhtsprrd_freelance = $('#tblhtsprrd_freelance').DataTable( {
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
						targets: [1,3,5,6,12,13,14,15]
					},
					{
						searchPanes:{
							show: false
						},
						targets: [0,2,4,7,8,9,10,11,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35]
					}
				],
				ajax: {
					url: "../../models/htsprrd/htsprrd_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
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
						$table       = 'tblhtsprrd_freelance';
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
											tblhtsprrd_freelance.ajax.reload(null,false);
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
											tblhtsprrd_freelance.ajax.reload(null,false);
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
									tblhtsprrd_freelance.ajax.reload(null,false);
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
									tblhtsprrd_freelance.ajax.reload(null,false);
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
					freelance_makan = api.column( 17 ).data().sum();
					freelance_lb = api.column( 24 ).data().sum();
					freelance_aw = api.column( 25 ).data().sum();
					freelance_ak = api.column( 26 ).data().sum();
					freelance_i1 = api.column( 27 ).data().sum();
					freelance_i2 = api.column( 28 ).data().sum();
					freelance_i3 = api.column( 29 ).data().sum();
					freelance_tl = api.column( 30 ).data().sum();

					freelance_pot_ti = api.column( 31 ).data().sum();
					freelance_pot_overtime = api.column( 32 ).data().sum();
					freelance_overtime = api.column( 33 ).data().sum();
					freelance_pot_hk = api.column( 34 ).data().sum();
					freelance_pot_jam = api.column( 35 ).data().sum();

					$( '#freelance_makan' ).html( numFormat(freelance_makan) );
					$( '#freelance_lb' ).html( numFormat(freelance_lb) );
					$( '#freelance_aw' ).html( numFormat(freelance_aw) );
					$( '#freelance_ak' ).html( numFormat(freelance_ak) );
					$( '#freelance_i1' ).html( numFormat(freelance_i1) );
					$( '#freelance_i2' ).html( numFormat(freelance_i2) );
					$( '#freelance_i3' ).html( numFormat(freelance_i3) );
					$( '#freelance_tl' ).html( numFormat(freelance_tl) );

					$( '#freelance_pot_ti' ).html( numFormat(freelance_pot_ti) );
					$( '#freelance_pot_overtime' ).html( numFormat(freelance_pot_overtime) );
					$( '#freelance_overtime' ).html( numFormat(freelance_overtime) );
					$( '#freelance_pot_hk' ).html( numFormat(freelance_pot_hk) );
					$( '#freelance_pot_jam' ).html( numFormat(freelance_pot_jam) );

				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			tblhtsprrd_freelance.button('btnSetApprovePresensi:name').disable();
			tblhtsprrd_freelance.button('btncekNol:name').disable();
			tblhtsprrd_freelance.button('btnPresensiOK:name').disable();

			$('.nav-tabs a').on('shown.bs.tab', function (e) {
				var activeTabId = $(e.target).attr('href').substring(1);
				// console.log('Active Tab ID: ' + activeTabId);
				if (activeTabId == 'tabhtsprrd_freelance') {
					tblhtsprrd_freelance.searchPanes.container().appendTo( '#searchPanes1' );
					
				} else {
					tblhtsprrd_freelance.searchPanes.container().detach();
				}
			});


			tblhtsprrd_freelance.on( 'select', function( e, dt, type, indexes ) {
				htsprrd_data    = tblhtsprrd_freelance.row( { selected: true } ).data().htsprrd;
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
					tblhtsprrd_freelance.button('btncekNol:name').enable();
				} else {
					tblhtsprrd_freelance.button('btncekNol:name').disable();
				}
				// if (status_presensi_in == "" && status_presensi_out == "") {
				// 	if (st_clock_in == "No CI" && st_clock_out == "No CO") {
				// 	tblhtsprrd_freelance.button('btncekNol:name').enable();
				// 	} else {
				// 		tblhtsprrd_freelance.button('btncekNol:name').disable();
				// 	}
				// }

				cariKMJ(tblhtsprrd_freelance);
				// console.log(htlxxrh_kode);
				//Cek Apakah mengandung Kode Absen KD
				if (htlxxrh_kode.includes("KD/") && cek == 1) {
				    tblhtsprrd_freelance.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_in != "Late" && status_presensi_in == "Belum ada Izin") {
				    tblhtsprrd_freelance.button('btnPresensiOK:name').enable();
					// console.log("11111");
                }

				if (st_clock_out == "EARLY" && status_presensi_out == "Belum ada Izin") {
				    tblhtsprrd_freelance.button('btnPresensiOK:name').enable();
                }

				if (status_presensi_in == "Jadwal Salah") {
				    tblhtsprrd_freelance.button('btnPresensiOK:name').enable();
                }

				if (id_hemxxmh_select == 130 || id_hemxxmh_select == 134) {
				    tblhtsprrd_freelance.button('btnPresensiOK:name').enable();
                }
				// console.log(htsprrd_data.status_presensi_in);
			} );
			
			tblhtsprrd_freelance.on( 'deselect', function () {
				tblhtsprrd_freelance.button('btncekNol:name').disable();
				tblhtsprrd_freelance.button('btnPresensiOK:name').disable();
			} );
		///// end datatables
				
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

					tblhtsprrd_kbm.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);

					tblhtsprrd_karyawan.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);

					tblhtsprrd_kmj.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);

					tblhtsprrd_freelance.ajax.reload(function ( json ) {
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
