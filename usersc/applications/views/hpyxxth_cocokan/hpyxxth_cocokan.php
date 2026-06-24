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
    $nama_tabels_d[6] = 'hpyemtd_kontrak';
?>

<style>
	.modal-xxl {
		max-width: 90%;
	}

	table.dataTable thead th.lama {
		background: #ffeeba !important;
		/* color: #856404 !important; */
	}

	table.dataTable thead th.baru {
		background: #c3e6cb !important;
		/* color: #155724 !important; */
	}
</style>
<!-- begin content here -->

<div class="modal" id="modalUpload" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content animated bounceInRight">
			<form class="form-horizontal" id="frmUploadMaster" enctype="multipart/form-data">
				<div class="modal-header">
					<h4 class="modal-title">Upload Excel</h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">
						<label class="col-lg-2 col-form-label">File Excel</label>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="file" name="filename" class="form-control" id="frmUploadItem">
							</div>
						</div>
						<div class="col-sm-4">
							<button type="button" class="btn btn-success" onclick="window.open('../../../files/uploads/template_payroll.xlsx');">
								<i class="fa fa-download"></i>&nbsp;&nbsp;<span class="bold">Template</span>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
					<button class="btn btn-primary" type="submit" id="submitUpload">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Breakdown -->
<div class="modal fade" id="modalBreakdown" tabindex="-1" role="dialog" aria-labelledby="myModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-xxl" role="document">
    <div class="modal-content">
      
      <div class="modal-header">
        <h3 class="modal-title" id="myModal1Label"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
		<div class="table-responsive">
			<div class="row">
				<div class="col-12">
					<h3 id="text_upah">Potongan Upah</h3>
					<table id="potongan_upah" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Status Jadwal</th>
								<th>Status In</th>
								<th>Status Out</th>
								<th>Pot Upah</th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="col-12">
					<h3 id="text_premi">Potongan Premi</h3>
					<table id="potongan_premi" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Status Jadwal</th>
								<th>Status In</th>
								<th>Status Out</th>
								<th>Pot Premi</th>
							</tr>
						</thead>
					</table>
				</div>

				<div class="col-12">
					<h3 id="text_lembur">Data Lembur</h3>
					<table id="data_lembur" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>SPKL</th>
								<th>Jenis Lembur</th>
								<th>Status Istirahat</th>
								<th>Durasi SPKL</th>
								<th>Pot TI</th>
								<th>Pot Overtime</th>
								<th>Pot HK</th>
								<th>Pot Jam</th>
								<th>Lembur Final</th>
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
							<li><a class="nav-link active" data-toggle="tab" href="#tabhpyemtd_karyawan"> Tetap</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kontrak"> Kontrak</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kbm_reg"> KBM Reguler</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kbm_tr"> KBM Pelatihan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kmj" style="display: none"> KMJ</a></li>
							<li id="tab_freelance"><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_freelance" style="display: none"> Freelance</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd_karyawan" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<!-- TAMBAHAN -->
													<th class="text-center align-middle">ID</th>
													<th class="text-center align-middle">NIK</th>
													<th class="text-center align-middle">Nama</th>

													<th class="text-center align-middle">Divisi</th>
													<th class="text-center align-middle">Department</th>
													<th class="text-center align-middle">Unit Kerja</th>
													<th class="text-center align-middle">Jabatan</th>
													<th class="text-center align-middle">Grup Jabatan</th>
													<th class="text-center align-middle">Bagian</th>
													<th class="text-center align-middle">Skala Upah</th>
													<th class="text-center align-middle">Sub Tipe</th>
													<th class="text-center align-middle">Status</th>
													<th class="text-center align-middle">Tipe</th>
													<th class="text-center align-middle">Gender</th>

													<th class="text-center align-middle">PTKP</th>
													<th class="text-center align-middle">No Rek</th>
													<th class="text-center align-middle">No KTP</th>
													<th class="text-center align-middle">No NPWP</th>

													<!-- DATA GAJI -->
													<th class="text-center align-middle lama">Gaji Pokok (Lama)</th>
													<th class="text-center align-middle baru">Gaji Pokok (Baru)</th>

													<th class="text-center align-middle lama">Tj. Jabatan (Lama)</th>
													<th class="text-center align-middle baru">Tj. Jabatan (Baru)</th>

													<th class="text-center align-middle lama">Terima Lain (Lama)</th>
													<th class="text-center align-middle baru">Terima Lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Lain-lain (Lama)</th>
													<th class="text-center align-middle baru">Tj. Lain-lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Khusus (Lama)</th>
													<th class="text-center align-middle baru">Tj. Khusus (Baru)</th>

													<th class="text-center align-middle lama">Tj. Masa Kerja (Lama)</th>
													<th class="text-center align-middle baru">Tj. Masa Kerja (Baru)</th>

													<th class="text-center align-middle lama">Premi Absensi (Lama)</th>
													<th class="text-center align-middle baru">Premi Absensi (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (rp) (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Kontrak Berakhir (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Kontrak Berakhir (Baru)</th>

													<th class="text-center align-middle lama">Cuti Tahunan (Lama)</th>
													<th class="text-center align-middle baru">Cuti Tahunan (Baru)</th>

													<th class="text-center align-middle lama">Cuti Bersama (Lama)</th>
													<th class="text-center align-middle baru">Cuti Bersama (Baru)</th>

													<th class="text-center align-middle lama">Hari Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Hari Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">THR (Lama)</th>
													<th class="text-center align-middle baru">THR (Baru)</th>

													<!-- POTONGAN -->
													<th class="text-center align-middle text-danger lama">Potongan Makan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Makan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Upah (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Upah (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Upah (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Upah (Rp) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Jam (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Jam (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Jam (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Jam (Rp) (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Bruto (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Bruto (Baru)</th>

													<th class="text-center align-middle lama">Tarif TER (%) (Lama)</th>
													<th class="text-center align-middle baru">Tarif TER (%) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS JHT Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JHT Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JP Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JP Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JHT Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JHT Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JP Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JP Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS Kes Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS Kes Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Piutang Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Piutang Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Pot Denda APD (Lama)</th>
													<th class="text-center align-middle text-danger baru">Pot Denda APD (Baru)</th>

													<th class="text-center align-middle text-danger lama">Iuran SPSI (Lama)</th>
													<th class="text-center align-middle text-danger baru">Iuran SPSI (Baru)</th>

													<th class="text-center align-middle lama">Pendapatan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Pendapatan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Gaji Bersih (Lama)</th>
													<th class="text-center align-middle baru">Gaji Bersih (Baru)</th>

													<th class="text-center align-middle lama">Bulat (Lama)</th>
													<th class="text-center align-middle baru">Bulat (Baru)</th>

													<th class="text-center align-middle lama">Gaji Diterima (Lama)</th>
													<th class="text-center align-middle baru">Gaji Diterima (Baru)</th>
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
													<th>Total</th>
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
													<th id="karyawan_48"></th>
													<th id="karyawan_49"></th>
													<th id="karyawan_50"></th>
													<th id="karyawan_51"></th>
													<th id="karyawan_52"></th>
													<th id="karyawan_53"></th>
													<th id="karyawan_54"></th>
													<th id="karyawan_55"></th>
													<th id="karyawan_56"></th>
													<th id="karyawan_57"></th>
													<th id="karyawan_58"></th>
													<th id="karyawan_59"></th>
													<th id="karyawan_60"></th>
													<th id="karyawan_61"></th>
													<th id="karyawan_62"></th>
													<th id="karyawan_63"></th>
													<th id="karyawan_64"></th>
													<th id="karyawan_65"></th>
													<th id="karyawan_66"></th>
													<th id="karyawan_67"></th>
													<th id="karyawan_68"></th>
													<!-- Baru -->
													<th id="karyawan_69"></th>
													<th id="karyawan_70"></th>
													<th id="karyawan_71"></th>
													<th id="karyawan_72"></th>
													<th id="karyawan_73"></th>
													<th id="karyawan_74"></th>
													<th id="karyawan_75"></th>
													<th id="karyawan_76"></th>
													<th id="karyawan_77"></th>
													<th id="karyawan_78"></th>
													<th id="karyawan_79"></th>
													<th id="karyawan_80"></th>
													<th id="karyawan_81"></th>
													<th id="karyawan_82"></th>
													<th id="karyawan_83"></th>
													<th id="karyawan_84"></th>
													<th id="karyawan_85"></th>
													<th id="karyawan_86"></th>
													<th id="karyawan_87"></th>
													<th id="karyawan_88"></th>
													<th id="karyawan_89"></th>
													<th id="karyawan_90"></th>
													<th id="karyawan_91"></th>
													<th id="karyawan_92"></th>
													<th id="karyawan_93"></th>
													<th id="karyawan_94"></th>
													<th id="karyawan_95"></th>
													<th id="karyawan_96"></th>
													<th id="karyawan_97"></th>
													<th id="karyawan_98"></th>
													<th id="karyawan_99"></th>
													<th id="karyawan_100"></th>
													<th id="karyawan_101"></th>
													<th id="karyawan_102"></th>
													<th id="karyawan_103"></th>
													<th id="karyawan_104"></th>
													<th id="karyawan_105"></th>
													<th id="karyawan_106"></th>
													<th id="karyawan_107"></th>
													<th id="karyawan_108"></th>
													<th id="karyawan_109"></th>
													<th id="karyawan_110"></th>
													<th id="karyawan_111"></th>
													<th id="karyawan_112"></th>
													<th id="karyawan_113"></th>
													<th id="karyawan_114"></th>
													<th id="karyawan_115"></th>
													<th id="karyawan_116"></th>
													<th id="karyawan_117"></th>
													<th id="karyawan_118"></th>
													<th id="karyawan_119"></th>
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_kontrak" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_kontrak" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<!-- TAMBAHAN -->
													<th class="text-center align-middle">ID</th>
													<th class="text-center align-middle">NIK</th>
													<th class="text-center align-middle">Nama</th>
													
													<th class="text-center align-middle">Divisi</th>
													<th class="text-center align-middle">Department</th>
													<th class="text-center align-middle">Unit Kerja</th>
													<th class="text-center align-middle">Jabatan</th>
													<th class="text-center align-middle">Grup Jabatan</th>
													<th class="text-center align-middle">Bagian</th>
													<th class="text-center align-middle">Skala Upah</th>
													<th class="text-center align-middle">Sub Tipe</th>
													<th class="text-center align-middle">Status</th>
													<th class="text-center align-middle">Tipe</th>
													<th class="text-center align-middle">Gender</th>

													<th class="text-center align-middle">PTKP</th>
													<th class="text-center align-middle">No Rek</th>
													<th class="text-center align-middle">No KTP</th>
													<th class="text-center align-middle">No NPWP</th>

													<!-- DATA GAJI -->
													<th class="text-center align-middle lama">Gaji Pokok (Lama)</th>
													<th class="text-center align-middle baru">Gaji Pokok (Baru)</th>

													<th class="text-center align-middle lama">Tj. Jabatan (Lama)</th>
													<th class="text-center align-middle baru">Tj. Jabatan (Baru)</th>

													<th class="text-center align-middle lama">Terima Lain (Lama)</th>
													<th class="text-center align-middle baru">Terima Lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Lain-lain (Lama)</th>
													<th class="text-center align-middle baru">Tj. Lain-lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Khusus (Lama)</th>
													<th class="text-center align-middle baru">Tj. Khusus (Baru)</th>

													<th class="text-center align-middle lama">Tj. Masa Kerja (Lama)</th>
													<th class="text-center align-middle baru">Tj. Masa Kerja (Baru)</th>

													<th class="text-center align-middle lama">Premi Absensi (Lama)</th>
													<th class="text-center align-middle baru">Premi Absensi (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (rp) (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Kontrak Berakhir (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Kontrak Berakhir (Baru)</th>

													<th class="text-center align-middle lama">Cuti Tahunan (Lama)</th>
													<th class="text-center align-middle baru">Cuti Tahunan (Baru)</th>

													<th class="text-center align-middle lama">Cuti Bersama (Lama)</th>
													<th class="text-center align-middle baru">Cuti Bersama (Baru)</th>

													<th class="text-center align-middle lama">Hari Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Hari Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">THR (Lama)</th>
													<th class="text-center align-middle baru">THR (Baru)</th>

													<!-- POTONGAN -->
													<th class="text-center align-middle text-danger lama">Potongan Makan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Makan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Upah (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Upah (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Upah (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Upah (Rp) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Jam (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Jam (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Jam (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Jam (Rp) (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Bruto (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Bruto (Baru)</th>

													<th class="text-center align-middle lama">Tarif TER (%) (Lama)</th>
													<th class="text-center align-middle baru">Tarif TER (%) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS JHT Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JHT Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JP Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JP Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JHT Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JHT Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JP Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JP Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS Kes Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS Kes Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Piutang Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Piutang Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Pot Denda APD (Lama)</th>
													<th class="text-center align-middle text-danger baru">Pot Denda APD (Baru)</th>

													<th class="text-center align-middle text-danger lama">Iuran SPSI (Lama)</th>
													<th class="text-center align-middle text-danger baru">Iuran SPSI (Baru)</th>

													<th class="text-center align-middle lama">Pendapatan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Pendapatan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Gaji Bersih (Lama)</th>
													<th class="text-center align-middle baru">Gaji Bersih (Baru)</th>

													<th class="text-center align-middle lama">Bulat (Lama)</th>
													<th class="text-center align-middle baru">Bulat (Baru)</th>

													<th class="text-center align-middle lama">Gaji Diterima (Lama)</th>
													<th class="text-center align-middle baru">Gaji Diterima (Baru)</th>
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
													<th>Total</th>
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
													<th id="kontrak_48"></th>
													<th id="kontrak_49"></th>
													<th id="kontrak_50"></th>
													<th id="kontrak_51"></th>
													<th id="kontrak_52"></th>
													<th id="kontrak_53"></th>
													<th id="kontrak_54"></th>
													<th id="kontrak_55"></th>
													<th id="kontrak_56"></th>
													<th id="kontrak_57"></th>
													<th id="kontrak_58"></th>
													<th id="kontrak_59"></th>
													<th id="kontrak_60"></th>
													<th id="kontrak_61"></th>
													<th id="kontrak_62"></th>
													<th id="kontrak_63"></th>
													<th id="kontrak_64"></th>
													<th id="kontrak_65"></th>
													<th id="kontrak_66"></th>
													<th id="kontrak_67"></th>
													<th id="kontrak_68"></th>
													<!-- Baru -->
													<th id="kontrak_69"></th>
													<th id="kontrak_70"></th>
													<th id="kontrak_71"></th>
													<th id="kontrak_72"></th>
													<th id="kontrak_73"></th>
													<th id="kontrak_74"></th>
													<th id="kontrak_75"></th>
													<th id="kontrak_76"></th>
													<th id="kontrak_77"></th>
													<th id="kontrak_78"></th>
													<th id="kontrak_79"></th>
													<th id="kontrak_80"></th>
													<th id="kontrak_81"></th>
													<th id="kontrak_82"></th>
													<th id="kontrak_83"></th>
													<th id="kontrak_84"></th>
													<th id="kontrak_85"></th>
													<th id="kontrak_86"></th>
													<th id="kontrak_87"></th>
													<th id="kontrak_88"></th>
													<th id="kontrak_89"></th>
													<th id="kontrak_90"></th>
													<th id="kontrak_91"></th>
													<th id="kontrak_92"></th>
													<th id="kontrak_93"></th>
													<th id="kontrak_94"></th>
													<th id="kontrak_95"></th>
													<th id="kontrak_96"></th>
													<th id="kontrak_97"></th>
													<th id="kontrak_98"></th>
													<th id="kontrak_99"></th>
													<th id="kontrak_100"></th>
													<th id="kontrak_101"></th>
													<th id="kontrak_102"></th>
													<th id="kontrak_103"></th>
													<th id="kontrak_104"></th>
													<th id="kontrak_105"></th>
													<th id="kontrak_106"></th>
													<th id="kontrak_107"></th>
													<th id="kontrak_108"></th>
													<th id="kontrak_109"></th>
													<th id="kontrak_110"></th>
													<th id="kontrak_111"></th>
													<th id="kontrak_112"></th>
													<th id="kontrak_113"></th>
													<th id="kontrak_114"></th>
													<th id="kontrak_115"></th>
													<th id="kontrak_116"></th>
													<th id="kontrak_117"></th>
													<th id="kontrak_118"></th>
													<th id="kontrak_119"></th>
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
													<!-- TAMBAHAN -->
													<th class="text-center align-middle">ID</th>
													<th class="text-center align-middle">NIK</th>
													<th class="text-center align-middle">Nama</th>
													
													<th class="text-center align-middle">Divisi</th>
													<th class="text-center align-middle">Department</th>
													<th class="text-center align-middle">Unit Kerja</th>
													<th class="text-center align-middle">Jabatan</th>
													<th class="text-center align-middle">Grup Jabatan</th>
													<th class="text-center align-middle">Bagian</th>
													<th class="text-center align-middle">Skala Upah</th>
													<th class="text-center align-middle">Sub Tipe</th>
													<th class="text-center align-middle">Status</th>
													<th class="text-center align-middle">Tipe</th>
													<th class="text-center align-middle">Gender</th>

													<th class="text-center align-middle">PTKP</th>
													<th class="text-center align-middle">No Rek</th>
													<th class="text-center align-middle">No KTP</th>
													<th class="text-center align-middle">No NPWP</th>

													<!-- DATA GAJI -->
													<th class="text-center align-middle lama">Gaji Pokok (Lama)</th>
													<th class="text-center align-middle baru">Gaji Pokok (Baru)</th>

													<th class="text-center align-middle lama">Tj. Jabatan (Lama)</th>
													<th class="text-center align-middle baru">Tj. Jabatan (Baru)</th>

													<th class="text-center align-middle lama">Terima Lain (Lama)</th>
													<th class="text-center align-middle baru">Terima Lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Lain-lain (Lama)</th>
													<th class="text-center align-middle baru">Tj. Lain-lain (Baru)</th>

													<th class="text-center align-middle lama">Tj. Khusus (Lama)</th>
													<th class="text-center align-middle baru">Tj. Khusus (Baru)</th>

													<th class="text-center align-middle lama">Tj. Masa Kerja (Lama)</th>
													<th class="text-center align-middle baru">Tj. Masa Kerja (Baru)</th>

													<th class="text-center align-middle lama">Premi Absensi (Lama)</th>
													<th class="text-center align-middle baru">Premi Absensi (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x1.5 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x1.5 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x2 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x2 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur x3 (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur x3 (rp) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (jam) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (jam) (Baru)</th>

													<th class="text-center align-middle lama">Lembur Total (rp) (Lama)</th>
													<th class="text-center align-middle baru">Lembur Total (rp) (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Kontrak Berakhir (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Kontrak Berakhir (Baru)</th>

													<th class="text-center align-middle lama">Cuti Tahunan (Lama)</th>
													<th class="text-center align-middle baru">Cuti Tahunan (Baru)</th>

													<th class="text-center align-middle lama">Cuti Bersama (Lama)</th>
													<th class="text-center align-middle baru">Cuti Bersama (Baru)</th>

													<th class="text-center align-middle lama">Hari Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Hari Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">Kompensasi Sisa Cuti (Lama)</th>
													<th class="text-center align-middle baru">Kompensasi Sisa Cuti (Baru)</th>

													<th class="text-center align-middle lama">THR (Lama)</th>
													<th class="text-center align-middle baru">THR (Baru)</th>

													<!-- POTONGAN -->
													<th class="text-center align-middle text-danger lama">Potongan Makan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Makan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Upah (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Upah (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Upah (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Upah (Rp) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Total Pot Jam (Lama)</th>
													<th class="text-center align-middle text-danger baru">Total Pot Jam (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Jam (Rp) (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Jam (Rp) (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Sebelum PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Sebelum PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Bruto (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Bruto (Baru)</th>

													<th class="text-center align-middle lama">Tarif TER (%) (Lama)</th>
													<th class="text-center align-middle baru">Tarif TER (%) (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Penghasilan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Penghasilan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">BPJS JHT Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JHT Perusahaan (Baru)</th>

													<th class="text-center align-middle lama">BPJS JP Perusahaan (Lama)</th>
													<th class="text-center align-middle baru">BPJS JP Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JHT Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JHT Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS JP Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS JP Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">BPJS Kes Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">BPJS Kes Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS Kes Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS Kes Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKK Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKK Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan BPJS JKM Perusahaan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan BPJS JKM Perusahaan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Piutang Karyawan (Lama)</th>
													<th class="text-center align-middle text-danger baru">Piutang Karyawan (Baru)</th>

													<th class="text-center align-middle text-danger lama">Pot Denda APD (Lama)</th>
													<th class="text-center align-middle text-danger baru">Pot Denda APD (Baru)</th>

													<th class="text-center align-middle text-danger lama">Iuran SPSI (Lama)</th>
													<th class="text-center align-middle text-danger baru">Iuran SPSI (Baru)</th>

													<th class="text-center align-middle lama">Pendapatan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle baru">Pendapatan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle text-danger lama">Potongan Setelah PPh 21 (Lama)</th>
													<th class="text-center align-middle text-danger baru">Potongan Setelah PPh 21 (Baru)</th>

													<th class="text-center align-middle lama">Gaji Bersih (Lama)</th>
													<th class="text-center align-middle baru">Gaji Bersih (Baru)</th>

													<th class="text-center align-middle lama">Bulat (Lama)</th>
													<th class="text-center align-middle baru">Bulat (Baru)</th>

													<th class="text-center align-middle lama">Gaji Diterima (Lama)</th>
													<th class="text-center align-middle baru">Gaji Diterima (Baru)</th>
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
													<th>Total</th>
													<th id="kbm_reg_18"></th>
													<th id="kbm_reg_19"></th>
													<th id="kbm_reg_20"></th>
													<th id="kbm_reg_21"></th>
													<th id="kbm_reg_22"></th>
													<th id="kbm_reg_23"></th>
													<th id="kbm_reg_24"></th>
													<th id="kbm_reg_25"></th>
													<th id="kbm_reg_26"></th>
													<th id="kbm_reg_27"></th>
													<th id="kbm_reg_28"></th>
													<th id="kbm_reg_29"></th>
													<th id="kbm_reg_30"></th>
													<th id="kbm_reg_31"></th>
													<th id="kbm_reg_32"></th>
													<th id="kbm_reg_33"></th>
													<th id="kbm_reg_34"></th>
													<th id="kbm_reg_35"></th>
													<th id="kbm_reg_36"></th>
													<th id="kbm_reg_37"></th>
													<th id="kbm_reg_38"></th>
													<th id="kbm_reg_39"></th>
													<th id="kbm_reg_40"></th>
													<th id="kbm_reg_41"></th>
													<th id="kbm_reg_42"></th>
													<th id="kbm_reg_43"></th>
													<th id="kbm_reg_44"></th>
													<th id="kbm_reg_45"></th>
													<th id="kbm_reg_46"></th>
													<th id="kbm_reg_47"></th>
													<th id="kbm_reg_48"></th>
													<th id="kbm_reg_49"></th>
													<th id="kbm_reg_50"></th>
													<th id="kbm_reg_51"></th>
													<th id="kbm_reg_52"></th>
													<th id="kbm_reg_53"></th>
													<th id="kbm_reg_54"></th>
													<th id="kbm_reg_55"></th>
													<th id="kbm_reg_56"></th>
													<th id="kbm_reg_57"></th>
													<th id="kbm_reg_58"></th>
													<th id="kbm_reg_59"></th>
													<th id="kbm_reg_60"></th>
													<th id="kbm_reg_61"></th>
													<th id="kbm_reg_62"></th>
													<th id="kbm_reg_63"></th>
													<th id="kbm_reg_64"></th>
													<th id="kbm_reg_65"></th>
													<th id="kbm_reg_66"></th>
													<th id="kbm_reg_67"></th>
													<th id="kbm_reg_68"></th>
													<!-- Baru -->
													<th id="kbm_reg_69"></th>
													<th id="kbm_reg_70"></th>
													<th id="kbm_reg_71"></th>
													<th id="kbm_reg_72"></th>
													<th id="kbm_reg_73"></th>
													<th id="kbm_reg_74"></th>
													<th id="kbm_reg_75"></th>
													<th id="kbm_reg_76"></th>
													<th id="kbm_reg_77"></th>
													<th id="kbm_reg_78"></th>
													<th id="kbm_reg_79"></th>
													<th id="kbm_reg_80"></th>
													<th id="kbm_reg_81"></th>
													<th id="kbm_reg_82"></th>
													<th id="kbm_reg_83"></th>
													<th id="kbm_reg_84"></th>
													<th id="kbm_reg_85"></th>
													<th id="kbm_reg_86"></th>
													<th id="kbm_reg_87"></th>
													<th id="kbm_reg_88"></th>
													<th id="kbm_reg_89"></th>
													<th id="kbm_reg_90"></th>
													<th id="kbm_reg_91"></th>
													<th id="kbm_reg_92"></th>
													<th id="kbm_reg_93"></th>
													<th id="kbm_reg_94"></th>
													<th id="kbm_reg_95"></th>
													<th id="kbm_reg_96"></th>
													<th id="kbm_reg_97"></th>
													<th id="kbm_reg_98"></th>
													<th id="kbm_reg_99"></th>
													<th id="kbm_reg_100"></th>
													<th id="kbm_reg_101"></th>
													<th id="kbm_reg_102"></th>
													<th id="kbm_reg_103"></th>
													<th id="kbm_reg_104"></th>
													<th id="kbm_reg_105"></th>
													<th id="kbm_reg_106"></th>
													<th id="kbm_reg_107"></th>
													<th id="kbm_reg_108"></th>
													<th id="kbm_reg_109"></th>
													<th id="kbm_reg_110"></th>
													<th id="kbm_reg_111"></th>
													<th id="kbm_reg_112"></th>
													<th id="kbm_reg_113"></th>
													<th id="kbm_reg_114"></th>
													<th id="kbm_reg_115"></th>
													<th id="kbm_reg_116"></th>
													<th id="kbm_reg_117"></th>
													<th id="kbm_reg_118"></th>
													<th id="kbm_reg_119"></th>
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
													<th>Lembur Jam Pertama</th>
													<th>Rp Jam Pertama x 1,5</th>
													<th>Lembur Jam Kedua</th>
													<th>Rp Jam Kedua x 2</th>
													<th>Lembur Jam Ketiga</th>
													<th>Rp Jam Ketiga x 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp Final)</th>
													<th class="text-danger">Pot Makan</th>
													<th class="text-danger">Pot Lain</th>
													<th>Pendapatan Lain</th>
													<th class="text-danger">Pot Upah Harian</th>
													<th class="text-danger">Pot Upah Jam</th>
													<th>Gaji Bersih</th>
													<th>Bulat</th>
													<th>Diterima Karyawan</th>
												</tr>
											</thead>

											<tfoot>
												<tr>
													<th colspan="9" class="text-end">Total</th>
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
													<th>Terima Lain</th>
													<th>Tj. Lain-lain</th>
													<th>Tj. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Lembur Jam Pertama</th>
													<th>Rp Jam Pertama x 1,5</th>
													<th>Lembur Jam Kedua</th>
													<th>Rp Jam Kedua x 2</th>
													<th>Lembur Jam Ketiga</th>
													<th>Rp Jam Ketiga x 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp Final) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
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
													<th id="kmj_10"></th>
													<th id="kmj_10"></th>
													<th id="kmj_10"></th>
													<th id="kmj_11"></th>
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
													<th>Terima Lain</th>
													<th>Tj. Lain-lain</th>
													<th>Tj. Masa Kerja</th>
													<th>Premi Absen</th>
													<th>JKK</th>
													<th>JKM</th>
													<th>Trm JKK JKM</th>
													<th>Lembur Jam Pertama</th>
													<th>Rp Jam Pertama x 1,5</th>
													<th>Lembur Jam Kedua</th>
													<th>Rp Jam Kedua x 2</th>
													<th>Lembur Jam Ketiga</th>
													<th>Rp Jam Ketiga x 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Jam Final)</th>
													<th>Total Lembur (Rp Final) </th>
													<th>Lembur Susulan (Rp) </th>
													<th>PPh21 Back </th>
													<th>Kompensasi Rekontrak </th>
													<th>Koreksi Lembur</th>
													<th>Koreksi Perubahan Status</th>
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
													<th id="freelance_10"></th>
													<th id="freelance_10"></th>
													<th id="freelance_10"></th>
													<th id="freelance_11"></th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth_cocokan/fn/hpyxxth_cocokan_fn.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth, tblhpyxxth, show_inactive_status_hpyxxth = 0, id_hpyxxth;
        var edthpyemtd_kbm_reg, tblhpyemtd_kbm_reg, show_inactive_status_hpyemtd = 0, id_hpyemtd;
		// ------------- end of default variable
		var id_heyxxmh_old = 0, id_periode_payroll_old = 0;
		
		$(document).ready(function() {
			$('[data-toggle="tooltip"]').tooltip();
			
			$('.toggle-alert').click(function () {
				var $content = $(this).closest('.alert').find('.alert-content');
				$content.slideToggle(); // smooth hide/show
				var current = $(this).text();
				$(this).text(current === '−' ? '+' : '−');
			});
			
			//start datatables editor
			edthpyxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyxxth_cocokan.php",
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
						label: "Periode Payroll <sup class='text-danger'>*<sup>",
						name: "hpyxxth.id_periode_payroll",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/periode_payroll/periode_payroll_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_periode_payroll_old: id_periode_payroll_old,
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
								minimumResultsForSearch: -1
							}
						}
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
				edthpyxxth.field('hpyxxth.tanggal_awal').hide();
				edthpyxxth.field('hpyxxth.tanggal_akhir').hide();

				if(action == 'create'){
					tblhpyxxth.rows().deselect();
				}
			});

            edthpyxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthpyxxth.dependent( 'hpyxxth.id_periode_payroll', function ( val, data, callback ) {
				if (val > 0) {
					fn_tanggal(val);
				}
				return {}
			}, {event: 'keyup change'});
			
			edthpyxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					id_periode_payroll = edthpyxxth.field('hpyxxth.id_periode_payroll').val();
					if(!id_periode_payroll || id_periode_payroll == ''){
						edthpyxxth.field('hpyxxth.id_periode_payroll').error( 'Wajib diisi!' );
					}

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
					url: "../../models/hpyxxth_cocokan/hpyxxth_cocokan.php",
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
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					,{
						name: 'btnUpload',
						text: '<i class="fa fa-file-excel-o"></i>',
						className: 'btn btn-primary',
						titleAttr: 'Upload Excel',
						action: function ( e, dt, node, config ) {
							$('#modalUpload').modal('toggle');
						}
					}
				],
				rowCallback: function( row, data, index ) {
					if ( data.hpyxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhpyxxth.button('btnUpload:name').disable();
			
			tblhpyxxth.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kontrak, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekInitHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();
				
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).disable();
				
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
				id_periode_payroll_old = data_hpyxxth.id_periode_payroll;
				
				// atur hak akses
				tbl_details = [tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kontrak, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekSelectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).enable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).enable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).enable();
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).enable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).enable();
				tblhpyemtd_kontrak.button( 'btnPrint:name' ).enable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).enable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).enable();
				
        		$('#text_upah').html(`<b>Potongan Upah (${tanggal_awal_select} - ${tanggal_akhir_select})</b>`);
        		$('#text_lembur').html(`<b>Lembur (${tanggal_awal_select} - ${tanggal_akhir_select})</b>`);
				$('#text_premi').html(`
					<b>
						Potongan Premi 
						(${moment(tanggal_awal_select).startOf('month').format('DD MMM YYYY')} 
						- 
						${moment(tanggal_awal_select).endOf('month').format('DD MMM YYYY')})
					</b>
				`);
				
				tblhpyxxth.button('btnUpload:name').enable();
			} );
			
			tblhpyxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hpyxxth = 0;
				id_heyxxmh_old = 0;
				id_periode_payroll_old = 0;
				id_heyxxmh = 0

				tanggal_awal_select = null;
				tanggal_akhir_select = null;
				id_heyxxmh_select = 0;

				// atur hak akses
				tbl_details = [tblhpyemtd_kbm_reg, tblhpyemtd_karyawan, tblhpyemtd_kontrak, tblhpyemtd_kmj, tblhpyemtd_freelance, tblhpyemtd_kbm_tr];
				CekDeselectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();
				tblhpyemtd_kbm_reg.button( 'btnPrint:name' ).disable();
				tblhpyemtd_karyawan.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kontrak.button( 'btnPrint:name' ).disable();
				tblhpyemtd_kmj.button( 'btnPrint:name' ).disable();
				tblhpyemtd_freelance.button( 'btnPrint:name' ).disable();

				tblhpyxxth.button('btnUpload:name').disable();
			} );

				
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_karyawan = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyemtd_karyawan.php",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				columns: [
					{ data: "hpyemtd_cocokan.id", visible:false },
					{ data: "hpyemtd_cocokan.nrp" },
					{ data: "hpyemtd_cocokan.nama" },
					
					{ data: "hovxxmh.nama" },	//divisi
					{ data: "hodxxmh.nama" },	//dep
					{ data: "hosxxmh.nama" },	//unit
					{ data: "hetxxmh.nama" },	//jab
					{ data: "hevgrmh.nama" },	//grup
					{ data: "hobxxmh.nama" },	//Bagian
					{ data: "hevxxmh.nama" },	//Skala
					{ data: "heyxxmd.nama" },	//Sub Tipe
					{ data: "hesxxmh.nama" },	//Status
					{ data: "heyxxmh.nama" },	//Tipe
					{ data: "hemxxmh.gender" },	//Gender

					{ data: "hpyemtd_cocokan.ptkp" },
					{ data: "hpyemtd_cocokan.no_rekening" },
					{ data: "hpyemtd_cocokan.ktp" },
					{ data: "hpyemtd_cocokan.npwp" },

					// GAJI
					{ data: "hpyemtd_cocokan.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.lembur15", class: "text-right" },
					{ data: "hpyemtd.lembur15", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur2", class: "text-right" },
					{ data: "hpyemtd.lembur2", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur3", class: "text-right" },
					{ data: "hpyemtd.lembur3", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd_cocokan.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					// POTONGAN
					{ data: "hpyemtd_cocokan.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },
					{ data: "hpyemtd.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					
					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
				],
				buttons: [
    // BEGIN breaking generate button
    <?php
        $id_table    = 'id_hpyemtd';
        $table       = 'tblhpyemtd_karyawan';
        $edt         = 'edthpyemtd_karyawan';
        $show_status = '_hpyemtd';
        $table_name  = $nama_tabels_d[2];

        $arr_buttons_tools      = ['show_hide','copy','excel','colvis'];
        $arr_buttons_action     = [];
        $arr_buttons_approve    = [];

        include $abs_us_root.$us_url_root.'usersc/helpers/button_fn_generate.php';
    ?>,

{
    text: '<span class="fa fa-file-excel-o">&nbsp;&nbsp;Excel Berwarna</span>',
    className: 'btn btn-success',
    action: async function () {

        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Payroll');

        const dt = $('#tblhpyemtd_karyawan').DataTable();

        // =========================
        // MAPPING compareField()
        // =========================
        const compareMap = {
            18: 'gp',
            20: 't_jab',
            22: 'terima_lain',
            24: 'var_cost',
            26: 'tj_khusus',
            28: 'fix_cost',
            30: 'premi_abs',
            32: 'lembur15',
            34: 'rp_lembur15',
            36: 'lembur2',
            38: 'rp_lembur2',
            40: 'lembur3',
            42: 'rp_lembur3',
            44: 'total_lembur_jam_final',
            46: 'total_rp_lembur',
            48: 'komp_rekontrak',
            50: 'cuti_tahunan',
            52: 'cuti_bersama',
            54: 'sisa_cuti_hari',
            56: 'komp_sisa_cuti',
            58: 'thr',
            60: 'pot_makan',
            62: 'c_pot_upah',
            64: 'pot_upah',
            66: 'c_pot_jam',
            68: 'pot_jam',
            70: 'pendapatan_lain_before_pph',
            72: 'pot_lain_before_pph',
            74: 'bpjs_kes_perusahaan',
            76: 'jkk',
            78: 'jkm',
            80: 'bruto',
            82: 'persen_ter',
            84: 'pot_pph21',
            86: 'after_pph21',
            88: 'jht_perusahaan',
            90: 'jp_perusahaan',
            92: 'pot_jht_karyawan',
            94: 'pot_jp_karyawan',
            96: 'bpjs_kes_karyawan',
            98: 'bpjs_kes_perusahaan',
            100: 'jkk',
            102: 'jkm',
            104: 'pot_piutang',
            106: 'denda_apd',
            108: 'iuran_spsi',
            110: 'pendapatan_lain_after_pph',
            112: 'pot_lain_after_pph',
            114: 'gaji_bersih',
            116: 'bulat',
            118: 'gaji_terima'
        };

        // =========================
        // HEADER
        // =========================
        const headers = [];

        $('#tblhpyemtd_karyawan thead th').each(function () {
            headers.push($(this).text().trim());
        });

        worksheet.addRow(headers);

        const headerRow = worksheet.getRow(1);

        $('#tblhpyemtd_karyawan thead th').each(function (idx) {

            const cell = headerRow.getCell(idx + 1);

            cell.font = {
                bold: true,
                color: { argb: 'FF000000' }
            };

            cell.alignment = {
                horizontal: 'center',
                vertical: 'middle',
                wrapText: true
            };

            cell.border = {
                top: { style: 'thin' },
                left: { style: 'thin' },
                bottom: { style: 'thin' },
                right: { style: 'thin' }
            };

            if ($(this).hasClass('lama')) {

                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFFFEBAA' }
                };

            } else if ($(this).hasClass('baru')) {

                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFC3E6CB' }
                };

            } else {

                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFE9ECEF' }
                };
            }

            if ($(this).hasClass('text-danger')) {

                cell.font = {
                    bold: true,
                    color: { argb: 'FFFF0000' }
                };

            }

        });

        // =========================
		// DATA SEMUA PAGE
		// =========================
		const allRows = dt.rows({ search: 'applied' }).indexes().toArray();

		allRows.forEach(function (data) {

			const rowData = [];

			dt.columns(':visible').every(function () {

				let value = dt.cell(data, this.index()).render('display');

				if (value === null || value === undefined) {
					value = '';
				}

				value = $('<div>')
					.html(value)
					.text()
					.trim();

				rowData.push(value);

			});

			if (rowData.length === 0) {
				return;
			}

			const excelRow = worksheet.addRow(rowData);

			excelRow.eachCell(function (cell) {

				cell.border = {
					top: { style: 'thin' },
					left: { style: 'thin' },
					bottom: { style: 'thin' },
					right: { style: 'thin' }
				};

			});

			const rowObj = dt.row(data).data();

			// =========================
			// compareField() -> bg-danger
			// =========================
			Object.entries(compareMap).forEach(([colIndex, field]) => {

				const nilaiCocokan =
					Number(rowObj?.hpyemtd_cocokan?.[field] ?? 0);

				const nilaiAsli =
					Number(rowObj?.hpyemtd?.[field] ?? 0);

				if (nilaiCocokan !== nilaiAsli) {

					const cell =
						excelRow.getCell(Number(colIndex) + 1);

					cell.fill = {
						type: 'pattern',
						pattern: 'solid',
						fgColor: {
							argb: 'FFDC3545'
						}
					};

					cell.font = {
						color: {
							argb: 'FFFFFFFF'
						},
						bold: true
					};

					const selisih =
						nilaiCocokan - nilaiAsli;

					cell.note =
						`Selisih : ${formatNumber(selisih)}`;
				}

			});

		});

        // =========================
        // STYLE KOLOM
        // =========================
        $('#tblhpyemtd_karyawan thead th').each(function (idx) {

            const th = $(this);
            const col = worksheet.getColumn(idx + 1);

            col.width = Math.max(
                15,
                Math.min(
                    40,
                    th.text().length + 5
                )
            );

            if (th.hasClass('text-right')) {

                col.alignment = {
                    horizontal: 'right'
                };

            }

            if (th.hasClass('text-center')) {

                col.alignment = {
                    horizontal: 'center'
                };

            }

            if (th.hasClass('text-danger')) {

				col.eachCell(function (cell, rowNumber) {

					if (
						rowNumber > 1 &&
						!(
							cell.fill &&
							cell.fill.fgColor &&
							cell.fill.fgColor.argb === 'FFDC3545'
						)
					) {

						cell.font = {
							color: {
								argb: 'FFFF0000'
							}
						};

					}

				});

			}

        });

        // =========================
        // FREEZE HEADER
        // =========================
        worksheet.views = [
            {
                state: 'frozen',
                ySplit: 1
            }
        ];

        // =========================
        // AUTO FILTER
        // =========================
        worksheet.autoFilter = {
            from: 'A1',
            to: worksheet.getRow(1).getCell(headers.length)._address
        };

        const buffer = await workbook.xlsx.writeBuffer();

        saveAs(
            new Blob(
                [buffer],
                {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                }
            ),
            'Payroll.xlsx'
        );

    }
}

    // END breaking generate button
],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 119; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#karyawan_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				},
				rowCallback: function( row, data, index ) {
					compareField(row, data, 18, 'gp');
					compareField(row, data, 20, 't_jab');
					compareField(row, data, 22, 'terima_lain');
					compareField(row, data, 24, 'var_cost');
					compareField(row, data, 26, 'tj_khusus');
					compareField(row, data, 28, 'fix_cost');
					compareField(row, data, 30, 'premi_abs');

					compareField(row, data, 32, 'lembur15');
					compareField(row, data, 34, 'rp_lembur15');
					compareField(row, data, 36, 'lembur2');
					compareField(row, data, 38, 'rp_lembur2');
					compareField(row, data, 40, 'lembur3');
					compareField(row, data, 42, 'rp_lembur3');

					compareField(row, data, 44, 'total_lembur_jam_final');
					compareField(row, data, 46, 'total_rp_lembur');

					compareField(row, data, 48, 'komp_rekontrak');
					compareField(row, data, 50, 'cuti_tahunan');
					compareField(row, data, 52, 'cuti_bersama');
					compareField(row, data, 54, 'sisa_cuti_hari');
					compareField(row, data, 56, 'komp_sisa_cuti');
					compareField(row, data, 58, 'thr');

					compareField(row, data, 60, 'pot_makan');
					compareField(row, data, 62, 'c_pot_upah');
					compareField(row, data, 64, 'pot_upah');
					compareField(row, data, 66, 'c_pot_jam');
					compareField(row, data, 68, 'pot_jam');

					compareField(row, data, 70, 'pendapatan_lain_before_pph');
					compareField(row, data, 72, 'pot_lain_before_pph');

					compareField(row, data, 74, 'bpjs_kes_perusahaan');
					compareField(row, data, 76, 'jkk');
					compareField(row, data, 78, 'jkm');

					compareField(row, data, 80, 'bruto');
					compareField(row, data, 82, 'persen_ter');

					compareField(row, data, 84, 'pot_pph21');

					compareField(row, data, 86, 'after_pph21');

					compareField(row, data, 88, 'jht_perusahaan');
					compareField(row, data, 90, 'jp_perusahaan');

					compareField(row, data, 92, 'pot_jht_karyawan');
					compareField(row, data, 94, 'pot_jp_karyawan');
					compareField(row, data, 96, 'bpjs_kes_karyawan');

					compareField(row, data, 98, 'bpjs_kes_perusahaan');
					compareField(row, data, 100, 'jkk');
					compareField(row, data, 102, 'jkm');

					compareField(row, data, 104, 'pot_piutang');
					compareField(row, data, 106, 'denda_apd');
					compareField(row, data, 108, 'iuran_spsi');

					compareField(row, data, 110, 'pendapatan_lain_after_pph');
					compareField(row, data, 112, 'pot_lain_after_pph');

					compareField(row, data, 114, 'gaji_bersih');
					compareField(row, data, 116, 'bulat');
					compareField(row, data, 118, 'gaji_terima');
				},
				drawCallback: function () {
					$('[data-toggle="tooltip"]').tooltip({
						container: 'body'
					});
				}
			} );

			tblhpyemtd_karyawan.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_karyawan, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
				tblhpyemtd_karyawan.button('btnBreakdown:name').disable();
			} );

			tblhpyemtd_karyawan.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_karyawan.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				nrp       = data_hpyemtd.nrp;
				nama       = data_hpyemtd.nama;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_karyawan );
				detail_breakdown(id_transaksi_d);
				$('#myModal1Label').html(`<b>${nrp} - ${nama}<b>`);
				tblhpyemtd_karyawan.button('btnBreakdown:name').enable();
			} );

			tblhpyemtd_karyawan.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				nrp = '';
				nama = '';
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_karyawan );
				tblhpyemtd_karyawan.button('btnBreakdown:name').disable();
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kontrak = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyemtd_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_kontrak",
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
			
			edthpyemtd_kontrak.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_kontrak.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kontrak.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_kontrak.rows().deselect();
				}
			});

            edthpyemtd_kontrak.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_kontrak.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_kontrak.inError() ) {
					return false;
				}
			});

			edthpyemtd_kontrak.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kontrak.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_kontrak.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_kontrak = $('#tblhpyemtd_kontrak').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyemtd_kontrak.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				columns: [
					{ data: "hpyemtd_cocokan.id", visible:false },
					{ data: "hpyemtd_cocokan.nrp" },
					{ data: "hpyemtd_cocokan.nama" },
					
					{ data: "hovxxmh.nama" },	//divisi
					{ data: "hodxxmh.nama" },	//dep
					{ data: "hosxxmh.nama" },	//unit
					{ data: "hetxxmh.nama" },	//jab
					{ data: "hevgrmh.nama" },	//grup
					{ data: "hobxxmh.nama" },	//Bagian
					{ data: "hevxxmh.nama" },	//Skala
					{ data: "heyxxmd.nama" },	//Sub Tipe
					{ data: "hesxxmh.nama" },	//Status
					{ data: "heyxxmh.nama" },	//Tipe
					{ data: "hemxxmh.gender" },	//Gender

					{ data: "hpyemtd_cocokan.ptkp" },
					{ data: "hpyemtd_cocokan.no_rekening" },
					{ data: "hpyemtd_cocokan.ktp" },
					{ data: "hpyemtd_cocokan.npwp" },

					// GAJI
					{ data: "hpyemtd_cocokan.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.lembur15", class: "text-right" },
					{ data: "hpyemtd.lembur15", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur2", class: "text-right" },
					{ data: "hpyemtd.lembur2", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur3", class: "text-right" },
					{ data: "hpyemtd.lembur3", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd_cocokan.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					// POTONGAN
					{ data: "hpyemtd_cocokan.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },
					{ data: "hpyemtd.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					
					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hpyemtd';
						$table       = 'tblhpyemtd_kontrak';
						$edt         = 'edthpyemtd_kontrak';
						$show_status = '_hpyemtd';
						$table_name  = $nama_tabels_d[6];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 119; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kontrak_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				},
				rowCallback: function( row, data, index ) {
					compareField(row, data, 18, 'gp');
					compareField(row, data, 20, 't_jab');
					compareField(row, data, 22, 'terima_lain');
					compareField(row, data, 24, 'var_cost');
					compareField(row, data, 26, 'tj_khusus');
					compareField(row, data, 28, 'fix_cost');
					compareField(row, data, 30, 'premi_abs');

					compareField(row, data, 32, 'lembur15');
					compareField(row, data, 34, 'rp_lembur15');
					compareField(row, data, 36, 'lembur2');
					compareField(row, data, 38, 'rp_lembur2');
					compareField(row, data, 40, 'lembur3');
					compareField(row, data, 42, 'rp_lembur3');

					compareField(row, data, 44, 'total_lembur_jam_final');
					compareField(row, data, 46, 'total_rp_lembur');

					compareField(row, data, 48, 'komp_rekontrak');
					compareField(row, data, 50, 'cuti_tahunan');
					compareField(row, data, 52, 'cuti_bersama');
					compareField(row, data, 54, 'sisa_cuti_hari');
					compareField(row, data, 56, 'komp_sisa_cuti');
					compareField(row, data, 58, 'thr');

					compareField(row, data, 60, 'pot_makan');
					compareField(row, data, 62, 'c_pot_upah');
					compareField(row, data, 64, 'pot_upah');
					compareField(row, data, 66, 'c_pot_jam');
					compareField(row, data, 68, 'pot_jam');

					compareField(row, data, 70, 'pendapatan_lain_before_pph');
					compareField(row, data, 72, 'pot_lain_before_pph');

					compareField(row, data, 74, 'bpjs_kes_perusahaan');
					compareField(row, data, 76, 'jkk');
					compareField(row, data, 78, 'jkm');

					compareField(row, data, 80, 'bruto');
					compareField(row, data, 82, 'persen_ter');

					compareField(row, data, 84, 'pot_pph21');

					compareField(row, data, 86, 'after_pph21');

					compareField(row, data, 88, 'jht_perusahaan');
					compareField(row, data, 90, 'jp_perusahaan');

					compareField(row, data, 92, 'pot_jht_karyawan');
					compareField(row, data, 94, 'pot_jp_karyawan');
					compareField(row, data, 96, 'bpjs_kes_karyawan');

					compareField(row, data, 98, 'bpjs_kes_perusahaan');
					compareField(row, data, 100, 'jkk');
					compareField(row, data, 102, 'jkm');

					compareField(row, data, 104, 'pot_piutang');
					compareField(row, data, 106, 'denda_apd');
					compareField(row, data, 108, 'iuran_spsi');

					compareField(row, data, 110, 'pendapatan_lain_after_pph');
					compareField(row, data, 112, 'pot_lain_after_pph');

					compareField(row, data, 114, 'gaji_bersih');
					compareField(row, data, 116, 'bulat');
					compareField(row, data, 118, 'gaji_terima');
				},
				drawCallback: function () {
					$('[data-toggle="tooltip"]').tooltip({
						container: 'body'
					});
				}
			} );

			tblhpyemtd_kontrak.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_kontrak, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_kontrak.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_kontrak.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_kontrak );
			} );

			tblhpyemtd_kontrak.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_kontrak );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kbm_reg = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyemtd_kbm_reg.php",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_kbm_reg.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				columns: [
					{ data: "hpyemtd_cocokan.id", visible:false },
					{ data: "hpyemtd_cocokan.nrp" },
					{ data: "hpyemtd_cocokan.nama" },
					
					{ data: "hovxxmh.nama" },	//divisi
					{ data: "hodxxmh.nama" },	//dep
					{ data: "hosxxmh.nama" },	//unit
					{ data: "hetxxmh.nama" },	//jab
					{ data: "hevgrmh.nama" },	//grup
					{ data: "hobxxmh.nama" },	//Bagian
					{ data: "hevxxmh.nama" },	//Skala
					{ data: "heyxxmd.nama" },	//Sub Tipe
					{ data: "hesxxmh.nama" },	//Status
					{ data: "heyxxmh.nama" },	//Tipe
					{ data: "hemxxmh.gender" },	//Gender

					{ data: "hpyemtd_cocokan.ptkp" },
					{ data: "hpyemtd_cocokan.no_rekening" },
					{ data: "hpyemtd_cocokan.ktp" },
					{ data: "hpyemtd_cocokan.npwp" },

					// GAJI
					{ data: "hpyemtd_cocokan.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.lembur15", class: "text-right" },
					{ data: "hpyemtd.lembur15", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur2", class: "text-right" },
					{ data: "hpyemtd.lembur2", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.lembur3", class: "text-right" },
					{ data: "hpyemtd.lembur3", class: "text-right" },
					{ data: "hpyemtd_cocokan.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd.total_lembur_jam_final", class: "text-right" },
					{ data: "hpyemtd_cocokan.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					// POTONGAN
					{ data: "hpyemtd_cocokan.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },
					{ data: "hpyemtd.persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					
					{ data: "hpyemtd_cocokan.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "hpyemtd_cocokan.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd_cocokan.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "hpyemtd.pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "hpyemtd_cocokan.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd_cocokan.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "hpyemtd.gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
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
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 119; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_reg_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				},
				rowCallback: function( row, data, index ) {
					compareField(row, data, 18, 'gp');
					compareField(row, data, 20, 't_jab');
					compareField(row, data, 22, 'terima_lain');
					compareField(row, data, 24, 'var_cost');
					compareField(row, data, 26, 'tj_khusus');
					compareField(row, data, 28, 'fix_cost');
					compareField(row, data, 30, 'premi_abs');

					compareField(row, data, 32, 'lembur15');
					compareField(row, data, 34, 'rp_lembur15');
					compareField(row, data, 36, 'lembur2');
					compareField(row, data, 38, 'rp_lembur2');
					compareField(row, data, 40, 'lembur3');
					compareField(row, data, 42, 'rp_lembur3');

					compareField(row, data, 44, 'total_lembur_jam_final');
					compareField(row, data, 46, 'total_rp_lembur');

					compareField(row, data, 48, 'komp_rekontrak');
					compareField(row, data, 50, 'cuti_tahunan');
					compareField(row, data, 52, 'cuti_bersama');
					compareField(row, data, 54, 'sisa_cuti_hari');
					compareField(row, data, 56, 'komp_sisa_cuti');
					compareField(row, data, 58, 'thr');

					compareField(row, data, 60, 'pot_makan');
					compareField(row, data, 62, 'c_pot_upah');
					compareField(row, data, 64, 'pot_upah');
					compareField(row, data, 66, 'c_pot_jam');
					compareField(row, data, 68, 'pot_jam');

					compareField(row, data, 70, 'pendapatan_lain_before_pph');
					compareField(row, data, 72, 'pot_lain_before_pph');

					compareField(row, data, 74, 'bpjs_kes_perusahaan');
					compareField(row, data, 76, 'jkk');
					compareField(row, data, 78, 'jkm');

					compareField(row, data, 80, 'bruto');
					compareField(row, data, 82, 'persen_ter');

					compareField(row, data, 84, 'pot_pph21');

					compareField(row, data, 86, 'after_pph21');

					compareField(row, data, 88, 'jht_perusahaan');
					compareField(row, data, 90, 'jp_perusahaan');

					compareField(row, data, 92, 'pot_jht_karyawan');
					compareField(row, data, 94, 'pot_jp_karyawan');
					compareField(row, data, 96, 'bpjs_kes_karyawan');

					compareField(row, data, 98, 'bpjs_kes_perusahaan');
					compareField(row, data, 100, 'jkk');
					compareField(row, data, 102, 'jkm');

					compareField(row, data, 104, 'pot_piutang');
					compareField(row, data, 106, 'denda_apd');
					compareField(row, data, 108, 'iuran_spsi');

					compareField(row, data, 110, 'pendapatan_lain_after_pph');
					compareField(row, data, 112, 'pot_lain_after_pph');

					compareField(row, data, 114, 'gaji_bersih');
					compareField(row, data, 116, 'bulat');
					compareField(row, data, 118, 'gaji_terima');
				},
				drawCallback: function () {
					$('[data-toggle="tooltip"]').tooltip({
						container: 'body'
					});
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_kbm_tr.php",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_kbm_tr.php",
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
					left: 2
				},
				columns: [
					{ data: "hpyemtd_cocokan.id",visible:false },
					{ data: "hpyemtd_cocokan.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_cocokan.gp",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.lembur15",
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.lembur2",
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.lembur3",
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right ",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right ",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.gaji_terima",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
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
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 10; i <= 27; i++) {
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
			edthpyemtd_kmj = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth_cocokan/hpyemtd_kmj.php",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_kmj.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_cocokan.id",visible:false },
					{ data: "hpyemtd_cocokan.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_cocokan.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right ",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.gaji_terima",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_freelance.php",
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
					url: "../../models/hpyxxth_cocokan/hpyemtd_freelance.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2
				},
				
				columns: [
					{ data: "hpyemtd_cocokan.id",visible:false },
					{ data: "hpyemtd_cocokan.id_hpyxxth",visible:false },
					{ data: "kode" },
					{ data: "nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hevxxmh.nama",visible:false },
					{ 
						data: "hpyemtd_cocokan.gp",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.t_jab",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.pendapatan_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.var_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.fix_cost",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.premi_abs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jkk",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.trm_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.lembur15",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur15",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.lembur2",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur2",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.lembur3",
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.rp_lembur3",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.jam_lembur_final",
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.lemburbersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.overtime_susulan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right ",
						visible: false,
					},
					{ 
						data: "hpyemtd_cocokan.pph21_back",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.kompensasi_ak",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.koreksi_lembur",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.koreksi_status",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_makan",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_pph21",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jkkjkm",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jht",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_lain",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_upah",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_jam",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_bpjs",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.pot_psiun",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_pinjaman",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_klaim",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd_cocokan.pot_denda_apd",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.gaji_bersih",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.bulat",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{ 
						data: "hpyemtd_cocokan.gaji_terima",
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
			
			var frmUploadMaster = $("#frmUploadMaster").submit(function(e) {
				e.preventDefault();
				// $('#submit_ceklok').hide();
			}).validate({
				rules: {
					filename: "required"
				},
				messages: {
					filename: "Pilih file yang akan di-upload!"
				},
				submitHandler: function(form) { 
					$('#submitUpload').hide();
					let notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup window sampai ada notifikasi hasil upload!'
					},{
						allow_dismiss: false,
						type: 'danger',
						delay: 0,
						element: 'body',
					});

					//item
					var fd_item = new FormData();
					var item = $('#frmUploadItem')[0].files[0];
					if (item != undefined) {
						fd_item.append('filename',item);
						fd_item.append('id_hpyxxth',id_transaksi_h);
			
						$.ajax( {
							url: "../../models/hpyxxth_cocokan/hpyxxth_cocokan_fn_upload.php",
							type: 'POST',
							dataType: 'json',
							data: fd_item,
							contentType: false,
							processData: false,
							success: function ( json ) {
								notifyprogress.close();

								$.notify({
									message: json.data.message
								},{
									type: json.data.type_message
								});

								$("#frmUploadItem").val('');
								tblhpyxxth.ajax.reload(null,false);

								[
									tblhpyemtd_kbm_reg,
									tblhpyemtd_karyawan,
									tblhpyemtd_kontrak,
									tblhpyemtd_kmj,
									tblhpyemtd_freelance,
									tblhpyemtd_kbm_tr
								].forEach(tbl => tbl.ajax.reload(null, false));

								$('#modalUpload').modal('toggle'); 
								$('#submitUpload').show();
							},
							error: function (xhr, Status, err){
								// console.log('x');
							}
						} );
					}
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
