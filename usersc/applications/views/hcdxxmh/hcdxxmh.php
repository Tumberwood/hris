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

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<!-- start Custom Form Datatables Editor -->
				<div id="frmhcdxxmh">
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabpersonaldata"> Personal Data</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabsosmed"> Kontak & SosMed</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabdokumen"> Dokumen</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tablain"> Lain-lain</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabpersonaldata" class="tab-pane active">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.nama"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.gender"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.tanggal_lahir"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.id_gctxxmh_lahir"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.agama"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.perkawinan"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.suku"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdmdmh.gol_darah"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.id_files_foto"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdxxmh.id_files_cv"></editor-field>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabsosmed" class="tab-pane">
								<div class="panel-body">
									<!-- <div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.asal_sekolah"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.jurusan"></editor-field>
										</div>
									</div> -->
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.email_personal"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.whatsapp"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.twitter"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.facebook"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.linkedin"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.instagram"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.tiktok"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.handphone"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.alamat_tinggal"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdcsmh.id_gctxxmh_tinggal"></editor-field>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabdokumen" class="tab-pane">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.ktp_no"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.ktp_alamat"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.id_gctxxmh_ktp"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.sim_a"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.sim_b"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.sim_c"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.is_npwp"></editor-field>
										</div>
									</div>
									<div class="row npwp">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.npwp_no"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.npwp_alamat"></editor-field>
										</div>
									</div>
									<div class="row npwp">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.id_gctxxmh_npwp"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.id_gtxpkmh"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.bpjskes_no"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.bpjstk_no"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcddcmh.id_files_ktp"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcddcmh.id_files_sim"></editor-field>
										</div>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tablain" class="tab-pane">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.tempat_tinggal"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.kendaraan"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.kendaraan_milik"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_1"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_2"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_3"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_4"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_5"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_6"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_7"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_8"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_9"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_10"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_11"></editor-field>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_12"></editor-field>
										</div>
										<div class="col-lg-6">
											<editor-field name="hcdjbmh.intrv_self_13"></editor-field>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end Custom Form Datatables Editor -->
				
				<div class="table-responsive">
                    <table id="tblhcdxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Lengkap</th>
								<th>No. KTP</th>
								<th>No. WhatsApp</th>
								<th>Alamat Tinggal</th>
                            </tr>
                        </thead>
                    </table>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hcdxxmh/fn/hcdxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
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
		id_gctxxmh_npwp_old = 0;
		id_gtxpkmh_old = 0;
		id_gctxxmh_edu_old = 0;
		id_gctxxmh_job_old = 0;
		id_hetxxmh_old = 0;
		id_gedxxmh_fam_old = 0;
		id_gedxxmh_edu_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthcdxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdxxmh = show_inactive_status_hcdxxmh;
					}
				},
				table: "#tblhcdxxmh",
				template: "#frmhcdxxmh",
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
						def: "hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdxxmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Foto ",
						name: "hcdxxmh.id_files_foto",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthcdxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					},	{
						label: "Sertifikat Vaksin ",
						name: "hcdxxmh.id_files_vaksin",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthcdxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					},	{
						label: "Foto KTP",
						name: "hcddcmh.id_files_ktp",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthcdxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					},	{
						label: "Foto SIM ",
						name: "hcddcmh.id_files_sim",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthcdxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					},	{
						label: "File CV",
						name: "hcdxxmh.id_files_cv",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthcdxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					}, 	
					{
						label: "Rumah Tempat Tinggal",
						name: "hcdjbmh.tempat_tinggal",
						type: "select",
						options: [
							{ "label": "Select", "value": "" },
							{ "label": "Milik Sendiri", "value": "Milik Sendiri" },
							{ "label": "Orang Tua", "value": "Orang Tua" },
							{ "label": "Sewa/Kontrak", "value": "Sewa/Kontrak" },
							{ "label": "Kost", "value": "Kost" },
							{ "label": "Lain-lain", "value": "Lain-lain" }
						]
					},
					{
						label: "Kendaraan",
						name: "hcdjbmh.kendaraan",
						type: "select",
						options: [
							{ "label": "Select", "value": "" },
							{ "label": "Mobil", "value": "Mobil" },
							{ "label": "Motor", "value": "Motor" }
						]
					},
					{
						label: "Kendaraan Milik",
						name: "hcdjbmh.kendaraan_milik",
						type: "select",
						options: [
							{ "label": "Select", "value": "" },
							{ "label": "Sendiri", "value": "Sendiri" },
							{ "label": "Orang Tua", "value": "Orang Tua" },
							{ "label": "Suami/Istri", "value": "Suami/Istri" },
							{ "label": "Kantor", "value": "Kantor" },
							{ "label": "Lain-lain", "value": "Lain-lain" }
						]
					},	{
						label: "Apakah anda pernah melamar di group perusahaan ini sebelumnya? Kapan dan sebagai apa?",
						name: "hcdjbmh.intrv_self_1",
						type: "textarea"
					},	{
						label: "Selain di perusahaan ini, di Perusahaan mana lagi anda melamar saat ini dan sebagai apa?",
						name: "hcdjbmh.intrv_self_2",
						type: "textarea"
					},	{
						label: "Apakah anda mempunyai pekerjaan sampingan? Jelaskan.",
						name: "hcdjbmh.intrv_self_3",
						type: "textarea"
					},	{
						label: "Apakah anda mempunyai teman/keluarga yang bekerja di group perusahaan ini? Jelaskan.",
						name: "hcdjbmh.intrv_self_4",
						type: "textarea"
					},	{
						label: "Apakah anda pernah menderita sakit kronis / sakit keras / kecelakaan / operasi? Jelaskan.",
						name: "hcdjbmh.intrv_self_5",
						type: "textarea"
					},	{
						label: "Apakah anda pernah berurusan dengan polisi karena tindak kejahatan?",
						name: "hcdjbmh.intrv_self_6",
						type: "textarea"
					},	{
						label: "Apakah anda bersedia bertugas keluar kota?",
						name: "hcdjbmh.intrv_self_7",
						type: "textarea"
					},	{
						label: "Apakah anda bersedia ditempatkan keluar kota? Sebutkan nama kotanya",
						name: "hcdjbmh.intrv_self_8",
						type: "textarea"
					},	{
						label: "Jenis pekerjaan apa yang anda sukai?",
						name: "hcdjbmh.intrv_self_9",
						type: "textarea"
					},	{
						label: "Jenis pekerjaan apa yang tidak anda suka?",
						name: "hcdjbmh.intrv_self_10",
						type: "textarea"
					},	{
						label: "Gaji yang diharapkan",
						name: "hcdjbmh.intrv_self_11",
						type: "textarea"
					},	{
						label: "Fasilitas yang diharapkan",
						name: "hcdjbmh.intrv_self_12",
						type: "textarea"
					},	{
						label: "Kapankah anda dapat mulai bekerja?",
						name: "hcdjbmh.intrv_self_13",
						type: "textarea"
					},	{
						label: "Nama Lengkap <sup class='text-danger'>*<sup>",
						name: "hcdxxmh.nama"
					}, 	{
						label: "Suku Bangsa",
						name: "hcdxxmh.suku"
					}, 	{
						label: "Berat Badan (kg)",
						name: "hcdxxmh.berat"
					}, 	{
						label: "Tinggi Badan (cm)",
						name: "hcdxxmh.tinggi"
					}, 	{
						label: "Vaksin ke-",
						name: "hcdxxmh.vaksin"
					},	{
						label: "Tanggal Lahir <sup class='text-danger'>*<sup>",
						name: "hcdxxmh.tanggal_lahir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Tempat Lahir <sup class='text-danger'>*<sup>",
						name: "hcdxxmh.id_gctxxmh_lahir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_lahir_old,
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
					},	{
						label: "Jenis Kelamin <sup class='text-danger'>*<sup>",
						name: "hcdxxmh.gender",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Laki-laki", "value": "Laki-laki" },
							{ "label": "Perempuan", "value": "Perempuan" }
						]
					},	{
						label: "Golongan Darah",
						name: "hcdmdmh.gol_darah",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "A", "value": "A" },
							{ "label": "B", "value": "B" },
							{ "label": "AB", "value": "AB" },
							{ "label": "O", "value": "O" }
						]
					},	{
						label: "Berkacamata",
						name: "hcdmdmh.is_kacamata",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},	{
						label: "Jenis Kacamata",
						name: "hcdmdmh.jenis_kacamata",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Minus", "value": "Minus" },
							{ "label": "Plus", "value": "Plus" },
							{ "label": "Silinder", "value": "Silinder" }
						]
					},	{
						label: "Agama",
						name: "hcdxxmh.agama",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Islam", "value": "Islam" },
							{ "label": "Kristen", "value": "Kristen" },
							{ "label": "Katolik", "value": "Katolik" },
							{ "label": "Hindu", "value": "Hindu" },
							{ "label": "Budha", "value": "Budha" },
							{ "label": "Konghucu", "value": "Konghucu" }
						]
					},	{
						label: "Status Perkawinan",
						name: "hcdxxmh.perkawinan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Lajang", "value": "Lajang"},
							{ "label": "Kawin", "value": "Kawin"},
							{ "label": "Janda", "value": "Janda"},
							{ "label": "Duda", "value": "Duda"},
							{ "label": "Cerai", "value": "Cerai"}
						]
					}, 	{
						label: "Email Pribadi",
						name: "hcdcsmh.email_personal"
					}, 	{
						label: "No. WhatsApp<sup class='text-danger'>*<sup>",
						name: "hcdcsmh.whatsapp"
					}, 	{
						label: "Twitter",
						name: "hcdcsmh.twitter"
					}, 	{
						label: "Facebook",
						name: "hcdcsmh.facebook"
					}, 	{
						label: "Linkedin",
						name: "hcdcsmh.linkedin"
					}, 	{
						label: "Instagram",
						name: "hcdcsmh.instagram"
					}, 	{
						label: "Tiktok",
						name: "hcdcsmh.tiktok"
					}, 	{
						label: "No Handphone",
						name: "hcdcsmh.handphone"
					}, 	{
						label: "Alamat Tinggal<sup class='text-danger'>*<sup>",
						name: "hcdcsmh.alamat_tinggal",
						type: "textarea"
					}, 	{
						label: "Kota Tinggal",
						name: "hcdcsmh.id_gctxxmh_tinggal",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_tinggal_old,
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
					}, 	{
						label: "No. KTP<sup class='text-danger'>*<sup>",
						name: "hcddcmh.ktp_no"
					}, 	{
						label: "Alamat KTP",
						name: "hcddcmh.ktp_alamat",
						type: "textarea"
					},	{
						label: "Alamat NPWP",
						name: "hcddcmh.npwp_alamat",
						type: "textarea"
					},	{
						label: "Kota KTP",
						name: "hcddcmh.id_gctxxmh_ktp",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_ktp_old,
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
					},	{
						label: "Kota NPWP",
						name: "hcddcmh.id_gctxxmh_npwp",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_npwp_old,
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
					},	{
						label: "Status PTKP",
						name: "hcddcmh.id_gtxpkmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/gtxpkmh/gtxpkmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gtxpkmh_old: id_gtxpkmh_old,
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
					},	{
						label: "SIM A",
						name: "hcddcmh.sim_a"
					},	{
						label: "SIM B",
						name: "hcddcmh.sim_b"
					},	{
						label: "SIM C",
						name: "hcddcmh.sim_c"
					},	{
						label: "RT",
						name: "hcddcmh.rt"
					},	{
						label: "No. BPJS Kesehatan",
						name: "hcdjbmh.bpjskes_no"
					},	{
						label: "No. BPJS Ketenagakerjaan",
						name: "hcdjbmh.bpjstk_no"
					},	{
						label: "RW",
						name: "hcddcmh.rw"
					},	{
						label: "Asal Sekolah<sup class='text-danger'>*<sup>",
						name: "hcddcmh.asal_sekolah"
					},	
					{
						label: "Ber-NPWP",
						name: "hcddcmh.is_npwp",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 2 }
						]
					},
					{
						label: "Jurusan",
						name: "hcddcmh.jurusan"
					},	{
						label: "No. NPWP <sup class='text-danger'>*<sup>",
						name: "hcddcmh.npwp_no"
					},	{
						label: "Kelurahan",
						name: "hcddcmh.kelurahan"
					},	{
						label: "Kecamatan",
						name: "hcddcmh.kecamatan"
					},	{
						label: "Jabatan Yang Dilamar<sup class='text-danger'>*<sup>",
						name: "hcdjbmh.id_hetxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hetxxmh/hetxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hetxxmh: 0,
										id_hetxxmh_old: id_hetxxmh_old,
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
					}, 	{
						label: "Hobby",
						name: "hcdxxmh.hobby"
					}, 	{
						label: "Apakah anda puas dengan kemajuan yang pernah dicapai pada pekerjaan-pekerjaan anda terdahulu atau sebeIumnya, berikan pernyataan anda ?",
						name: "hcdjbmh.quiz1",
						type: "textarea"
					}, 	{
						label: "Peralatan / Sofware apa yang anda operasikan?",
						name: "hcdjbmh.quiz2",
					}, 	{
						label: "Apakah anda melakukan perubahan / perbaikan pada perusahaan terdahulu? Sebutkan nama perusahaan dan perubahan yang pernah Anda lakukan!",
						name: "hcdjbmh.quiz3",
						type: "textarea"
					}, 	{
						label: "Mengapa ingin bergabung dengan perusahaan kami?",
						name: "hcdjbmh.quiz4",
						type: "textarea"
					}, 	{
						label: "Apabila anda diterima sebagai karyawan pada perusahaan kami, apakah anda bersedia ditempatkan diseluruh unit kerja kami ?",
						name: "hcdjbmh.quiz5",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": "Ya" },
							{ "label": "Tidak", "value": "Tidak" }
						]
					}
				]
			} );
			
			edthcdxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhcdxxmh.rows().deselect();
				}
			});

            edthcdxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-xl");
			});
			
			edthcdxxmh.dependent( 'hcddcmh.is_npwp', function ( val, data, callback ) {
				// code here
				if (val == 1) {
					$('.npwp').show();
				} else {
					$('.npwp').hide();
				}
				
				return {}
			}, {event: 'keyup change'});
			
			edthcdxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcdxxmh.nama 
					nama = edthcdxxmh.field('hcdxxmh.nama').val();
					if(!nama || nama == ''){
						edthcdxxmh.field('hcdxxmh.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdxxmh.nama 

					// BEGIN of validasi hcdcsmh.alamat_tinggal 
					alamat_tinggal = edthcdxxmh.field('hcdcsmh.alamat_tinggal').val();
					if(!alamat_tinggal || alamat_tinggal == ''){
						edthcdxxmh.field('hcdcsmh.alamat_tinggal').error( 'Wajib diisi!' );
					}
					// END of validasi hcdxxmh.alamat_tinggal 

					// BEGIN of validasi hcdcsmh.whatsapp 
					whatsapp = edthcdxxmh.field('hcdcsmh.whatsapp').val();
					if(!whatsapp || whatsapp == ''){
						edthcdxxmh.field('hcdcsmh.whatsapp').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(whatsapp <= 0 ){
						edthcdxxmh.field('hcdcsmh.whatsapp').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(whatsapp) ){
						edthcdxxmh.field('hcdcsmh.whatsapp').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hcdcsmh.whatsapp 

					// validasi angka berat badan dan tinggi badan
					tinggi = edthcdxxmh.field('hcdxxmh.tinggi').val();
					berat = edthcdxxmh.field('hcdxxmh.berat').val();
					vaksin = edthcdxxmh.field('hcdxxmh.vaksin').val();
					handphone = edthcdxxmh.field('hcdcsmh.handphone').val();
					rt = edthcdxxmh.field('hcddcmh.rt').val();
					rw = edthcdxxmh.field('hcddcmh.rw').val();
					sim_a = edthcdxxmh.field('hcddcmh.sim_a').val();
					sim_b = edthcdxxmh.field('hcddcmh.sim_b').val();
					sim_c = edthcdxxmh.field('hcddcmh.sim_c').val();
					npwp_no = edthcdxxmh.field('hcddcmh.npwp_no').val();
					is_npwp = edthcdxxmh.field('hcddcmh.is_npwp').val();
					bpjskes_no = edthcdxxmh.field('hcdjbmh.bpjskes_no').val();
					bpjstk_no = edthcdxxmh.field('hcdjbmh.bpjstk_no').val();

					if (is_npwp == 1) {
						if(!npwp_no || npwp_no == ''){
						edthcdxxmh.field('hcddcmh.npwp_no').error( 'Wajib diisi!' );
						}
						// validasi min atau max angka
						if(npwp_no <= 0 ){
							edthcdxxmh.field('hcddcmh.npwp_no').error( 'Inputan harus > 0' );
						}
						if(isNaN(npwp_no) ){
							edthcdxxmh.field('hcddcmh.npwp_no').error( 'Inputan harus berupa Angka!' );
						}
					}

					if(isNaN(tinggi) ){
						edthcdxxmh.field('hcdxxmh.tinggi').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(bpjskes_no) ){
						edthcdxxmh.field('hcdjbmh.bpjskes_no').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(bpjstk_no) ){
						edthcdxxmh.field('hcdjbmh.bpjstk_no').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(berat) ){
						edthcdxxmh.field('hcdxxmh.berat').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(vaksin) ){
						edthcdxxmh.field('hcdxxmh.vaksin').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(handphone) ){
						edthcdxxmh.field('hcdcsmh.handphone').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(rt) ){
						edthcdxxmh.field('hcddcmh.rt').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(rw) ){
						edthcdxxmh.field('hcddcmh.rw').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(sim_a) ){
						edthcdxxmh.field('hcddcmh.sim_a').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(sim_b) ){
						edthcdxxmh.field('hcddcmh.sim_b').error( 'Inputan harus berupa Angka!' );
					}
					if(isNaN(sim_c) ){
						edthcdxxmh.field('hcddcmh.sim_c').error( 'Inputan harus berupa Angka!' );
					}

					// BEGIN of validasi hcdxxmh.tanggal_lahir 
					tanggal_lahir = edthcdxxmh.field('hcdxxmh.tanggal_lahir').val();
					if(!tanggal_lahir || tanggal_lahir == ''){
						edthcdxxmh.field('hcdxxmh.tanggal_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hcdxxmh.tanggal_lahir 

					// BEGIN of validasi hcdxxmh.id_gctxxmh_lahir 
					id_gctxxmh_lahir = edthcdxxmh.field('hcdxxmh.id_gctxxmh_lahir').val();
					if(!id_gctxxmh_lahir || id_gctxxmh_lahir == ''){
						edthcdxxmh.field('hcdxxmh.id_gctxxmh_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hcdxxmh.id_gctxxmh_lahir 

					// BEGIN of validasi hcdxxmh.gender 
					jenis_kelamin = edthcdxxmh.field('hcdxxmh.gender').val();
					if(!jenis_kelamin || jenis_kelamin == ''){
						edthcdxxmh.field('hcdxxmh.gender').error( 'Wajib diisi!' );
					}
					// END of validasi hcdxxmh.gender 

					// BEGIN of validasi hcddcmh.ktp_no 
					ktp_no = edthcdxxmh.field('hcddcmh.ktp_no').val();
					if(!ktp_no || ktp_no == ''){
						edthcdxxmh.field('hcddcmh.ktp_no').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(ktp_no <= 0 ){
						edthcdxxmh.field('hcddcmh.ktp_no').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(ktp_no) ){
						edthcdxxmh.field('hcddcmh.ktp_no').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hcddcmh.ktp_no 
				}
				
				if ( edthcdxxmh.inError() ) {
					return false;
				}
			});

			edthcdxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdxxmh.field('finish_on').val(finish_on);
			});
			
			edthcdxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdxxmh = $('#tblhcdxxmh').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdxxmh = show_inactive_status_hcdxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hcdxxmh.id",visible:false },
					{ data: "hcdxxmh.nama" },
					{ data: "hcddcmh.ktp_no" },
					{ data: "hcdcsmh.whatsapp" },
					{ data: "hcdcsmh.alamat_tinggal" }
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdxxmh';
						$table       = 'tblhcdxxmh';
						$edt         = 'edthcdxxmh';
						$show_status = '_hcdxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhcdxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhcdfmmd, tblhcdedmd, tblhcdjbmd, tblhcdogmd, tblhcddhmd, tblhcdecmd, tblhcdtrmd, tblhcdlgmd, tblhcdrfmd];
				CekInitHeaderHD(tblhcdxxmh, tbl_details);
			} );
			
			tblhcdxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_hcdxxmh = tblhcdxxmh.row( { selected: true } ).data().hcdxxmh;
				id_hcdxxmh  = data_hcdxxmh.id;
				id_transaksi_h   = id_hcdxxmh; // dipakai untuk general
				is_approve       = data_hcdxxmh.is_approve;
				is_nextprocess   = data_hcdxxmh.is_nextprocess;
				is_jurnal        = data_hcdxxmh.is_jurnal;
				is_active        = data_hcdxxmh.is_active;
				id_gctxxmh_lahir_old        = data_hcdxxmh.id_gctxxmh_lahir;
				
				data_hcdcsmh = tblhcdxxmh.row( { selected: true } ).data().hcdcsmh;
				id_gctxxmh_tinggal_old        = data_hcdcsmh.id_gctxxmh_tinggal;
				
				data_hcddcmh = tblhcdxxmh.row( { selected: true } ).data().hcddcmh;
				id_gctxxmh_ktp_old        = data_hcddcmh.id_gctxxmh_ktp;
				id_gctxxmh_npwp_old        = data_hcddcmh.id_gctxxmh_npwp;
				id_gtxpkmh_old        = data_hcddcmh.id_gtxpkmh;

				data_hcdjbmh = tblhcdxxmh.row( { selected: true } ).data().hcdjbmh;
				id_hetxxmh_old        = data_hcdjbmh.id_hetxxmh;
				 
				// atur hak akses
				tbl_details = [tblhcdfmmd, tblhcdedmd, tblhcdjbmd, tblhcdogmd, tblhcddhmd, tblhcdecmd, tblhcdtrmd, tblhcdlgmd, tblhcdrfmd];
				CekSelectHeaderHD(tblhcdxxmh, tbl_details);

			} );
			
			tblhcdxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hcdxxmh = '';
				id_gctxxmh_lahir_old = 0;
				id_gctxxmh_npwp_old = 0;
				id_gtxpkmh_old = 0;
				id_gctxxmh_ktp_old = 0;
				id_hetxxmh_old = 0;
				id_gctxxmh_tinggal_old = 0;

				// atur hak akses
				tbl_details = [tblhcdfmmd, tblhcdedmd, tblhcdjbmd, tblhcdogmd, tblhcddhmd, tblhcdecmd, tblhcdtrmd, tblhcdlgmd, tblhcdrfmd];
				CekDeselectHeaderHD(tblhcdxxmh, tbl_details);
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthcdfmmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdfmmd = show_inactive_status_hcdfmmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcdfmmd",
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
						def: "hcdfmmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcdfmmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdfmmd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Hubungan <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.hubungan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ayah", "value": "Ayah" },
							{ "label": "Ibu", "value": "Ibu" },
							{ "label": "Kakak", "value": "Kakak" },
							{ "label": "Adik", "value": "Adik" },
							{ "label": "Suami", "value": "Suami" },
							{ "label": "Istri", "value": "Istri" },
							{ "label": "Anak", "value": "Anak" }
						]
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.nama"
					}, 	{
						label: "Tempat Lahir <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.id_gctxxmh_lahir",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_lahir_fam_old,
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
					},	{
						label: "Tanggal Lahir <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.tanggal_lahir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Jenis Kelamin",
						name: "hcdfmmd.gender",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Laki-laki", "value": "Laki-laki" },
							{ "label": "Perempuan", "value": "Perempuan" }
						]
					},	{
						label: "Pendidikan Terakhir <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.id_gedxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gedxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gedxxmh_old: id_gedxxmh_fam_old,
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
					},	{
						label: "Pekerjaan <sup class='text-danger'>*<sup>",
						name: "hcdfmmd.pekerjaan"
					}
				]
			} );
			
			edthcdfmmd.on( 'preOpen', function( e, mode, action ) {
				edthcdfmmd.field('hcdfmmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdfmmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcdfmmd.rows().deselect();
				}
			});

            edthcdfmmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcdfmmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hcdfmmd.hubungan 
					hubungan = edthcdfmmd.field('hcdfmmd.hubungan').val();
					if(!hubungan || hubungan == ''){
						edthcdfmmd.field('hcdfmmd.hubungan').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.hubungan 

					// BEGIN of validasi hcdfmmd.nama 
					nama = edthcdfmmd.field('hcdfmmd.nama').val();
					if(!nama || nama == ''){
						edthcdfmmd.field('hcdfmmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.nama 

					// BEGIN of validasi hcdfmmd.id_gctxxmh_lahir 
					id_gctxxmh_lahir = edthcdfmmd.field('hcdfmmd.id_gctxxmh_lahir').val();
					if(!id_gctxxmh_lahir || id_gctxxmh_lahir == ''){
						edthcdfmmd.field('hcdfmmd.id_gctxxmh_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.id_gctxxmh_lahir 

					// BEGIN of validasi hcdfmmd.tanggal_lahir 
					tanggal_lahir = edthcdfmmd.field('hcdfmmd.tanggal_lahir').val();
					if(!tanggal_lahir || tanggal_lahir == ''){
						edthcdfmmd.field('hcdfmmd.tanggal_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.tanggal_lahir 

					// BEGIN of validasi hcdfmmd.gender 
					gender = edthcdfmmd.field('hcdfmmd.gender').val();
					if(!gender || gender == ''){
						edthcdfmmd.field('hcdfmmd.gender').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.gender 

					// BEGIN of validasi hcdfmmd.id_gedxxmh 
					id_gedxxmh = edthcdfmmd.field('hcdfmmd.id_gedxxmh').val();
					if(!id_gedxxmh || id_gedxxmh == ''){
						edthcdfmmd.field('hcdfmmd.id_gedxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.id_gedxxmh 

					// BEGIN of validasi hcdfmmd.pekerjaan 
					pekerjaan = edthcdfmmd.field('hcdfmmd.pekerjaan').val();
					if(!pekerjaan || pekerjaan == ''){
						edthcdfmmd.field('hcdfmmd.pekerjaan').error( 'Wajib diisi!' );
					}
					// END of validasi hcdfmmd.pekerjaan 
				}
				
				if ( edthcdfmmd.inError() ) {
					return false;
				}
			});

			edthcdfmmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdfmmd.field('finish_on').val(finish_on);
			});
			
			edthcdfmmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdfmmd = $('#tblhcdfmmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdfmmd = show_inactive_status_hcdfmmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcdfmmd.id",visible:false },
					{ data: "hcdfmmd.id_hcdxxmh",visible:false },
					{ data: "hcdfmmd.hubungan" },
					{ data: "hcdfmmd.nama" },
					{ data: "hcdfmmd.gender" },
					{ data: "gctxxmh.nama" },
					{ data: "hcdfmmd.tanggal_lahir" },
					{ data: "gedxxmh.nama" },
					{ data: "hcdfmmd.pekerjaan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdfmmd';
						$table       = 'tblhcdfmmd';
						$edt         = 'edthcdfmmd';
						$show_status = '_hcdfmmd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdfmmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcdfmmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcdfmmd, 'hcdfmmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcdfmmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcdfmmd = tblhcdfmmd.row( { selected: true } ).data().hcdfmmd;
				id_hcdfmmd   = data_hcdfmmd.id;
				id_transaksi_d    = id_hcdfmmd; // dipakai untuk general
				is_active_d       = data_hcdfmmd.is_active;
				id_gedxxmh_fam_old       = data_hcdfmmd.id_gedxxmh;
				id_gctxxmh_lahir_fam_old       = data_hcdfmmd.id_gctxxmh_lahir;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcdfmmd );
			} );

			tblhcdfmmd.on( 'deselect', function() {
				id_hcdfmmd = '';
				is_active_d = 0;
				id_gedxxmh_fam_old = 0;
				id_gctxxmh_lahir_fam_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcdfmmd );
			} );

// --------- end _detail --------------- //		
			
			
// --------- start _detail pendidikan--------------- //

			//start datatables editor
			edthcdedmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdedmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdedmd = show_inactive_status_hcdedmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcdedmd",
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
						def: "hcdedmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcdedmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdedmd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Jenjang Pendidikan <sup class='text-danger'>*<sup>",
						name: "hcdedmd.id_gedxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gedxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gedxxmh_old: id_gedxxmh_edu_old,
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
					},	{
						label: "Nama Institusi <sup class='text-danger'>*<sup>",
						name: "hcdedmd.nama"
					}, 	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "hcdedmd.id_gctxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_edu_old,
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
					},	{
						label: "Tahun Lulus",
						name: "hcdedmd.tahun_lulus"
					},	{
						label: "Jurusan",
						name: "hcdedmd.jurusan"
					},	{
						label: "Nilai Akhir",
						name: "hcdedmd.nilai_akhir"
					}
				]
			} );
			
			edthcdedmd.on( 'preOpen', function( e, mode, action ) {
				edthcdedmd.field('hcdedmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdedmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcdedmd.rows().deselect();
				}
			});

            edthcdedmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcdedmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcdedmd.nama 
					nama = edthcdedmd.field('hcdedmd.nama').val();
					if(!nama || nama == ''){
						edthcdedmd.field('hcdedmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdedmd.nama 

					// BEGIN of validasi hcdedmd.id_gctxxmh 
					id_gctxxmh = edthcdedmd.field('hcdedmd.id_gctxxmh').val();
					if(!id_gctxxmh || id_gctxxmh == ''){
						edthcdedmd.field('hcdedmd.id_gctxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hcdedmd.id_gctxxmh 

					// BEGIN of validasi hcdedmd.id_gedxxmh 
					id_gedxxmh = edthcdedmd.field('hcdedmd.id_gedxxmh').val();
					if(!id_gedxxmh || id_gedxxmh == ''){
						edthcdedmd.field('hcdedmd.id_gedxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hcdedmd.id_gedxxmh 


					tahun_lulus = edthcdedmd.field('hcdedmd.tahun_lulus').val();
					// validasi angka
					if(isNaN(tahun_lulus) ){
						edthcdedmd.field('hcdedmd.tahun_lulus').error( 'Inputan harus berupa Angka!' );
					}

					nilai_akhir = edthcdedmd.field('hcdedmd.nilai_akhir').val();
					// validasi angka
					if(isNaN(nilai_akhir) ){
						edthcdedmd.field('hcdedmd.nilai_akhir').error( 'Inputan harus berupa Angka!' );
					}
				}
				
				if ( edthcdedmd.inError() ) {
					return false;
				}
			});

			edthcdedmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdedmd.field('finish_on').val(finish_on);
			});
			
			edthcdedmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdedmd = $('#tblhcdedmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdedmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdedmd = show_inactive_status_hcdedmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcdedmd.id",visible:false },
					{ data: "hcdedmd.id_hcdxxmh",visible:false },
					{ data: "gedxxmh.nama" },
					{ data: "hcdedmd.nama" },
					{ data: "gctxxmh.nama" },
					{ data: "hcdedmd.tahun_lulus" },
					{ data: "hcdedmd.jurusan" },
					{ data: "hcdedmd.nilai_akhir" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdedmd';
						$table       = 'tblhcdedmd';
						$edt         = 'edthcdedmd';
						$show_status = '_hcdedmd';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdedmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcdedmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcdedmd, 'hcdedmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcdedmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcdedmd = tblhcdedmd.row( { selected: true } ).data().hcdedmd;
				id_hcdedmd   = data_hcdedmd.id;
				id_transaksi_d    = id_hcdedmd; // dipakai untuk general
				is_active_d       = data_hcdedmd.is_active;
				id_gctxxmh_edu_old       = data_hcdedmd.id_gctxxmh;
				id_gedxxmh_edu_old       = data_hcdedmd.id_gedxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcdedmd );
			} );

			tblhcdedmd.on( 'deselect', function() {
				id_hcdedmd = '';
				is_active_d = 0;
				id_gctxxmh_edu_old = 0;
				id_gedxxmh_edu_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcdedmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hcdjbmd PEKERJAAN --------------- //

			//start datatables editor
			edthcdjbmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdjbmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdjbmd = show_inactive_status_hcdjbmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcdjbmd",
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
						def: "hcdjbmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcdjbmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdjbmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Perusahaan <sup class='text-danger'>*<sup>",
						name: "hcdjbmd.nama"
					},	{
						label: "Alamat",
						name: "hcdjbmd.alamat",
						type: "textarea"
					}, 	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "hcdjbmd.id_gctxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_job_old,
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
					},	{
						label: "Jenis Usaha",
						name: "hcdjbmd.jenis"
					}, {
						label: "Tanggal Awal",
						name: "hcdjbmd.tanggal_awal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, {
						label: "Tanggal Akhir",
						name: "hcdjbmd.tanggal_akhir",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "jabatan Awal",
						name: "hcdjbmd.jabatan_awal"
					},	{
						label: "jabatan Akhir",
						name: "hcdjbmd.jabatan_akhir"
					},	{
						label: "Nama Atasan Langsung",
						name: "hcdjbmd.nama_atasan"
					},	{
						label: "Gaji Terakhir",
						name: "hcdjbmd.gaji"
					}
				]
			} );
			
			edthcdjbmd.on( 'preOpen', function( e, mode, action ) {
				edthcdjbmd.field('hcdjbmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdjbmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcdjbmd.rows().deselect();
				}
			});

            edthcdjbmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcdjbmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcdjbmd.nama 
					nama = edthcdjbmd.field('hcdjbmd.nama').val();
					if(!nama || nama == ''){
						edthcdjbmd.field('hcdjbmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdjbmd.nama 

					// BEGIN of validasi hcdjbmd.id_gctxxmh 
					id_gctxxmh = edthcdjbmd.field('hcdjbmd.id_gctxxmh').val();
					if(!id_gctxxmh || id_gctxxmh == ''){
						edthcdjbmd.field('hcdjbmd.id_gctxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hcdjbmd.id_gctxxmh 

					gaji = edthcdjbmd.field('hcdjbmd.gaji').val();
					// validasi angka
					if(isNaN(gaji) ){
						edthcdjbmd.field('hcdjbmd.gaji').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edthcdjbmd.inError() ) {
					return false;
				}
			});

			edthcdjbmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdjbmd.field('finish_on').val(finish_on);
			});
			
			edthcdjbmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdjbmd = $('#tblhcdjbmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdjbmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdjbmd = show_inactive_status_hcdjbmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcdjbmd.id",visible:false },
					{ data: "hcdjbmd.id_hcdxxmh",visible:false },
					{ data: "hcdjbmd.nama" },
					{ data: "hcdjbmd.jenis" },
					{ data: "hcdjbmd.alamat" },
					{ data: "gctxxmh.nama" },
					{ data: "hcdjbmd.tanggal_awal" },
					{ data: "hcdjbmd.tanggal_akhir" },
					{ data: "hcdjbmd.jabatan_awal" },
					{ data: "hcdjbmd.jabatan_akhir" },
					{ data: "hcdjbmd.nama_atasan" },
					{ data: "hcdjbmd.gaji",
						render: $.fn.dataTable.render.number( ',', '.', 0,'Rp. ','' ),
						class: "text-right" 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdjbmd';
						$table       = 'tblhcdjbmd';
						$edt         = 'edthcdjbmd';
						$show_status = '_hcdjbmd';
						$table_name  = $nama_tabels_d[2];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdjbmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcdjbmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcdjbmd, 'hcdjbmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcdjbmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcdjbmd = tblhcdjbmd.row( { selected: true } ).data().hcdjbmd;
				id_hcdjbmd   = data_hcdjbmd.id;
				id_transaksi_d    = id_hcdjbmd; // dipakai untuk general
				is_active_d       = data_hcdjbmd.is_active;
				id_gctxxmh_job_old       = data_hcdjbmd.id_gctxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcdjbmd );
			} );

			tblhcdjbmd.on( 'deselect', function() {
				id_hcdjbmd = '';
				is_active_d = 0;
				id_gctxxmh_job_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcdjbmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hcdogmd ORGANISASI --------------- //

			//start datatables editor
			edthcdogmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdogmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdogmd = show_inactive_status_hcdogmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcdogmd",
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
						def: "hcdogmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcdogmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdogmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Organisasi <sup class='text-danger'>*<sup>",
						name: "hcdogmd.nama"
					},	{
						label: "Jenis Organisasi",
						name: "hcdogmd.jenis"
					},	{
						label: "Tahun",
						name: "hcdogmd.tahun"
					},	{
						label: "Jabatan",
						name: "hcdogmd.jabatan"
					}
				]
			} );
			
			edthcdogmd.on( 'preOpen', function( e, mode, action ) {
				edthcdogmd.field('hcdogmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdogmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcdogmd.rows().deselect();
				}
			});

            edthcdogmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcdogmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcdogmd.nama 
					nama = edthcdogmd.field('hcdogmd.nama').val();
					if(!nama || nama == ''){
						edthcdogmd.field('hcdogmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdogmd.nama 

				}
				
				if ( edthcdogmd.inError() ) {
					return false;
				}
			});

			edthcdogmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdogmd.field('finish_on').val(finish_on);
			});
			
			edthcdogmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdogmd = $('#tblhcdogmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdogmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdogmd = show_inactive_status_hcdogmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcdogmd.id",visible:false },
					{ data: "hcdogmd.id_hcdxxmh",visible:false },
					{ data: "hcdogmd.nama" },
					{ data: "hcdogmd.jenis" },
					{ data: "hcdogmd.tahun" },
					{ data: "hcdogmd.jabatan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdogmd';
						$table       = 'tblhcdogmd';
						$edt         = 'edthcdogmd';
						$show_status = '_hcdogmd';
						$table_name  = $nama_tabels_d[3];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdogmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcdogmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcdogmd, 'hcdogmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcdogmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcdogmd = tblhcdogmd.row( { selected: true } ).data().hcdogmd;
				id_hcdogmd   = data_hcdogmd.id;
				id_transaksi_d    = id_hcdogmd; // dipakai untuk general
				is_active_d       = data_hcdogmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcdogmd );
			} );

			tblhcdogmd.on( 'deselect', function() {
				id_hcdogmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcdogmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hcddhmd Penyakit --------------- //

			//start datatables editor
			edthcddhmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcddhmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcddhmd = show_inactive_status_hcddhmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcddhmd",
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
						def: "hcddhmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcddhmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcddhmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Jenis Penyakit <sup class='text-danger'>*<sup>",
						name: "hcddhmd.nama"
					},	{
						label: "Tahun",
						name: "hcddhmd.tahun"
					},	{
						label: "Berapa Lama",
						name: "hcddhmd.lama"
					},	{
						label: "Dirawat Di",
						name: "hcddhmd.dirawat_di"
					}
				]
			} );
			
			edthcddhmd.on( 'preOpen', function( e, mode, action ) {
				edthcddhmd.field('hcddhmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcddhmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcddhmd.rows().deselect();
				}
			});

            edthcddhmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcddhmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcddhmd.nama 
					nama = edthcddhmd.field('hcddhmd.nama').val();
					if(!nama || nama == ''){
						edthcddhmd.field('hcddhmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcddhmd.nama 

				}
				
				if ( edthcddhmd.inError() ) {
					return false;
				}
			});

			edthcddhmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcddhmd.field('finish_on').val(finish_on);
			});
			
			edthcddhmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcddhmd = $('#tblhcddhmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcddhmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcddhmd = show_inactive_status_hcddhmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcddhmd.id",visible:false },
					{ data: "hcddhmd.id_hcdxxmh",visible:false },
					{ data: "hcddhmd.nama" },
					{ data: "hcddhmd.tahun" },
					{ data: "hcddhmd.lama" },
					{ data: "hcddhmd.dirawat_di" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcddhmd';
						$table       = 'tblhcddhmd';
						$edt         = 'edthcddhmd';
						$show_status = '_hcddhmd';
						$table_name  = $nama_tabels_d[4];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcddhmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcddhmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcddhmd, 'hcddhmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcddhmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcddhmd = tblhcddhmd.row( { selected: true } ).data().hcddhmd;
				id_hcddhmd   = data_hcddhmd.id;
				id_transaksi_d    = id_hcddhmd; // dipakai untuk general
				is_active_d       = data_hcddhmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcddhmd );
			} );

			tblhcddhmd.on( 'deselect', function() {
				id_hcddhmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcddhmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hcdecmd Kontak Darurat --------------- //

			//start datatables editor
			edthcdecmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hcdxxmh/hcdecmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdecmd = show_inactive_status_hcdecmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				table: "#tblhcdecmd",
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
						def: "hcdecmd",
						type: "hidden"
					},	{
						label: "id_hcdxxmh",
						name: "hcdecmd.id_hcdxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hcdecmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Kontak Darurat <sup class='text-danger'>*<sup>",
						name: "hcdecmd.nama"
					},	{
						label: "Alamat",
						name: "hcdecmd.alamat"
					},	{
						label: "No HP <sup class='text-danger'>*<sup>",
						name: "hcdecmd.no_hp"
					},	{
						label: "Hubungan",
						name: "hcdecmd.hubungan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ayah", "value": "Ayah" },
							{ "label": "Ibu", "value": "Ibu" },
							{ "label": "Kakak", "value": "Kakak" },
							{ "label": "Adik", "value": "Adik" },
							{ "label": "Suami", "value": "Suami" },
							{ "label": "Istri", "value": "Istri" },
							{ "label": "Anak", "value": "Anak" }
						]
					}
				]
			} );
			
			edthcdecmd.on( 'preOpen', function( e, mode, action ) {
				edthcdecmd.field('hcdecmd.id_hcdxxmh').val(id_hcdxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdecmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhcdecmd.rows().deselect();
				}
			});

            edthcdecmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthcdecmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hcdecmd.nama 
					nama = edthcdecmd.field('hcdecmd.nama').val();
					if(!nama || nama == ''){
						edthcdecmd.field('hcdecmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hcdecmd.nama 

					//  validasi hcdecmd.no_hp
					no_hp = edthcdecmd.field('hcdecmd.no_hp').val();
					if(!no_hp || no_hp == ''){
						edthcdecmd.field('hcdecmd.no_hp').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(no_hp <= 0 ){
						edthcdecmd.field('hcdecmd.no_hp').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(no_hp) ){
						edthcdecmd.field('hcdecmd.no_hp').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edthcdecmd.inError() ) {
					return false;
				}
			});

			edthcdecmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthcdecmd.field('finish_on').val(finish_on);
			});
			
			edthcdecmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhcdecmd = $('#tblhcdecmd').DataTable( {
				ajax: {
					url: "../../models/hcdxxmh/hcdecmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hcdecmd = show_inactive_status_hcdecmd;
						d.id_hcdxxmh = id_hcdxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hcdecmd.id",visible:false },
					{ data: "hcdecmd.id_hcdxxmh",visible:false },
					{ data: "hcdecmd.nama" },
					{ data: "hcdecmd.alamat" },
					{ data: "hcdecmd.no_hp" },
					{ data: "hcdecmd.hubungan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hcdecmd';
						$table       = 'tblhcdecmd';
						$edt         = 'edthcdecmd';
						$show_status = '_hcdecmd';
						$table_name  = $nama_tabels_d[5];

						$arr_buttons_tools = array();
						$arr_buttons_tools = ['show_hide','copy','excel'];

						$arr_buttons = array();
						$arr_buttons = ['colvis', 'create', 'edit', 'nonaktif_d'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hcdecmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhcdecmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhcdxxmh, tblhcdecmd, 'hcdecmd' );
				CekDrawDetailHDFinal(tblhcdxxmh);
			} );

			tblhcdecmd.on( 'select', function( e, dt, type, indexes ) {
				data_hcdecmd = tblhcdecmd.row( { selected: true } ).data().hcdecmd;
				id_hcdecmd   = data_hcdecmd.id;
				id_transaksi_d    = id_hcdecmd; // dipakai untuk general
				is_active_d       = data_hcdecmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhcdxxmh, tblhcdecmd );
			} );

			tblhcdecmd.on( 'deselect', function() {
				id_hcdecmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhcdxxmh, tblhcdecmd );
			} );

// --------- end _detail --------------- //		
 // --------- start _detail hcdtrmd PELATIHAN --------------- //

        //start datatables editor
        edthcdtrmd = new $.fn.dataTable.Editor( {
            ajax: {
                url: "../../models/hcdxxmh/hcdtrmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdtrmd = show_inactive_status_hcdtrmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            table: "#tblhcdtrmd",
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
                    def: "hcdtrmd",
                    type: "hidden"
                },	{
                    label: "id_hcdxxmh",
                    name: "hcdtrmd.id_hcdxxmh",
                    type: "hidden"
                },	{
                    label: "Active Status",
                    name: "hcdtrmd.is_active",
                    type: "hidden",
                    def: 1
                },	{
                    label: "Nama Pelatihan <sup class='text-danger'>*<sup>",
                    name: "hcdtrmd.nama",
                    type: "textarea"
                },	{
                    label: "Penyelenggara <sup class='text-danger'>*<sup>",
                    name: "hcdtrmd.lembaga"
                },	{
                    label: "Bersertifikat<sup class='text-danger'>*<sup>",
                    name: "hcdtrmd.bersertifikat",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Ya", "value": "Ya" },
                        { "label": "Tidak", "value": "Tidak" }
                    ]
                }, 	{
                    label: "Sertifikat<sup class='text-danger'>*<sup>",
                    name: "hcdtrmd.id_files",
                    type: "upload",
                    display: function ( fileId, counter ) {
                        if(fileId > 0){
                            return '<a href="'+edthcdtrmd.file( 'files', fileId ).web_path+'" target="_blank">'+ edthcdtrmd.file( 'files', fileId ).filename + '</a>'; 
                        }else{
                            return '';
                        }
                    },
                    noFileText: 'File belum diinput'
                },  {
                    label: "Tanggal Mulai",
                    name: "hcdtrmd.tanggal_mulai",
                    type: "datetime",
                    def: function () { 
                        return new Date(); 
                    },
                    opts:{
                        minDate: new Date('1900-01-01'),
                        firstDay: 0
                    },
                    format: 'DD MMM YYYY'
                }, {
                    label: "Tanggal Selesai",
                    name: "hcdtrmd.tanggal_selesai",
                    type: "datetime",
                    def: function () { 
                        return new Date(); 
                    },
                    opts:{
                        minDate: new Date('1900-01-01'),
                        firstDay: 0
                    },
                    format: 'DD MMM YYYY'
                },	{
                    label: "Dibiayai Oleh",
                    name: "hcdtrmd.biayai",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Sendiri", "value": "Sendiri" },
                        { "label": "Perusahaan", "value": "Perusahaan" },
                        { "label": "Gratis", "value": "Gratis" }
                    ]
                }
            ]
        } );
        edthcdtrmd.field('hcdtrmd.id_files').hide();

        edthcdtrmd.on( 'preOpen', function( e, mode, action ) {
            edthcdtrmd.field('hcdtrmd.id_hcdxxmh').val(id_hcdxxmh);
            
            start_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdtrmd.field('start_on').val(start_on);

            if(action == 'create'){
                tblhcdtrmd.rows().deselect();
            }
        });

        edthcdtrmd.on("open", function (e, mode, action) {
            $(".modal-dialog").addClass("modal-lg");
        });

        edthcdtrmd.dependent( 'hcdtrmd.bersertifikat', function ( val, data, callback ) {
            if(val == "Ya"){
                edthcdtrmd.field('hcdtrmd.id_files').show();
                edthcdtrmd.field('hcdtrmd.id_files').val();
            }else{
                edthcdtrmd.field('hcdtrmd.id_files').hide();
                edthcdtrmd.field('hcdtrmd.id_files').val('');
            }
            return {}
        }, {event: 'keyup change'})

        edthcdtrmd.on( 'preSubmit', function (e, data, action) {
            if(action != 'remove'){

                // BEGIN of validasi hcdtrmd.nama 
                nama = edthcdtrmd.field('hcdtrmd.nama').val();
                if(!nama || nama == ''){
                    edthcdtrmd.field('hcdtrmd.nama').error( 'Wajib diisi!' );
                }
                // END of validasi hcdtrmd.nama 

                // BEGIN of validasi hcdtrmd.lembaga 
                lembaga = edthcdtrmd.field('hcdtrmd.lembaga').val();
                if(!lembaga || lembaga == ''){
                    edthcdtrmd.field('hcdtrmd.lembaga').error( 'Wajib diisi!' );
                }
                // END of validasi hcdtrmd.lembaga 

                // BEGIN of validasi hcdtrmd.bersertifikat 
                bersertifikat = edthcdtrmd.field('hcdtrmd.bersertifikat').val();
                if(!bersertifikat || bersertifikat == ''){
                    edthcdtrmd.field('hcdtrmd.bersertifikat').error( 'Wajib diisi!' );
                }
                // END of validasi hcdtrmd.bersertifikat 

                if (bersertifikat == 'Ya') {
                    // BEGIN of validasi hcdtrmd.id_files 
                    id_files = edthcdtrmd.field('hcdtrmd.id_files').val();
                    if(!id_files || id_files == ''){
                        edthcdtrmd.field('hcdtrmd.id_files').error( 'Wajib diisi!' );
                    }
                    // END of validasi hcdtrmd.id_files 
                }

            }
            
            if ( edthcdtrmd.inError() ) {
                return false;
            }
        });

        edthcdtrmd.on('initSubmit', function(e, action) {
            finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdtrmd.field('finish_on').val(finish_on);
        });

        edthcdtrmd.on( 'postSubmit', function (e, json, data, action, xhr) {
            // event setelah Create atau Edit, dibedakan dari parameter action
            // action : "create" | "edit"
            // do something
        } );

        //start datatables
        tblhcdtrmd = $('#tblhcdtrmd').DataTable( {
            ajax: {
                url: "../../models/hcdxxmh/hcdtrmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdtrmd = show_inactive_status_hcdtrmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            order: [[ 2, "desc" ]],
            columns: [
                { data: "hcdtrmd.id",visible:false },
                { data: "hcdtrmd.id_hcdxxmh",visible:false },
                { data: "hcdtrmd.nama" },
                { data: "hcdtrmd.lembaga" },
                { data: "hcdtrmd.bersertifikat" },
                { data: "hcdtrmd.tanggal_mulai" },
                { data: "hcdtrmd.tanggal_selesai" }
            ],
            buttons: [
                // BEGIN breaking generate button
                <?php
                    $id_table    = 'id_hcdtrmd';
                    $table       = 'tblhcdtrmd';
                    $edt         = 'edthcdtrmd';
                    $show_status = '_hcdtrmd';
                    $table_name  = $nama_tabels_d[6];

                    $arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
                    $arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
                    $arr_buttons_approve 	= [];
                    include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
                ?>
                // END breaking generate button
            ],
            rowCallback: function( row, data, index ) {
                if ( data.hcdtrmd.is_active == 0 ) {
                    $('td', row).addClass('text-danger');
                }
            }
        } );

        tblhcdtrmd.on( 'draw', function( e, settings ) { 
            // atur hak akses
            
            cek_c_detail= 1;
			CekDrawDetailHD(tblhcdxxmh, tblhcdtrmd, 'hcdtrmd' );
			CekDrawDetailHDFinal(tblhcdxxmh);
        } );

        tblhcdtrmd.on( 'select', function( e, dt, type, indexes ) {
            data_hcdtrmd = tblhcdtrmd.row( { selected: true } ).data().hcdtrmd;
            id_hcdtrmd   = data_hcdtrmd.id;
            id_transaksi_d    = id_hcdtrmd; // dipakai untuk general
            is_active_d       = data_hcdtrmd.is_active;
			CekSelectDetailHD(tblhcdxxmh, tblhcdtrmd );
        } );

        tblhcdtrmd.on( 'deselect', function() {
            id_hcdtrmd = '';
            is_active_d = 0;
			CekDeselectDetailHD(tblhcdxxmh, tblhcdtrmd );
        } );

// --------- end _detail --------------- //	

// --------- start _detail hcdlgmd BAHASA --------------- //

        //start datatables editor
        edthcdlgmd = new $.fn.dataTable.Editor( {
            ajax: {
                url: "../../models/hcdxxmh/hcdlgmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdlgmd = show_inactive_status_hcdlgmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            table: "#tblhcdlgmd",
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
                    def: "hcdlgmd",
                    type: "hidden"
                },	{
                    label: "id_hcdxxmh",
                    name: "hcdlgmd.id_hcdxxmh",
                    type: "hidden"
                },	{
                    label: "Active Status",
                    name: "hcdlgmd.is_active",
                    type: "hidden",
                    def: 1
                }, 	{
                    label: "Bahasa <sup class='text-danger'>*<sup>",
                    name: "hcdlgmd.nama"
                },	{
                    label: "Kemampuan Mendengar	",
                    name: "hcdlgmd.mendengar",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Sangat Baik", "value": "Sangat Baik" },
                        { "label": "Baik", "value": "Baik" },
                        { "label": "Kurang Baik", "value": "Kurang Baik" }
                    ]
                },	{
                    label: "Kemampuan Membaca	",
                    name: "hcdlgmd.membaca",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Sangat Baik", "value": "Sangat Baik" },
                        { "label": "Baik", "value": "Baik" },
                        { "label": "Kurang Baik", "value": "Kurang Baik" }
                    ]
                },	{
                    label: "Kemampuan Menulis	",
                    name: "hcdlgmd.menulis",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Sangat Baik", "value": "Sangat Baik" },
                        { "label": "Baik", "value": "Baik" },
                        { "label": "Kurang Baik", "value": "Kurang Baik" }
                    ]
                },	{
                    label: "Kemampuan Percakapan	",
                    name: "hcdlgmd.percakapan",
                    type: "select",
                    placeholder : "Select",
                    options: [
                        { "label": "Sangat Baik", "value": "Sangat Baik" },
                        { "label": "Baik", "value": "Baik" },
                        { "label": "Kurang Baik", "value": "Kurang Baik" }
                    ]
                }
            ]
        } );

        edthcdlgmd.on( 'preOpen', function( e, mode, action ) {
            edthcdlgmd.field('hcdlgmd.id_hcdxxmh').val(id_hcdxxmh);
            
            start_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdlgmd.field('start_on').val(start_on);

            if(action == 'create'){
                tblhcdlgmd.rows().deselect();
            }
        });

        edthcdlgmd.on("open", function (e, mode, action) {
            $(".modal-dialog").addClass("modal-lg");
        });

        edthcdlgmd.on( 'preSubmit', function (e, data, action) {
            if(action != 'remove'){
                // BEGIN of validasi hcdlgmd.nama 
                nama = edthcdlgmd.field('hcdlgmd.nama').val();
                if(!nama || nama == ''){
                    edthcdlgmd.field('hcdlgmd.nama').error( 'Wajib diisi!' );
                }
                // END of validasi hcdlgmd.nama 

            }
            
            if ( edthcdlgmd.inError() ) {
                return false;
            }
        });

        edthcdlgmd.on('initSubmit', function(e, action) {
            finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdlgmd.field('finish_on').val(finish_on);
        });

        edthcdlgmd.on( 'postSubmit', function (e, json, data, action, xhr) {
            // event setelah Create atau Edit, dibedakan dari parameter action
            // action : "create" | "edit"
            // do something
        } );

        //start datatables
        tblhcdlgmd = $('#tblhcdlgmd').DataTable( {
            ajax: {
                url: "../../models/hcdxxmh/hcdlgmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdlgmd = show_inactive_status_hcdlgmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            order: [[ 2, "desc" ]],
            columns: [
                { data: "hcdlgmd.id",visible:false },
                { data: "hcdlgmd.id_hcdxxmh",visible:false },
                { data: "hcdlgmd.nama" },
                { data: "hcdlgmd.membaca" },
                { data: "hcdlgmd.mendengar" },
                { data: "hcdlgmd.menulis" },
                { data: "hcdlgmd.percakapan" }
            ],
            buttons: [
                // BEGIN breaking generate button
                <?php
                    $id_table    = 'id_hcdlgmd';
                    $table       = 'tblhcdlgmd';
                    $edt         = 'edthcdlgmd';
                    $show_status = '_hcdlgmd';
                    $table_name  = $nama_tabels_d[7];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
                // END breaking generate button
            ],
            rowCallback: function( row, data, index ) {
                if ( data.hcdlgmd.is_active == 0 ) {
                    $('td', row).addClass('text-danger');
                }
            }
        } );

        tblhcdlgmd.on( 'draw', function( e, settings ) { 
            // atur hak akses
            cek_c_detail= 1;
			CekDrawDetailHD(tblhcdxxmh, tblhcdlgmd, 'hcdlgmd' );
			CekDrawDetailHDFinal(tblhcdxxmh);
        } );

        tblhcdlgmd.on( 'select', function( e, dt, type, indexes ) {
            data_hcdlgmd = tblhcdlgmd.row( { selected: true } ).data().hcdlgmd;
            id_hcdlgmd   = data_hcdlgmd.id;
            id_transaksi_d    = id_hcdlgmd; // dipakai untuk general
            is_active_d       = data_hcdlgmd.is_active;
			CekSelectDetailHD(tblhcdxxmh, tblhcdlgmd );
        } );

        tblhcdlgmd.on( 'deselect', function() {
            id_hcdlgmd = '';
            is_active_d = 0;
			CekDeselectDetailHD(tblhcdxxmh, tblhcdlgmd );
        } );

// --------- end _detail --------------- //	

// --------- start _detail hcdrfmd Kontak Darurat --------------- //

        //start datatables editor
        edthcdrfmd = new $.fn.dataTable.Editor( {
            ajax: {
                url: "../../models/hcdxxmh/hcdrfmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdrfmd = show_inactive_status_hcdrfmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            table: "#tblhcdrfmd",
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
                    def: "hcdrfmd",
                    type: "hidden"
                },	{
                    label: "id_hcdxxmh",
                    name: "hcdrfmd.id_hcdxxmh",
                    type: "hidden"
                },	{
                    label: "Active Status",
                    name: "hcdrfmd.is_active",
                    type: "hidden",
                    def: 1
                },	{
                    label: "Nama Referensi <sup class='text-danger'>*<sup>",
                    name: "hcdrfmd.nama"
                },	{
                    label: "Alamat",
                    name: "hcdrfmd.alamat"
                },	{
                    label: "Pekerjaan",
                    name: "hcdrfmd.pekerjaan"
                },	{
                    label: "No HP",
                    name: "hcdrfmd.no_hp"
                },	{
                    label: "Hubungan",
                    name: "hcdrfmd.hubungan"
                }
            ]
        } );

        edthcdrfmd.on( 'preOpen', function( e, mode, action ) {
            edthcdrfmd.field('hcdrfmd.id_hcdxxmh').val(id_hcdxxmh);
            
            start_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdrfmd.field('start_on').val(start_on);

            if(action == 'create'){
                tblhcdrfmd.rows().deselect();
            }
        });

        edthcdrfmd.on("open", function (e, mode, action) {
            $(".modal-dialog").addClass("modal-lg");
        });

        edthcdrfmd.on( 'preSubmit', function (e, data, action) {
            if(action != 'remove'){

                // BEGIN of validasi hcdrfmd.nama 
                nama = edthcdrfmd.field('hcdrfmd.nama').val();
                if(!nama || nama == ''){
                    edthcdrfmd.field('hcdrfmd.nama').error( 'Wajib diisi!' );
                }
                // END of validasi hcdrfmd.nama 

            }
            
            if ( edthcdrfmd.inError() ) {
                return false;
            }
        });

        edthcdrfmd.on('initSubmit', function(e, action) {
            finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
            edthcdrfmd.field('finish_on').val(finish_on);
        });

        edthcdrfmd.on( 'postSubmit', function (e, json, data, action, xhr) {
            // event setelah Create atau Edit, dibedakan dari parameter action
            // action : "create" | "edit"
            // do something
        } );

        //start datatables
        tblhcdrfmd = $('#tblhcdrfmd').DataTable( {
            ajax: {
                url: "../../models/hcdxxmh/hcdrfmd.php",
                type: 'POST',
                data: function (d){
                    d.show_inactive_status_hcdrfmd = show_inactive_status_hcdrfmd;
                    d.id_hcdxxmh = id_hcdxxmh;
                }
            },
            order: [[ 2, "desc" ]],
            columns: [
                { data: "hcdrfmd.id",visible:false },
                { data: "hcdrfmd.id_hcdxxmh",visible:false },
                { data: "hcdrfmd.nama" },
                { data: "hcdrfmd.alamat" },
                { data: "hcdrfmd.pekerjaan" },
                { data: "hcdrfmd.no_hp" },
                { data: "hcdrfmd.hubungan" }
            ],
            buttons: [
                // BEGIN breaking generate button
                <?php
                    $id_table    = 'id_hcdrfmd';
                    $table       = 'tblhcdrfmd';
                    $edt         = 'edthcdrfmd';
                    $show_status = '_hcdrfmd';
                    $table_name  = $nama_tabels_d[8];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
                // END breaking generate button
            ],
            rowCallback: function( row, data, index ) {
                if ( data.hcdrfmd.is_active == 0 ) {
                    $('td', row).addClass('text-danger');
                }
            }
        } );

        tblhcdrfmd.on( 'draw', function( e, settings ) { 
            // atur hak akses
            cek_c_detail= 1;
			CekDrawDetailHD(tblhcdxxmh, tblhcdrfmd, 'hcdrfmd' );
			CekDrawDetailHDFinal(tblhcdxxmh);
        } );

        tblhcdrfmd.on( 'select', function( e, dt, type, indexes ) {
            data_hcdrfmd = tblhcdrfmd.row( { selected: true } ).data().hcdrfmd;
            id_hcdrfmd   = data_hcdrfmd.id;
            id_transaksi_d    = id_hcdrfmd; // dipakai untuk general
            is_active_d       = data_hcdrfmd.is_active;
			CekSelectDetailHD(tblhcdxxmh, tblhcdrfmd );
        } );

        tblhcdrfmd.on( 'deselect', function() {
            id_hcdrfmd = '';
            is_active_d = 0;
			CekDeselectDetailHD(tblhcdxxmh, tblhcdrfmd );
        } );

// --------- end _detail --------------- //		

			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
