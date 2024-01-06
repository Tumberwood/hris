<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'makan_h';
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
            </div>
        </div>
    </div>
</div>
<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<!-- start Custom Form Datatables Editor -->
				<!-- <div id="custom_makan_h">
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.tanggal"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.jumlah_ceklok_s1"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="makan_h.nominal_s1"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.subtotal_s1"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.jumlah_ceklok_s2"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="makan_h.nominal_s2"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.subtotal_s2"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.jumlah_ceklok_s3"></editor-field>
							</div>
							<div class="col-lg-6">
								<editor-field name="makan_h.nominal_s3"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.subtotal_s3"></editor-field>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6">
								<editor-field name="makan_h.subtotal_all"></editor-field>
							</div>
						</div>
					</div>
				</div> -->
				<!-- end Custom Form Datatables Editor -->
				<div class="tabs-container">
					<ul class="nav nav-tabs" role="tablist">
						<li><a class="nav-link active" data-toggle="tab" href="#tabAll"> All</a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tabMakan"> Mesin Finger</a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tabMakanManual"> Makan Manual</a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tabKoor"> Koordinator</a></li>
						<li><a class="nav-link" data-toggle="tab" href="#tabUmum"> Umum/HL</a></li>
					</ul>
					<div class="tab-content">
						<div role="tabpanel" id="tabMakan" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblmakan_h" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>Tanggal </th>
												<th>Shift 1</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 2</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 3</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th id="makan_sh1"></th>
												<th></th>
												<th id="makan_total_sh1"></th>
												<th id="sh2"></th>
												<th></th>
												<th id="makan_total_sh2"></th>
												<th id="makan_sh3"></th>
												<th></th>
												<th id="makan_total_sh3"></th>
												<th id="makan_subtotal_all"></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabMakanManual" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblmakan_h_manual" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>Tanggal </th>
												<th>Shift 1</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 2</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 3</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th id="manual_sh1"></th>
												<th></th>
												<th id="manual_total_sh1"></th>
												<th id="sh2"></th>
												<th></th>
												<th id="manual_total_sh2"></th>
												<th id="manual_sh3"></th>
												<th></th>
												<th id="manual_total_sh3"></th>
												<th id="manual_subtotal_all"></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabKoor" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblmakan_h_koordinator" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>Tanggal </th>
												<th>Shift 1</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 2</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 3</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th id="koordinator_sh1"></th>
												<th></th>
												<th id="koordinator_total_sh1"></th>
												<th id="sh2"></th>
												<th></th>
												<th id="koordinator_total_sh2"></th>
												<th id="koordinator_sh3"></th>
												<th></th>
												<th id="koordinator_total_sh3"></th>
												<th id="koordinator_subtotal_all"></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabUmum" class="tab-pane">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblmakan_h_umum" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>Tanggal </th>
												<th>Shift 1</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 2</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Shift 3</th>
												<th>Nominal</th>
												<th>Subtotal</th>
												<th>Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th id="umum_sh1"></th>
												<th></th>
												<th id="umum_total_sh1"></th>
												<th id="sh2"></th>
												<th></th>
												<th id="umum_total_sh2"></th>
												<th id="umum_sh3"></th>
												<th></th>
												<th id="umum_total_sh3"></th>
												<th id="umum_subtotal_all"></th>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
						<div role="tabpanel" id="tabAll" class="tab-pane active">
							<div class="panel-body">
								<div class="table-responsive">
									<table id="tblmakan_h_all" class="table table-striped table-bordered table-hover nowrap" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>Tanggal </th>
												<th>Shift 1</th>
												<th>Subtotal</th>
												<th>Shift 2</th>
												<th>Subtotal</th>
												<th>Shift 3</th>
												<th>Subtotal</th>
												<th>Total</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th></th>
												<th></th>
												<th id="all_2"></th>
												<th id="all_3"></th>
												<th id="all_4"></th>
												<th id="all_5"></th>
												<th id="all_6"></th>
												<th id="all_7"></th>
												<th id="all_8"></th>
											</tr>
										</tfoot>
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

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/makan_h/fn/makan_h_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmakan_h, tblmakan_h, show_inactive_status_makan_h = 0, id_makan_h;
		var edtmakan_h_manual, tblmakan_h_manual, show_inactive_status_makan_h = 0, id_makan_h;
		var edtmakan_h_koordinator, tblmakan_h_koordinator, show_inactive_status_makan_h = 0, id_makan_h;
		var edtmakan_h_umum, tblmakan_h_umum, show_inactive_status_makan_h = 0, id_makan_h;
		var tblmakan_h_all, show_inactive_status_makan_h = 0, id_makan_h;
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
		// $('#start_date').datepicker('setDate', "15 Dec 2023");
		// $('#end_date').datepicker('setDate', "15 Dec 2023");
		$('#start_date').datepicker('setDate', awal_bulan_dmy);
		$('#end_date').datepicker('setDate', tanggal_hariini_dmy);
        // END datepicker init
		
		$(document).ready(function() {
			start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
			end_date   = moment($('#end_date').val()).format('YYYY-MM-DD');

		//start datatables editor
			edtmakan_h = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/makan_h/makan_h_makan.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				// template: "#custom_makan_h",
				table: "#tblmakan_h",
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
						def: "makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_h.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "makan_h.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 
					{
						label: "Shift 1 ",
						name: "makan_h.jumlah_ceklok_s1",
						type: "readonly"
					},
					{
						label: "Nominal 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s1"
					},
					{
						label: "Subtotal Shift 1 ",
						name: "makan_h.subtotal_s1",
						type: "readonly"
					},
					{
						label: "Shift 2 ",
						name: "makan_h.jumlah_ceklok_s2",
						type: "readonly"
					},
					{
						label: "Nominal 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s2"
					},
					{
						label: "Subtotal Shift 2 ",
						name: "makan_h.subtotal_s2",
						type: "readonly"
					},
					{
						label: "Shift 3 ",
						name: "makan_h.jumlah_ceklok_s3",
						type: "readonly"
					},
					{
						label: "Nominal 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s3"
					},
					{
						label: "Subtotal Shift 3 ",
						name: "makan_h.subtotal_s3",
						type: "readonly"
					},
					{
						label: "Subtotal All ",
						name: "makan_h.subtotal_all",
						type: "readonly"
					}
				]
			} );

			edtmakan_h.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_h.rows().deselect();
				}

			});

			edtmakan_h.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtmakan_h.dependent( 'makan_h.tanggal', function ( val, data, callback ) {
				if (val != null) {
					jumlahMakan('makan', edtmakan_h);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h.dependent( 'makan_h.nominal_s1', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan', edtmakan_h);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h.dependent( 'makan_h.nominal_s2', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan', edtmakan_h);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h.dependent( 'makan_h.nominal_s3', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan', edtmakan_h);
				}
				return {}
			}, {event: 'keyup change'});

            edtmakan_h.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi makan_h.tanggal
					if ( ! edtmakan_h.field('makan_h.tanggal').isMultiValue() ) {
						tanggal = edtmakan_h.field('makan_h.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtmakan_h.field('makan_h.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi makan_h.tanggal
					
					nominal_s1 = edtmakan_h.field('makan_h.nominal_s1').val();
					nominal_s2 = edtmakan_h.field('makan_h.nominal_s2').val();
					nominal_s3 = edtmakan_h.field('makan_h.nominal_s3').val();
					
					if(!nominal_s1 || nominal_s1 == ''){
						edtmakan_h.field('makan_h.nominal_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s1 <= 0 ){
						edtmakan_h.field('makan_h.nominal_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s1) ){
						edtmakan_h.field('makan_h.nominal_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					nominal_s2 = edtmakan_h.field('makan_h.nominal_s2').val();
					if(!nominal_s2 || nominal_s2 == ''){
						edtmakan_h.field('makan_h.nominal_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s2 <= 0 ){
						edtmakan_h.field('makan_h.nominal_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s2) ){
						edtmakan_h.field('makan_h.nominal_s2').error( 'Inputan harus berupa Angka!' );
					}

					nominal_s3 = edtmakan_h.field('makan_h.nominal_s3').val();
					if(!nominal_s3 || nominal_s3 == ''){
						edtmakan_h.field('makan_h.nominal_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s3 <= 0 ){
						edtmakan_h.field('makan_h.nominal_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s3) ){
						edtmakan_h.field('makan_h.nominal_s3').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edtmakan_h.inError() ) {
					return false;
				}
			});
			
			edtmakan_h.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h.field('finish_on').val(finish_on);
			});

			edtmakan_h.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblmakan_h.rows().deselect();
				tblmakan_h.ajax.reload(null, false);
			});

			//start datatables
			tblmakan_h = $('#tblmakan_h').DataTable( {
				ajax: {
					url: "../../models/makan_h/makan_h_makan.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_h.id",visible:false },
					{ data: "makan_h.tanggal" },
					{
						data: "makan_h.jumlah_ceklok_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_all",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_h';
						$table       = 'tblmakan_h';
						$edt         = 'edtmakan_h';
						$show_status = '_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					
					// s_pot_jam = api.column( 16 ).data().sum();
					makan_sh1 = api.column( 2 ).data().sum();
					makan_total_sh1 = api.column( 4 ).data().sum();

					$( '#makan_sh1' ).html( numFormat(makan_sh1) );
					$( '#makan_total_sh1' ).html( numFormat(makan_total_sh1) );
					
					makan_sh2 = api.column( 5 ).data().sum();
					makan_total_sh2 = api.column( 7 ).data().sum();

					$( '#makan_sh2' ).html( numFormat(makan_sh2) );
					$( '#makan_total_sh2' ).html( numFormat(makan_total_sh2) );
					
					makan_sh3 = api.column( 8 ).data().sum();
					makan_total_sh3 = api.column( 10 ).data().sum();

					$( '#makan_sh3' ).html( numFormat(makan_sh3) );
					$( '#makan_total_sh3' ).html( numFormat(makan_total_sh3) );

					makan_subtotal_all = api.column( 11 ).data().sum();
					$( '#makan_subtotal_all' ).html( numFormat(makan_subtotal_all) );
				}
			} );

			tblmakan_h.on( 'init', function () {
				// atur hak akses
			} );
			
			tblmakan_h.on( 'select', function( e, dt, type, indexes ) {
				makan_h_data    = tblmakan_h.row( { selected: true } ).data().makan_h;
				id_makan_h      = makan_h_data.id;
				id_transaksi_h = id_makan_h; // dipakai untuk general
				is_approve     = makan_h_data.is_approve;
				is_nextprocess = makan_h_data.is_nextprocess;
				is_jurnal      = makan_h_data.is_jurnal;
				is_active      = makan_h_data.is_active;

				id_hemxxmh_old = makan_h_data.id_hemxxmh;
				id_htpxxmh_old = makan_h_data.id_htpxxmh;

				// atur hak akses
			} );

			tblmakan_h.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_h = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
			} );
		//END View

		//start datatables editor
			edtmakan_h_manual = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/makan_h/makan_h_makan_manual.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				// template: "#custom_makan_h",
				table: "#tblmakan_h_manual",
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
						def: "makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_h.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "makan_h.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 
					{
						label: "Shift 1 ",
						name: "makan_h.jumlah_ceklok_s1",
						type: "readonly"
					},
					{
						label: "Nominal 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s1"
					},
					{
						label: "Subtotal Shift 1 ",
						name: "makan_h.subtotal_s1",
						type: "readonly"
					},
					{
						label: "Shift 2 ",
						name: "makan_h.jumlah_ceklok_s2",
						type: "readonly"
					},
					{
						label: "Nominal 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s2"
					},
					{
						label: "Subtotal Shift 2 ",
						name: "makan_h.subtotal_s2",
						type: "readonly"
					},
					{
						label: "Shift 3 ",
						name: "makan_h.jumlah_ceklok_s3",
						type: "readonly"
					},
					{
						label: "Nominal 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s3"
					},
					{
						label: "Subtotal Shift 3 ",
						name: "makan_h.subtotal_s3",
						type: "readonly"
					},
					{
						label: "Subtotal All ",
						name: "makan_h.subtotal_all",
						type: "readonly"
					}
				]
			} );

			edtmakan_h_manual.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_manual.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_h_manual.rows().deselect();
				}

			});

			edtmakan_h_manual.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtmakan_h_manual.dependent( 'makan_h.tanggal', function ( val, data, callback ) {
				if (val != null) {
					jumlahMakan('makan manual', edtmakan_h_manual);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_manual.dependent( 'makan_h.nominal_s1', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan manual', edtmakan_h_manual);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_manual.dependent( 'makan_h.nominal_s2', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan manual', edtmakan_h_manual);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_manual.dependent( 'makan_h.nominal_s3', function ( val, data, callback ) {
				if (val > 0) {
					jumlahMakan('makan manual', edtmakan_h_manual);
				}
				return {}
			}, {event: 'keyup change'});

            edtmakan_h_manual.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi makan_h.tanggal
					if ( ! edtmakan_h_manual.field('makan_h.tanggal').isMultiValue() ) {
						tanggal = edtmakan_h_manual.field('makan_h.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtmakan_h_manual.field('makan_h.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi makan_h.tanggal
					
					nominal_s1 = edtmakan_h_manual.field('makan_h.nominal_s1').val();
					nominal_s2 = edtmakan_h_manual.field('makan_h.nominal_s2').val();
					nominal_s3 = edtmakan_h_manual.field('makan_h.nominal_s3').val();
					
					if(!nominal_s1 || nominal_s1 == ''){
						edtmakan_h_manual.field('makan_h.nominal_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s1 <= 0 ){
						edtmakan_h_manual.field('makan_h.nominal_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s1) ){
						edtmakan_h_manual.field('makan_h.nominal_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					nominal_s2 = edtmakan_h_manual.field('makan_h.nominal_s2').val();
					if(!nominal_s2 || nominal_s2 == ''){
						edtmakan_h_manual.field('makan_h.nominal_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s2 <= 0 ){
						edtmakan_h_manual.field('makan_h.nominal_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s2) ){
						edtmakan_h_manual.field('makan_h.nominal_s2').error( 'Inputan harus berupa Angka!' );
					}

					nominal_s3 = edtmakan_h_manual.field('makan_h.nominal_s3').val();
					if(!nominal_s3 || nominal_s3 == ''){
						edtmakan_h_manual.field('makan_h.nominal_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s3 <= 0 ){
						edtmakan_h_manual.field('makan_h.nominal_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s3) ){
						edtmakan_h_manual.field('makan_h.nominal_s3').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edtmakan_h_manual.inError() ) {
					return false;
				}
			});
			
			edtmakan_h_manual.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_manual.field('finish_on').val(finish_on);
			});

			edtmakan_h_manual.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblmakan_h_manual.rows().deselect();
				tblmakan_h_manual.ajax.reload(null, false);
			});

			//start datatables
			tblmakan_h_manual = $('#tblmakan_h_manual').DataTable( {
				ajax: {
					url: "../../models/makan_h/makan_h_makan_manual.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_h.id",visible:false },
					{ data: "makan_h.tanggal" },
					{
						data: "makan_h.jumlah_ceklok_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_all",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_h';
						$table       = 'tblmakan_h_manual';
						$edt         = 'edtmakan_h_manual';
						$show_status = '_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					
					// s_pot_jam = api.column( 16 ).data().sum();
					manual_sh1 = api.column( 2 ).data().sum();
					manual_total_sh1 = api.column( 4 ).data().sum();

					$( '#manual_sh1' ).html( numFormat(manual_sh1) );
					$( '#manual_total_sh1' ).html( numFormat(manual_total_sh1) );
					
					manual_sh2 = api.column( 5 ).data().sum();
					manual_total_sh2 = api.column( 7 ).data().sum();

					$( '#manual_sh2' ).html( numFormat(manual_sh2) );
					$( '#manual_total_sh2' ).html( numFormat(manual_total_sh2) );
					
					manual_sh3 = api.column( 8 ).data().sum();
					manual_total_sh3 = api.column( 10 ).data().sum();

					$( '#manual_sh3' ).html( numFormat(manual_sh3) );
					$( '#manual_total_sh3' ).html( numFormat(manual_total_sh3) );

					manual_subtotal_all = api.column( 11 ).data().sum();
					$( '#manual_subtotal_all' ).html( numFormat(manual_subtotal_all) );
				}
			} );

			tblmakan_h_manual.on( 'init', function () {
				// atur hak akses
			} );
			
			tblmakan_h_manual.on( 'select', function( e, dt, type, indexes ) {
				makan_h_data    = tblmakan_h_manual.row( { selected: true } ).data().makan_h;
				id_makan_h      = makan_h_data.id;
				id_transaksi_h = id_makan_h; // dipakai untuk general
				is_approve     = makan_h_data.is_approve;
				is_nextprocess = makan_h_data.is_nextprocess;
				is_jurnal      = makan_h_data.is_jurnal;
				is_active      = makan_h_data.is_active;

				id_hemxxmh_old = makan_h_data.id_hemxxmh;
				id_htpxxmh_old = makan_h_data.id_htpxxmh;

				// atur hak akses
			} );

			tblmakan_h_manual.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_h = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
			} );
		//END View

		//start datatables editor
			edtmakan_h_koordinator = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/makan_h/makan_h_makan_koordinator.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				// template: "#custom_makan_h",
				table: "#tblmakan_h_koordinator",
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
						def: "makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_h.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "makan_h.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 
					{
						label: "Shift 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s1"
					},
					{
						label: "Nominal 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s1"
					},
					{
						label: "Subtotal Shift 1 ",
						name: "makan_h.subtotal_s1",
						type: "readonly"
					},
					{
						label: "Shift 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s2"
					},
					{
						label: "Nominal 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s2"
					},
					{
						label: "Subtotal Shift 2 ",
						name: "makan_h.subtotal_s2",
						type: "readonly"
					},
					{
						label: "Shift 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s3"
					},
					{
						label: "Nominal 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s3"
					},
					{
						label: "Subtotal Shift 3 ",
						name: "makan_h.subtotal_s3",
						type: "readonly"
					},
					{
						label: "Subtotal All ",
						name: "makan_h.subtotal_all",
						type: "readonly"
					}
				]
			} );

			edtmakan_h_koordinator.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_koordinator.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_h_koordinator.rows().deselect();
				}

			});

			edtmakan_h_koordinator.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtmakan_h_koordinator.dependent( 'makan_h.jumlah_ceklok_s1', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_koordinator.dependent( 'makan_h.jumlah_ceklok_s2', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_koordinator.dependent( 'makan_h.jumlah_ceklok_s3', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_koordinator.dependent( 'makan_h.nominal_s1', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_koordinator.dependent( 'makan_h.nominal_s2', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_koordinator.dependent( 'makan_h.nominal_s3', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_koordinator);
				}
				return {}
			}, {event: 'keyup change'});

            edtmakan_h_koordinator.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi makan_h.tanggal
					if ( ! edtmakan_h_koordinator.field('makan_h.tanggal').isMultiValue() ) {
						tanggal = edtmakan_h_koordinator.field('makan_h.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtmakan_h_koordinator.field('makan_h.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi makan_h.tanggal
					
					// Jumlah Ceklok
					jumlah_ceklok_s1 = edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s1').val();
					jumlah_ceklok_s2 = edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s2').val();
					jumlah_ceklok_s3 = edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s3').val();
					
					if(!jumlah_ceklok_s1 || jumlah_ceklok_s1 == ''){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s1 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s1) ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					jumlah_ceklok_s2 = edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s2').val();
					if(!jumlah_ceklok_s2 || jumlah_ceklok_s2 == ''){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s2 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s2) ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s2').error( 'Inputan harus berupa Angka!' );
					}

					jumlah_ceklok_s3 = edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s3').val();
					if(!jumlah_ceklok_s3 || jumlah_ceklok_s3 == ''){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s3 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s3) ){
						edtmakan_h_koordinator.field('makan_h.jumlah_ceklok_s3').error( 'Inputan harus berupa Angka!' );
					}

					//Nominal
					nominal_s1 = edtmakan_h_koordinator.field('makan_h.nominal_s1').val();
					nominal_s2 = edtmakan_h_koordinator.field('makan_h.nominal_s2').val();
					nominal_s3 = edtmakan_h_koordinator.field('makan_h.nominal_s3').val();
					
					if(!nominal_s1 || nominal_s1 == ''){
						edtmakan_h_koordinator.field('makan_h.nominal_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s1 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.nominal_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s1) ){
						edtmakan_h_koordinator.field('makan_h.nominal_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					nominal_s2 = edtmakan_h_koordinator.field('makan_h.nominal_s2').val();
					if(!nominal_s2 || nominal_s2 == ''){
						edtmakan_h_koordinator.field('makan_h.nominal_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s2 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.nominal_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s2) ){
						edtmakan_h_koordinator.field('makan_h.nominal_s2').error( 'Inputan harus berupa Angka!' );
					}

					nominal_s3 = edtmakan_h_koordinator.field('makan_h.nominal_s3').val();
					if(!nominal_s3 || nominal_s3 == ''){
						edtmakan_h_koordinator.field('makan_h.nominal_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s3 <= 0 ){
						edtmakan_h_koordinator.field('makan_h.nominal_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s3) ){
						edtmakan_h_koordinator.field('makan_h.nominal_s3').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edtmakan_h_koordinator.inError() ) {
					return false;
				}
			});
			
			edtmakan_h_koordinator.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_koordinator.field('finish_on').val(finish_on);
			});

			edtmakan_h_koordinator.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblmakan_h_koordinator.rows().deselect();
				tblmakan_h_koordinator.ajax.reload(null, false);
			});

			//start datatables
			tblmakan_h_koordinator = $('#tblmakan_h_koordinator').DataTable( {
				ajax: {
					url: "../../models/makan_h/makan_h_makan_koordinator.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_h.id",visible:false },
					{ data: "makan_h.tanggal" },
					{
						data: "makan_h.jumlah_ceklok_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_all",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_h';
						$table       = 'tblmakan_h_koordinator';
						$edt         = 'edtmakan_h_koordinator';
						$show_status = '_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					
					// s_pot_jam = api.column( 16 ).data().sum();
					koordinator_sh1 = api.column( 2 ).data().sum();
					koordinator_total_sh1 = api.column( 4 ).data().sum();

					$( '#koordinator_sh1' ).html( numFormat(koordinator_sh1) );
					$( '#koordinator_total_sh1' ).html( numFormat(koordinator_total_sh1) );
					
					koordinator_sh2 = api.column( 5 ).data().sum();
					koordinator_total_sh2 = api.column( 7 ).data().sum();

					$( '#koordinator_sh2' ).html( numFormat(koordinator_sh2) );
					$( '#koordinator_total_sh2' ).html( numFormat(koordinator_total_sh2) );
					
					koordinator_sh3 = api.column( 8 ).data().sum();
					koordinator_total_sh3 = api.column( 10 ).data().sum();

					$( '#koordinator_sh3' ).html( numFormat(koordinator_sh3) );
					$( '#koordinator_total_sh3' ).html( numFormat(koordinator_total_sh3) );

					koordinator_subtotal_all = api.column( 11 ).data().sum();
					$( '#koordinator_subtotal_all' ).html( numFormat(koordinator_subtotal_all) );
				}
			} );

			tblmakan_h_koordinator.on( 'init', function () {
				// atur hak akses
			} );
			
			tblmakan_h_koordinator.on( 'select', function( e, dt, type, indexes ) {
				makan_h_data    = tblmakan_h_koordinator.row( { selected: true } ).data().makan_h;
				id_makan_h      = makan_h_data.id;
				id_transaksi_h = id_makan_h; // dipakai untuk general
				is_approve     = makan_h_data.is_approve;
				is_nextprocess = makan_h_data.is_nextprocess;
				is_jurnal      = makan_h_data.is_jurnal;
				is_active      = makan_h_data.is_active;

				id_hemxxmh_old = makan_h_data.id_hemxxmh;
				id_htpxxmh_old = makan_h_data.id_htpxxmh;

				// atur hak akses
			} );

			tblmakan_h_koordinator.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_h = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
			} );
		//END View

		//start datatables editor
			edtmakan_h_umum = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/makan_h/makan_h_makan_umum.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				// template: "#custom_makan_h",
				table: "#tblmakan_h_umum",
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
						def: "makan_h",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "makan_h.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "makan_h.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#end_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 
					{
						label: "Shift 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s1"
					},
					{
						label: "Nominal 1 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s1"
					},
					{
						label: "Subtotal Shift 1 ",
						name: "makan_h.subtotal_s1",
						type: "readonly"
					},
					{
						label: "Shift 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s2"
					},
					{
						label: "Nominal 2 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s2"
					},
					{
						label: "Subtotal Shift 2 ",
						name: "makan_h.subtotal_s2",
						type: "readonly"
					},
					{
						label: "Shift 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.jumlah_ceklok_s3"
					},
					{
						label: "Nominal 3 <sup class='text-danger'>*<sup>",
						name: "makan_h.nominal_s3"
					},
					{
						label: "Subtotal Shift 3 ",
						name: "makan_h.subtotal_s3",
						type: "readonly"
					},
					{
						label: "Subtotal All ",
						name: "makan_h.subtotal_all",
						type: "readonly"
					}
				]
			} );

			edtmakan_h_umum.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_umum.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblmakan_h_umum.rows().deselect();
				}

			});

			edtmakan_h_umum.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtmakan_h_umum.dependent( 'makan_h.jumlah_ceklok_s1', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_umum.dependent( 'makan_h.jumlah_ceklok_s2', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_umum.dependent( 'makan_h.jumlah_ceklok_s3', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_umum.dependent( 'makan_h.nominal_s1', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_umum.dependent( 'makan_h.nominal_s2', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});
			
			edtmakan_h_umum.dependent( 'makan_h.nominal_s3', function ( val, data, callback ) {
				if (val > 0) {
					subtotal(edtmakan_h_umum);
				}
				return {}
			}, {event: 'keyup change'});

            edtmakan_h_umum.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi makan_h.tanggal
					if ( ! edtmakan_h_umum.field('makan_h.tanggal').isMultiValue() ) {
						tanggal = edtmakan_h_umum.field('makan_h.tanggal').val();
						if(!tanggal || tanggal == ''){
							edtmakan_h_umum.field('makan_h.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi makan_h.tanggal
					
					// Jumlah Ceklok
					jumlah_ceklok_s1 = edtmakan_h_umum.field('makan_h.jumlah_ceklok_s1').val();
					jumlah_ceklok_s2 = edtmakan_h_umum.field('makan_h.jumlah_ceklok_s2').val();
					jumlah_ceklok_s3 = edtmakan_h_umum.field('makan_h.jumlah_ceklok_s3').val();
					
					if(!jumlah_ceklok_s1 || jumlah_ceklok_s1 == ''){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s1 <= 0 ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s1) ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					jumlah_ceklok_s2 = edtmakan_h_umum.field('makan_h.jumlah_ceklok_s2').val();
					if(!jumlah_ceklok_s2 || jumlah_ceklok_s2 == ''){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s2 <= 0 ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s2) ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s2').error( 'Inputan harus berupa Angka!' );
					}

					jumlah_ceklok_s3 = edtmakan_h_umum.field('makan_h.jumlah_ceklok_s3').val();
					if(!jumlah_ceklok_s3 || jumlah_ceklok_s3 == ''){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(jumlah_ceklok_s3 <= 0 ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(jumlah_ceklok_s3) ){
						edtmakan_h_umum.field('makan_h.jumlah_ceklok_s3').error( 'Inputan harus berupa Angka!' );
					}

					//Nominal
					nominal_s1 = edtmakan_h_umum.field('makan_h.nominal_s1').val();
					nominal_s2 = edtmakan_h_umum.field('makan_h.nominal_s2').val();
					nominal_s3 = edtmakan_h_umum.field('makan_h.nominal_s3').val();
					
					if(!nominal_s1 || nominal_s1 == ''){
						edtmakan_h_umum.field('makan_h.nominal_s1').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s1 <= 0 ){
						edtmakan_h_umum.field('makan_h.nominal_s1').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s1) ){
						edtmakan_h_umum.field('makan_h.nominal_s1').error( 'Inputan harus berupa Angka!' );
					}
					
					nominal_s2 = edtmakan_h_umum.field('makan_h.nominal_s2').val();
					if(!nominal_s2 || nominal_s2 == ''){
						edtmakan_h_umum.field('makan_h.nominal_s2').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s2 <= 0 ){
						edtmakan_h_umum.field('makan_h.nominal_s2').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s2) ){
						edtmakan_h_umum.field('makan_h.nominal_s2').error( 'Inputan harus berupa Angka!' );
					}

					nominal_s3 = edtmakan_h_umum.field('makan_h.nominal_s3').val();
					if(!nominal_s3 || nominal_s3 == ''){
						edtmakan_h_umum.field('makan_h.nominal_s3').error( 'Wajib diisi!' );
					}
					
					// validasi min atau max angka
					if(nominal_s3 <= 0 ){
						edtmakan_h_umum.field('makan_h.nominal_s3').error( 'Inputan harus > 0' );
					}
					
					// validasi angka
					if(isNaN(nominal_s3) ){
						edtmakan_h_umum.field('makan_h.nominal_s3').error( 'Inputan harus berupa Angka!' );
					}

				}
				
				if ( edtmakan_h_umum.inError() ) {
					return false;
				}
			});
			
			edtmakan_h_umum.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmakan_h_umum.field('finish_on').val(finish_on);
			});

			edtmakan_h_umum.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblmakan_h_umum.rows().deselect();
				tblmakan_h_umum.ajax.reload(null, false);
			});

			//start datatables
			tblmakan_h_umum = $('#tblmakan_h_umum').DataTable( {
				ajax: {
					url: "../../models/makan_h/makan_h_makan_umum.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
						d.show_inactive_status_makan_h = show_inactive_status_makan_h;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "makan_h.id",visible:false },
					{ data: "makan_h.tanggal" },
					{
						data: "makan_h.jumlah_ceklok_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},

					{
						data: "makan_h.subtotal_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.jumlah_ceklok_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.nominal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "makan_h.subtotal_all",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_h';
						$table       = 'tblmakan_h_umum';
						$edt         = 'edtmakan_h_umum';
						$show_status = '_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.makan_h.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					
					// s_pot_jam = api.column( 16 ).data().sum();
					umum_sh1 = api.column( 2 ).data().sum();
					umum_total_sh1 = api.column( 4 ).data().sum();

					$( '#umum_sh1' ).html( numFormat(umum_sh1) );
					$( '#umum_total_sh1' ).html( numFormat(umum_total_sh1) );
					
					umum_sh2 = api.column( 5 ).data().sum();
					umum_total_sh2 = api.column( 7 ).data().sum();

					$( '#umum_sh2' ).html( numFormat(umum_sh2) );
					$( '#umum_total_sh2' ).html( numFormat(umum_total_sh2) );
					
					umum_sh3 = api.column( 8 ).data().sum();
					umum_total_sh3 = api.column( 10 ).data().sum();

					$( '#umum_sh3' ).html( numFormat(umum_sh3) );
					$( '#umum_total_sh3' ).html( numFormat(umum_total_sh3) );

					umum_subtotal_all = api.column( 11 ).data().sum();
					$( '#umum_subtotal_all' ).html( numFormat(umum_subtotal_all) );
				}
			} );

			tblmakan_h_umum.on( 'init', function () {
				// atur hak akses
			} );
			
			tblmakan_h_umum.on( 'select', function( e, dt, type, indexes ) {
				makan_h_data    = tblmakan_h_umum.row( { selected: true } ).data().makan_h;
				id_makan_h      = makan_h_data.id;
				id_transaksi_h = id_makan_h; // dipakai untuk general
				is_approve     = makan_h_data.is_approve;
				is_nextprocess = makan_h_data.is_nextprocess;
				is_jurnal      = makan_h_data.is_jurnal;
				is_active      = makan_h_data.is_active;

				id_hemxxmh_old = makan_h_data.id_hemxxmh;
				id_htpxxmh_old = makan_h_data.id_htpxxmh;

				// atur hak akses
			} );

			tblmakan_h_umum.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_makan_h = 0;
				id_hemxxmh_old = 0;
				id_htpxxmh_old = 0;

				// atur hak akses
			} );
		//END View
			
		//start datatables editor
			//start datatables
			tblmakan_h_all = $('#tblmakan_h_all').DataTable( {
				ajax: {
					url: "../../models/makan_h/makan_h.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.end_date = end_date;
					},
					dataSrc: function (json) {
						return json;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "id",visible:false },
					{ data: "tanggal" },
					{
						data: "shift1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "total_s1" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "shift2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "total_s2" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "shift3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "total_s3" ,
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					},
					{
						data: "subtotal_all",
						render: $.fn.dataTable.render.number( ',', '.', 2,'','' ),
						class: "text-right"
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_makan_h';
						$table       = 'tblmakan_h_all';
						$edt         = 'edtmakan_h_all';
						$show_status = '_makan_h';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					
					for (var i = 2; i <= 8; i++) {
						var columnIndex = i;
						var sum_all = api.column(columnIndex).data().sum();
						// Bisa dilakukan sum berdasarkan paginasi (sum per paginasi / tidak sum semua data) dengan menambahkan { page: 'current' }
						var sum = api.column(columnIndex, { page: 'current' }).data().sum();
						$('#all_' + columnIndex).html(numFormat(sum_all));
						
					}
				}
			} );
		//END View
			
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

				tblmakan_h.rows().deselect();
				tblmakan_h.ajax.reload(function ( json ) {
					notifyprogress.close();
				}, false);

				tblmakan_h_manual.rows().deselect();
				tblmakan_h_manual.ajax.reload(function ( json ) {
					notifyprogress.close();
				}, false);

				tblmakan_h_koordinator.rows().deselect();
				tblmakan_h_koordinator.ajax.reload(function ( json ) {
					notifyprogress.close();
				}, false);

				tblmakan_h_umum.rows().deselect();
				tblmakan_h_umum.ajax.reload(function ( json ) {
					notifyprogress.close();
				}, false);

				tblmakan_h_all.rows().deselect();
				tblmakan_h_all.ajax.reload(function ( json ) {
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
