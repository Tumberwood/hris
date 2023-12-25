<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hpyxxth';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hpyemtd';
    $nama_tabels_d[1] = 'hpyemtd_kbm_reg';
    $nama_tabels_d[2] = 'hpyemtd_karyawan';
    $nama_tabels_d[3] = 'hpyemtd_kmj';
    $nama_tabels_d[4] = 'hpyemtd_freelance';
    $nama_tabels_d[5] = 'hpyemtd_kbm_tr';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="alert alert-info alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
					Sebelum melakukan Generate Payroll, pastikan sudah melakukan Approve data-data pada menu berikut ini!!!
					<ul>
						<li>Report Presensi</li>
						<li>Payroll Lain-lain</li>
					</ul>
				</div>
				<div class="table-responsive">
					<table id="tblhpyxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
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
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhpyemtd"> All</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kbm_reg"> KBM Reguler</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kbm_tr"> KBM Pelatihan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_karyawan"> Karyawan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kmj"> KMJ</a></li>
							<li id="tab_freelance"><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_freelance"> Freelance</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_kbm_reg" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_kbm_reg" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_kbm_tr" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_kbm_tr" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_karyawan" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_kmj" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_kmj" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_freelance" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_freelance" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
													<th>NIK</th>
													<th>Nama</th>
													<th>Department</th>
													<th>Jabatan</th>
													<th>Tipe</th>
													<th>Sub Tipe</th>
													<th>Status</th>
													<th>Level</th>
													<th>Gaji Pokok</th>
													<th>TJ. Jabatan</th>
													<th>Var Cost</th>
													<th>TJ. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Pendapatan Lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp) </th>
													<th>Lembur Susulan (Rp)</th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot JKK JKM</th>
													<th class="text-danger">Pot JHT</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th class="text-danger">Pot BPJS</th>
													<th class="text-danger">Pot Pensiun</th>
													<th class="text-danger">Pot Pinjaman</th>
													<th class="text-danger">Pot Klaim</th>
													<th class="text-danger">Pot Denda APD</th>
													<th class="text-danger">Pot PPH21</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth/fn/hpyxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth, tblhpyxxth, show_inactive_status_hpyxxth = 0, id_hpyxxth;
        var edthpyemtd_kbm_reg, tblhpyemtd_kbm_reg, show_inactive_status_hpyemtd = 0, id_hpyemtd;
		// ------------- end of default variable
		var id_heyxxmh_old = 0;
		

		$(document).ready(function() {
			
			//start datatables editor
			edthpyxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth = show_inactive_status_hpyxxth;
					}
				},
				table: "#tblhpyxxth",
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
						def: "hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyxxth.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hpyxxth.tanggal_awal",
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
						name: "hpyxxth.tanggal_akhir",
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
						name: "hpyxxth.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyxxth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhpyxxth.rows().deselect();
				}
			});

            edthpyxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hpyxxth.tanggal_awal
					if ( ! edthpyxxth.field('hpyxxth.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthpyxxth.field('hpyxxth.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthpyxxth.field('hpyxxth.tanggal_awal').error( 'Wajib diisi!' );
						}else{
							tanggal_awal_ymd = moment(tanggal_awal).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth.tanggal_awal

					// BEGIN of validasi hpyxxth.tanggal_akhir
					if ( ! edthpyxxth.field('hpyxxth.tanggal_akhir').isMultiValue() ) {
						tanggal_akhir = edthpyxxth.field('hpyxxth.tanggal_akhir').val();
						if(!tanggal_akhir || tanggal_akhir == ''){
							edthpyxxth.field('hpyxxth.tanggal_akhir').error( 'Wajib diisi!' );
						}else{
							tanggal_akhir_ymd = moment(tanggal_akhir).format('YYYY-MM-DD');
						}
					}
					// END of validasi hpyxxth.tanggal_akhir

				}
				
				if ( edthpyxxth.inError() ) {
					return false;
				}
			});

			edthpyxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyxxth.field('finish_on').val(finish_on);
			});
			
			edthpyxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyxxth = $('#tblhpyxxth').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyxxth = show_inactive_status_hpyxxth;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hpyxxth.id",visible:false },
					{ data: "hpyxxth.tanggal_awal",visible:false },
					{ 
						data: null ,
						render: function (data, type, row) {
							return row.hpyxxth.tanggal_awal + " - " + row.hpyxxth.tanggal_akhir;
					   	}
					},
					{ data: "heyxxmh.nama",visible:false },
					{ data: "hpyxxth.keterangan" },
					{ data: "hpyxxth.generated_on" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyxxth';
						$table       = 'tblhpyxxth';
						$edt         = 'edthpyxxth';
						$show_status = '_hpyxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGeneratePresensi',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hpyxxth/hpyxxth_fn_gen_payroll_ferry.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hpyxxth		: id_hpyxxth,
									tanggal_awal	: tanggal_awal_select,
									tanggal_akhir	: tanggal_akhir_select,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhpyxxth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
					{
						text: 'PPh21',
						name: 'btnGenPPh21',
						className: 'btn btn-xs btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var timestamp = moment(timestamp).format('YYYY-MM-DD HH:mm:ss');

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hpyxxth/hpyxxth_fn_gen_pph21.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_hpyxxth		: id_hpyxxth,
									tanggal_awal	: tanggal_awal_select,
									tanggal_akhir	: tanggal_akhir_select,
									timestamp		: timestamp
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhpyxxth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhpyxxth.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekInitHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();
				
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd.button( 'btnPrintSingle:name' ).disable();
			} );
			
			tblhpyxxth.on( 'select', function( e, dt, type, indexes ) {
				data_hpyxxth = tblhpyxxth.row( { selected: true } ).data().hpyxxth;
				id_hpyxxth  = data_hpyxxth.id;
				id_transaksi_h   = id_hpyxxth; // dipakai untuk general
				is_approve       = data_hpyxxth.is_approve;
				is_nextprocess   = data_hpyxxth.is_nextprocess;
				is_jurnal        = data_hpyxxth.is_jurnal;
				is_active        = data_hpyxxth.is_active;
				tanggal_awal_select        = data_hpyxxth.tanggal_awal;
				tanggal_akhir_select        = data_hpyxxth.tanggal_akhir;
				id_heyxxmh_select        = data_hpyxxth.id_heyxxmh;

				id_heyxxmh_old = data_hpyxxth.id_heyxxmh;
				
				// atur hak akses
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekSelectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).enable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).enable();
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).enable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).enable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).enable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).enable();
			} );
			
			tblhpyxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth = 0;
				id_heyxxmh_old = 0;
				id_heyxxmh = 0

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekDeselectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).disable();

				tblhpyemtd.button( 'btnPrintSingle:name' ).disable();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd.rows().deselect();
				}
			});

            edthpyemtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd.inError() ) {
					return false;
				}
			});

			edthpyemtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd.field('finish_on').val(finish_on);
			});

			
			edthpyemtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd = $('#tblhpyemtd').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				fixedColumns:   {
					left: 1
				},
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd';
						$edt         = 'edthpyemtd';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print_single.php?id_hpyemtd=' + id_hpyemtd, 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#all_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				id_hemxxmh       = data_hpyemtd.id_hemxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd );
				tblhpyemtd.button( 'btnPrintSingle:name' ).enable();
			} );

			tblhpyemtd.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				id_hemxxmh = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd );
				tblhpyemtd.button( 'btnPrintSingle:name' ).disable();
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kbm_reg = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_kbm_reg",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_kbm_reg.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_kbm_reg.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm_reg.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_kbm_reg.rows().deselect();
				}
			});

            edthpyemtd_kbm_reg.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_kbm_reg.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_kbm_reg.inError() ) {
					return false;
				}
			});

			edthpyemtd_kbm_reg.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm_reg.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_kbm_reg.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_kbm_reg = $('#tblhpyemtd_kbm_reg').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_kbm_reg';
						$edt         = 'edthpyemtd_kbm_reg';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=1&id_hesxxmh=4', 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_reg' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_kbm_reg.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_kbm_reg, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_kbm_reg.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_kbm_reg.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_kbm_reg );
			} );

			tblhpyemtd_kbm_reg.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_kbm_reg );
			} );

// --------- end _detail --------------- //		
			
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kbm_tr = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_kbm_tr",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_kbm_tr.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_kbm_tr.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm_tr.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_kbm_tr.rows().deselect();
				}
			});

            edthpyemtd_kbm_tr.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_kbm_tr.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_kbm_tr.inError() ) {
					return false;
				}
			});

			edthpyemtd_kbm_tr.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm_tr.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_kbm_tr.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_kbm_tr = $('#tblhpyemtd_kbm_tr').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm_tr.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_kbm_tr';
						$edt         = 'edthpyemtd_kbm_tr';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=1&id_hesxxmh=3', 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_tr' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_kbm_tr.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_kbm_tr, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_kbm_tr.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_kbm_tr.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_kbm_tr );
			} );

			tblhpyemtd_kbm_tr.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_kbm_tr );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_karyawan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_karyawan",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_karyawan.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_karyawan.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_karyawan.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_karyawan.rows().deselect();
				}
			});

            edthpyemtd_karyawan.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_karyawan.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_karyawan.inError() ) {
					return false;
				}
			});

			edthpyemtd_karyawan.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_karyawan.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_karyawan.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_karyawan = $('#tblhpyemtd_karyawan').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_karyawan';
						$edt         = 'edthpyemtd_karyawan';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=3', 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#karyawan_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_karyawan.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_karyawan, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_karyawan.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_karyawan.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_karyawan );
			} );

			tblhpyemtd_karyawan.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_karyawan );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kmj = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_kmj",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_kmj.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_kmj.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kmj.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_kmj.rows().deselect();
				}
			});

            edthpyemtd_kmj.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_kmj.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_kmj.inError() ) {
					return false;
				}
			});

			edthpyemtd_kmj.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kmj.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_kmj.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_kmj = $('#tblhpyemtd_kmj').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_kmj';
						$edt         = 'edthpyemtd_kmj';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=4', 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kmj_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_kmj.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_kmj, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_kmj.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_kmj.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_kmj );
			} );

			tblhpyemtd_kmj.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_kmj );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_freelance = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_freelance",
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
						def: "hpyemtd",
						type: "hidden"
					},	{
						label: "id_hpyxxth",
						name: "hpyemtd.id_hpyxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hpyemtd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Keterangan",
						name: "hpyemtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthpyemtd_freelance.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_freelance.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_freelance.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_freelance.rows().deselect();
				}
			});

            edthpyemtd_freelance.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_freelance.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_freelance.inError() ) {
					return false;
				}
			});

			edthpyemtd_freelance.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_freelance.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_freelance.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_freelance = $('#tblhpyemtd_freelance').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 1
				},
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_freelance';
						$edt         = 'edthpyemtd_freelance';
						$show_status = '_hpyemtd';
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
							window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=5', 'hpyxxth');
						}
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 46; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#freelance_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_freelance.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_freelance, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_freelance.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_freelance.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_freelance );
			} );

			tblhpyemtd_freelance.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_freelance );
			} );

// --------- end _detail --------------- //		
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
