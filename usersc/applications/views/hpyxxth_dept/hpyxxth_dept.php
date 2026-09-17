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

	table.dataTable thead th.satu {
		background: #c3d7e6 !important;
		/* color: #155724 !important; */
	}

	table.dataTable thead th.dua {
		background: #cce6c3 !important;
		/* color: #155724 !important; */
	}
</style>
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
							
							<li><a class="nav-link" data-toggle="tab" href="#tabpentunjuk"> Petunjuk</a></li>
						</ul>
						<div class="tab-content">
							<div role="tabpanel" id="tabhpyemtd_karyawan" class="tab-pane active">
								<div class="panel-body">
									<div class="table-responsive">
										<h3 class="keterangan_payroll"></h3>
										<table id="tblhpyemtd_karyawan" class="table table-striped table-bordered table-hover nowrap" width="100%">
											<thead>
												<tr>
													<!-- TAMBAHAN -->
													<th class="text-center align-middle">ID</th>
													<th class="text-center align-middle">Department</th>

													<!-- DATA GAJI -->
													<th class="text-center align-middle">Gaji Pokok</th>
													<th class="text-center align-middle">Tj. Jabatan</th>
													<th class="text-center align-middle">Terima Lain</th>
													<th class="text-center align-middle">Tj. Lain-lain</th>
													<th class="text-center align-middle">Tj. Khusus</th>
													<th class="text-center align-middle">Tj. Masa Kerja</th>
													<th class="text-center align-middle">Premi Absensi</th>
													<th class="text-center align-middle">Lembur x1.5 (jam)</th>
													<th class="text-center align-middle">Lembur x1.5 (rp)</th>
													<th class="text-center align-middle">Lembur x2 (jam)</th>
													<th class="text-center align-middle">Lembur x2 (rp)</th>
													<th class="text-center align-middle">Lembur x3 (jam)</th>
													<th class="text-center align-middle">Lembur x3 (rp)</th>
													<th class="text-center align-middle">Lembur Total (jam)</th>
													<th class="text-center align-middle">Lembur Total (rp)</th>
													<th class="text-center align-middle">Kompensasi Kontrak Berakhir</th>
													<th class="text-center align-middle">Cuti Tahunan</th>
													<th class="text-center align-middle">Cuti Bersama (Cuti Masal)</th>
													<th class="text-center align-middle">Hari Sisa Cuti</th>
													<th class="text-center align-middle">Kompensasi Sisa Cuti</th>
													<th class="text-center align-middle">THR</th>

													<!-- POTONGAN -->
													<th class="text-center align-middle text-danger">Potongan makan</th>
													<th class="text-center align-middle text-danger">Total Pot Upah</th>
													<th class="text-center align-middle text-danger">Potongan Upah (Rp)</th>
													<th class="text-center align-middle text-danger">Total Pot Resign</th>
													<th class="text-center align-middle text-danger">Potongan Resign (Rp)</th>
													<th class="text-center align-middle text-danger">Total Pot Jam</th>
													<th class="text-center align-middle text-danger">Potongan Jam (Rp)</th>

													<th class="text-center align-middle">Penghasilan Sebelum PPh 21</th>
													<th class="text-center align-middle text-danger">Potongan Sebelum PPh 21</th>

													<th class="text-center align-middle">BPJS Kes Perusahaan</th>
													<th class="text-center align-middle">BPJS JKK Perusahaan</th>
													<th class="text-center align-middle">BPJS JKM Perusahaan</th>
													<th class="text-center align-middle">Penghasilan Bruto</th>
													<th class="text-center align-middle">Tarif TER (%)</th>

													<th class="text-center align-middle text-danger">Potongan PPh 21 (TER/Tahunan)</th>

													<th class="text-center align-middle">Penghasilan Setelah PPh 21</th>
													<th class="text-center align-middle">BPJS JHT Perusahaan</th>
													<th class="text-center align-middle">BPJS JP Perusahaan</th>

													<th class="text-center align-middle text-danger">BPJS JHT Karyawan (pot_jht)</th>
													<th class="text-center align-middle text-danger">BPJS JP Karyawan (pot_psiun)</th>
													<th class="text-center align-middle text-danger">BPJS Kes Karyawan (pot_bpjs)</th>
													
													<!-- Potongan JKK JKM BPJS KES -->
													<th class="text-center align-middle text-danger">BPJS Kes Perusahaan</th>
													<th class="text-center align-middle text-danger">Potongan BPJS JKK Perusahaan</th>
													<th class="text-center align-middle text-danger">Potongan BPJS JKM Perusahaan</th>

													<th class="text-center align-middle text-danger">Piutang Karyawan</th>
													<th class="text-center align-middle text-danger">Pot Denda APD</th>
													<th class="text-center align-middle text-danger">Iuran SPSI (potongan)</th>

													<th class="text-center align-middle">Pendapatan Setelah PPh 21</th>
													<th class="text-center align-middle text-danger">Potongan Setelah PPh 21</th>

													<th class="text-center align-middle">Gaji Bersih</th>
													<th class="text-center align-middle">Bulat</th>
													<th class="text-center align-middle">Gaji Diterima</th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th></th>
													<th></th>
													<th id="karyawan_2"></th>
													<th id="karyawan_3"></th>
													<th id="karyawan_4"></th>
													<th id="karyawan_5"></th>
													<th id="karyawan_6"></th>
													<th id="karyawan_7"></th>
													<th id="karyawan_8"></th>
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
												</tr>
											</tfoot>
										</table>
									</div> <!-- end of table -->
								</div>
							</div>
							
							<div role="tabpanel" id="tabpentunjuk" class="tab-pane">
								<div class="panel-body">
									<div class="alert alert-info">
										<b>📌 Petunjuk Rumus Payroll:</b><br>
										<b>Terima Lain (Baru)</b> = Premi Absensi (Baru) - Lembur Total (rp) (Lama) + Lembur Total (rp) (Baru) + Potongan Upah (Rp) (Lama) - Potongan Jam (Rp) (Baru) - Potongan Sebelum PPh 21 (Baru)
										<br><br>
										<small>* Catatan 1: jika Premi Absensi (Lama) - Premi Absensi (Baru) = 0, maka Premi Absensi (Baru) tidak ditambahkan.</small><br>
										<small>* Catatan 2: jika Potongan Upah (Rp) (Lama) - Potongan Upah (Rp) (Baru) &lt; 2, maka Potongan Upah (Rp) (Lama) tidak ditambahkan.</small>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hpyxxth_dept/fn/hpyxxth_dept_fn.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthpyxxth, tblhpyxxth, show_inactive_status_hpyxxth = 0, id_hpyxxth;
        var edthpyemtd_kbm_reg, tblhpyemtd_karyawan, show_inactive_status_hpyemtd = 0, id_hpyemtd;
		// ------------- end of default variable
		var id_heyxxmh_old = 0, id_periode_payroll_old = 0;
		var notifyprogress = '';
		
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
					url: "../../models/hpyxxth_dept/hpyxxth_dept.php",
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
					url: "../../models/hpyxxth_dept/hpyxxth_dept.php",
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
				tbl_details = [tblhpyemtd_karyawan];
				CekInitHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();
				

				tblhpyemtd_karyawan.button('btnExcelBerwarna:name').disable();
				
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
				keterangan_header        = data_hpyxxth.keterangan;

				id_heyxxmh_old = data_hpyxxth.id_heyxxmh;
				id_periode_payroll_old = data_hpyxxth.id_periode_payroll;
				
				notifyLoadingKucing();
				$('.keterangan_payroll').text(keterangan_header);

				// atur hak akses
				tbl_details = [tblhpyemtd_karyawan];
				CekSelectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).enable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).enable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).enable();
				
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
				tblhpyemtd_karyawan.button('btnExcelBerwarna:name').enable();
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
				tbl_details = [tblhpyemtd_karyawan];
				CekDeselectHeaderHD(tblhpyxxth, tbl_details);
				tblhpyxxth.button( 'btnGeneratePresensi:name' ).disable();
				tblhpyxxth.button( 'btnGeneratePresensiNew:name' ).disable();
				tblhpyxxth.button( 'btnGenPPh21:name' ).disable();

				tblhpyxxth.button('btnUpload:name').disable();
				tblhpyemtd_karyawan.button('btnExcelBerwarna:name').disable();
			} );

				
// --------- start _detail --------------- //

			//start datatables
			tblhpyemtd_karyawan = $('#tblhpyemtd_karyawan').DataTable( {
				ajax: {
					url: "../../models/hpyxxth_dept/hpyemtd_karyawan.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hpyemtd = show_inactive_status_hpyemtd;
						d.id_hpyxxth = id_hpyxxth;
					},
					dataSrc: 'data.htsprrd'
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				// scrollX: true,
				fixedColumns:   {
					left: 2 //buat hanya NIK dan nama aja yg di freeze
				},
				columns: [
					{ data: "id", visible:false },
					{ data: "dept" },

					// GAJI
					{ data: "gp", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "t_jab", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "terima_lain", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "var_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "tj_khusus", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "fix_cost", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "premi_abs", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "lembur15", class: "text-right" },
					{ data: "rp_lembur15", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "lembur2", class: "text-right" },
					{ data: "rp_lembur2", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "lembur3", class: "text-right" },
					{ data: "rp_lembur3", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "total_lembur_jam_final", class: "text-right" },
					{ data: "total_rp_lembur", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "komp_rekontrak", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "cuti_tahunan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "cuti_bersama", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "sisa_cuti_hari", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "komp_sisa_cuti", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "thr", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					// POTONGAN
					{ data: "pot_makan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "c_pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "pot_upah", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					
					{ data: "c_pot_resign", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "pot_resign", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "c_pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "pot_jam", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "pendapatan_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "pot_lain_before_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "bruto", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "persen_ter", render: $.fn.dataTable.render.number(',', '.', 2), class: "text-right" },

					{ data: "pot_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "after_pph21", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "jht_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "jp_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "pot_jht_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "pot_jp_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "bpjs_kes_karyawan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					
					{ data: "bpjs_kes_perusahaan", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "jkk", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "jkm", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },

					{ data: "pot_piutang", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "denda_apd", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },
					{ data: "iuran_spsi", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "pendapatan_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "pot_lain_after_pph", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right text-danger" },

					{ data: "gaji_bersih", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "bulat", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" },
					{ data: "gaji_terima", render: $.fn.dataTable.render.number(',', '.', 0), class: "text-right" }
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

					// END breaking generate button
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api = this.api();
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 

					for (var i = 1; i <= 54; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#karyawan_' + columnIndex).html(numFormat(sum_all));

						// console.log('Number of Pages: ' + api.page.info().pages);
					}
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
				if(notifyprogress != ''){
					notifyprogress.close();
				}
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
	

		} );// end of document.ready
	
	</script>


<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
