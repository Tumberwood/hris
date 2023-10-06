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
    $nama_tabels_d[1] = 'hpyemtd_kbm';
    $nama_tabels_d[2] = 'hpyemtd_karyawan';
    $nama_tabels_d[3] = 'hpyemtd_kmj';
    $nama_tabels_d[4] = 'hpyemtd_freelance';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tblhpyxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
								<th>ID</th>
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
							<li><a class="nav-link" data-toggle="tab" href="#tabhpyemtd_kbm"> KBM</a></li>
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
													<th>Bonus Lain-lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Rp) </th>
													<th>Pot Makan</th>
													<th>Pot JKK JKM</th>
													<th>Pot JHT</th>
													<th>Pot Upah</th>
													<th>Pot BPJS</th>
													<th>Pot Pensiun</th>
													<th>Pot Hutang</th>
													<th>Pot Lain-lain</th>
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
													<th>Total</th>
													<th></th>
													<th id="all_9"></th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							<div role="tabpanel" id="tabhpyemtd_kbm" class="tab-pane">
								<div class="panel-body">
									<div class="table-responsive">
										<table id="tblhpyemtd_kbm" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<th>ID</th>
													<th>id_hpyxxth</th>
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
													<th>Bonus Lain-lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Rp) </th>
													<th>Pot Makan</th>
													<th>Pot JKK JKM</th>
													<th>Pot JHT</th>
													<th>Pot Upah</th>
													<th>Pot BPJS</th>
													<th>Pot Pensiun</th>
													<th>Pot Hutang</th>
													<th>Pot Lain-lain</th>
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
													<th>Total</th>
													<th></th>
													<th id="kbm_9"></th>
													<th id="kbm_10"></th>
													<th id="kbm_11"></th>
													<th id="kbm_12"></th>
													<th id="kbm_13"></th>
													<th id="kbm_14"></th>
													<th id="kbm_15"></th>
													<th id="kbm_16"></th>
													<th id="kbm_17"></th>
													<th id="kbm_18"></th>
													<th id="kbm_19"></th>
													<th id="kbm_20"></th>
													<th id="kbm_21"></th>
													<th id="kbm_22"></th>
													<th id="kbm_23"></th>
													<th id="kbm_24"></th>
													<th id="kbm_25"></th>
													<th id="kbm_26"></th>
													<th id="kbm_27"></th>
													<th id="kbm_28"></th>
													<th id="kbm_29"></th>
													<th id="kbm_30"></th>
													<th id="kbm_31"></th>
													<th id="kbm_32"></th>
													<th id="kbm_33"></th>
													<th id="kbm_34"></th>
													<th id="kbm_35"></th>
													<th id="kbm_36"></th>
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
													<th>Bonus Lain-lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Rp) </th>
													<th>Pot Makan</th>
													<th>Pot JKK JKM</th>
													<th>Pot JHT</th>
													<th>Pot Upah</th>
													<th>Pot BPJS</th>
													<th>Pot Pensiun</th>
													<th>Pot Hutang</th>
													<th>Pot Lain-lain</th>
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
													<th>Total</th>
													<th></th>
													<th id="karyawan_9"></th>
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
													<th>Bonus Lain-lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Rp) </th>
													<th>Pot Makan</th>
													<th>Pot JKK JKM</th>
													<th>Pot JHT</th>
													<th>Pot Upah</th>
													<th>Pot BPJS</th>
													<th>Pot Pensiun</th>
													<th>Pot Hutang</th>
													<th>Pot Lain-lain</th>
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
													<th>Total</th>
													<th></th>
													<th id="kmj_9"></th>
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
													<th>Bonus Lain-lain</th>
													<th>Lembur 1,5</th>
													<th>Rp Lembur 1,5</th>
													<th>Lembur 2</th>
													<th>Rp Lembur 2</th>
													<th>Lembur 3</th>
													<th>Rp Lembur 3</th>
													<th>Total Lembur (Jam)</th>
													<th>Total Lembur (Rp) </th>
													<th>Pot Makan</th>
													<th>Pot JKK JKM</th>
													<th>Pot JHT</th>
													<th>Pot Upah</th>
													<th>Pot BPJS</th>
													<th>Pot Pensiun</th>
													<th>Pot Hutang</th>
													<th>Pot Lain-lain</th>
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
													<th>Total</th>
													<th></th>
													<th id="freelance_9"></th>
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
        var edthpyemtd_kbm, tblhpyemtd_kbm, show_inactive_status_hpyemtd = 0, id_hpyemtd;
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
				order: [[ 1, "desc" ],[2, "asc"]],
				columns: [
					{ data: "hpyxxth.id",visible:false },
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
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance];
				CekInitHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
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
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance];
				CekSelectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).enable();
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
				tbl_details = [tblhpyemtd, tblhpyemtd_kbm, tblhpyemtd_karyawan, tblhpyemtd_kmj, tblhpyemtd_freelance];
				CekDeselectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
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
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
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
						data: "hpyemtd.bonus_lain",
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
						data: "hpyemtd.lemburbersih",
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
						data: "hpyemtd.pot_utang",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_lain",
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
					// END breaking generate button
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 9; i <= 36; i++) {
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
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd );
			} );

			tblhpyemtd.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd );
			} );

// --------- end _detail --------------- //		
			
// --------- start _detail --------------- //

			//start datatables editor
			edthpyemtd_kbm = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				table: "#tblhpyemtd_kbm",
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
			
			edthpyemtd_kbm.on( 'preOpen', function( e, mode, action ) {
				edthpyemtd_kbm.field('hpyemtd.id_hpyxxth').val(id_hpyxxth);
				
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhpyemtd_kbm.rows().deselect();
				}
			});

            edthpyemtd_kbm.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edthpyemtd_kbm.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
				}
				
				if ( edthpyemtd_kbm.inError() ) {
					return false;
				}
			});

			edthpyemtd_kbm.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthpyemtd_kbm.field('finish_on').val(finish_on);
			});

			
			edthpyemtd_kbm.on( 'postSubmit', function (e, json, data, action, xhr) {
				// event setelah Create atau Edit, dibedakan dari parameter action
				// action : "create" | "edit"
				// do something
			} );
			
			//start datatables
			tblhpyemtd_kbm = $('#tblhpyemtd_kbm').DataTable( {
				ajax: {
					url: "../../models/hpyxxth/hpyemtd_kbm.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					}
				},
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
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
						data: "hpyemtd.bonus_lain",
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
						data: "hpyemtd.lemburbersih",
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
						data: "hpyemtd.pot_utang",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_lain",
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
						$table       = 'tblhpyemtd_kbm';
						$edt         = 'edthpyemtd_kbm';
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

					for (var i = 9; i <= 36; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#kbm_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
				}
			} );

			tblhpyemtd_kbm.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tblhpyxxth, tblhpyemtd_kbm, 'hpyemtd' );
				CekDrawDetailHDFinal(tblhpyxxth);
			} );

			tblhpyemtd_kbm.on( 'select', function( e, dt, type, indexes ) {
				data_hpyemtd = tblhpyemtd_kbm.row( { selected: true } ).data().hpyemtd;
				id_hpyemtd   = data_hpyemtd.id;
				id_transaksi_d    = id_hpyemtd; // dipakai untuk general
				is_active_d       = data_hpyemtd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tblhpyxxth, tblhpyemtd_kbm );
			} );

			tblhpyemtd_kbm.on( 'deselect', function() {
				id_hpyemtd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tblhpyxxth, tblhpyemtd_kbm );
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
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
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
						data: "hpyemtd.bonus_lain",
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
						data: "hpyemtd.lemburbersih",
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
						data: "hpyemtd.pot_utang",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_lain",
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
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 9; i <= 36; i++) {
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
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
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
						data: "hpyemtd.bonus_lain",
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
						data: "hpyemtd.lemburbersih",
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
						data: "hpyemtd.pot_utang",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_lain",
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
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 9; i <= 36; i++) {
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
				order: [[ 2, "desc" ]],
				responsive: false,
				// scrollX: true,
				columns: [
					{ data: "hpyemtd.id",visible:false },
					{ data: "hpyemtd.id_hpyxxth",visible:false },
					{ data: "hemxxmh_data" },
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
						data: "hpyemtd.bonus_lain",
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
						data: "hpyemtd.lemburbersih",
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
						data: "hpyemtd.pot_utang",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right "
					},
					{
						data: "hpyemtd.pot_lain",
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
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 9; i <= 36; i++) {
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
