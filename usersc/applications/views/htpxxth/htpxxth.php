<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpxxth';
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
                    <table id="tblhtpxxth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
								<th>Nomor</th>
								<th>Tanggal </th>
								<th>Employee</th>
								<th>Jenis</th>
								<th>Jabatan</th>
								<th>Department</th>
								<th>Keterangan </th>
								<th>Jam Awal</th>
								<th>Jam Akhir</th>
								<th>Approve</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpxxth/fn/htpxxth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpxxth, tblhtpxxth, show_inactive_status_htpxxth = 0, id_htpxxth;
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
			edthtpxxth = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/htpxxth/htpxxth.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htpxxth = show_inactive_status_htpxxth;
					}
				},
				table: "#tblhtpxxth",
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
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
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
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpxxth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpxxth.rows().deselect();

					edthtpxxth.field('kategori_dokumen').val('');
					edthtpxxth.field('kategori_dokumen_value').val('');
					edthtpxxth.field('field_tanggal').val('tanggal');
				}

				edthtpxxth.field('htpxxth.jam_awal').hide();
				edthtpxxth.field('htpxxth.jam_akhir').hide();
			});

			edthtpxxth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthtpxxth.dependent( 'htpxxth.id_htpxxmh', function ( val, data, callback ) {
				if(val > 0){
					id_htpxxmh = val;
					get_htpxxmh();
					if(jenis_jam == 1){
						edthtpxxth.field('htpxxth.jam_awal').show();
						edthtpxxth.field('htpxxth.jam_akhir').hide();

						edthtpxxth.field('htpxxth.jam_awal').label('Jam Awal <sup class="text-danger">*<sup>');
						edthtpxxth.field('htpxxth.jam_akhir').label('Jam Akhir');
						
						edthtpxxth.field('htpxxth.jam_akhir').val(null);
					}else if (jenis_jam == 2){
						edthtpxxth.field('htpxxth.jam_awal').hide();
						edthtpxxth.field('htpxxth.jam_akhir').show();

						edthtpxxth.field('htpxxth.jam_awal').label('Jam Awal');
						edthtpxxth.field('htpxxth.jam_akhir').label('Jam Akhir <sup class="text-danger">*<sup>');

						edthtpxxth.field('htpxxth.jam_awal').val(null);
					}else if (jenis_jam == 3){
						edthtpxxth.field('htpxxth.jam_awal').show();
						edthtpxxth.field('htpxxth.jam_akhir').show();
						edthtpxxth.field('htpxxth.jam_awal').label('Jam Awal <sup class="text-danger">*<sup>');
						edthtpxxth.field('htpxxth.jam_akhir').label('Jam Akhir <sup class="text-danger">*<sup>');

					}else{
						edthtpxxth.field('htpxxth.jam_awal').hide();
						edthtpxxth.field('htpxxth.jam_akhir').hide();

						edthtpxxth.field('htpxxth.jam_awal').label('Jam Awal');
						edthtpxxth.field('htpxxth.jam_akhir').label('Jam Akhir');

						edthtpxxth.field('htpxxth.jam_awal').val(null);
						edthtpxxth.field('htpxxth.jam_akhir').val(null);
					}
				}
				return {}
			}, {event: 'keyup change'});

            edthtpxxth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htpxxth.tanggal
					if ( ! edthtpxxth.field('htpxxth.tanggal').isMultiValue() ) {
						tanggal = edthtpxxth.field('htpxxth.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtpxxth.field('htpxxth.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpxxth.tanggal
					
					// BEGIN of validasi htpxxth.id_hemxxmh
					if ( ! edthtpxxth.field('htpxxth.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edthtpxxth.field('htpxxth.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtpxxth.field('htpxxth.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpxxth.id_hemxxmh

					// BEGIN of validasi htpxxth.id_htpxxmh
					if ( ! edthtpxxth.field('htpxxth.id_htpxxmh').isMultiValue() ) {
						id_htpxxmh = edthtpxxth.field('htpxxth.id_htpxxmh').val();
						if(!id_htpxxmh || id_htpxxmh == ''){
							edthtpxxth.field('htpxxth.id_htpxxmh').error( 'Wajib diisi!' );
						}else{
							get_htpxxmh();
							jam_awal = edthtpxxth.field('htpxxth.jam_awal').val();
							jam_akhir = edthtpxxth.field('htpxxth.jam_akhir').val();
							if(jenis_jam == 1){
								if(!jam_awal || jam_awal == ''){
									edthtpxxth.field('htpxxth.jam_awal').error( 'Wajib diisi!' );
								}
							}else if (jenis_jam == 2){
								if(!jam_akhir || jam_akhir == ''){
									edthtpxxth.field('htpxxth.jam_akhir').error( 'Wajib diisi!' );
								}
							}else if (jenis_jam == 3){
								if(!jam_awal || jam_awal == ''){
									edthtpxxth.field('htpxxth.jam_awal').error( 'Wajib diisi!' );
								}
								if(!jam_akhir || jam_akhir == ''){
									edthtpxxth.field('htpxxth.jam_akhir').error( 'Wajib diisi!' );
								}
							}	
						}
					}
					// END of validasi htpxxth.id_htpxxmh

					// BEGIN of validasi htpxxth.keterangan
					if ( ! edthtpxxth.field('htpxxth.keterangan').isMultiValue() ) {
						keterangan = edthtpxxth.field('htpxxth.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtpxxth.field('htpxxth.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htpxxth.keterangan

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
				tblhtpxxth.rows().deselect();
				tblhtpxxth.ajax.reload(null, false);
			});

			//start datatables
			tblhtpxxth = $('#tblhtpxxth').DataTable( {
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
						targets: [0,1,7,8,9,10]
					}
				],
				ajax: {
					url: "../../models/htpxxth/htpxxth.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_htpxxth = show_inactive_status_htpxxth;
					}
				},
				scrollX: true,
				responsive: false,
				order: [[ 2, "desc" ],[1, "desc"]],
				columns: [
					{ data: "htpxxth.id",visible:false },
					{ data: "htpxxth.kode" },
					{ data: "htpxxth.tanggal" },
					{ data: "hemxxmh_data" },
					{ data: "htpxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "hodxxmh.nama" },
					{ data: "htpxxth.keterangan" },
					{ data: "htpxxth.jam_awal" },
					{ data: "htpxxth.jam_akhir" },
					{ 
						data: "htpxxth.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							}
						}
					},
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpxxth';
						$table       = 'tblhtpxxth';
						$edt         = 'edthtpxxth';
						$show_status = '_htpxxth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpxxth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );
			
			tblhtpxxth.searchPanes.container().appendTo( '#searchPanes1' );

			tblhtpxxth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpxxth);
			} );
			
			tblhtpxxth.on( 'select', function( e, dt, type, indexes ) {
				htpxxth_data    = tblhtpxxth.row( { selected: true } ).data().htpxxth;
				id_htpxxth      = htpxxth_data.id;
				id_transaksi_h = id_htpxxth; // dipakai untuk general
				is_approve     = htpxxth_data.is_approve;
				is_nextprocess = htpxxth_data.is_nextprocess;
				is_jurnal      = htpxxth_data.is_jurnal;
				is_active      = htpxxth_data.is_active;

				id_hemxxmh_old = htpxxth_data.id_hemxxmh;
				id_htpxxmh_old = htpxxth_data.id_htpxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhtpxxth);
			} );

			tblhtpxxth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpxxth = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtpxxth);
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

				tblhtpxxth.rows().deselect();
				tblhtpxxth.ajax.reload(function ( json ) {
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
