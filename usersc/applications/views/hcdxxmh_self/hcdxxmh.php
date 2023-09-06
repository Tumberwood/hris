<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hcdxxmh';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hcdfmmd';
    $nama_tabels_d[1] = 'hcdedmd';
    $nama_tabels_d[2] = 'hcdjbmd';
    $nama_tabels_d[3] = 'hcdogmd';
    $nama_tabels_d[4] = 'hcddhmd';
    $nama_tabels_d[5] = 'hcdecmd';
    $nama_tabels_d[6] = 'hcdtrmd';
    $nama_tabels_d[7] = 'hcdlgmd';
    $nama_tabels_d[8] = 'hcdrfmd';
?>
  <style>
    .file-preview img {
      max-width: 200px;
      max-height: 200px;
    }

	.fields-info {
		color: red;
		font-size: 9px;
    	font-weight: bold;
	}

  </style>
<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<!-- start Custom Form Datatables Editor -->
				<div class="container">
   		 			<form id="myForm">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#tabpersonal">Personal Data</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tabsosmed">Kontak & Sosmed</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tabdokumen">Dokumen</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#tablain">Lain-lain</a>
							</li>
						</ul>

						<div class="tab-content">
							<div id="tabpersonal" class="tab-pane fade show active">
								<br>
								<div class="row">
									<div class="form-group">
										<label for="id_hcd"></label>
										<input type="text" class="form-control" id="id_hcd" name="id_hcd" disabled>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="hcdxxmh_nama">Nama Lengkap <sup class='text-danger'>*</sup></label>
											<input type="text" class="form-control" name="hcdxxmh.nama" id="hcdxxmh_nama">
										</div>
									</div>
											<!-- <div class="form-group">
											<label for="hcdxxmh_vaksin">Vaksin ke-</label>
											<input type="text" class="form-control" name="hcdxxmh.vaksin" id="hcdxxmh_vaksin">
											</div>  -->
									<div class="col-md-2">
										<div class="form-group">
											<label for="hcdxxmh_gender">Jenis Kelamin <sup class="text-danger">*</sup></label>
											<select class="form-control" name="hcdxxmh.gender" id="hcdxxmh_gender" placeholder="Select">
												<option value="">Select</option>
												<option value="Laki-laki">Laki-laki</option>
												<option value="Perempuan">Perempuan</option>
											</select>
										</div>
									</div>
									
									<div class="col-md-2">
										<div class="form-group">
											<label for="hcdxxmh_tanggal_lahir">Tanggal Lahir <sup class='text-danger'>*</sup></label>
											<input type="date" class="form-control" name="hcdxxmh.tanggal_lahir" id="hcdxxmh_tanggal_lahir" value="DD MMM YYYY" min="1900-01-01">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">												
											<label>Tempat Lahir</label>
											<select class="form-control" id="selectid_gctxxmh_lahir" name="selectid_gctxxmh_lahir"></select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_agama">Agama</label>
											<select class="form-control" name="hcdxxmh.agama" id="hcdxxmh_agama" placeholder="Select">
												<option value="">Select</option>
												<option value="Islam">Islam</option>
												<option value="Kristen">Kristen</option>
												<option value="Katolik">Katolik</option>
												<option value="Hindu">Hindu</option>
												<option value="Budha">Budha</option>
												<option value="Konghucu">Konghucu</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_perkawinan">Status Perkawinan</label>
											<select class="form-control" name="hcdxxmh.perkawinan" id="hcdxxmh_perkawinan" placeholder="Select">
												<option value="">Select</option>
												<option value="Lajang">Lajang</option>
												<option value="Kawin">Kawin</option>
												<option value="Janda">Janda</option>
												<option value="Duda">Duda</option>
												<option value="Cerai">Cerai</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_suku">Suku Bangsa</label>
											<input type="text" class="form-control" name="hcdxxmh.suku" id="hcdxxmh_suku">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdmdmh_gol_darah">Golongan Darah</label>
											<select class="form-control" name="hcdmdmh.gol_darah" id="hcdmdmh_gol_darah">
												<option value="">Select</option>
												<option value="A">A</option>
												<option value="B">B</option>
												<option value="AB">AB</option>
												<option value="O">O</option>
											</select>
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_berat">Berat Badan (kg)</label>
											<input type="text" class="form-control" name="hcdxxmh.berat" id="hcdxxmh_berat">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_tinggi">Tinggi Badan (cm)</label>
											<input type="text" class="form-control" name="hcdxxmh.tinggi" id="hcdxxmh_tinggi">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_no_sepatu">No Sepatu</label>
											<input type="text" class="form-control" name="hcdxxmh.no_sepatu" id="hcdxxmh_no_sepatu">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdxxmh_ukuran_seragam">Ukuran Seragam</label>
											<select class="form-control" name="hcdxxmh.ukuran_seragam" id="hcdxxmh_ukuran_seragam">
												<option value="">Select</option>
												<option value="XS">XS</option>
												<option value="S">S</option>
												<option value="M">M</option>
												<option value="L">L</option>
												<option value="XL">XL</option>
												<option value="XXL">XXL</option>
												<option value="XXXL">XXXL</option>
											</select>
										</div>
									</div>
								</div>	 -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdxxmh_id_files_foto">Foto</label>
											<input type="file" class="form-control-file" name="hcdxxmh_id_files_foto" id="hcdxxmh_id_files_foto"
											onchange="previewImage(this, 'hcdxxmh_id_files_foto_preview')" accept="image/*" required>
											<div class="file-preview">
												<img src="" alt="" id="hcdxxmh_id_files_foto_preview">
												<span class="no-file-text">Belum ada gambar</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdxxmh_id_files_cv">CV</label>
											<input type="file" class="form-control-file" name="hcdxxmh_id_files_cv" id="hcdxxmh_id_files_cv"
												onchange="previewFile(this, 'hcdxxmh_id_files_cv_preview')" accept=".jpg, .jpeg, .png, .pdf" required>
											<div class="file-preview">
												<img src="" alt="" id="hcdxxmh_id_files_cv_preview" style="display: none; max-height: 200px;">
												<iframe src="" frameborder="0" id="hcdxxmh_id_files_cv_preview_pdf" style="display: none; max-height: 200px;"></iframe>
												<span class="no-file-text">Belum ada file</span>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
								</div>
							</div>

							<div id="tabsosmed" class="tab-pane fade">
								<br>
								<!-- <div class="row">
									<div class="col-6">
										<div class="form-group">
											<label for="hcddcmh_asal_sekolah">Asal Sekolah<sup class="text-danger">*</sup></label>
											<input type="text" class="form-control" name="hcddcmh[asal_sekolah]" id="hcddcmh_asal_sekolah">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcddcmh_jurusan">Jurusan</label>
											<input type="text" class="form-control" name="hcddcmh[jurusan]" id="hcddcmh_jurusan">
										</div>
									</div>
								</div> -->
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdcsmh_email_personal">Email Pribadi</label>
											<input type="email" class="form-control" name="hcdcsmh.email_personal" id="hcdcsmh_email_personal">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdcsmh_twitter">Twitter</label>
											<input type="email" class="form-control" name="hcdcsmh.twitter" id="hcdcsmh_twitter">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdcsmh_facebook">Facebook</label>
											<input type="email" class="form-control" name="hcdcsmh.facebook" id="hcdcsmh_facebook">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdcsmh_linkedin">Linkedin</label>
											<input type="email" class="form-control" name="hcdcsmh.linkedin" id="hcdcsmh_linkedin">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdcsmh_instagram">Instagram</label>
											<input type="email" class="form-control" name="hcdcsmh.instagram" id="hcdcsmh_instagram">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdcsmh_tiktok">Tiktok</label>
											<input type="email" class="form-control" name="hcdcsmh.tiktok" id="hcdcsmh_tiktok">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdcsmh_whatsapp">No WhatsApp<sup class="text-danger">*</sup></label>
											<input type="text" class="form-control" name="hcdcsmh.whatsapp" id="hcdcsmh_whatsapp">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label for="hcdcsmh_handphone">No Handphone</label>
											<input type="text" class="form-control" name="hcdcsmh.handphone" id="hcdcsmh_handphone">
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="hcdcsmh_alamat_tinggal">Alamat Tinggal</label>
											<textarea class="form-control" name="hcdcsmh.alamat_tinggal" id="hcdcsmh_alamat_tinggal"></textarea>
											<p class="fields-info">Isi dengan Alamat Tinggal Sekarang <br> <br>
											Contoh: Perumahan Bumi Permai, Jalan Bengawan Solo 17, RT 09 RW 03, Kelurahan Kaliurang, Kecamatan Kebahagiaan, Kota Malang, Jawa Timur</p>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">												
											<label>Kota Tinggal</label>
											<select class="form-control" id="kota_tinggal" name="kota_tinggal"></select>
										</div>
									</div>
								</div>
								<!-- <div class="row">
									<div class="col-md-1">
										<div class="form-group">
											<label for="rt">RT</label>
											<input type="text" class="form-control" name="rt" id="rt">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label for="rw">RW</label>
											<input type="text" class="form-control" name="rw" id="rw">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="kelurahan">Kelurahan</label>
											<input type="text" class="form-control" name="kelurahan" id="kelurahan">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="kecamatan">Kecamatan</label>
											<input type="text" class="form-control" name="kecamatan" id="kecamatan">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">												
											<label>Kota Tinggal</label>
											<select class="form-control" id="kota_tinggal" name="kota_tinggal"></select>
										</div>
									</div>
								</div> -->
							</div>

							<div id="tabdokumen" class="tab-pane fade">
								<br>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcddcmh_ktp_no">No KTP<sup class="text-danger">*</sup></label>
											<input type="text" class="form-control" name="hcddcmh.ktp_no" id="hcddcmh_ktp_no">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcddcmh_ktp_alamat">Alamat KTP</label>
											<textarea class="form-control" name="hcddcmh.ktp_alamat" id="hcddcmh_ktp_alamat"></textarea>
											<p class="fields-info">Isi dengan Alamat KTP dan Gunakan Format seperti mengisi Alamat Tinggal</p>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">												
											<label>Kota KTP</label>
											<select class="form-control" id="kota_ktp" name="kota_ktp"></select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcddcmh_sim_a">SIM A</label>
											<input type="text" class="form-control" name="hcddcmh.sim_a" id="hcddcmh_sim_a">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcddcmh_sim_b">SIM B</label>
											<input type="text" class="form-control" name="hcddcmh.sim_b" id="hcddcmh_sim_b">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcddcmh_sim_c">SIM C</label>
											<input type="text" class="form-control" name="hcddcmh.sim_c" id="hcddcmh_sim_c">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="hcddcmh_is_npwp">Ber-NPWP</label>
											<select class="form-control" name="hcddcmh.is_npwp" id="hcddcmh_is_npwp">
												<option value="">Select</option>
												<option value="1">Ya</option>
												<option value="2">Tidak</option>
											</select>
										</div>
									</div>
								</div>
								<div id="npwp" style="display: none;">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="hcddcmh_npwp_no">No NPWP</label>
												<input type="text" class="form-control" name="hcddcmh.npwp_no" id="hcddcmh_npwp_no">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="hcddcmh_npwp_alamat">Alamat NPWP</label>
												<textarea class="form-control" name="hcddcmh.npwp_alamat" id="hcddcmh_npwp_alamat"></textarea>
												<p class="fields-info" style="color: red; font-size: 7px; font-weight: bold;">Isi dengan Alamat NPWP dan Gunakan Format seperti mengisi Alamat Tinggal</p>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label>Kota NPWP</label>
												<select class="form-control" id="hcddcmh_id_gctxxmh_npwp" name="hcddcmh_id_gctxxmh_npwp"></select>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label>Status PTKP</label>
												<select class="form-control" id="hcddcmh_id_gtxpkmh" name="hcddcmh_id_gtxpkmh"></select>
											</div>
										</div>
									</div>
								</div>
								<!-- BELUM ADA DI MODELS -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_bpjskes_no">No BPJS Kesehatan</label>
											<input type="text" class="form-control" name="hcdjbmh.bpjskes_no" id="hcdjbmh_bpjskes_no">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_bpjstk_no">No BPJS Ketenagakerjaan</label>
											<input type="text" class="form-control" name="hcdjbmh.bpjstk_no" id="hcdjbmh_bpjstk_no">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcddcmh_id_files_ktp">Foto KTP</label>
											<input type="file" class="form-control-file" name="hcddcmh.id_files_ktp" id="hcddcmh_id_files_ktp"
												onchange="previewImage(this, 'hcddcmh_id_files_ktp_preview')">
											<div class="file-preview">
												<img src="" alt="" id="hcddcmh_id_files_ktp_preview">
												<span class="no-file-text">Belum ada gambar</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcddcmh_id_files_sim">Foto SIM </label>
											<input type="file" class="form-control-file" name="hcddcmh.id_files_sim" id="hcddcmh_id_files_sim"
												onchange="previewImage(this, 'hcddcmh_id_files_sim_preview')">
											<div class="file-preview">
												<img src="" alt="" id="hcddcmh_id_files_sim_preview">
												<span class="no-file-text">Belum ada gambar</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div id="tablain" class="tab-pane fade">
								<br>
								<div class="row">
									<div class="col-md-12">
										<div class="form-group">
											<label for="hcdjbmh_tempat_tinggal">Rumah Tempat Tinggal</label>
											<select class="form-control" name="hcdjbmh.tempat_tinggal" id="hcdjbmh_tempat_tinggal">
												<option value="">Select</option>
												<option value="Milik Sendiri">Milik Sendiri</option>
												<option value="Orang Tua">Orang Tua</option>
												<option value="Sewa/Kontrak">Sewa/Kontrak</option>
												<option value="Kost">Kost</option>
												<option value="Lain-lain">Lain-lain</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_kendaraan">Kendaraan</label>
											<select class="form-control" name="hcdjbmh.kendaraan" id="hcdjbmh_kendaraan">
												<option value="">Select</option>
												<option value="Mobil">Mobil</option>
												<option value="Motor">Motor</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_kendaraan_milik">Kendaraan Milik</label>
											<select class="form-control" name="hcdjbmh.kendaraan_milik" id="hcdjbmh_kendaraan_milik">
												<option value="">Select</option>
												<option value="Sendiri">Sendiri</option>
												<option value="Orang Tua">Orang Tua</option>
												<option value="Suami/Istri">Suami/Istri</option>
												<option value="Kantor">Kantor</option>
												<option value="Lain-lain">Lain-lain</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_1">Apakah anda pernah melamar di group perusahaan ini sebelumnya? Kapan dan sebagai apa?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_1" id="hcdjbmh_intrv_self_1"></textarea>
											<p class="fields-info"> Contoh: YA, Pada Tanggal 10 Juli 2023 sebagai Junior Web Developer. <br>
																	Contoh: TIDAK, Saya tidak pernah melamar di group perusahaan ini.
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_2">Selain di perusahaan ini, di Perusahaan mana lagi anda melamar saat ini dan sebagai apa?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_2" id="hcdjbmh_intrv_self_2"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_3">Apakah anda mempunyai pekerjaan sampingan? Jelaskan.</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_3" id="hcdjbmh_intrv_self_3"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_4">Apakah anda mempunyai teman/keluarga yang bekerja di group perusahaan ini? Jelaskan.</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_4" id="hcdjbmh_intrv_self_4"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_5">Apakah anda pernah menderita sakit kronis / sakit keras / kecelakaan / operasi? Jelaskan.</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_5" id="hcdjbmh_intrv_self_5"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_6">Apakah anda pernah berurusan dengan polisi karena tindak kejahatan?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_6" id="hcdjbmh_intrv_self_6"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_7">Apakah anda bersedia bertugas keluar kota?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_7" id="hcdjbmh_intrv_self_7"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_8">Apakah anda bersedia ditempatkan keluar kota? Sebutkan nama kotanya</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_8" id="hcdjbmh_intrv_self_8"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_9">Jenis pekerjaan apa yang anda sukai?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_9" id="hcdjbmh_intrv_self_9"></textarea>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_10">Jenis pekerjaan apa yang tidak anda suka?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_10" id="hcdjbmh_intrv_self_10"></textarea>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_11">Gaji yang diharapkan</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_11" id="hcdjbmh_intrv_self_11"></textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_12">Fasilitas yang diharapkan</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_12" id="hcdjbmh_intrv_self_12"></textarea>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="hcdjbmh_intrv_self_13">Kapankah anda dapat mulai bekerja?</label>
											<textarea class="form-control" name="hcdjbmh.intrv_self_13" id="hcdjbmh_intrv_self_13"></textarea>
										</div>
									</div>
								</div>
							</div>	
								<button type="button" id="update" class="btn btn-primary">Update</button>
								<button type="button" id="create" class="btn btn-primary">Create</button>
							</div>
						</div>
					</form>
				</div>
				
				<div class="table-responsive">
					<br>
                    <legend>Detail</legend>
			
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhcdfmmd"> Keluarga</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdedmd"> Pendidikan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdjbmd"> Pekerjaan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdogmd"> Organisasi</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcddhmd"> Riwayat Penyakit</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdecmd"> Kontak Darurat</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdtrmd"> Pelatihan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdlgmd"> Bahasa</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhcdrfmd"> Referensi</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhcdfmmd" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdfmmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Hubungan</th>
													<th>Nama</th>
													<th>Jenis Kelamin</th>
													<th>Tempat Lahir</th>
													<th>Tanggal Lahir</th>
													<th>Pendidikan Terakhir</th>
													<th>Pekerjaan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhcdedmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdedmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Jenjang</th>
													<th>Nama</th>
													<th>Kota</th>
													<th>Tahun Lulus</th>
													<th>Jurusan</th>
													<th>Nilai Akhir</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcdjbmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdjbmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Perusahaan</th>
													<th>Jenis Usaha</th>
													<th>Alamat</th>
													<th>Kota</th>
													<th>Tanggal Awal</th>
													<th>Tanggal Akhir</th>
													<th>Jabatan Awal</th>
													<th>Jabatan Akhir</th>
													<th>Atasan Langsung</th>
													<th>Gaji Terakhir</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcdogmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdogmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Nama</th>
													<th>Jenis Organisasi</th>
													<th>Tahun</th>
													<th>Jabatan</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcddhmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcddhmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Jenis Penyakit</th>
													<th>Tahun</th>
													<th>Berapa Lama</th>
													<th>Tempat Dirawat</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcdecmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdecmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Nama</th>
													<th>Alamat</th>
													<th>No HP</th>
													<th>Hubungan</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div> 
							<div role="tabpanel" id="tabhcdtrmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdtrmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Nama</th>
													<th>Lembaga</th>
													<th>Bersertifikat</th>
													<th>Tanggal Mulai</th>
													<th>Tanggal Selesai</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcdlgmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdlgmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Bahasa</th>
													<th>Membaca</th>
													<th>Mendengar</th>
													<th>Menulis</th>
													<th>Percakapan</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhcdrfmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhcdrfmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hcdxxmh</th>
													<th>Nama</th>
													<th>Alamat</th>
													<th>Pekerjaan</th>
													<th>No HP</th>
													<th>Hubungan</th>
												</tr>
											</thead>
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
</div>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hcdxxmh_self/fn/hcdxxmh_fn.php'; ?>

<script>
	// ------------- default variable, do not erase
	var edthcdxxmh, tblhcdxxmh, show_inactive_status_hcdxxmh = 0, id_hcdxxmh;
	var edthcdfmmd, tblhcdfmmd, show_inactive_status_hcdfmmd = 0, id_hcdfmmd;
	var edthcdedmd, tblhcdedmd, show_inactive_status_hcdedmd = 0, id_hcdedmd;
	var edthcdjbmd, tblhcdjbmd, show_inactive_status_hcdjbmd = 0, id_hcdjbmd;
	var edthcdogmd, tblhcdogmd, show_inactive_status_hcdogmd = 0, id_hcdogmd;
	var edthcddhmd, tblhcddhmd, show_inactive_status_hcddhmd = 0, id_hcddhmd;
	var edthcdecmd, tblhcdecmd, show_inactive_status_hcdecmd = 0, id_hcdecmd;
	var edthcdtrmd, tblhcdtrmd, show_inactive_status_hcdtrmd = 0, id_hcdtrmd;
	var edthcdlgmd, tblhcdlgmd, show_inactive_status_hcdlgmd = 0, id_hcdlgmd;
	var edthcdrfmd, tblhcdrfmd, show_inactive_status_hcdrfmd = 0, id_hcdrfmd;
	// ------------- end of default variable
		var id_gctxxmh_lahir_old = 0;
		id_gctxxmh_tinggal_old = 0;
		id_gctxxmh_lahir_fam_old = 0;
		id_gctxxmh_ktp_old = 0;
		id_gctxxmh_edu_old = 0;
		id_gctxxmh_job_old = 0;
		id_hetxxmh_old = 0;
		id_gedxxmh_fam_old = 0;
		id_gedxxmh_edu_old = 0;
		
	//hide id_hcd
	document.getElementById('id_hcd').style.display = 'none';

    function previewImage(input, previewId) {
		var preview = document.getElementById(previewId);
		var file = input.files[0];
		var reader = new FileReader();

		reader.onloadend = function () {
			preview.src = reader.result;
		}

		if (file) {
			var allowedFormats = ['jpg', 'jpeg', 'png']; // Supported photo formats

			var fileFormat = file.name.split('.').pop().toLowerCase();
			if (!allowedFormats.includes(fileFormat)) {
			alert("File harus berformat PNG, JPG, atau JPEG.");
			input.value = ""; // Reset the file input
			preview.src = "";
			preview.style.display = "none";
			preview.nextElementSibling.style.display = "block"; // Show the "Belum ada gambar" text
			return;
			}

			if (file.size <= 500 * 1024) { // Maximum file size: 500 KB
			reader.readAsDataURL(file);
			preview.style.display = "block";
			preview.nextElementSibling.style.display = "none"; // Hide the "Belum ada gambar" text
			} else {
			alert("Foto maksimal 500 KB.");
			input.value = ""; // Reset the file input
			preview.src = "";
			preview.style.display = "none";
			preview.nextElementSibling.style.display = "block"; // Show the "Belum ada gambar" text
			}
		} else {
			preview.src = "";
			preview.style.display = "none";
			preview.nextElementSibling.style.display = "block"; // Show the "Belum ada gambar" text
		}
	}

    function previewFile(input, previewId) {
		var preview = document.getElementById(previewId);
		var file = input.files[0];
		var reader = new FileReader();

		console.log(reader.result);

		reader.onloadend = function () {
			if (file.type === 'application/pdf') {
				$('#hcdxxmh_id_files_cv_preview_pdf').attr('src', reader.result);
				$('#hcdxxmh_id_files_cv_preview_pdf').css('display', 'block');
				$('#hcdxxmh_id_files_cv_preview').css('display', 'none');
				$('.no-file-text').css('display', 'none');
			} else {
				$('#hcdxxmh_id_files_cv_preview').attr('src', reader.result);
				$('#hcdxxmh_id_files_cv_preview').css('display', 'block');
				$('#hcdxxmh_id_files_cv_preview_pdf').css('display', 'none');
				$('.no-file-text').css('display', 'none');
			}
		};



		if (file) {
			var allowedFormats = ['jpg', 'jpeg', 'png', 'pdf'];
			var fileExtension = file.name.split('.').pop().toLowerCase();

			if (!allowedFormats.includes(fileExtension)) {
			alert('File harus berformat PNG, JPG, JPEG, atau PDF.');
			input.value = '';
			preview.src = '';
			preview.style.display = 'none';
			preview.nextElementSibling.style.display = 'block';
			return;
			}

			if (file.size <= 2000 * 1024) {
			reader.readAsDataURL(file);
			} else {
			alert('File maksimal 2 MB.');
			input.value = '';
			preview.src = '';
			preview.style.display = 'none';
			preview.nextElementSibling.style.display = 'block';
			}
		} else {
			preview.src = '';
			preview.style.display = 'none';
			preview.nextElementSibling.style.display = 'block';
		}
	}




	$(document).ready(function() {
    // Make an AJAX request when the page loads
		$.ajax({
			url: '../../models/hcdxxmh/hcdxxmh_self.php',
			type: 'GET',
			success: function(data) {
				var parsedData = JSON.parse(data);

				if (parsedData.id_hcd > 0) {
					$('#update').show();
					$('#create').hide();
				} else {
					$('#update').hide();
					$('#create').show();
				}
				//PERSONAL DATA
				$('#id_hcd').val(parsedData.id_hcd);
				$('#hcdxxmh_nama').val(parsedData.nama);
				$('#hcdxxmh_gender').val(parsedData.gender);
				$('#hcdxxmh_tanggal_lahir').val(parsedData.tanggal_lahir);
				
				if (parsedData.tempat_lahir !== 0 && parsedData.tempat_lahir !== null) {
					select2Kota($('#selectid_gctxxmh_lahir'), parsedData.tempat_lahir);
					$('#selectid_gctxxmh_lahir').select2('open');

					setTimeout(function() {
						$('#selectid_gctxxmh_lahir').select2('close');
					}, 5);
				}

				$('#hcdxxmh_agama').val(parsedData.agama);
				$('#hcdxxmh_perkawinan').val(parsedData.perkawinan);
				$('#hcdxxmh_suku').val(parsedData.suku);
				$('#hcdmdmh_gol_darah').val(parsedData.gol_darah);
				// $('#hcdxxmh_berat').val(parsedData.berat);
				// $('#hcdxxmh_tinggi').val(parsedData.tinggi);
				// $('#hcdxxmh_no_sepatu').val(parsedData.no_sepatu);
				// $('#hcdxxmh_ukuran_seragam').val(parsedData.ukuran_seragam);
				// $('#hcddcmh_asal_sekolah').val(parsedData.asal_sekolah);
				// $('#hcddcmh_jurusan').val(parsedData.jurusan);
				
				//SOSMED
				$('#hcdcsmh_email_personal').val(parsedData.email_personal);
				$('#hcdcsmh_twitter').val(parsedData.twitter);
				$('#hcdcsmh_facebook').val(parsedData.facebook);
				$('#hcdcsmh_linkedin').val(parsedData.linkedin);
				$('#hcdcsmh_instagram').val(parsedData.instagram);
				$('#hcdcsmh_tiktok').val(parsedData.tiktok);
				$('#hcdcsmh_handphone').val(parsedData.handphone);
				$('#hcdcsmh_whatsapp').val(parsedData.whatsapp);
				$('#hcdcsmh_alamat_tinggal').val(parsedData.alamat_tinggal);
				if (parsedData.kota_tinggal !== 0 && parsedData.kota_tinggal !== null) {
					select2Kota($('#kota_tinggal'), parsedData.kota_tinggal);
					$('#kota_tinggal').select2('open');
				
					setTimeout(function() {
						$('#kota_tinggal').select2('close');
					}, 5);
				}
				// $('#rt').val(parsedData.rt);
				// $('#rw').val(parsedData.rw);
				// $('#kelurahan').val(parsedData.kelurahan);
				// $('#kecamatan').val(parsedData.kecamatan);

				//DOKUMEN
				$('#hcddcmh_ktp_no').val(parsedData.ktp_no);
				$('#hcddcmh_ktp_alamat').val(parsedData.ktp_alamat);
				if (parsedData.tempat_ktp !== 0 && parsedData.tempat_ktp !== null) {
					select2Kota($('#kota_ktp'), parsedData.tempat_ktp);
					$('#kota_ktp').select2('open');
				
					setTimeout(function() {
						$('#kota_ktp').select2('close');
					}, 5);
				}
				$('#hcddcmh_sim_a').val(parsedData.sim_a);
				$('#hcddcmh_sim_b').val(parsedData.sim_b);
				$('#hcddcmh_sim_c').val(parsedData.sim_c);
				if (parsedData.is_npwp === '1') {
					$('#npwp').css('display', 'block');
				}
				$('#hcddcmh_is_npwp').val(parsedData.is_npwp);
				$('#hcddcmh_npwp_no').val(parsedData.npwp_no);
				$('#hcddcmh_npwp_alamat').val(parsedData.npwp_alamat);

				if (parsedData.id_gctxxmh_npwp !== 0 && parsedData.id_gctxxmh_npwp !== null) {
					select2Kota($('#hcddcmh_id_gctxxmh_npwp'), parsedData.id_gctxxmh_npwp);
					$('#hcddcmh_id_gctxxmh_npwp').select2('open');

					setTimeout(function() {
						$('#hcddcmh_id_gctxxmh_npwp').select2('close');
					}, 5);
				}

				if (parsedData.id_gtxpkmh !== 0 && parsedData.id_gtxpkmh !== null) {
					select2Ptkp($('#hcddcmh_id_gtxpkmh'), parsedData.id_gtxpkmh);
					$('#hcddcmh_id_gtxpkmh').select2('open');

					setTimeout(function() {
						$('#hcddcmh_id_gtxpkmh').select2('close');
					}, 5);
				}


				// $('#hobby').val(parsedData.hobby);

				//LAIN-LAIN
				$('#hcdjbmh_bpjskes_no').val(parsedData.bpjskes_no);
				$('#hcdjbmh_bpjstk_no').val(parsedData.bpjstk_no);
				
				$('#hcdjbmh_tempat_tinggal').val(parsedData.tempat_tinggal);
				$('#hcdjbmh_kendaraan').val(parsedData.kendaraan);
				$('#hcdjbmh_kendaraan_milik').val(parsedData.kendaraan_milik);

				//LOOP interview self lain-lain
				for (var i = 1; i <= 13; i++) {
					$('#hcdjbmh_intrv_self_' + i).val(parsedData['intrv_self_' + i]);
				}

				//FOTO KANDIDAT
				if (parsedData.foto) {
					// Update the src attribute of the <img> element with parsedData.foto
					$('#hcdxxmh_id_files_foto_preview').attr('src', parsedData.foto);

					// Show the <img> element and hide "Belum ada gambar" text
					$('#hcdxxmh_id_files_foto_preview').css('display', 'block');
					$('.no-file-text').css('display', 'none');
				}
				//FOTO KTP
				if (parsedData.ktp) {
					// Update the src attribute of the <img> element with parsedData.ktp
					$('#hcddcmh_id_files_ktp_preview').attr('src', parsedData.ktp);

					// Show the <img> element and hide "Belum ada gambar" text
					$('#hcddcmh_id_files_ktp_preview').css('display', 'block');
					$('.no-file-text').css('display', 'none');
				}
				//FOTO SIM
				if (parsedData.sim) {
					// Update the src attribute of the <img> element with parsedData.sim
					$('#hcddcmh_id_files_sim_preview').attr('src', parsedData.sim);

					// Show the <img> element and hide "Belum ada gambar" text
					$('#hcddcmh_id_files_sim_preview').css('display', 'block');
					$('.no-file-text').css('display', 'none');
				}

				//cv KANDIDAT
				if (parsedData.cv) {
					var is_pdf = parsedData.cv.substr(-3);
					if (is_pdf == 'pdf') {
						$('#hcdxxmh_id_files_cv_preview_pdf').attr('src', parsedData.cv);
						$('#hcdxxmh_id_files_cv_preview_pdf').css('display', 'block');
						$('.no-file-text').css('display', 'none');
					} else {
						$('#hcdxxmh_id_files_cv_preview').attr('src', parsedData.cv);
						$('#hcdxxmh_id_files_cv_preview').css('display', 'block');
						$('.no-file-text').css('display', 'none');
					}
				}

				//END OF FETCH DATA

				//BEGIN UPDATE
				updateHeader(parsedData.id_hcd);

				//CREATE
				createHeader();

				//deklarasi var id_hcdxxmh untuk DETAIL
				id_hcdxxmh =  $('#id_hcd').val();
				// atur hak akses
				var familyData = family(id_hcdxxmh);
				var educationData = education(id_hcdxxmh);
				var jobsData = jobs(id_hcdxxmh);
				var organisasiData = organisasi(id_hcdxxmh);
				var penyakitData = penyakit(id_hcdxxmh);
				var kontakDaruratData = kontakDarurat(id_hcdxxmh);
				var pelatihanData = pelatihan(id_hcdxxmh);
				var bahasaData = bahasa(id_hcdxxmh);
				var referensiData = referensi(id_hcdxxmh);
			}
		});
	});


</script>

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
