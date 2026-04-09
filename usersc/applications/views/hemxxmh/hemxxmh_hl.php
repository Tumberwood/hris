<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'hemxxmh';
    $nama_tabels_d 	= [];
    $nama_tabels_d[0] = 'hemfmmd';
    $nama_tabels_d[1] = 'hadxxtd';
    $nama_tabels_d[2] = 'htlxxth';
    $nama_tabels_d[3] = 'htpxxth';
    $nama_tabels_d[4] = 'hemjbrd';

	$nama_tabels_d[5] = 'hemedmd';
	$nama_tabels_d[6] = 'hemjbmd';
	$nama_tabels_d[7] = 'hemlgmd';
	$nama_tabels_d[8] = 'hemskmd';
	$nama_tabels_d[9] = 'hemtrmd';

	
    $nama_tabels_d[10] = 'hemogmd';
    $nama_tabels_d[11] = 'hemdhmd';
    $nama_tabels_d[12] = 'hemecmd';
?>

<!-- begin content here -->
<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<!-- start Custom Form Datatables Editor -->
				<div id="custom_hem">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.nama"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemxxmh.kode"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.kode_finger"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.ktp_no"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.no_bpjs_tk"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.no_bpjs_kes"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hovxxmh"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hodxxmh"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hobxxmh"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hosxxmh"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hevgrmh"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hevxxmh"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hetxxmh"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_heyxxmd"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_heyxxmh"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_hesxxmh"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.id_holxxmd_2"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.id_gtxpkmh"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.tanggal_masuk"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.tanggal_akhir_kontrak"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.grup_hk"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemjbmh.jumlah_grup"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.is_pot_makan"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.is_npwp"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.is_tukar"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.npwp_no"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.npwp_alamat"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.gender"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.id_gctxxmh_lahir"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemxxmh.tanggal_lahir"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.alamat"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.ktp_alamat"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.id_gctxxmh_domisili"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.id_gctxxmh_ktp"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.domisili_desa"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.ktp_desa"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemdcmh.domisili_kecamatan"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="hemdcmh.ktp_kecamatan"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.id_files_foto"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemjbmh.is_harian_lepas"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="status_aktif"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="hemxxmh.keterangan"></editor-field>
							</div>
						</div>
					</div>
				</div>
				<!-- end Custom Form Datatables Editor -->
				<div class="table-responsive">
                    <table id="tblhemxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Kode Finger</th>
                                <th>No KTP</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Unit Kerja</th>
                                <th>Grup Jabatan</th>
                                <th>Jabatan</th>
                                <th>Area Kerja</th>
                                <th>Tipe</th>
                                <th>Sub Tipe</th>
                                <th>Status</th>
                                <th>Grade</th>
                                <th>Bagian</th>
                                <th>Tanggal Join</th>
                                <th>Tanggal Akhir Kontrak</th>
                                <th>Grup HK</th>
                                <th>Gender</th>
                                <th>Harian Lepas</th>
                                <th>Aktif</th>
                            </tr>
                        </thead>
                    </table>
                    <legend>Detail</legend>
					<div class="tabs-container">
						<ul class="nav nav-tabs" role="tablist">
							<li><a class="nav-link active" data-toggle="tab" href="#tabhemfmmd"> Keluarga</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemedmd"> Pendidikan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemjbmd"> Pengalaman Kerja</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemlgmd"> Bahasa</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemskmd"> Kemampuan</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemtrmd"> Pelatihan</a></li>
							
							<li><a class="nav-link" data-toggle="tab" href="#tabhemogmd"> Organisasi</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemdhmd"> Riwayat Penyakit</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemecmd"> Kontak Darurat</a></li>

							<li><a class="nav-link" data-toggle="tab" href="#tabhadxxtd "> Pelanggaran</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhtlxxth"> Absensi</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhtpxxth"> Izin</a></li>
							<li><a class="nav-link" data-toggle="tab" href="#tabhemjbrd"> Job History</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhemfmmd" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemfmmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Hubungan</th>
													<th>Nama</th>
													<th>Gender</th>
													<th>Tanggal Lahir</th>
													<th>Pendidikan Terakhir</th>
													<th>Pekerjaan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>
							<div role="tabpanel" id="tabhadxxtd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhadxxtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Kode</th>
													<th>Jenis</th>
													<th>Pelanggaran</th>
													<th>Tanggal Berlaku</th>
													<th>Keterangan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>
							<div role="tabpanel" id="tabhtlxxth" class="tab-pane">
								<div class="panel-body">
									<h3 id="sisa_cuti_text"></h3>
									<div class="table-responsive">
										<table id="tblhtlxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Kode</th>
													<th>Tanggal Awal</th>
													<th>Tanggal Akhir</th>
                                					<th>Jenis</th>
													<th>Keterangan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>
							<div role="tabpanel" id="tabhtpxxth" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhtpxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Kode</th>
													<th>Tanggal </th>
                                					<th>Jenis</th>
													<th>Jam Awal</th>
													<th>Jam Akhir</th>
													<th>Keterangan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>
							<div role="tabpanel" id="tabhemjbrd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemjbrd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Kode</th>
													<th>Status </th>
                                					<th>Jenis Rotasi</th>
													<th>Tanggal Awal</th>
													<th>Tanggal Akhir</th>
													<th>Keterangan</th>
												</tr>
											</thead>
										</table>
									</div> <!-- end of table -->

								</div>
							</div>

							
							<div role="tabpanel" id="tabhemedmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemedmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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
							<div role="tabpanel" id="tabhemjbmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemjbmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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
							<div role="tabpanel" id="tabhemlgmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemlgmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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
							<div role="tabpanel" id="tabhemskmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemskmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
													<th>Jenis Skill</th>
													<th>Tingkat</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
							<div role="tabpanel" id="tabhemtrmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemtrmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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

							<div role="tabpanel" id="tabhemogmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemogmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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
							<div role="tabpanel" id="tabhemdhmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemdhmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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
							<div role="tabpanel" id="tabhemecmd" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhemecmd" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hemxxmh</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hemxxmh/fn/hemxxmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthemxxmh, tblhemxxmh, show_inactive_status_hemxxmh = 0, id_hemxxmh;
        var edthemfmmd, tblhemfmmd, show_inactive_status_hemfmmd = 0, id_hemfmmd;
        var edthadxxtd, tblhadxxtd, show_inactive_status_hadxxtd = 0, id_hadxxtd;
        var edthtlxxth, tblhtlxxth, show_inactive_status_htlxxth = 0, id_htlxxth;
        var edthtpxxth, tblhtpxxth, show_inactive_status_htpxxth = 0, id_htpxxth;
        var edthemjbrd, tblhemjbrd, show_inactive_status_hemjbrd = 0, id_hemjbrd;

		var edthemedmd, tblhemedmd, show_inactive_status_hemedmd = 0, id_hemedmd;
        var edthemjbmd, tblhemjbmd, show_inactive_status_hemjbmd = 0, id_hemjbmd;
        var edthemlgmd, tblhemlgmd, show_inactive_status_hemlgmd = 0, id_hemlgmd;
        var edthemskmd, tblhemskmd, show_inactive_status_hemskmd = 0, id_hemskmd;
        var edthemtrmd, tblhemtrmd, show_inactive_status_hemtrmd = 0, id_hemtrmd;

		var edthemogmd, tblhemogmd, show_inactive_status_hemogmd = 0, id_hemogmd;
        var edthemdhmd, tblhemdhmd, show_inactive_status_hemdhmd = 0, id_hemdhmd;
        var edthemecmd, tblhemecmd, show_inactive_status_hemecmd = 0, id_hemecmd;
		// ------------- end of default variable

		var id_hovxxmh_old = 0, id_hodxxmh_old = 0, id_hosxxmh_old = 0, id_hetxxmh_old = 0, id_hevxxmh_old = 0, id_heyxxmh_old = 0, id_hesxxmh_old = 0;
		var id_hobxxmh_old = 0;
		var id_hedlvmh_old = 0;
		var id_gtxpkmh_old = 0, id_holxxmd_2_old = 0;
		var id_heyxxmd_old = 0, tanggal_keluar_old = null, id_gctxxmh_old = 0;
		var id_gctxxmh_domisili_old = 0, id_gctxxmh_ktp_old = 0;
		var id_gctxxmh_old_pendidikan = 0;
		var id_gedxxmh_old_pendidikan = 0;
		var id_hevgrmh_old = 0;

		$(document).ready(function() {

			//start datatables editor
			edthemxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemxxmh_hl.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemxxmh = show_inactive_status_hemxxmh;
					}
				},
				template: "#custom_hem",
				table: "#tblhemxxmh",
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
						def: "hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemxxmh.is_active",
                        type: "hidden",
						def: 1
					},		
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hemxxmh.nama"
					}, 	
					{
						label: "Foto",
						name: "hemxxmh.id_files_foto",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthemxxmh.file( 'files', fileId ).web_path+'"/>';
							}else{
								return '';
							}
							
						},
						noFileText: 'Belum ada gambar'
					},
					{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "hemxxmh.kode"
					}, 	
					{
						label: "Kode Finger<sup class='text-danger'>*<sup>",
						name: "hemxxmh.kode_finger"
					}, 	
					{
						label: "No KTP <sup class='text-danger'>*<sup>",
						name: "hemdcmh.ktp_no"
					}, 	
					{
						label: "No BPJS TK <sup class='text-danger'>*<sup>",
						name: "hemdcmh.no_bpjs_tk"
					}, 	
					{
						label: "No BPJS Kesehatan <sup class='text-danger'>*<sup>",
						name: "hemdcmh.no_bpjs_kes"
					}, 
					{
						label: "Grup Jabatan <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hevgrmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hevgrmh/hevgrmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hevgrmh_old: id_hevgrmh_old,
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
						label: "Divisi <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hovxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hovxxmh/hovxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hovxxmh: 0,
										id_hovxxmh_old: id_hovxxmh_old,
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
						label: "Department <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hodxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hodxxmh/hodxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hodxxmh: 0,
										id_hodxxmh_old: id_hodxxmh_old,
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
						label: "Unit Kerja <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hosxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hosxxmh/hosxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hosxxmh: 0,
										id_hosxxmh_old: id_hosxxmh_old,
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
						label: "Bagian <sup class='text-danger'>*<sup>",  
						name: "hemjbmh.id_hobxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hobxxmh/hobxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hobxxmh_old: id_hobxxmh_old,
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
						label: "Grade",
						name: "hemjbmh.id_hevxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hevxxmh/hevxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hevxxmh: 0,
										id_hevxxmh_old: id_hevxxmh_old,
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
						label: "Jabatan <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hetxxmh",
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
					},
					{
						label: "Sub Tipe <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_heyxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmd/heyxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmd: 0,
										id_heyxxmd_old: id_heyxxmd_old,
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
						label: "Tipe <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_heyxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh: 0,
										id_heyxxmh_old: id_heyxxmh_old,
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
						label: "Status <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_hesxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hesxxmh/hesxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hesxxmh: 0,
										id_hesxxmh_old: id_hesxxmh_old,
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
						label: "Area Kerja <sup class='text-danger'>*<sup>",
						name: "hemjbmh.id_holxxmd_2",
						type: "select2",
						fieldInfo: "Master Area Kerja",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/holxxmd_2/holxxmd_2_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_holxxmd_2_old: id_holxxmd_2_old,
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
						label: "Tanggal Join",
						name: "hemjbmh.tanggal_masuk",
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
						label: "Tanggal Akhir Kontrak",
						name: "hemjbmh.tanggal_akhir_kontrak",
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
						label: "Grup Hari Kerja <sup class='text-danger'>*<sup>",
						name: "hemjbmh.grup_hk",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "5 HK", "value": 1 },
							{ "label": "6 HK", "value": 2 }
						]
					},
					{
						label: "4 Grup <sup class='text-danger'>*<sup>",
						name: "hemjbmh.jumlah_grup",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Tidak", "value": 1 },
							{ "label": "Ya", "value": 2 }
						]
					},
					{
						label: "Potong Makan <sup class='text-danger'>*<sup>",
						name: "hemxxmh.is_pot_makan",
						fieldInfo: "Catering = Tidak dipotong makan.",
						type: "select2",
						def: 1,
						options: [
							{ "label": "Potong Makan", "value": 1 },
							{ "label": "Catering", "value": 0 }
						]
					},
					{
						label: "Tukar Jadwal <sup class='text-danger'>*<sup>",
						name: "hemxxmh.is_tukar",
						type: "select2",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": -9 }
						]
					},
					{
						label: "Keterangan",
						name: "hemxxmh.keterangan",
						type: "textarea"
					},
					{
						label: "Status Aktif",
						name: "status_aktif",
						type: "select",
						options: [
							{ "label": "Aktif", "value": 1 },
							{ "label": "Non Aktif", "value": 0 }
						]
					},
					{
						label: "PTKP <sup class='text-danger'>*<sup>",
						name: "hemdcmh.id_gtxpkmh",
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
					},
					{
						label: "NPWP <sup class='text-danger'>*<sup>",
						name: "hemdcmh.is_npwp",
						type: "select2",
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},
					{
						label: "No NPWP <sup class='text-danger'>*<sup>" ,
						name: "hemdcmh.npwp_no"
					},
					{
						label: "Alamat NPWP <sup class='text-danger'>*<sup>" ,
						name: "hemdcmh.npwp_alamat",
						type: "textarea"
					},
					{
						label: "Gender <sup class='text-danger'>*<sup>",
						name: "hemxxmh.gender",
						type: "select2",
						options: [
							{ "label": "Laki-laki", "value": "Laki-laki" },
							{ "label": "Perempuan", "value": "Perempuan" },
						]
					},
					{
						label: "Kota Lahir <sup class='text-danger'>*<sup>",
						name: "hemxxmh.id_gctxxmh_lahir",
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
										id_gctxxmh_old: id_gctxxmh_old,
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
						label: "Kota KTP",
						name: "hemdcmh.id_gctxxmh_ktp",
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
								minimumResultsForSearch: -1
							}
						}
					},
					{
						label: "Kota Domisili",
						name: "hemdcmh.id_gctxxmh_domisili",
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
										id_gctxxmh_old: id_gctxxmh_domisili_old,
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
						label: "Tanggal Lahir  <sup class='text-danger'>*<sup>",
						name: "hemxxmh.tanggal_lahir",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Alamat Domisili" ,
						name: "hemdcmh.alamat",
						type: "textarea"
					},
					{
						label: "Alamat KTP " ,
						name: "hemdcmh.ktp_alamat",
						type: "textarea"
					},
					{
						label: "Desa Domisili" ,
						name: "hemdcmh.domisili_desa",
						type: "textarea"
					},
					{
						label: "Desa KTP " ,
						name: "hemdcmh.ktp_desa",
						type: "textarea"
					},
					{
						label: "Kecamatan Domisili" ,
						name: "hemdcmh.domisili_kecamatan",
						type: "textarea"
					},
					{
						label: "Kecamatan KTP " ,
						name: "hemdcmh.ktp_kecamatan",
						type: "textarea"
					},
					{
						label: "Harian Lepas",
						name: "hemjbmh.is_harian_lepas",
						type: "select",
						placeholder : "Select",
						def: 1,
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					},
				]
			} );
			
			edthemxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemxxmh.field('start_on').val(start_on);
				edthemxxmh.field('status_aktif').hide();
				edthemxxmh.field('hemjbmh.id_heyxxmh').disable();
				edthemxxmh.field('hemjbmh.is_harian_lepas').disable();

				if (action == 'edit') {
					edthemxxmh.field('status_aktif').show();
					edthemxxmh.field('status_aktif').val(is_active);
					edthemxxmh.field('hemjbmh.grup_hk').hide();
					edthemxxmh.field('hemjbmh.jumlah_grup').disable();
					edthemxxmh.field('hemjbmh.tanggal_akhir_kontrak').hide();
				} else {
					edthemxxmh.field('hemjbmh.grup_hk').show();
					edthemxxmh.field('hemjbmh.jumlah_grup').enable();
					edthemxxmh.field('hemjbmh.tanggal_akhir_kontrak').show();
				}
			});

            edthemxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-xl");
			});

			edthemxxmh.dependent( 'hemdcmh.is_npwp', function ( val, data, callback ) {
				if (val == 1) {
					edthemxxmh.field('hemdcmh.npwp_no').show();
					edthemxxmh.field('hemdcmh.npwp_alamat').show();
					edthemxxmh.field('hemdcmh.npwp_no').val();
					edthemxxmh.field('hemdcmh.npwp_alamat').val();
				} else {
					edthemxxmh.field('hemdcmh.npwp_no').hide();
					edthemxxmh.field('hemdcmh.npwp_alamat').hide();
					edthemxxmh.field('hemdcmh.npwp_no').val('');
					edthemxxmh.field('hemdcmh.npwp_alamat').val('');
				}
				return {}
			}, {event: 'keyup change'});
			
			edthemxxmh.dependent( 'hemjbmh.id_hesxxmh', function ( val, data, callback ) {
				tanggal_akhir_kontrak();
				return {}
			}, {event: 'keyup change'});
			
			edthemxxmh.dependent( 'hemjbmh.tanggal_masuk', function ( val, data, callback ) {
				tanggal_akhir_kontrak();
				return {}
			}, {event: 'keyup change'});
			
			edthemxxmh.dependent( 'hemjbmh.id_heyxxmd', function ( val, data, callback ) {
				if (val > 0) {
					get_heyxxmh();
				}
				return {}
			}, {event: 'keyup change'});
			
			edthemxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemjbmh.id_hevgrmh 
					id_hevgrmh = edthemxxmh.field('hemjbmh.id_hevgrmh').val();
					if(!id_hevgrmh || id_hevgrmh == ''){
						edthemxxmh.field('hemjbmh.id_hevgrmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hevgrmh 

					id_gctxxmh_lahir = edthemxxmh.field('hemxxmh.id_gctxxmh_lahir').val();
					if(!id_gctxxmh_lahir || id_gctxxmh_lahir == ''){
						edthemxxmh.field('hemxxmh.id_gctxxmh_lahir').error( 'Wajib diisi!' );
					}
					
					gender = edthemxxmh.field('hemxxmh.gender').val();
					if(!gender || gender == ''){
						edthemxxmh.field('hemxxmh.gender').error( 'Wajib diisi!' );
					}

					tanggal_lahir = edthemxxmh.field('hemxxmh.tanggal_lahir').val();
					if(!tanggal_lahir || tanggal_lahir == ''){
						edthemxxmh.field('hemxxmh.tanggal_lahir').error( 'Wajib diisi!' );
					}
					
					// BEGIN of validasi hemxxmh.kode 
					kode = edthemxxmh.field('hemxxmh.kode').val();
					if(!kode || kode == ''){
						edthemxxmh.field('hemxxmh.kode').error( 'Wajib diisi!' );
					}
					
					// BEGIN of cek unik hemxxmh.kode 
					if(action == 'create'){
						id_hemxxmh = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'hemxxmh',
							nama_field: 'kode',
							nama_field_value: '"'+kode+'"',
							id_transaksi: id_hemxxmh
						},
						success: function ( json ) {
							if(json.data.count == 1){
								edthemxxmh.field('hemxxmh.kode').error( 'Data tidak boleh kembar!' );
							}
						}
					} );
					// END of cek unik hemxxmh.kode 
					// END of validasi hemxxmh.kode 

					// BEGIN of validasi hemxxmh.nama 
					nama = edthemxxmh.field('hemxxmh.nama').val();
					if(!nama || nama == ''){
						edthemxxmh.field('hemxxmh.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.nama 
					
					// BEGIN of validasi hemxxmh.is_pot_makan 
					is_pot_makan = edthemxxmh.field('hemxxmh.is_pot_makan').val();
					if(!is_pot_makan || is_pot_makan == ''){
						edthemxxmh.field('hemxxmh.is_pot_makan').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.is_pot_makan 
					
					// BEGIN of validasi hemxxmh.is_tukar 
					is_tukar = edthemxxmh.field('hemxxmh.is_tukar').val();
					if(!is_tukar || is_tukar == ''){
						edthemxxmh.field('hemxxmh.is_tukar').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.is_tukar 

					// BEGIN of validasi hemdcmh.ktp_no 
					ktp_no = edthemxxmh.field('hemdcmh.ktp_no').val();
					if(!ktp_no || ktp_no == ''){
						edthemxxmh.field('hemdcmh.ktp_no').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(ktp_no <= 0 ){
						edthemxxmh.field('hemdcmh.ktp_no').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(ktp_no) ){
						edthemxxmh.field('hemdcmh.ktp_no').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hemxxmh.kode_finger 

					// BEGIN of validasi hemdcmh.no_bpjs_kes 
					no_bpjs_kes = edthemxxmh.field('hemdcmh.no_bpjs_kes').val();
					if(!no_bpjs_kes || no_bpjs_kes == ''){
						edthemxxmh.field('hemdcmh.no_bpjs_kes').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(no_bpjs_kes <= 0 ){
						edthemxxmh.field('hemdcmh.no_bpjs_kes').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(no_bpjs_kes) ){
						edthemxxmh.field('hemdcmh.no_bpjs_kes').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hemxxmh.no_bpjs_kes 

					// BEGIN of validasi hemdcmh.no_bpjs_tk 
					no_bpjs_tk = edthemxxmh.field('hemdcmh.no_bpjs_tk').val();
					if(!no_bpjs_tk || no_bpjs_tk == ''){
						edthemxxmh.field('hemdcmh.no_bpjs_tk').error( 'Wajib diisi!' );
					}
					// END of validasi hemxxmh.no_bpjs_tk 

					// BEGIN of validasi hemxxmh.kode_finger 
					kode_finger = edthemxxmh.field('hemxxmh.kode_finger').val();
					if(!kode_finger || kode_finger == ''){
						edthemxxmh.field('hemxxmh.kode_finger').error( 'Wajib diisi!' );
					}
					// validasi min atau max angka
					if(kode_finger <= 0 ){
						edthemxxmh.field('hemxxmh.kode_finger').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(kode_finger) ){
						edthemxxmh.field('hemxxmh.kode_finger').error( 'Inputan harus berupa Angka!' );
					}
					// END of validasi hemxxmh.kode_finger 

					// BEGIN of validasi hemjbmh.id_hovxxmh 
					id_hovxxmh = edthemxxmh.field('hemjbmh.id_hovxxmh').val();
					if(!id_hovxxmh || id_hovxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hovxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hovxxmh 

					// BEGIN of validasi hemjbmh.id_hodxxmh 
					id_hodxxmh = edthemxxmh.field('hemjbmh.id_hodxxmh').val();
					if(!id_hodxxmh || id_hodxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hodxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hodxxmh 

					// BEGIN of validasi hemjbmh.id_hosxxmh 
					id_hosxxmh = edthemxxmh.field('hemjbmh.id_hosxxmh').val();
					if(!id_hosxxmh || id_hosxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hosxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hosxxmh 

					// BEGIN of validasi hemjbmh.id_hevxxmh 
					// id_hevxxmh = edthemxxmh.field('hemjbmh.id_hevxxmh').val();
					// if(!id_hevxxmh || id_hevxxmh == ''){
					// 	edthemxxmh.field('hemjbmh.id_hevxxmh').error( 'Wajib diisi!' );
					// }
					// END of validasi hemjbmh.id_hevxxmh 

					// BEGIN of validasi hemjbmh.id_hobxxmh 
					id_hobxxmh = edthemxxmh.field('hemjbmh.id_hobxxmh').val();
					if(!id_hobxxmh || id_hobxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hobxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hobxxmh 

					is_npwp = edthemxxmh.field('hemdcmh.is_npwp').val();
					if(!is_npwp || is_npwp == ''){
						edthemxxmh.field('hemdcmh.is_npwp').error( 'Wajib diisi!' );
					}
					if (is_npwp == 1) {
						// BEGIN of validasi hemdcmh.npwp_no 
						npwp_no = edthemxxmh.field('hemdcmh.npwp_no').val();
						if(!npwp_no || npwp_no == ''){
							edthemxxmh.field('hemdcmh.npwp_no').error( 'Wajib diisi!' );
						}
								
						if(isNaN(npwp_no) ){
							edthemxxmh.field('hemdcmh.npwp_no').error( 'Inputan harus berupa Angka!' );
						}
						// END of validasi hemdcmh.npwp_no 

						// BEGIN of validasi hemdcmh.npwp_alamat 
						npwp_alamat = edthemxxmh.field('hemdcmh.npwp_alamat').val();
						if(!npwp_alamat || npwp_alamat == ''){
							edthemxxmh.field('hemdcmh.npwp_alamat').error( 'Wajib diisi!' );
						}
						// END of validasi hemdcmh.npwp_alamat 
					}

					// BEGIN of validasi hemjbmh.id_heyxxmd 
					id_heyxxmd = edthemxxmh.field('hemjbmh.id_heyxxmd').val();
					if(!id_heyxxmd || id_heyxxmd == ''){
						edthemxxmh.field('hemjbmh.id_heyxxmd').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_heyxxmd 

					// BEGIN of validasi hemjbmh.jumlah_grup 
					jumlah_grup = edthemxxmh.field('hemjbmh.jumlah_grup').val();
					if(!jumlah_grup || jumlah_grup == ''){
						edthemxxmh.field('hemjbmh.jumlah_grup').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.jumlah_grup 

					// BEGIN of validasi hemjbmh.id_hesxxmh 
					id_hesxxmh = edthemxxmh.field('hemjbmh.id_hesxxmh').val();
					if(!id_hesxxmh || id_hesxxmh == ''){
						edthemxxmh.field('hemjbmh.id_hesxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_hesxxmh 

					// BEGIN of validasi hemjbmh.id_holxxmd_2 
					id_holxxmd_2 = edthemxxmh.field('hemjbmh.id_holxxmd_2').val();
					if(!id_holxxmd_2 || id_holxxmd_2 == ''){
						edthemxxmh.field('hemjbmh.id_holxxmd_2').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.id_holxxmd_2 

					// BEGIN of validasi hemdcmh.id_gtxpkmh 
					id_gtxpkmh = edthemxxmh.field('hemdcmh.id_gtxpkmh').val();
					if(!id_gtxpkmh || id_gtxpkmh == ''){
						edthemxxmh.field('hemdcmh.id_gtxpkmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemdcmh.id_gtxpkmh 

					// BEGIN of validasi hemjbmh.tanggal_masuk 
					tanggal_masuk = edthemxxmh.field('hemjbmh.tanggal_masuk').val();
					if(!tanggal_masuk || tanggal_masuk == ''){
						edthemxxmh.field('hemjbmh.tanggal_masuk').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmh.tanggal_masuk 

					if (action == 'create') {
						// BEGIN of validasi hemjbmh.grup_hk 
						grup_hk = edthemxxmh.field('hemjbmh.grup_hk').val();
						if(!grup_hk || grup_hk == ''){
							edthemxxmh.field('hemjbmh.grup_hk').error( 'Wajib diisi!' );
						}
						// END of validasi hemjbmh.grup_hk 
					}
				}
				
				if ( edthemxxmh.inError() ) {
					return false;
				}
			});

			edthemxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemxxmh.field('finish_on').val(finish_on);
				if (action == 'edit') {
					status_aktif = edthemxxmh.field('status_aktif').val()
					edthemxxmh.field('hemxxmh.is_active').val(status_aktif);
					// get_tgl_keluar();
				}
			});
			
			edthemxxmh.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
				tblhemxxmh.ajax.reload(null,false);
				tblhemjbrd.ajax.reload(null,false);
			} );
				
			edthemxxmh.on( 'close', function () {
				edthemxxmh.enable();
			} );
			
			//start datatables
			tblhemxxmh = $('#tblhemxxmh').DataTable( {
				searchPanes:{
					layout: 'columns-4'
				},
				dom: 
					"<P>"+
					"<lf>"+
					"<B>"+
					"<rt>"+
					"<'row'<'col-sm-4'i><'col-sm-8'p>>",
				columnDefs:[
					{
						searchPanes:{
							show: true,
						},
						targets: [4,5,6,7,8,9,10,11,13,15]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				],
				ajax: {
					url: "../../models/hemxxmh/hemxxmh_hl.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemxxmh = show_inactive_status_hemxxmh;
					}
				},
				order: [[ 1, "desc" ]],
				responsive: false,
				scrollX: true,
				columns: [
					{ data: "hemxxmh.id",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.kode_finger" },
					{ data: "hemdcmh.ktp_no" },
					{ data: "hemxxmh.nama" }, //4
					{ data: "hodxxmh.nama" },
					{ data: "hosxxmh.nama" },
					{ data: "hevgrmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "holxxmd_2.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },//10
					{ data: "hevxxmh.nama" },//10
					{ data: "hobxxmh.nama" },//10
					{ data: "hemjbmh.tanggal_masuk" },
					{ data: "hemjbmh.tanggal_keluar" },
					{ 
						data: "hemjbmh.grup_hk",
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '5HK';
							}else if(data == 2){
								return '6HK';
							}else{
								return '<span class="text-danger"> Data Invalid</span>';
							}
						}
					},
					{ data: "hemxxmh.gender" },
					{ 
						data: "hemjbmh.is_harian_lepas",
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}
						}
					},
					{ 
						data: "hemxxmh.is_active",
						render: function (data){
							if (data == 0){
								return '<i class="fa fa-remove text-danger"></i>';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == -9){
								return '<span class="text-danger">Data Error</span>';
							}
						}
					}
				],
				buttons: [

					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemxxmh';
						$table       = 'tblhemxxmh';
						$edt         = 'edthemxxmh';
						$show_status = '_hemxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblhemxxmh.searchPanes.container().appendTo( '#searchPanes1' );

			tblhemxxmh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tblhemfmmd, tblhadxxtd, tblhtlxxth, tblhtpxxth, tblhemjbrd,
					tblhemedmd,
					tblhemjbmd,
					tblhemlgmd,
					tblhemskmd,
					tblhemtrmd,

					tblhemogmd,
					tblhemdhmd,
					tblhemecmd,
				];
				CekInitHeaderHD(tblhemxxmh, tbl_details);
			} );
			
			tblhemxxmh.on( 'select', function( e, dt, type, indexes ) {
				data_hemxxmh = tblhemxxmh.row( { selected: true } ).data().hemxxmh;
				id_hemxxmh  = data_hemxxmh.id;
				id_transaksi_h   = id_hemxxmh; // dipakai untuk general
				is_approve       = data_hemxxmh.is_approve;
				is_nextprocess   = data_hemxxmh.is_nextprocess;
				is_jurnal        = data_hemxxmh.is_jurnal;
				is_active        = data_hemxxmh.is_active;
				id_gctxxmh_old        = data_hemxxmh.id_gctxxmh_lahir;

				data_hemjbmh = tblhemxxmh.row( { selected: true } ).data().hemjbmh;
				id_hovxxmh_old   = data_hemjbmh.id_hovxxmh;
				id_hodxxmh_old   = data_hemjbmh.id_hodxxmh;
				id_hosxxmh_old   = data_hemjbmh.id_hosxxmh;
				id_hevxxmh_old   = data_hemjbmh.id_hevxxmh;
				id_hetxxmh_old   = data_hemjbmh.id_hetxxmh;
				id_heyxxmh_old   = data_hemjbmh.id_heyxxmh;
				id_heyxxmd_old   = data_hemjbmh.id_heyxxmd;
				id_hobxxmh_old   = data_hemjbmh.id_hobxxmh;
				id_hesxxmh_old   = data_hemjbmh.id_hesxxmh;
				id_holxxmd_2_old   = data_hemjbmh.id_holxxmd_2;
				id_hevgrmh_old   = data_hemjbmh.id_hevgrmh;
				tanggal_keluar_old   = data_hemjbmh.tanggal_akhir_kontrak;

				data_hemdcmh = tblhemxxmh.row( { selected: true } ).data().hemdcmh;
				id_gtxpkmh_old   = data_hemdcmh.id_gtxpkmh;
				id_gctxxmh_ktp_old   = data_hemdcmh.id_gctxxmh_ktp;
				id_gctxxmh_domisili_old   = data_hemdcmh.id_gctxxmh_domisili;
				
				// atur hak akses
				tbl_details = [tblhemfmmd, tblhadxxtd, tblhtlxxth, tblhtpxxth, tblhemjbrd,
					tblhemedmd,
					tblhemjbmd,
					tblhemlgmd,
					tblhemskmd,
					tblhemtrmd,

					tblhemogmd,
					tblhemdhmd,
					tblhemecmd,
				];
				CekSelectHeaderHD(tblhemxxmh, tbl_details);

				$('#sisa_cuti_text').empty();
				const tanggal = moment().format('YYYY-MM-DD');
				const year = moment().format('YYYY');

				$.ajax( {
					url: "../../models/htlxxth/fn_sisa_saldo_cuti.php",
					dataType: 'json',
					type: 'POST',
					data: {
						tanggal: tanggal,
						id_hemxxmh: id_hemxxmh
					},
					success: function ( json ) {
						saldo = json.data.rs_saldo.sisa_saldo;
						$('#sisa_cuti_text').html('Sisa Cuti '+ year + ' : ' +saldo);
					}
				} );
			} );
			
			tblhemxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hemxxmh = 0;
				id_heyxxmd_old = 0;
				id_hobxxmh_old = 0;
				id_gtxpkmh_old = 0;
				id_hovxxmh_old   = 0, id_hodxxmh_old   = 0, id_hosxxmh_old   = 0, id_hevxxmh_old   = 0, id_hetxxmh_old   = 0, id_heyxxmh_old   = 0, id_hesxxmh_old   = 0, tanggal_keluar_old = null;
				id_holxxmd_2_old   = 0;
				id_hevgrmh_old   = 0;
				id_gctxxmh_old   = 0;
				
				id_gctxxmh_ktp_old = 0;
				id_gctxxmh_domisili_old = 0;

				// atur hak akses
				tbl_details = [tblhemfmmd, tblhadxxtd, tblhtlxxth, tblhtpxxth, tblhemjbrd,
					tblhemedmd,
					tblhemjbmd,
					tblhemlgmd,
					tblhemskmd,
					tblhemtrmd,

					tblhemogmd,
					tblhemdhmd,
					tblhemecmd,
				];
				CekDeselectHeaderHD(tblhemxxmh, tbl_details);
				
				$('#sisa_cuti_text').empty();
			} );
			
// --------- start _detail --------------- //

			//start datatables editor
			edthemfmmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemfmmd = show_inactive_status_hemfmmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemfmmd",
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
						def: "hemfmmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemfmmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemfmmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Hubungan <sup class='text-danger'>*<sup>",
						name: "hemfmmd.hubungan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ayah", "value": "Ayah" },
							{ "label": "Ibu", "value": "Ibu" },
							{ "label": "Kakak", "value": "Kakak" },
							{ "label": "Adik", "value": "Adik" },
							{ "label": "Suami", "value": "Suami" },
							{ "label": "Istri", "value": "Istri" },
							{ "label": "Anak", "value": "Anak" },
						]
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "hemfmmd.nama"
					},	{
						label: "Gender <sup class='text-danger'>*<sup>",
						name: "hemfmmd.gender",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Laki-laki", "value": "Laki-laki" },
							{ "label": "Perempuan", "value": "Perempuan" }
						]
					}, 	{
						label: "Tanggal Lahir <sup class='text-danger'>*<sup>",
						name: "hemfmmd.tanggal_lahir",
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
						label: "Pendidikan Terakhir",
						name: "hemfmmd.id_hedlvmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hedlvmh/hedlvmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hedlvmh_old: id_hedlvmh_old,
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
						name: "hemfmmd.pekerjaan"
					}
				]
			} );
			
			edthemfmmd.on( 'preOpen', function( e, mode, action ) {
				edthemfmmd.field('hemfmmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemfmmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemfmmd.rows().deselect();
				}
			});

            edthemfmmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemfmmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemfmmd.nama 
					nama = edthemfmmd.field('hemfmmd.nama').val();
					if(!nama || nama == ''){
						edthemfmmd.field('hemfmmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.nama

					// BEGIN of validasi hemfmmd.tanggal_lahir 
					tanggal_lahir = edthemfmmd.field('hemfmmd.tanggal_lahir').val();
					if(!tanggal_lahir || tanggal_lahir == ''){
						edthemfmmd.field('hemfmmd.tanggal_lahir').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.tanggal_lahir

					// BEGIN of validasi hemfmmd.gender 
					gender = edthemfmmd.field('hemfmmd.gender').val();
					if(!gender || gender == ''){
						edthemfmmd.field('hemfmmd.gender').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.gender

					// BEGIN of validasi hemfmmd.hubungan 
					hubungan = edthemfmmd.field('hemfmmd.hubungan').val();
					if(!hubungan || hubungan == ''){
						edthemfmmd.field('hemfmmd.hubungan').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.hubungan

					// BEGIN of validasi hemfmmd.pekerjaan 
					pekerjaan = edthemfmmd.field('hemfmmd.pekerjaan').val();
					if(!pekerjaan || pekerjaan == ''){
						edthemfmmd.field('hemfmmd.pekerjaan').error( 'Wajib diisi!' );
					}
					// END of validasi hemfmmd.pekerjaan
				
				}
				
				if ( edthemfmmd.inError() ) {
					return false;
				}
			});

			edthemfmmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemfmmd.field('finish_on').val(finish_on);
			});
			
			edthemfmmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemfmmd = $('#tblhemfmmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemfmmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemfmmd = show_inactive_status_hemfmmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemfmmd.id",visible:false },
					{ data: "hemfmmd.id_hemxxmh",visible:false },
					{ data: "hemfmmd.hubungan" },
					{ data: "hemfmmd.nama" },
					{ data: "hemfmmd.gender" },
					{ data: "hemfmmd.tanggal_lahir" },
					{ data: "hedlvmh.nama" },
					{ data: "hemfmmd.pekerjaan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemfmmd';
						$table       = 'tblhemfmmd';
						$edt         = 'edthemfmmd';
						$show_status = '_hemfmmd';
						$table_name  = $nama_tabels_d[0];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemfmmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemfmmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemfmmd, 'hemfmmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemfmmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemfmmd = tblhemfmmd.row( { selected: true } ).data().hemfmmd;
				id_hemfmmd   = data_hemfmmd.id;
				id_transaksi_d    = id_hemfmmd; // dipakai untuk general
				is_active_d       = data_hemfmmd.is_active;

				id_hedlvmh_old       = data_hemfmmd.id_hemfmmd;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemfmmd );
			} );

			tblhemfmmd.on( 'deselect', function() {
				id_hemfmmd = 0;
				is_active_d = 0;
				id_hedlvmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemfmmd );
			} );

// --------- end _detail --------------- //
// --------- start _detail --------------- //

			//start datatables editor
			edthadxxtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hadxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxtd = show_inactive_status_hadxxtd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhadxxtd",
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
						def: "hadxxtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hadxxtd.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Nama",
						name: "hadxxtd.id_hemxxmh",
						type: "select2",
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
						label: "Pelanggaran <sup class='text-danger'>*<sup>",
						name: "hadxxtd.id_havxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/havxxmh/havxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_havxxmh_old: id_havxxmh_old,
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
						label: "Tindakan <sup class='text-danger'>*<sup>",
						name: "hadxxtd.id_hadxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hadxxmh/hadxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hadxxmh_saran: id_hadxxmh_saran,
										id_hadxxmh_old: id_hadxxmh_old,
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
						label: "Tanggal Mulai Berlaku",
						name: "hadxxtd.tanggal_awal",
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
						label: "Tanggal Selesai Berlaku",
						name: "hadxxtd.tanggal_akhir",
						type: "datetime",
						format: 'DD MMM YYYY'
					},
					{
						label: "Bukti",
						name: "hadxxtd.id_files_bukti",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthadxxtd.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada lampiran'
					},
					{
						label: "Dokumen",
						name: "hadxxtd.id_files_dokumen",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthadxxtd.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada lampiran'
					},
					{
						label: "Keterangan",
						name: "hadxxtd.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthadxxtd.on( 'preOpen', function( e, mode, action ) {
				edthadxxtd.field('hadxxtd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxtd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhadxxtd.rows().deselect();
				}
			});

            edthadxxtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
            edthadxxtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hadxxtd.id_hemxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edthadxxtd.field('hadxxtd.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthadxxtd.field('hadxxtd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_hemxxmh

					// BEGIN of validasi hadxxtd.id_havxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_havxxmh').isMultiValue() ) {
						id_havxxmh = edthadxxtd.field('hadxxtd.id_havxxmh').val();
						if(!id_havxxmh || id_havxxmh == ''){
							edthadxxtd.field('hadxxtd.id_havxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_havxxmh

					// BEGIN of validasi hadxxtd.id_hadxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_hadxxmh').isMultiValue() ) {
						id_hadxxmh = edthadxxtd.field('hadxxtd.id_hadxxmh').val();
						if(!id_hadxxmh || id_hadxxmh == ''){
							edthadxxtd.field('hadxxtd.id_hadxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_hadxxmh
				}
				
				if ( edthadxxtd.inError() ) {
					return false;
				}
			});

			edthadxxtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxtd.field('finish_on').val(finish_on);
			});
			
			edthadxxtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhadxxtd = $('#tblhadxxtd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hadxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxtd = show_inactive_status_hadxxtd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hadxxtd.id",visible:false },
					{ data: "hadxxtd.id_hemxxmh",visible:false },
					{ data: "hadxxtd.kode" },
					{ data: "hadxxmh.nama" },
					{ data: "havxxmh.nama" },
					{ data: "hadxxtd.tanggal_awal" },
					{ data: "hadxxtd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hadxxtd';
						$table       = 'tblhadxxtd';
						$edt         = 'edthadxxtd';
						$show_status = '_hadxxtd';
						$table_name  = $nama_tabels_d[1];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hadxxtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhadxxtd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhadxxtd, 'hadxxtd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhadxxtd.on( 'select', function( e, dt, type, indexes ) {
				data_hadxxtd = tblhadxxtd.row( { selected: true } ).data().hadxxtd;
				id_hadxxtd   = data_hadxxtd.id;
				id_transaksi_d    = id_hadxxtd; // dipakai untuk general
				is_active_d       = data_hadxxtd.is_active;

				id_hedlvmh_old       = data_hadxxtd.id_hadxxtd;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhadxxtd );
			} );

			tblhadxxtd.on( 'deselect', function() {
				id_hadxxtd = 0;
				is_active_d = 0;
				id_hedlvmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhadxxtd );
			} );

// --------- end _detail --------------- //
// --------- start _detail --------------- //

			//start datatables editor
			edthtlxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/htlxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxth = show_inactive_status_htlxxth;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhtlxxth",
				formOptions: {
					main: {
						focus: 3
					}
				},
				fields: [ 
					{
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						label: "field_tanggal",
						name: "field_tanggal",
						type: "hidden"
					},	{
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
						def: "htlxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htlxxth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htlxxth.id_hemxxmh",
						type: "select2",
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
										id_heyxxmh: id_heyxxmh,
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
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "htlxxth.id_htlxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htlxxmh/htlxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htlxxmh_old: id_htlxxmh_old,
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
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "htlxxth.tanggal_awal",
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
						label: "Tanggal Akhir <sup class='text-danger'>*<sup>",
						name: "htlxxth.tanggal_akhir",
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
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htlxxth.keterangan",
						type: "textarea"
					},	{
						label: "Lampiran",
						name: "htlxxth.id_files_lampiran",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthtlxxth.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada gambar'
					}
				]
			} );
			
			edthtlxxth.on( 'preOpen', function( e, mode, action ) {
				edthtlxxth.field('htlxxth.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtlxxth.rows().deselect();
				}
			});

            edthtlxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
            edthtlxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edthtlxxth.inError() ) {
					return false;
				}
			});

			edthtlxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlxxth.field('finish_on').val(finish_on);
			});
			
			edthtlxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtlxxth = $('#tblhtlxxth').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/htlxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlxxth = show_inactive_status_htlxxth;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 4, "desc" ]],
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "htlxxth.id",visible:false },
					{ data: "htlxxth.id_hemxxmh",visible:false },
					{ data: "htlxxth.kode" },
					{ data: "htlxxth.tanggal_awal" },
					{ data: "htlxxth.tanggal_akhir" },
					{ data: "htlxxmh.nama" },
					{ data: "htlxxth.keterangan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htlxxth';
						$table       = 'tblhtlxxth';
						$edt         = 'edthtlxxth';
						$show_status = '_htlxxth';
						$table_name  = $nama_tabels_d[2];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htlxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtlxxth.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhtlxxth, 'htlxxth' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhtlxxth.on( 'select', function( e, dt, type, indexes ) {
				data_htlxxth = tblhtlxxth.row( { selected: true } ).data().htlxxth;
				id_htlxxth   = data_htlxxth.id;
				id_transaksi_d    = id_htlxxth; // dipakai untuk general
				is_active_d       = data_htlxxth.is_active;

				id_hedlvmh_old       = data_htlxxth.id_htlxxth;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhtlxxth );
			} );

			tblhtlxxth.on( 'deselect', function() {
				id_htlxxth = 0;
				is_active_d = 0;
				id_hedlvmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhtlxxth );
			} );

// --------- end _detail --------------- //
// --------- start _detail --------------- //

			//start datatables editor
			edthtpxxth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/htpxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpxxth = show_inactive_status_htpxxth;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhtpxxth",
				formOptions: {
					main: {
						focus: 3
					}
				},
				fields: [ 
					{
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						label: "field_tanggal",
						name: "field_tanggal",
						type: "hidden"
					},	{
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
						def: "htpxxth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpxxth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htpxxth.tanggal",
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
						label: "Employee <sup class='text-danger'>*<sup>",
						name: "htpxxth.id_hemxxmh",
						type: "select2",
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
										id_heyxxmh: id_heyxxmh,
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
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "htpxxth.id_htpxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htpxxmh/htpxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htpxxmh_old: id_htpxxmh_old,
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
						label: "Jam Awal",
						name: "htpxxth.jam_awal",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'HH:mm'
					},	{
						label: "Jam Akhir",
						name: "htpxxth.jam_akhir",
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'HH:mm'
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htpxxth.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edthtpxxth.on( 'preOpen', function( e, mode, action ) {
				edthtpxxth.field('htpxxth.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpxxth.field('start_on').val(start_on);

				if(action == 'create'){
					tblhtpxxth.rows().deselect();
				}
			});

            edthtpxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
            edthtpxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edthtpxxth.inError() ) {
					return false;
				}
			});

			edthtpxxth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpxxth.field('finish_on').val(finish_on);
			});
			
			edthtpxxth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhtpxxth = $('#tblhtpxxth').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/htpxxth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpxxth = show_inactive_status_htpxxth;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 3, "desc" ]],
				// order: [[ 2, "desc" ]], sementara disable karena kode kosong
				columns: [
					{ data: "htpxxth.id",visible:false },
					{ data: "htpxxth.id_hemxxmh",visible:false },
					{ data: "htpxxth.kode" },
					{ data: "htpxxth.tanggal" },
					{ data: "htpxxmh.nama" },
					{ data: "htpxxth.jam_awal" },
					{ data: "htpxxth.jam_akhir" },
					{ data: "htpxxth.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpxxth';
						$table       = 'tblhtpxxth';
						$edt         = 'edthtpxxth';
						$show_status = '_htpxxth';
						$table_name  = $nama_tabels_d[3];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhtpxxth.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhtpxxth, 'htpxxth' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhtpxxth.on( 'select', function( e, dt, type, indexes ) {
				data_htpxxth = tblhtpxxth.row( { selected: true } ).data().htpxxth;
				id_htpxxth   = data_htpxxth.id;
				id_transaksi_d    = id_htpxxth; // dipakai untuk general
				is_active_d       = data_htpxxth.is_active;

				id_hedlvmh_old       = data_htpxxth.id_htpxxth;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhtpxxth );
			} );

			tblhtpxxth.on( 'deselect', function() {
				id_htpxxth = 0;
				is_active_d = 0;
				id_hedlvmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhtpxxth );
			} );

// --------- end _detail --------------- //
// --------- start _detail --------------- //

			//start datatables editor
			edthemjbrd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemjbrd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemjbrd = show_inactive_status_hemjbrd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemjbrd",
				formOptions: {
					main: {
						focus: 3
					}
				},
				fields: []
			} );
			
			edthemjbrd.on( 'preOpen', function( e, mode, action ) {
				edthemjbrd.field('hemjbrd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemjbrd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemjbrd.rows().deselect();
				}
			});

            edthemjbrd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
            edthemjbrd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
				}
				
				if ( edthemjbrd.inError() ) {
					return false;
				}
			});

			edthemjbrd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemjbrd.field('finish_on').val(finish_on);
			});
			
			edthemjbrd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemjbrd = $('#tblhemjbrd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemjbrd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemjbrd = show_inactive_status_hemjbrd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 0, "desc" ]],
				// order: [[ 2, "desc" ]], sementara disable karena kode kosong
				columns: [
					{ data: "hemjbrd.id",visible:false },
					{ data: "hemjbrd.id_hemxxmh",visible:false },
					{ data: "hemjbrd.kode" },
					{ data: "hesxxmh.nama" },
					{ data: "harxxmh.nama" },
					{ data: "hemjbrd.tanggal_awal" },
					{ data: "hemjbrd.tanggal_akhir" },
					{ data: "hemjbrd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemjbrd';
						$table       = 'tblhemjbrd';
						$edt         = 'edthemjbrd';
						$show_status = '_hemjbrd';
						$table_name  = $nama_tabels_d[3];

						$arr_buttons_tools 		= ['copy','excel','colvis'];;
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
			} );

			tblhemjbrd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemjbrd, 'hemjbrd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemjbrd.on( 'select', function( e, dt, type, indexes ) {
				data_hemjbrd = tblhemjbrd.row( { selected: true } ).data().hemjbrd;
				id_hemjbrd   = data_hemjbrd.id;
				id_transaksi_d    = id_hemjbrd; // dipakai untuk general

				id_hedlvmh_old       = data_hemjbrd.id_hemjbrd;
				is_active_d = 1;
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemjbrd );
			} );

			tblhemjbrd.on( 'deselect', function() {
				id_hemjbrd = 0;
				id_hedlvmh_old = 0;
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemjbrd );
			} );

// --------- end _detail --------------- //


// --------- start _detail pendidikan--------------- //

			//start datatables editor
			edthemedmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemedmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemedmd = show_inactive_status_hemedmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemedmd",
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
						def: "hemedmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemedmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemedmd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Jenjang Pendidikan <sup class='text-danger'>*<sup>",
						name: "hemedmd.id_gedxxmh",
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
										id_gedxxmh_old: id_gedxxmh_old_pendidikan,
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
					},	{
						label: "Nama Institusi <sup class='text-danger'>*<sup>",
						name: "hemedmd.nama"
					}, 	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "hemedmd.id_gctxxmh",
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
										id_gctxxmh_old: id_gctxxmh_old_pendidikan,
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
					},	{
						label: "Tahun Lulus",
						name: "hemedmd.tahun_lulus"
					},	{
						label: "Jurusan",
						name: "hemedmd.jurusan"
					},	{
						label: "Nilai Akhir",
						name: "hemedmd.nilai_akhir"
					}
				]
			} );
			
			edthemedmd.on( 'preOpen', function( e, mode, action ) {
				edthemedmd.field('hemedmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemedmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemedmd.rows().deselect();
				}
			});

            edthemedmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemedmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemedmd.nama 
					nama = edthemedmd.field('hemedmd.nama').val();
					if(!nama || nama == ''){
						edthemedmd.field('hemedmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemedmd.nama 

					// BEGIN of validasi hemedmd.id_gctxxmh 
					id_gctxxmh = edthemedmd.field('hemedmd.id_gctxxmh').val();
					if(!id_gctxxmh || id_gctxxmh == ''){
						edthemedmd.field('hemedmd.id_gctxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemedmd.id_gctxxmh 

					// BEGIN of validasi hemedmd.id_gedxxmh 
					id_gedxxmh = edthemedmd.field('hemedmd.id_gedxxmh').val();
					if(!id_gedxxmh || id_gedxxmh == ''){
						edthemedmd.field('hemedmd.id_gedxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemedmd.id_gedxxmh 

				}
				
				if ( edthemedmd.inError() ) {
					return false;
				}
			});

			edthemedmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemedmd.field('finish_on').val(finish_on);
			});
			
			edthemedmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemedmd = $('#tblhemedmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemedmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemedmd = show_inactive_status_hemedmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemedmd.id",visible:false },
					{ data: "hemedmd.id_hemxxmh",visible:false },
					{ data: "gedxxmh.nama" },
					{ data: "hemedmd.nama" },
					{ data: "gctxxmh.nama" },
					{ data: "hemedmd.tahun_lulus" },
					{ data: "hemedmd.jurusan" },
					{ data: "hemedmd.nilai_akhir" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemedmd';
						$table       = 'tblhemedmd';
						$edt         = 'edthemedmd';
						$show_status = '_hemedmd';
						$table_name  = $nama_tabels_d[5];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemedmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemedmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemedmd, 'hemedmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemedmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemedmd = tblhemedmd.row( { selected: true } ).data().hemedmd;
				id_hemedmd   = data_hemedmd.id;
				id_transaksi_d    = id_hemedmd; // dipakai untuk general
				is_active_d       = data_hemedmd.is_active;
				id_gedxxmh_old_pendidikan       = data_hemedmd.id_gedxxmh;
				id_gctxxmh_old_pendidikan       = data_hemedmd.id_gctxxmh;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemedmd );
			} );

			tblhemedmd.on( 'deselect', function() {
				id_hemedmd = '';
				id_gedxxmh_old_pendidikan = 0;
				id_gctxxmh_old_pendidikan = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemedmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hemjbmd PEKERJAAN --------------- //

			//start datatables editor
			edthemjbmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemjbmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemjbmd = show_inactive_status_hemjbmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemjbmd",
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
						def: "hemjbmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemjbmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemjbmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Perusahaan <sup class='text-danger'>*<sup>",
						name: "hemjbmd.nama"
					},	{
						label: "Alamat",
						name: "hemjbmd.alamat",
						type: "textarea"
					}, 	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "hemjbmd.id_gctxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true
						}
					},	{
						label: "Jenis Usaha",
						name: "hemjbmd.jenis"
					}, {
						label: "Tanggal Awal",
						name: "hemjbmd.tanggal_awal",
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
						name: "hemjbmd.tanggal_akhir",
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
						name: "hemjbmd.jabatan_awal"
					},	{
						label: "jabatan Akhir",
						name: "hemjbmd.jabatan_akhir"
					},	{
						label: "Nama Atasan Langsung",
						name: "hemjbmd.nama_atasan"
					},	{
						label: "Gaji Terakhir",
						name: "hemjbmd.gaji"
					}
				]
			} );
			
			edthemjbmd.on( 'preOpen', function( e, mode, action ) {
				edthemjbmd.field('hemjbmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemjbmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemjbmd.rows().deselect();
				}
			});

            edthemjbmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemjbmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemjbmd.nama 
					nama = edthemjbmd.field('hemjbmd.nama').val();
					if(!nama || nama == ''){
						edthemjbmd.field('hemjbmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmd.nama 

					// BEGIN of validasi hemjbmd.id_gctxxmh 
					id_gctxxmh = edthemjbmd.field('hemjbmd.id_gctxxmh').val();
					if(!id_gctxxmh || id_gctxxmh == ''){
						edthemjbmd.field('hemjbmd.id_gctxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi hemjbmd.id_gctxxmh 

				}
				
				if ( edthemjbmd.inError() ) {
					return false;
				}
			});

			edthemjbmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemjbmd.field('finish_on').val(finish_on);
			});
			
			edthemjbmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemjbmd = $('#tblhemjbmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemjbmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemjbmd = show_inactive_status_hemjbmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemjbmd.id",visible:false },
					{ data: "hemjbmd.id_hemxxmh",visible:false },
					{ data: "hemjbmd.nama" },
					{ data: "hemjbmd.jenis" },
					{ data: "hemjbmd.alamat" },
					{ data: "gctxxmh.nama" },
					{ data: "hemjbmd.tanggal_awal" },
					{ data: "hemjbmd.tanggal_akhir" },
					{ data: "hemjbmd.jabatan_awal" },
					{ data: "hemjbmd.jabatan_akhir" },
					{ data: "hemjbmd.nama_atasan" },
					{ data: "hemjbmd.gaji",
						render: $.fn.dataTable.render.number( ',', '.', 0,'Rp. ','' ),
						class: "text-right" 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemjbmd';
						$table       = 'tblhemjbmd';
						$edt         = 'edthemjbmd';
						$show_status = '_hemjbmd';
						$table_name  = $nama_tabels_d[6];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemjbmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemjbmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemjbmd, 'hemjbmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemjbmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemjbmd = tblhemjbmd.row( { selected: true } ).data().hemjbmd;
				id_hemjbmd   = data_hemjbmd.id;
				id_transaksi_d    = id_hemjbmd; // dipakai untuk general
				is_active_d       = data_hemjbmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemjbmd );
			} );

			tblhemjbmd.on( 'deselect', function() {
				id_hemjbmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemjbmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hemlgmd BAHASA --------------- //

			//start datatables editor
			edthemlgmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemlgmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemlgmd = show_inactive_status_hemlgmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemlgmd",
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
						def: "hemlgmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemlgmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemlgmd.is_active",
                        type: "hidden",
						def: 1
					}, 	
					// {
					// 	label: "Bahasa <sup class='text-danger'>*<sup>",
					// 	name: "hemlgmd.id_hlgxxmh",
					// 	type: "select2",
					// 	opts: {
					// 		placeholder : "Select",
					// 		allowClear: true
					// 	}
					// },	
					{
						label: "Bahasa <sup class='text-danger'>*<sup>",
						name: "hemlgmd.nama",
					},	
					{
						label: "Kemampuan Mendengar	",
						name: "hemlgmd.mendengar",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Sangat Baik", "value": "Sangat Baik" },
							{ "label": "Mahir", "value": "Mahir" },
							{ "label": "Baik", "value": "Baik" },
							{ "label": "Kurang Baik", "value": "Kurang Baik" }
						]
					},	{
						label: "Kemampuan Membaca	",
						name: "hemlgmd.membaca",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Sangat Baik", "value": "Sangat Baik" },
							{ "label": "Mahir", "value": "Mahir" },
							{ "label": "Baik", "value": "Baik" },
							{ "label": "Kurang Baik", "value": "Kurang Baik" }
						]
					},	{
						label: "Kemampuan Menulis	",
						name: "hemlgmd.menulis",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Sangat Baik", "value": "Sangat Baik" },
							{ "label": "Mahir", "value": "Mahir" },
							{ "label": "Baik", "value": "Baik" },
							{ "label": "Kurang Baik", "value": "Kurang Baik" }
						]
					},	{
						label: "Kemampuan Percakapan	",
						name: "hemlgmd.percakapan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Sangat Baik", "value": "Sangat Baik" },
							{ "label": "Mahir", "value": "Mahir" },
							{ "label": "Baik", "value": "Baik" },
							{ "label": "Kurang Baik", "value": "Kurang Baik" }
						]
					}
				]
			} );
			
			edthemlgmd.on( 'preOpen', function( e, mode, action ) {
				edthemlgmd.field('hemlgmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemlgmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemlgmd.rows().deselect();
				}
			});

            edthemlgmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemlgmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemlgmd.nama 
					nama = edthemlgmd.field('hemlgmd.nama').val();
					if(!nama || nama == ''){
						edthemlgmd.field('hemlgmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemlgmd.nama 

				}
				
				if ( edthemlgmd.inError() ) {
					return false;
				}
			});

			edthemlgmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemlgmd.field('finish_on').val(finish_on);
			});
			
			edthemlgmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemlgmd = $('#tblhemlgmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemlgmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemlgmd = show_inactive_status_hemlgmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemlgmd.id",visible:false },
					{ data: "hemlgmd.id_hemxxmh",visible:false },
					{ data: "hemlgmd.nama" },
					{ data: "hemlgmd.membaca" },
					{ data: "hemlgmd.mendengar" },
					{ data: "hemlgmd.menulis" },
					{ data: "hemlgmd.percakapan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemlgmd';
						$table       = 'tblhemlgmd';
						$edt         = 'edthemlgmd';
						$show_status = '_hemlgmd';
						$table_name  = $nama_tabels_d[7];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemlgmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemlgmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemlgmd, 'hemlgmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemlgmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemlgmd = tblhemlgmd.row( { selected: true } ).data().hemlgmd;
				id_hemlgmd   = data_hemlgmd.id;
				id_transaksi_d    = id_hemlgmd; // dipakai untuk general
				is_active_d       = data_hemlgmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemlgmd );
			} );

			tblhemlgmd.on( 'deselect', function() {
				id_hemlgmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemlgmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hemskmd KEMAMPUAN --------------- //

			//start datatables editor
			edthemskmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemskmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemskmd = show_inactive_status_hemskmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemskmd",
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
						def: "hemskmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemskmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemskmd.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Jenis Skill <sup class='text-danger'>*<sup>",
						name: "hemskmd.nama"
					},	{
						label: "Tingkat Kemampuan 	",
						name: "hemskmd.tingkat",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Sangat Baik", "value": "Sangat Baik" },
							{ "label": "Mahir", "value": "Mahir" },
							{ "label": "Baik", "value": "Baik" },
							{ "label": "Kurang Baik", "value": "Kurang Baik" }
						]
					}
				]
			} );
			
			edthemskmd.on( 'preOpen', function( e, mode, action ) {
				edthemskmd.field('hemskmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemskmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemskmd.rows().deselect();
				}
			});

            edthemskmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemskmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hemskmd.nama 
					nama = edthemskmd.field('hemskmd.nama').val();
					if(!nama || nama == ''){
						edthemskmd.field('hemskmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemskmd.nama 

				}
				
				if ( edthemskmd.inError() ) {
					return false;
				}
			});

			edthemskmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemskmd.field('finish_on').val(finish_on);
			});
			
			edthemskmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemskmd = $('#tblhemskmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemskmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemskmd = show_inactive_status_hemskmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemskmd.id",visible:false },
					{ data: "hemskmd.id_hemxxmh",visible:false },
					{ data: "hemskmd.nama" },
					{ data: "hemskmd.tingkat" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemskmd';
						$table       = 'tblhemskmd';
						$edt         = 'edthemskmd';
						$show_status = '_hemskmd';
						$table_name  = $nama_tabels_d[8];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemskmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemskmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemskmd, 'hemskmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemskmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemskmd = tblhemskmd.row( { selected: true } ).data().hemskmd;
				id_hemskmd   = data_hemskmd.id;
				id_transaksi_d    = id_hemskmd; // dipakai untuk general
				is_active_d       = data_hemskmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemskmd );
			} );

			tblhemskmd.on( 'deselect', function() {
				id_hemskmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemskmd );
			} );

// --------- end _detail --------------- //	

			
// --------- start _detail hemtrmd PELATIHAN --------------- //

			//start datatables editor
			edthemtrmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemtrmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemtrmd = show_inactive_status_hemtrmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemtrmd",
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
						def: "hemtrmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemtrmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemtrmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Pelatihan <sup class='text-danger'>*<sup>",
						name: "hemtrmd.nama",
						type: "textarea"
					},	{
						label: "Lembaga <sup class='text-danger'>*<sup>",
						name: "hemtrmd.lembaga"
					},	{
						label: "Bersertifikat",
						name: "hemtrmd.bersertifikat",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Ya", "value": "Ya" },
							{ "label": "Tidak", "value": "Tidak" }
						]
					}, {
						label: "Tanggal Mulai",
						name: "hemtrmd.tanggal_mulai",
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
						name: "hemtrmd.tanggal_selesai",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}
				]
			} );
			
			edthemtrmd.on( 'preOpen', function( e, mode, action ) {
				edthemtrmd.field('hemtrmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemtrmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemtrmd.rows().deselect();
				}
			});

            edthemtrmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemtrmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemtrmd.nama 
					nama = edthemtrmd.field('hemtrmd.nama').val();
					if(!nama || nama == ''){
						edthemtrmd.field('hemtrmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemtrmd.nama 

					// BEGIN of validasi hemtrmd.lembaga 
					lembaga = edthemtrmd.field('hemtrmd.lembaga').val();
					if(!lembaga || lembaga == ''){
						edthemtrmd.field('hemtrmd.lembaga').error( 'Wajib diisi!' );
					}
					// END of validasi hemtrmd.lembaga 

				}
				
				if ( edthemtrmd.inError() ) {
					return false;
				}
			});

			edthemtrmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemtrmd.field('finish_on').val(finish_on);
			});
			
			edthemtrmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemtrmd = $('#tblhemtrmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemtrmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemtrmd = show_inactive_status_hemtrmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemtrmd.id",visible:false },
					{ data: "hemtrmd.id_hemxxmh",visible:false },
					{ data: "hemtrmd.nama" },
					{ data: "hemtrmd.lembaga" },
					{ data: "hemtrmd.bersertifikat" },
					{ data: "hemtrmd.tanggal_mulai" },
					{ data: "hemtrmd.tanggal_selesai" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemtrmd';
						$table       = 'tblhemtrmd';
						$edt         = 'edthemtrmd';
						$show_status = '_hemtrmd';
						$table_name  = $nama_tabels_d[9];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'view','nonaktif_h','approve'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemtrmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemtrmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemtrmd, 'hemtrmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemtrmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemtrmd = tblhemtrmd.row( { selected: true } ).data().hemtrmd;
				id_hemtrmd   = data_hemtrmd.id;
				id_transaksi_d    = id_hemtrmd; // dipakai untuk general
				is_active_d       = data_hemtrmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemtrmd );
			} );

			tblhemtrmd.on( 'deselect', function() {
				id_hemtrmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemtrmd );
			} );

// --------- end _detail --------------- //		


// --------- start _detail hemogmd ORGANISASI --------------- //

			//start datatables editor
			edthemogmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemogmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemogmd = show_inactive_status_hemogmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemogmd",
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
						def: "hemogmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemogmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemogmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Organisasi <sup class='text-danger'>*<sup>",
						name: "hemogmd.nama"
					},	{
						label: "Jenis Organisasi",
						name: "hemogmd.jenis"
					},	{
						label: "Tahun",
						name: "hemogmd.tahun"
					},	{
						label: "Jabatan",
						name: "hemogmd.jabatan"
					}
				]
			} );
			
			edthemogmd.on( 'preOpen', function( e, mode, action ) {
				edthemogmd.field('hemogmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemogmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemogmd.rows().deselect();
				}
			});

            edthemogmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemogmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemogmd.nama 
					nama = edthemogmd.field('hemogmd.nama').val();
					if(!nama || nama == ''){
						edthemogmd.field('hemogmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemogmd.nama 

				}
				
				if ( edthemogmd.inError() ) {
					return false;
				}
			});

			edthemogmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemogmd.field('finish_on').val(finish_on);
			});
			
			edthemogmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemogmd = $('#tblhemogmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemogmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemogmd = show_inactive_status_hemogmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemogmd.id",visible:false },
					{ data: "hemogmd.id_hemxxmh",visible:false },
					{ data: "hemogmd.nama" },
					{ data: "hemogmd.jenis" },
					{ data: "hemogmd.tahun" },
					{ data: "hemogmd.jabatan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemogmd';
						$table       = 'tblhemogmd';
						$edt         = 'edthemogmd';
						$show_status = '_hemogmd';
						$table_name  = $nama_tabels_d[10];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemogmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemogmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemogmd, 'hemogmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemogmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemogmd = tblhemogmd.row( { selected: true } ).data().hemogmd;
				id_hemogmd   = data_hemogmd.id;
				id_transaksi_d    = id_hemogmd; // dipakai untuk general
				is_active_d       = data_hemogmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemogmd );
			} );

			tblhemogmd.on( 'deselect', function() {
				id_hemogmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemogmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hemdhmd Penyakit --------------- //

			//start datatables editor
			edthemdhmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemdhmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemdhmd = show_inactive_status_hemdhmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemdhmd",
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
						def: "hemdhmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemdhmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemdhmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Jenis Penyakit <sup class='text-danger'>*<sup>",
						name: "hemdhmd.nama"
					},	{
						label: "Tahun",
						name: "hemdhmd.tahun"
					},	{
						label: "Berapa Lama",
						name: "hemdhmd.lama"
					},	{
						label: "Dirawat Di",
						name: "hemdhmd.dirawat_di"
					}
				]
			} );
			
			edthemdhmd.on( 'preOpen', function( e, mode, action ) {
				edthemdhmd.field('hemdhmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemdhmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemdhmd.rows().deselect();
				}
			});

            edthemdhmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemdhmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemdhmd.nama 
					nama = edthemdhmd.field('hemdhmd.nama').val();
					if(!nama || nama == ''){
						edthemdhmd.field('hemdhmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemdhmd.nama 

				}
				
				if ( edthemdhmd.inError() ) {
					return false;
				}
			});

			edthemdhmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemdhmd.field('finish_on').val(finish_on);
			});
			
			edthemdhmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemdhmd = $('#tblhemdhmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemdhmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemdhmd = show_inactive_status_hemdhmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemdhmd.id",visible:false },
					{ data: "hemdhmd.id_hemxxmh",visible:false },
					{ data: "hemdhmd.nama" },
					{ data: "hemdhmd.tahun" },
					{ data: "hemdhmd.lama" },
					{ data: "hemdhmd.dirawat_di" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemdhmd';
						$table       = 'tblhemdhmd';
						$edt         = 'edthemdhmd';
						$show_status = '_hemdhmd';
						$table_name  = $nama_tabels_d[11];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemdhmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemdhmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemdhmd, 'hemdhmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemdhmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemdhmd = tblhemdhmd.row( { selected: true } ).data().hemdhmd;
				id_hemdhmd   = data_hemdhmd.id;
				id_transaksi_d    = id_hemdhmd; // dipakai untuk general
				is_active_d       = data_hemdhmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemdhmd );
			} );

			tblhemdhmd.on( 'deselect', function() {
				id_hemdhmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemdhmd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail hemecmd Kontak Darurat --------------- //

			//start datatables editor
			edthemecmd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hemxxmh/hemecmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemecmd = show_inactive_status_hemecmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				table: "#tblhemecmd",
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
						def: "hemecmd",
						type: "hidden"
					},	{
						label: "id_hemxxmh",
						name: "hemecmd.id_hemxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hemecmd.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Nama Kontak Darurat <sup class='text-danger'>*<sup>",
						name: "hemecmd.nama"
					},	{
						label: "Alamat",
						name: "hemecmd.alamat"
					},	{
						label: "No HP <sup class='text-danger'>*<sup>",
						name: "hemecmd.no_hp"
					},	{
						label: "Hubungan",
						name: "hemecmd.hubungan",
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
			
			edthemecmd.on( 'preOpen', function( e, mode, action ) {
				edthemecmd.field('hemecmd.id_hemxxmh').val(id_hemxxmh);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemecmd.field('start_on').val(start_on);

				if(action == 'create'){
					tblhemecmd.rows().deselect();
				}
			});

            edthemecmd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthemecmd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi hemecmd.nama 
					nama = edthemecmd.field('hemecmd.nama').val();
					if(!nama || nama == ''){
						edthemecmd.field('hemecmd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi hemecmd.nama 

					//  validasi hemecmd.no_hp
					no_hp = edthemecmd.field('hemecmd.no_hp').val();
					if(!no_hp || no_hp == ''){
						edthemecmd.field('hemecmd.no_hp').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(no_hp <= 0 ){
						edthemecmd.field('hemecmd.no_hp').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(no_hp) ){
						edthemecmd.field('hemecmd.no_hp').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edthemecmd.inError() ) {
					return false;
				}
			});

			edthemecmd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthemecmd.field('finish_on').val(finish_on);
			});
			
			edthemecmd.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhemecmd = $('#tblhemecmd').DataTable( {
				ajax: {
					url: "../../models/hemxxmh/hemecmd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hemecmd = show_inactive_status_hemecmd;
						d.id_hemxxmh = id_hemxxmh;
					}
				},
				order: [[ 2, "desc" ]],
				columns: [
					{ data: "hemecmd.id",visible:false },
					{ data: "hemecmd.id_hemxxmh",visible:false },
					{ data: "hemecmd.nama" },
					{ data: "hemecmd.alamat" },
					{ data: "hemecmd.no_hp" },
					{ data: "hemecmd.hubungan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hemecmd';
						$table       = 'tblhemecmd';
						$edt         = 'edthemecmd';
						$show_status = '_hemecmd';
						$table_name  = $nama_tabels_d[12];

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_d'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hemecmd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

			tblhemecmd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhemxxmh, tblhemecmd, 'hemecmd' );
				CekDrawDetailHDFinal(tblhemxxmh);
			} );

			tblhemecmd.on( 'select', function( e, dt, type, indexes ) {
				data_hemecmd = tblhemecmd.row( { selected: true } ).data().hemecmd;
				id_hemecmd   = data_hemecmd.id;
				id_transaksi_d    = id_hemecmd; // dipakai untuk general
				is_active_d       = data_hemecmd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhemxxmh, tblhemecmd );
			} );

			tblhemecmd.on( 'deselect', function() {
				id_hemecmd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhemxxmh, tblhemecmd );
			} );

// --------- end _detail --------------- //		
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
