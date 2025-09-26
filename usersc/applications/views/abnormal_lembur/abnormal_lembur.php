<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'abnormal_lembur';
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
                    <table id="tblabnormal_lembur" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
							<tr>
								<th rowspan="2">No</th>
								<th rowspan="2">NIK</th>
								<th rowspan="2">Nama</th>
								<th rowspan="2">Status</th>
								<th rowspan="2">Bagian</th>
								<th rowspan="2">Tanggal</th>
								<th colspan="3" class="text-center">Perkalian Jam Lembur</th>
							</tr>
							<tr>
								<th>Lembur Program</th>
								<th>Lembur Seharusnya</th>
								<th>Selisih</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/abnormal_lembur/fn/abnormal_lembur_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtabnormal_lembur, tblabnormal_lembur, show_inactive_status_abnormal_lembur = 0, id_abnormal_lembur;
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
			edtabnormal_lembur = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/abnormal_lembur/abnormal_lembur.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_abnormal_lembur = show_inactive_status_abnormal_lembur;
					}
				},
				table: "#tblabnormal_lembur",
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
						def: "abnormal_lembur",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "abnormal_lembur.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "abnormal_lembur.tanggal",
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
						name: "abnormal_lembur.id_hemxxmh",
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
						label: "Lembur Program <sup class='text-danger'>*<sup>",
						name: "abnormal_lembur.lembur_program",
					},
					{
						label: "Lembur Seharusnya <sup class='text-danger'>*<sup>",
						name: "abnormal_lembur.lembur_seharusnya",
					},
					{
						label: "Selisih ",
						name: "abnormal_lembur.selisih",
						type: "readonly"
					},
					{
						label: "Keterangan",
						name: "abnormal_lembur.keterangan",
						type: "textarea"
					},
				]
			} );

			edtabnormal_lembur.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtabnormal_lembur.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblabnormal_lembur.rows().deselect();
				}
			});

			edtabnormal_lembur.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edtabnormal_lembur.dependent( 'abnormal_lembur.lembur_program', function ( val, data, callback ) {
				if(val > 0){
					lembur_program = edtabnormal_lembur.field('abnormal_lembur.lembur_program').val();
					lembur_seharusnya = edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').val();

					selisih = lembur_seharusnya - lembur_program;
					edtabnormal_lembur.field('abnormal_lembur.selisih').val(selisih);
				}
				return {}
			}, {event: 'keyup change'});

			edtabnormal_lembur.dependent( 'abnormal_lembur.lembur_seharusnya', function ( val, data, callback ) {
				if(val > 0){
					lembur_program = edtabnormal_lembur.field('abnormal_lembur.lembur_program').val();
					lembur_seharusnya = edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').val();

					selisih = lembur_seharusnya - lembur_program;
					edtabnormal_lembur.field('abnormal_lembur.selisih').val(selisih);
				}
				return {}
			}, {event: 'keyup change'});

            edtabnormal_lembur.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi abnormal_lembur.tanggal
					if ( ! edtabnormal_lembur.field('abnormal_lembur.tanggal').isMultiValue() ) {
						tanggal = edtabnormal_lembur.field('abnormal_lembur.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtabnormal_lembur.field('abnormal_lembur.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi abnormal_lembur.tanggal
					
					// BEGIN of validasi abnormal_lembur.id_hemxxmh
					if ( ! edtabnormal_lembur.field('abnormal_lembur.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edtabnormal_lembur.field('abnormal_lembur.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edtabnormal_lembur.field('abnormal_lembur.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi abnormal_lembur.id_hemxxmh
					
					// BEGIN of validasi abnormal_lembur.lembur_program
					if ( ! edtabnormal_lembur.field('abnormal_lembur.lembur_program').isMultiValue() ) {
						lembur_program = edtabnormal_lembur.field('abnormal_lembur.lembur_program').val();
						if(!lembur_program || lembur_program == ''){
							edtabnormal_lembur.field('abnormal_lembur.lembur_program').error( 'Wajib diisi!' );
						}

						// validasi min atau max angka
						if(lembur_program <= 0 ){
							edtabnormal_lembur.field('abnormal_lembur.lembur_program').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(lembur_program) ){
							edtabnormal_lembur.field('abnormal_lembur.lembur_program').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi abnormal_lembur.lembur_program
					
					// BEGIN of validasi abnormal_lembur.lembur_seharusnya
					if ( ! edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').isMultiValue() ) {
						lembur_seharusnya = edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').val();
						if(!lembur_seharusnya || lembur_seharusnya == ''){
							edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').error( 'Wajib diisi!' );
						}

						// validasi min atau max angka
						if(lembur_seharusnya <= 0 ){
							edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(lembur_seharusnya) ){
							edtabnormal_lembur.field('abnormal_lembur.lembur_seharusnya').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi abnormal_lembur.lembur_seharusnya

				}
				
				if ( edtabnormal_lembur.inError() ) {
					return false;
				}
			});
			
			edtabnormal_lembur.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtabnormal_lembur.field('finish_on').val(finish_on);
			});

			edtabnormal_lembur.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblabnormal_lembur.rows().deselect();
				tblabnormal_lembur.ajax.reload(null, false);
			});

			//start datatables
			tblabnormal_lembur = $('#tblabnormal_lembur').DataTable( {
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
					url: "../../models/abnormal_lembur/abnormal_lembur.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_abnormal_lembur = show_inactive_status_abnormal_lembur;
					}
				},
				scrollX: true,
				responsive: false,
				order: [[ 5, "asc" ]],
				columns: [
					{ data: "abnormal_lembur.id",visible:false },
					{ data: "hemxxmh.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hosxxmh.nama" },
					{ data: "abnormal_lembur.tanggal" },
					{ 
						data: "abnormal_lembur.lembur_program" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "abnormal_lembur.lembur_seharusnya" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{ 
						data: "abnormal_lembur.selisih" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_abnormal_lembur';
						$table       = 'tblabnormal_lembur';
						$edt         = 'edtabnormal_lembur';
						$show_status = '_abnormal_lembur';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.abnormal_lembur.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblabnormal_lembur.searchPanes.container().appendTo( '#searchPanes1' );

			tblabnormal_lembur.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblabnormal_lembur);
			} );
			
			tblabnormal_lembur.on( 'select', function( e, dt, type, indexes ) {
				abnormal_lembur_data    = tblabnormal_lembur.row( { selected: true } ).data().abnormal_lembur;
				id_abnormal_lembur      = abnormal_lembur_data.id;
				id_transaksi_h = id_abnormal_lembur; // dipakai untuk general
				is_approve     = abnormal_lembur_data.is_approve;
				is_nextprocess = abnormal_lembur_data.is_nextprocess;
				is_jurnal      = abnormal_lembur_data.is_jurnal;
				is_active      = abnormal_lembur_data.is_active;

				id_hemxxmh_old = abnormal_lembur_data.id_hemxxmh;
				id_htpxxmh_old = abnormal_lembur_data.id_htpxxmh;

				// atur hak akses
				CekSelectHeaderH(tblabnormal_lembur);
			} );

			tblabnormal_lembur.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_abnormal_lembur = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblabnormal_lembur);
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

				tblabnormal_lembur.rows().deselect();
				tblabnormal_lembur.ajax.reload(function ( json ) {
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
