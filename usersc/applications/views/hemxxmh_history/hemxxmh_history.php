<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hemxxmh_history';
	$nama_tabels_d = [];
	
	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= $_GET['id_hemxxmh'];
	} else {
		$id_hemxxmh		= 0;
	}
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
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
                <form class="form-horizontal" id="frmistirahat_tidak_sah">
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
				<div class="table-responsive">
                    <table id="tblistirahat_tidak_sah" class="table table-striped table-bordered table-hover nowrap" width="100%">
						<thead>
							<tr>
								<th>Kode</th>
								<th>No KTP</th>
								<th>Nama</th>
								<th>Foto</th>
								<th>Department</th>
								<th>Bagian</th>
								<th>Jabatan</th>
								<th>Alamat Sesuai KTP</th>
								<th>Desa / Dusun KTP</th>
								<th>Kecamatan KTP</th>
								<th>Kab/Kota KTP</th>
								<th>Tempat Lahir</th>
								<th>Tanggal Lahir</th>
								<th>Usia (tahun)</th>
								<th>Alamat Domisili</th>
								<th>Desa / Dusun Domisili</th>
								<th>Kabupaten / Kota Domisili</th>
								<th>Area Kerja</th>
								<th>Tipe</th>
								<th>Sub Tipe</th>
								<th>Status</th>
								<th>Tanggal Bekerja</th>
								<th>Grup HK</th>
								<th>Gender</th>
								<th>Tanggal Akhir Kontrak</th>
								<th>Tanggal Keluar</th>
							</tr>
						</thead>
                    </table>
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

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var tblistirahat_tidak_sah, show_inactive_status_istirahat_tidak_sah = 0;
		var id_hemxxmh = 0;
		var id_hemxxmh_old = 0;
		var id_hem_get = <?php echo $id_hemxxmh ?>;
		var tanggal_get = "<?php echo $awal ?>";

		// console.log(id_hem_get);
		// console.log(tanggal_get);
		// ------------- end of default variable

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		
		if (tanggal_get === '') {
			$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
			$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
		} else {
			$('#start_date').datepicker('setDate', new Date(tanggal_get));
			$('#end_date').datepicker('setDate', new Date(tanggal_get));
		}
        // END datepicker init

		//Select2 init
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
					if (id_hem_get > 0) {
						var options = data.results.map(function (result) {
							return {
								id: result.id,
								text: result.text
							};
						});

						//add by ferry agar auto select 07 sep 23
						if (params.page && params.page === 1) {
							$('#select_hemxxmh').empty().select2({ data: options });
						} else {
							$('#select_hemxxmh').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
						}

						return {
							results: options,
							pagination: {
								more: true
							}
						};
					} else {
						return {
							results: data.results,
							pagination: {
								more: true
							}
						};
					}
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
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');
			
			id_hemxxmh_old = id_hem_get;
			
			$('#select_hemxxmh').select2('open');

			setTimeout(function() {
				$('#select_hemxxmh').select2('close');
			}, 5);

			//start datatables
			tblistirahat_tidak_sah = $('#tblistirahat_tidak_sah').DataTable( {
				searchPanes:{
					layout: 'columns-3',
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
						// targets: [1,14,3,4,5,6]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				],
				ajax: {
					url: "../../models/hemxxmh_history/hemxxmh_history.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.id_hemxxmh = id_hemxxmh;
					},
					dataSrc: 'data.htsprrd'
				},
				order: [[ 1, "asc" ]],
				responsive: false,
				columns: [
					{ data: "nrp" },
					{ data: "ktp_no" },
					{ data: "nama" },
					{ 
						data: "web_path",
						render: function (data, type, row) {
							if(data){
								return '<a href="'+row.web_path+'" target="_blank">'+ row.filename + '</a>';
							}else{
								return '';
							}
						}
					},
					{ data: "departemen" },
					{ data: "bagian" },
					{ data: "jabatan" },
					{ data: "alamat_ktp" },
					{ data: "ktp_desa" },
					{ data: "ktp_kecamatan" },
					{ data: "kota_ktp" },

					{ data: "kota_lahir" },
					{ data: "tanggal_lahir" },
					{ data: "umur" },

					{ data: "alamat" },
					{ data: "domisili_desa" },
					{ data: "kota_domisili" },

					{ data: "area_kerja" },
					{ data: "tipe" },
					{ data: "sub_tipe" },
					{ data: "status" },
					{ data: "tanggal_bekerja" },
					{ data: "grup_hk" },
					{ data: "gender" },
					{ data: "tanggal_akhir_kontrak" },
					{ data: "tanggal_keluar" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_prcittd';
						$table       = 'tblprcittd';
						$edt         = 'edtprcittd';
						$show_status = '_prcittd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools = ['copy','excel','colvis'];
						$arr_buttons_action = [];
						$arr_buttons_approve = [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				},
			} );

			tblistirahat_tidak_sah.searchPanes.container().appendTo( '#searchPanes1' );

			$("#frmistirahat_tidak_sah").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
					
				},
				submitHandler: function(frmistirahat_tidak_sah) {
					start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
					end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
					if ($('#select_hemxxmh').val() > 0) {
						id_hemxxmh = $('#select_hemxxmh').val();
					} else {
						if (id_hem_get != 0) {
							id_hemxxmh = id_hem_get;
						} else {
							id_hemxxmh = $('#select_hemxxmh').val();
						}
					}

					notifyprogress = $.notify({
						message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					},{
						z_index: 9999,
						allow_dismiss: false,
						type: 'info',
						delay: 0
					});

					tblistirahat_tidak_sah.rows().deselect();
					tblistirahat_tidak_sah.ajax.reload(function ( json ) {
						notifyprogress.close();
					}, false);
					return false; 
				}
			});
			
			if (id_hem_get > 0) {
				$("#frmistirahat_tidak_sah").submit();
			}
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
