<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hpyxxth_2';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hpyemtd_2';
    $nama_tabels_d[1] = 'hpyemtd_2_kbm_reg';
    $nama_tabels_d[2] = 'hpyemtd_2_karyawan';
    $nama_tabels_d[3] = 'hpyemtd_2_kmj';
    $nama_tabels_d[4] = 'hpyemtd_2_freelance';
    $nama_tabels_d[5] = 'hpyemtd_2_kbm_tr';
    $nama_tabels_d[6] = 'hpyemtd_2_kontrak';
?>

<!-- begin content here -->

<!-- Breakdown -->
<div class="modal fade" id="modalOutstandingApproval" tabindex="-1" role="dialog" aria-labelledby="myModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="myModal1Label">Outstanding Approval Report Presensi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
		<div class="table-responsive" id="proteksi">
			<div class="row">
				<div class="col-12">
					<h3>Report Presensi</h3>
					<table id="report_presensi" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Status</th>
							</tr>
						</thead>
					</table>
				</div>
				<div class="col-12">
					<h3>Payroll Lain-lain</h3>
					<table id="payroll_lain" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Nominal</th>
                                <th>Perhitungan</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Approval</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
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
					<strong>Perhatian!</strong><br>
					Sebelum melakukan <b>Generate Payroll</b>, pastikan sudah melakukan <b>Approve</b> data-data pada menu berikut ini:
					<ul style="margin-top: 10px;">
						<li>
						<a href="../htsprrd/htsprrd.php" class="text-primary" target="_blank">
							<i class="fa fa-calendar-check-o"></i> Report Presensi
						</a>
						</li>
						<li>
						<a href="../hpy_piutang_d/hpy_piutang_d.php" class="text-primary" target="_blank">
							<i class="fa fa-money"></i> Payroll Lain-lain
						</a>
						</li>
					</ul>
				</div>
				<div class="table-responsive">
					<table id="tblhpyxxth_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
                                <th>Tanggal Awal</th>
                                <th>Periode</th>
                                <th>Jenis</th>
                                <th>Keterangan</th>
                                <th>Generated On</th>
                            </tr>
                        </thead>
                    </table>
					<div class="tabs-container">
						<div class="alert alert-warning">
							<div class="d-flex justify-content-between align-items-start">
								<strong>Informasi Proporsional Gaji</strong>
								<button class="btn btn-sm btn-warning toggle-alert" type="button">−</button>
							</div>
    						<div class="alert-content mt-2" style="display: none;"> <!-- Tambah display: none -->
								Gaji akan dihitung secara <strong>proporsional</strong> (dibagi sesuai jumlah hari kerja bukan OFF) untuk pegawai yang:
								<ul>
								<li>Baru masuk kerja</li>
								<li>Berubah status (misalnya promosi)</li>
								<li>Berhenti kerja (resign atau terminasi)</li>
								</ul>
								...dan kejadian tersebut terjadi <strong>antara tanggal 1 sampai akhir bulan dalam periode gajian</strong>.

								<br><br>
								<strong>Contoh:</strong><br>
								Pegawai A diperpanjang kontraknya pada tanggal <strong>25 Jan 2025</strong>.

								<br><br>
								Meskipun periode payroll adalah dari <strong>23 Des 2024 s/d 22 Jan 2025</strong>, perhitungan proporsional tetap mengacu pada bulan kalender, yaitu:

								<br><strong>➡️ 1 Jan s/d 31 Jan 2025</strong>. Proporsional ini berlaku untuk NIK baru dan NIK lama yang melakukan perubahan status.

								<br><br>
								Komponen gaji yang dihitung secara proporsional:
								<ul>
									<li>Gaji Pokok</li>
									<li>Tunjangan Jabatan</li>
									<li>Fix Cost</li>
								</ul>
							</div>
						</div>
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhpyemtd_2"> All</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_karyawan"> Tetap</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_kontrak"> Kontrak</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_kbm_reg"> KBM Reguler</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_kbm_tr"> KBM Pelatihan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_kmj"> KMJ</a></li>
							<li id="tab_freelance"><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_2_freelance"> Freelance</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd_2" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="all_10"></th>
													<th id="all_11"></th>
													<th id="all_12"></th>
													<th id="all_13"></th>
													<th id="all_14"></th>
													<th id="all_15"></th>
													<th id="all_16"></th>
													<th id="all_17"></th>
													<th id="all_18"></th>
													<th id="all_19"></th>
													<th id="all_20"></th>
													<th id="all_21"></th>
													<th id="all_22"></th>
													<th id="all_23"></th>
													<th id="all_24"></th>
													<th id="all_25"></th>
													<th id="all_26"></th>
													<th id="all_27"></th>
													<th id="all_28"></th>
													<th id="all_29"></th>
													<th id="all_30"></th>
													<th id="all_31"></th>
													<th id="all_32"></th>
													<th id="all_33"></th>
													<th id="all_34"></th>
													<th id="all_35"></th>
													<th id="all_36"></th>
													<th id="all_37"></th>
													<th id="all_38"></th>
													<th id="all_39"></th>
													<th id="all_40"></th>
													<th id="all_41"></th>
													<th id="all_42"></th>
													<th id="all_43"></th>
													<th id="all_44"></th>
													<th id="all_45"></th>
													<th id="all_46"></th>
													<th id="all_47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_kbm_reg" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_kbm_reg" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="kbm_reg10"></th>
													<th id="kbm_reg11"></th>
													<th id="kbm_reg12"></th>
													<th id="kbm_reg13"></th>
													<th id="kbm_reg14"></th>
													<th id="kbm_reg15"></th>
													<th id="kbm_reg16"></th>
													<th id="kbm_reg17"></th>
													<th id="kbm_reg18"></th>
													<th id="kbm_reg19"></th>
													<th id="kbm_reg20"></th>
													<th id="kbm_reg21"></th>
													<th id="kbm_reg22"></th>
													<th id="kbm_reg23"></th>
													<th id="kbm_reg24"></th>
													<th id="kbm_reg25"></th>
													<th id="kbm_reg26"></th>
													<th id="kbm_reg27"></th>
													<th id="kbm_reg28"></th>
													<th id="kbm_reg29"></th>
													<th id="kbm_reg30"></th>
													<th id="kbm_reg31"></th>
													<th id="kbm_reg32"></th>
													<th id="kbm_reg33"></th>
													<th id="kbm_reg34"></th>
													<th id="kbm_reg35"></th>
													<th id="kbm_reg36"></th>
													<th id="kbm_reg37"></th>
													<th id="kbm_reg38"></th>
													<th id="kbm_reg39"></th>
													<th id="kbm_reg40"></th>
													<th id="kbm_reg41"></th>
													<th id="kbm_reg42"></th>
													<th id="kbm_reg43"></th>
													<th id="kbm_reg44"></th>
													<th id="kbm_reg45"></th>
													<th id="kbm_reg46"></th>
													<th id="kbm_reg47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_kbm_tr" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_kbm_tr" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="kbm_tr10"></th>
													<th id="kbm_tr11"></th>
													<th id="kbm_tr12"></th>
													<th id="kbm_tr13"></th>
													<th id="kbm_tr14"></th>
													<th id="kbm_tr15"></th>
													<th id="kbm_tr16"></th>
													<th id="kbm_tr17"></th>
													<th id="kbm_tr18"></th>
													<th id="kbm_tr19"></th>
													<th id="kbm_tr20"></th>
													<th id="kbm_tr21"></th>
													<th id="kbm_tr22"></th>
													<th id="kbm_tr23"></th>
													<th id="kbm_tr24"></th>
													<th id="kbm_tr25"></th>
													<th id="kbm_tr26"></th>
													<th id="kbm_tr27"></th>
													<th id="kbm_tr28"></th>
													<th id="kbm_tr29"></th>
													<th id="kbm_tr30"></th>
													<th id="kbm_tr31"></th>
													<th id="kbm_tr32"></th>
													<th id="kbm_tr33"></th>
													<th id="kbm_tr34"></th>
													<th id="kbm_tr35"></th>
													<th id="kbm_tr36"></th>
													<th id="kbm_tr37"></th>
													<th id="kbm_tr38"></th>
													<th id="kbm_tr39"></th>
													<th id="kbm_tr40"></th>
													<th id="kbm_tr41"></th>
													<th id="kbm_tr42"></th>
													<th id="kbm_tr43"></th>
													<th id="kbm_tr44"></th>
													<th id="kbm_tr45"></th>
													<th id="kbm_tr46"></th>
													<th id="kbm_tr47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_karyawan" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="karyawan_10"></th>
													<th id="karyawan_11"></th>
													<th id="karyawan_12"></th>
													<th id="karyawan_13"></th>
													<th id="karyawan_14"></th>
													<th id="karyawan_15"></th>
													<th id="karyawan_16"></th>
													<th id="karyawan_17"></th>
													<th id="karyawan_18"></th>
													<th id="karyawan_19"></th>
													<th id="karyawan_20"></th>
													<th id="karyawan_21"></th>
													<th id="karyawan_22"></th>
													<th id="karyawan_23"></th>
													<th id="karyawan_24"></th>
													<th id="karyawan_25"></th>
													<th id="karyawan_26"></th>
													<th id="karyawan_27"></th>
													<th id="karyawan_28"></th>
													<th id="karyawan_29"></th>
													<th id="karyawan_30"></th>
													<th id="karyawan_31"></th>
													<th id="karyawan_32"></th>
													<th id="karyawan_33"></th>
													<th id="karyawan_34"></th>
													<th id="karyawan_35"></th>
													<th id="karyawan_36"></th>
													<th id="karyawan_37"></th>
													<th id="karyawan_38"></th>
													<th id="karyawan_39"></th>
													<th id="karyawan_40"></th>
													<th id="karyawan_41"></th>
													<th id="karyawan_42"></th>
													<th id="karyawan_43"></th>
													<th id="karyawan_44"></th>
													<th id="karyawan_45"></th>
													<th id="karyawan_46"></th>
													<th id="karyawan_47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_kontrak" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_kontrak" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="kontrak_10"></th>
													<th id="kontrak_11"></th>
													<th id="kontrak_12"></th>
													<th id="kontrak_13"></th>
													<th id="kontrak_14"></th>
													<th id="kontrak_15"></th>
													<th id="kontrak_16"></th>
													<th id="kontrak_17"></th>
													<th id="kontrak_18"></th>
													<th id="kontrak_19"></th>
													<th id="kontrak_20"></th>
													<th id="kontrak_21"></th>
													<th id="kontrak_22"></th>
													<th id="kontrak_23"></th>
													<th id="kontrak_24"></th>
													<th id="kontrak_25"></th>
													<th id="kontrak_26"></th>
													<th id="kontrak_27"></th>
													<th id="kontrak_28"></th>
													<th id="kontrak_29"></th>
													<th id="kontrak_30"></th>
													<th id="kontrak_31"></th>
													<th id="kontrak_32"></th>
													<th id="kontrak_33"></th>
													<th id="kontrak_34"></th>
													<th id="kontrak_35"></th>
													<th id="kontrak_36"></th>
													<th id="kontrak_37"></th>
													<th id="kontrak_38"></th>
													<th id="kontrak_39"></th>
													<th id="kontrak_40"></th>
													<th id="kontrak_41"></th>
													<th id="kontrak_42"></th>
													<th id="kontrak_43"></th>
													<th id="kontrak_44"></th>
													<th id="kontrak_45"></th>
													<th id="kontrak_46"></th>
													<th id="kontrak_47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_kmj" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_kmj" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="kmj_10"></th>
													<th id="kmj_11"></th>
													<th id="kmj_12"></th>
													<th id="kmj_13"></th>
													<th id="kmj_14"></th>
													<th id="kmj_15"></th>
													<th id="kmj_16"></th>
													<th id="kmj_17"></th>
													<th id="kmj_18"></th>
													<th id="kmj_19"></th>
													<th id="kmj_20"></th>
													<th id="kmj_21"></th>
													<th id="kmj_22"></th>
													<th id="kmj_23"></th>
													<th id="kmj_24"></th>
													<th id="kmj_25"></th>
													<th id="kmj_26"></th>
													<th id="kmj_27"></th>
													<th id="kmj_28"></th>
													<th id="kmj_29"></th>
													<th id="kmj_30"></th>
													<th id="kmj_31"></th>
													<th id="kmj_32"></th>
													<th id="kmj_33"></th>
													<th id="kmj_34"></th>
													<th id="kmj_35"></th>
													<th id="kmj_36"></th>
													<th id="kmj_37"></th>
													<th id="kmj_38"></th>
													<th id="kmj_39"></th>
													<th id="kmj_40"></th>
													<th id="kmj_41"></th>
													<th id="kmj_42"></th>
													<th id="kmj_43"></th>
													<th id="kmj_44"></th>
													<th id="kmj_45"></th>
													<th id="kmj_46"></th>
													<th id="kmj_47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_2_freelance" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_2_freelance" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth_2</th>
													<th>NRP</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Insen</th>
													<th>TJ. Jabatan</th>
													<th>Uniform</th>
													<th>RP Free</th>
													<th>Terima Lain</th>
													<th>Var Cost</th>
													<th>Fix Cost</th>
													<th>Premi Absen</th>
													<th>Trm JKK JKM</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot PPH21</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Gaji Diterima</th>
													
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
													<th>Total</th>
													<th></th>
													<th id="freelance_10"></th>
													<th id="freelance_11"></th>
													<th id="freelance_12"></th>
													<th id="freelance_13"></th>
													<th id="freelance_14"></th>
													<th id="freelance_15"></th>
													<th id="freelance_16"></th>
													<th id="freelance_17"></th>
													<th id="freelance_18"></th>
													<th id="freelance_19"></th>
													<th id="freelance_20"></th>
													<th id="freelance_21"></th>
													<th id="freelance_22"></th>
													<th id="freelance_23"></th>
													<th id="freelance_24"></th>
													<th id="freelance_25"></th>
													<th id="freelance_26"></th>
													<th id="freelance_27"></th>
													<th id="freelance_28"></th>
													<th id="freelance_29"></th>
													<th id="freelance_30"></th>
													<th id="freelance_31"></th>
													<th id="freelance_32"></th>
													<th id="freelance_33"></th>
													<th id="freelance_34"></th>
													<th id="freelance_35"></th>
													<th id="freelance_36"></th>
													<th id="freelance_37"></th>
													<th id="freelance_38"></th>
													<th id="freelance_39"></th>
													<th id="freelance_40"></th>
													<th id="freelance_41"></th>
													<th id="freelance_42"></th>
													<th id="freelance_43"></th>
													<th id="freelance_44"></th>
													<th id="freelance_45"></th>
													<th id="freelance_46"></th>
													<th id="freelance_47"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth_2/fn/hpyxxth_2_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth_2, tblhpyxxth_2, show_inactive_status_hpyxxth_2 = 0, id_hpyxxth_2;
        var edthpyemtd_2_kbm_reg, tblhpyemtd_2_kbm_reg, show_inactive_status_hpyemtd_2 = 0, id_hpyemtd_2;
		// ------------- end of default variable
		var id_heyxxmh_old = 0;
		

		$(document).ready(function() {
			$('.toggle-alert').click(function () {
				var $content = $(this).closest('.alert').find('.alert-content');
				$content.slideToggle(); // smooth hide/show
				var current = $(this).text();
				$(this).text(current === '−' ? '+' : '−');
			});
			
			//start datatables editor
			edthpyxxth_2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyxxth_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_2 = show_inactive_status_hpyxxth_2;
					}
				},
				table: "#tblhpyxxth_2",
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
						def: "hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyxxth_2.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hpyxxth_2.tanggal_awal",
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
						name: "hpyxxth_2.tanggal_akhir",
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
						name: "hpyxxth_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyxxth_2.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_2.field('start_on').val(start_on);

				if(action == 'create'){
					tblhpyxxth_2.rows().deselect();
				}
			});

            edthpyxxth_2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyxxth_2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpyxxth_2.tanggal_awal
					if ( ! edthpyxxth_2.field('hpyxxth_2.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthpyxxth_2.field('hpyxxth_2.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthpyxxth_2.field('hpyxxth_2.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_2.tanggal_awal

					// BEGIN of validasi hpyxxth_2.tanggal_akhir
					if ( ! edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthpyxxth_2.field('hpyxxth_2.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth_2.tanggal_akhir

				}
				
				if ( edthpyxxth_2.inError() ) {
					return false;
				}
			});

			edthpyxxth_2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth_2.field('finish_on').val(finish_on);
			});
			
			edthpyxxth_2.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyxxth_2 = $('#tblhpyxxth_2').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyxxth_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth_2 = show_inactive_status_hpyxxth_2;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hpyxxth_2.id",visible:false },
					{ data: "hpyxxth_2.tanggal_awal",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth_2.tanggal_awal + " - " + row.hpyxxth_2.tanggal_akhir;
					   	}
					},
					{ data: "heyxxmh.nama",visible:false },
					{ data: "hpyxxth_2.keterangan" },
					{ data: "hpyxxth_2.generated_on" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyxxth_2';
						$table       = 'tblhpyxxth_2';
						$edt         = 'edthpyxxth_2';
						$show_status = '_hpyxxth_2';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// {
					// 	text: '<i class="fa fa-google"></i>',
					// 	name: 'btnGeneratePresensiNew',
					// 	className: 'btn btn-xs btn-outline',
					// 	titleAttr: '',
					// 	action: function ( e, dt, node, config ) {
					// 		e.preventDefault(); 
					// 		var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

					// 		notifyprogress = $.notify({
					// 			message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					// 		},{
					// 			z_index: 9999,
					// 			allow_dismiss: false,
					// 			type: 'info',
					// 			delay: 0
					// 		});

					// 		$.ajax( {
					// 			url: "../../models/hpyxxth_2/hpyxxth_2_fn_gen_payroll_ferry_2025.php",
					// 			dataType: 'json',
					// 			type: 'POST',
					// 			data: {
					// 				id_hpyxxth_2		: id_hpyxxth_2,
					// 				tanggal_awal	: tanggal_awal_select,
					// 				tanggal_akhir	: tanggal_akhir_select,
					// 				timestamp		: timestamp
					// 			},
					// 			success: function ( json ) {

					// 				$.notify({
					// 					message: json.data.message
					// 				},{
					// 					type: json.data.type_message
					// 				});

					// 				tblhpyxxth_2.ajax.reload(function ( json ) {
					// 					notifyprogress.close();
					// 				}, false);
					// 			}
					// 		} );
					// 	}
					// },
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensiNew',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function (e, dt, node, config) {
							e.preventDefault();

							const timestampNow = moment().format('YYYY-MM-DD HH:mm:ss');

							// === Langkah 1: tampilkan konfirmasi di tengah ===
							let notifConfirm = $.notify({
								message: `
									<div style="text-align:center;">
										<strong>Yakin ingin generate presensi baru?</strong><br>
										Proses ini bisa memakan waktu beberapa saat.<br><br>
										<button id="confirmYes" class="btn btn-xs btn-success">Ya</button>
										<button id="confirmNo" class="btn btn-xs btn-danger">Batal</button>
									</div>
								`
							}, {
								z_index: 9999,
								allow_dismiss: false,
								type: 'warning',
								delay: 0,
								newest_on_top: true,
								placement: {
									from: "top",
									align: "center"
								},
								offset: {
									y: $(window).height() / 2 - 100, // posisi agak ke tengah vertikal
									x: 0
								},
								template: `
									<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"
										style="text-align:center; margin:auto; position:fixed; left:0; right:0; top:{1}px; z-index:9999;">
										<span data-notify="message">{2}</span>
									</div>
								`
							});

							// === Langkah 2: handle tombol ===
							$(document).off('click', '#confirmYes').on('click', '#confirmYes', function() {
								notifConfirm.close();

								// tampilkan notifikasi proses
								notifyprogress = $.notify({
									message: `
										<div style="text-align:center;">
											<i class="fa fa-spinner fa-spin"></i> Processing...</br>
											Jangan tutup halaman sampai notifikasi ini hilang!
										</div>
									`
								}, {
									z_index: 9999,
									allow_dismiss: false,
									type: 'info',
									delay: 0,
									placement: {
										from: "top",
										align: "center"
									},
									offset: { y: $(window).height() / 2 - 100, x: 0 },
									template: `
										<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert"
											style="text-align:center; margin:auto; position:fixed; left:0; right:0; top:{1}px; z-index:9999;">
											<span data-notify="message">{2}</span>
										</div>
									`
								});

								// === Jalankan AJAX ===
								$.ajax({
									url: "../../models/hpyxxth_2/hpyxxth_2_fn_gen_payroll_ferry_2025.php",
									dataType: 'json',
									type: 'POST',
									data: {
										id_hpyxxth_2: id_hpyxxth_2,
										tanggal_awal: tanggal_awal_select,
										tanggal_akhir: tanggal_akhir_select,
										timestamp: timestampNow
									},
									success: function (json) {
										notifyprogress.close();

										$.notify({
											message: json.data.message
										}, {
											type: json.data.type_message,
											z_index: 9999
										});

										tblhpyxxth_2.ajax.reload(null, false);
									},
									error: function () {
										notifyprogress.close();
										$.notify({
											message: 'Terjadi kesalahan saat memproses data.'
										}, {
											type: 'danger',
											z_index: 9999
										});
									}
								});
							});

							// === Tombol batal ===
							$(document).off('click', '#confirmNo').on('click', '#confirmNo', function() {
								notifConfirm.close();
								$.notify({
									message: 'Dibatalkan oleh pengguna.'
								}, {
									type: 'warning',
									z_index: 9999
								});
							});
						}
					},
					{
						text: 'Outstanding Approval',
						name: 'btnOutstanding',
						className: 'btn btn-outline',
						titleAttr: 'Outstanding Approval',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							$('#modalOutstandingApproval').modal('show');
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth_2.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpyxxth_2.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhpyemtd_2, tblhpyemtd_2_kbm_reg, tblhpyemtd_2_karyawan, tblhpyemtd_2_kontrak, tblhpyemtd_2_kmj, tblhpyemtd_2_freelance, tblhpyemtd_2_kbm_tr];
				CekInitHeaderHD(tblhpyxxth_2, tbl_details);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth_2.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth_2.button( 'btnGenPPh21:name' ).disable();
				
				tblhpyemtd_2_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd_2.button( 'btnPrintSingle:name' ).disable();
				tblhpyxxth_2.button( 'btnOutstanding:name' ).disable();
			} );
			
			tblhpyxxth_2.on( 'select', function( e, dt, type, indexes ) {
				data_hpyxxth_2 = tblhpyxxth_2.row( { selected: true } ).data().hpyxxth_2;
				id_hpyxxth_2  = data_hpyxxth_2.id;
				id_transaksi_h   = id_hpyxxth_2; // dipakai untuk general
				is_approve       = data_hpyxxth_2.is_approve;
				is_nextprocess   = data_hpyxxth_2.is_nextprocess;
				is_jurnal        = data_hpyxxth_2.is_jurnal;
				is_active        = data_hpyxxth_2.is_active;
				tanggal_awal_select        = data_hpyxxth_2.tanggal_awal;
				tanggal_akhir_select        = data_hpyxxth_2.tanggal_akhir;
				id_heyxxmh_select        = data_hpyxxth_2.id_heyxxmh;

				id_heyxxmh_old = data_hpyxxth_2.id_heyxxmh;
				
				// atur hak akses
				tbl_details = [tblhpyemtd_2, tblhpyemtd_2_kbm_reg, tblhpyemtd_2_karyawan, tblhpyemtd_2_kontrak, tblhpyemtd_2_kmj, tblhpyemtd_2_freelance, tblhpyemtd_2_kbm_tr];
				CekSelectHeaderHD(tblhpyxxth_2, tbl_details);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).enable();
				tblhpyxxth_2.button( 'btnGeneratePresensiNew:name' ).enable();
				tblhpyxxth_2.button( 'btnGenPPh21:name' ).enable();
				tblhpyemtd_2_kbm_reg.button( 'btnPrint:name' ).enable();
				tblhpyemtd_2_karyawan.button( 'btnPrint:name' ).enable();
				tblhpyemtd_2_kontrak.button( 'btnPrint:name' ).enable();
				tblhpyemtd_2_kmj.button( 'btnPrint:name' ).enable();
				tblhpyemtd_2_freelance.button( 'btnPrint:name' ).enable();
				
				outstandingApproval(id_hpyxxth_2);
				tblhpyxxth_2.button( 'btnOutstanding:name' ).enable();
			} );
			
			tblhpyxxth_2.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth_2 = 0;
				id_heyxxmh_old = 0;
				id_heyxxmh = 0

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				tbl_details = [tblhpyemtd_2, tblhpyemtd_2_kbm_reg, tblhpyemtd_2_karyawan, tblhpyemtd_2_kontrak, tblhpyemtd_2_kmj, tblhpyemtd_2_freelance, tblhpyemtd_2_kbm_tr];
				CekDeselectHeaderHD(tblhpyxxth_2, tbl_details);
				tblhpyxxth_2.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth_2.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth_2.button( 'btnGenPPh21:name' ).disable();
				tblhpyemtd_2_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_2_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd_2.button( 'btnPrintSingle:name' ).disable();
				tblhpyxxth_2.button( 'btnOutstanding:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2 = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2.rows().deselect();
				}
			});

            edthpyemtd_2.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2.inError() ) {
					return false;
				}
			});

			edthpyemtd_2.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2 = $('#tblhpyemtd_2').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				fixedColumns:   {
					left: 2
				},
				// scrollX: true,
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2';
						$edt         = 'edthpyemtd_2';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate 
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrintSingle',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji Per Pegawai',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print_single.php?id_hpyemtd_2=' + id_hpyemtd_2, 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#all_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				id_hemxxmh       = data_hpyemtd_2.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2 );
				tblhpyemtd_2.button( 'btnPrintSingle:name' ).enable();
			} );

			tblhpyemtd_2.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				id_hemxxmh = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2 );
				tblhpyemtd_2.button( 'btnPrintSingle:name' ).disable();
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_kbm_reg = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_kbm_reg",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_kbm_reg.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_kbm_reg.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kbm_reg.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_kbm_reg.rows().deselect();
				}
			});

            edthpyemtd_2_kbm_reg.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_kbm_reg.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_kbm_reg.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_kbm_reg.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kbm_reg.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_kbm_reg.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_kbm_reg = $('#tblhpyemtd_2_kbm_reg').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_kbm_reg';
						$edt         = 'edthpyemtd_2_kbm_reg';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=1&id_hesxxmh=4', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_reg' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_kbm_reg.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_reg, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_kbm_reg.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_kbm_reg.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_reg );
			} );

			tblhpyemtd_2_kbm_reg.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_reg );
			} );

// --------- end _detail --------------- //		
			
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_kbm_tr = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_kbm_tr",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_kbm_tr.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_kbm_tr.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kbm_tr.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_kbm_tr.rows().deselect();
				}
			});

            edthpyemtd_2_kbm_tr.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_kbm_tr.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_kbm_tr.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_kbm_tr.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kbm_tr.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_kbm_tr.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_kbm_tr = $('#tblhpyemtd_2_kbm_tr').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_kbm_tr';
						$edt         = 'edthpyemtd_2_kbm_tr';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[5];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=1&id_hesxxmh=3', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_tr' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_kbm_tr.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_tr, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_kbm_tr.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_kbm_tr.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_tr );
			} );

			tblhpyemtd_2_kbm_tr.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kbm_tr );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_karyawan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_karyawan",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_karyawan.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_karyawan.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_karyawan.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_karyawan.rows().deselect();
				}
			});

            edthpyemtd_2_karyawan.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_karyawan.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_karyawan.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_karyawan.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_karyawan.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_karyawan.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_karyawan = $('#tblhpyemtd_2_karyawan').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_karyawan';
						$edt         = 'edthpyemtd_2_karyawan';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[2];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=3', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#karyawan_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_karyawan.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_karyawan, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_karyawan.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_karyawan.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_karyawan );
			} );

			tblhpyemtd_2_karyawan.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_karyawan );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_kontrak = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_kontrak",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_kontrak.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_kontrak.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kontrak.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_kontrak.rows().deselect();
				}
			});

            edthpyemtd_2_kontrak.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_kontrak.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_kontrak.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_kontrak.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kontrak.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_kontrak.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_kontrak = $('#tblhpyemtd_2_kontrak').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_kontrak';
						$edt         = 'edthpyemtd_2_kontrak';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[6];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=3', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kontrak_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_kontrak.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_kontrak, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_kontrak.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_kontrak.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kontrak );
			} );

			tblhpyemtd_2_kontrak.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kontrak );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_kmj = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_kmj",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_kmj.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_kmj.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kmj.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_kmj.rows().deselect();
				}
			});

            edthpyemtd_2_kmj.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_kmj.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_kmj.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_kmj.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_kmj.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_kmj.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_kmj = $('#tblhpyemtd_2_kmj').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_kmj';
						$edt         = 'edthpyemtd_2_kmj';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[3];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=4', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kmj_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_kmj.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_kmj, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_kmj.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_kmj.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kmj );
			} );

			tblhpyemtd_2_kmj.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_kmj );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_2_freelance = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				table: "#tblhpyemtd_2_freelance",
				formOptions: {
					main: {
						focus: 3
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
						def: "hpyemtd_2",
						type: "hidden"
					},	{
						label: "id_hpyxxth_2",
						name: "hpyemtd_2.id_hpyxxth_2",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd_2.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd_2.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_2_freelance.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_2_freelance.field('hpyemtd_2.id_hpyxxth_2').val(id_hpyxxth_2);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_freelance.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_2_freelance.rows().deselect();
				}
			});

            edthpyemtd_2_freelance.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_2_freelance.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_2_freelance.inError() ) {
					return false;
				}
			});

			edthpyemtd_2_freelance.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_2_freelance.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_2_freelance.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_2_freelance = $('#tblhpyemtd_2_freelance').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_2/hpyemtd_2_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd_2 = show_inactive_status_hpyemtd_2;
						d.id_hpyxxth_2 = id_hpyxxth_2;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_2.id",visible:false },
					{ data: "hpyemtd_2.id_hpyxxth_2",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_2.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},
					{
						data: null,
						defaultContent: 0, // kalau value null/undefined → isi 0
						render: function (data, type, row) {
							if (data === null || data === '' || parseFloat(data) === 0) {
								return '0'; // tampil 0 saja
							}
							
						},
						class: "text-right"
					},

					{ 
						data: "hpyemtd_2.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_2.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_2.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_2.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd_2';
						$table       = 'tblhpyemtd_2_freelance';
						$edt         = 'edthpyemtd_2_freelance';
						$show_status = '_hpyemtd_2';
						$table_name  = $nama_tabels_d[4];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					,{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Slip Gaji',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('hpyxxth_2_print.php?id_hpyxxth_2=' + id_hpyxxth_2 + '&id_heyxxmd=5', 'hpyxxth_2');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 47; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#freelance_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_2_freelance.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth_2, tblhpyemtd_2_freelance, 'hpyemtd_2' );
				CekDrawDetailHDFinal(tblhpyxxth_2);
			} );

			tblhpyemtd_2_freelance.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd_2 = tblhpyemtd_2_freelance.row( { selected: true } ).data().hpyemtd_2;
				id_hpyemtd_2   = data_hpyemtd_2.id;
				id_transaksi_d    = id_hpyemtd_2; // dipakai untuk general
				is_active_d       = data_hpyemtd_2.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth_2, tblhpyemtd_2_freelance );
			} );

			tblhpyemtd_2_freelance.on( 'deselect', function() {
				id_hpyemtd_2 = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth_2, tblhpyemtd_2_freelance );
			} );

// --------- end _detail --------------- //		
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
