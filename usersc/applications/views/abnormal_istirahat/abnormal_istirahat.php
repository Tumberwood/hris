<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'abnormal_istirahat';
	$nama_tabels_d = [];
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
                <form class="form-horizontal" id="frmFilter">
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
                    <table id="tblabnormal_istirahat" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
							<tr>
								<th>NO</th>
								<th>Tanggal</th>
								<th>NIK</th>
								<th>Nama</th>
                                <th>Department</th>
                                <th>Unit Kerja</th>
                                <th>Grup Jabatan</th>
                                <th>Jabatan</th>
                                <th>Area Kerja</th>
                                <th>Tipe</th>
                                <th>Sub Tipe</th>
                                <th>Status</th>
                                <th>Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/abnormal_istirahat/fn/abnormal_istirahat_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtabnormal_istirahat, tblabnormal_istirahat, show_inactive_status_abnormal_istirahat = 0, id_abnormal_istirahat;
		// ------------- end of default variable
		
		is_need_approval = 1;
		// is_need_generate_kode = 1;

		var id_hemxxmh_old = 0, id_htpxxmh_old = 0;
		var jenis_jam;
		
		id_heyxxmh = "<?php echo $_SESSION['str_arr_ha_heyxxmh']; ?>";
		console.log(id_heyxxmh);

		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "dd M yyyy",
			minViewMode: 'month' 
		});
		$('#start_date').datepicker('setDate', awal_bulan_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
        // END datepicker init
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

			//start datatables editor
			edtabnormal_istirahat = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/abnormal_istirahat/abnormal_istirahat.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_abnormal_istirahat = show_inactive_status_abnormal_istirahat;
					}
				},
				table: "#tblabnormal_istirahat",
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
						def: "abnormal_istirahat",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "abnormal_istirahat.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "abnormal_istirahat.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},	{
						label: "Employee <sup class='text-danger'>*<sup>",
						name: "abnormal_istirahat.id_hemxxmh",
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
					}, 	
					{
						label: "Keterangan",
						name: "abnormal_istirahat.keterangan",
						type: "textarea"
					},
				]
			} );

			edtabnormal_istirahat.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtabnormal_istirahat.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblabnormal_istirahat.rows().deselect();
				}
			});

			edtabnormal_istirahat.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtabnormal_istirahat.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi abnormal_istirahat.tanggal
					if ( ! edtabnormal_istirahat.field('abnormal_istirahat.tanggal').isMultiValue() ) {
						tanggal = edtabnormal_istirahat.field('abnormal_istirahat.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtabnormal_istirahat.field('abnormal_istirahat.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi abnormal_istirahat.tanggal
					
					// BEGIN of validasi abnormal_istirahat.id_hemxxmh
					if ( ! edtabnormal_istirahat.field('abnormal_istirahat.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edtabnormal_istirahat.field('abnormal_istirahat.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edtabnormal_istirahat.field('abnormal_istirahat.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi abnormal_istirahat.id_hemxxmh

					
					// BEGIN of cek unik kombinasi abnormal_istirahat.id_hemxxmh dan abnormal_istirahat.tanggal 
					tanggal_ymd = moment(edtabnormal_istirahat.field('abnormal_istirahat.tanggal').val()).format('YYYY-MM-DD');
					if(action == 'create'){
						id_abnormal_istirahat = 0;
					}
					
					$.ajax( {
						url: '../../../helpers/validate_fn_unique.php',
						dataType: 'json',
						type: 'POST',
						async: false,
						data: {
							table_name: 'abnormal_istirahat',
							nama_field: 'tanggal, id_hemxxmh',
							nama_field_value: '"'+tanggal_ymd+'",'+ id_hemxxmh,
							id_transaksi: id_abnormal_istirahat
						},
						success: function ( json ) {
							if(json.data.count >= 1){
								edtabnormal_istirahat.field('abnormal_istirahat.tanggal').error( 'Data Pegawai pada Tanggal tersebut sudah ada!' );
								edtabnormal_istirahat.field('abnormal_istirahat.id_hemxxmh').error( 'Data Pegawai pada Tanggal tersebut sudah ada!' );
							}
						}
					} );
					// BEGIN of cek unik kombinasi abnormal_istirahat.id_hemxxmh dan abnormal_istirahat.tanggal 
					
				}
				
				if ( edtabnormal_istirahat.inError() ) {
					return false;
				}
			});
			
			edtabnormal_istirahat.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtabnormal_istirahat.field('finish_on').val(finish_on);
			});

			edtabnormal_istirahat.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblabnormal_istirahat.rows().deselect();
				tblabnormal_istirahat.ajax.reload(null, false);
			});

			//start datatables
			tblabnormal_istirahat = $('#tblabnormal_istirahat').DataTable( {
				searchPanes:{
					layout: 'columns-4',
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
						targets: [2,3,4,5,6]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					}
				],
				ajax: {
					url: "../../models/abnormal_istirahat/abnormal_istirahat.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_abnormal_istirahat = show_inactive_status_abnormal_istirahat;
					}
				},
				scrollX: true,
				responsive: false,
				order: [[ 5, "asc" ]],
				columns: [
					{ data: "abnormal_istirahat.id",visible:false },
					{ data: "abnormal_istirahat.tanggal" },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "hosxxmh.nama" },
					{ data: "hevgrmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "holxxmd_2.nama" },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },//10
					{ data: "abnormal_istirahat.keterangan" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_abnormal_istirahat';
						$table       = 'tblabnormal_istirahat';
						$edt         = 'edtabnormal_istirahat';
						$show_status = '_abnormal_istirahat';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.abnormal_istirahat.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblabnormal_istirahat.searchPanes.container().appendTo( '#searchPanes1' );

			tblabnormal_istirahat.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblabnormal_istirahat);
			} );
			
			tblabnormal_istirahat.on( 'select', function( e, dt, type, indexes ) {
				abnormal_istirahat_data    = tblabnormal_istirahat.row( { selected: true } ).data().abnormal_istirahat;
				id_abnormal_istirahat      = abnormal_istirahat_data.id;
				id_transaksi_h = id_abnormal_istirahat; // dipakai untuk general
				is_approve     = abnormal_istirahat_data.is_approve;
				is_nextprocess = abnormal_istirahat_data.is_nextprocess;
				is_jurnal      = abnormal_istirahat_data.is_jurnal;
				is_active      = abnormal_istirahat_data.is_active;

				id_hemxxmh_old = abnormal_istirahat_data.id_hemxxmh;
				id_htpxxmh_old = abnormal_istirahat_data.id_htpxxmh;

				// atur hak akses
				CekSelectHeaderH(tblabnormal_istirahat);
			} );

			tblabnormal_istirahat.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_abnormal_istirahat = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblabnormal_istirahat);
			} );
			
		} );// end of document.ready

		$("#frmFilter").submit(function(e) {
			e.preventDefault();
		}).validate({
			rules: {
				
			},
			submitHandler: function(frmFilter) {
				start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
				end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
				
				notifyprogress = $.notify({
					message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
				},{
					z_index: 9999,
					allow_dismiss: false,
					type: 'info',
					delay: 0
				});

				tblabnormal_istirahat.rows().deselect();
				tblabnormal_istirahat.ajax.reload(function ( json ) {
					notifyprogress.close();
				}, false);
				return false; 
			}
		});
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
