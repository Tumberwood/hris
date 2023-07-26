<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hgspeth';
	$nama_tabels_d = [];
?>

<!-- begin content here -->
<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title p-xs">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content p-xs">
                <div class="p-xs" id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhgspeth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Pola</th>
                                <th>Siklus</th>
                                <th>Tanggal Awal</th>
                                <th>Tanggal Akhir</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgspeth/fn/hgspeth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgspeth, tblhgspeth, show_inactive_status_hgspeth = 0, id_hgspeth;
		// ------------- end of default variable

		var id_htsptth_old = 0, id_hemxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthgspeth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgspeth/hgspeth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgspeth = show_inactive_status_hgspeth;
					}
				},
				table: "#tblhgspeth",
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
						def: "hgspeth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgspeth.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Nama",
						name: "hgspeth.id_hemxxmh",
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
						label: "Pola <sup class='text-danger'>*<sup>",
						name: "hgspeth.id_htsptth",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/htsptth/htsptth_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_htsptth_old: id_htsptth_old,
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
						label: "Siklus  <sup class='text-danger'>*<sup>",
						name: "hgspeth.jumlah_siklus"
					}, 	
					{
						label: "Tanggal Awal",
						name: "hgspeth.tanggal_awal",
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
						name: "hgspeth.keterangan",
						type: "textarea"
					}
				]
			} );

			edthgspeth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgspeth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgspeth.rows().deselect();

					edthgspeth.field('kategori_dokumen').val('');
					edthgspeth.field('kategori_dokumen_value').val('');
					edthgspeth.field('field_tanggal').val('created_on');
				}
			});

			edthgspeth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthgspeth.dependent( 'hgspeth.id_hemxxmh', function ( val, data, callback ) {
				
				return {}
			}, {event: 'keyup change'});

            edthgspeth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hgspeth.id_htsptth
					if ( ! edthgspeth.field('hgspeth.id_htsptth').isMultiValue() ) {
						id_htsptth = edthgspeth.field('hgspeth.id_htsptth').val();
						if(!id_htsptth || id_htsptth == ''){
							edthgspeth.field('hgspeth.id_htsptth').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgspeth.id_htsptth

					// BEGIN of validasi hgspeth.tanggal_awal
					if ( ! edthgspeth.field('hgspeth.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthgspeth.field('hgspeth.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthgspeth.field('hgspeth.tanggal_awal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgspeth.tanggal_awal

					// BEGIN of validasi hgspeth.jumlah_siklus
					if ( ! edthgspeth.field('hgspeth.jumlah_siklus').isMultiValue() ) {
						jumlah_siklus = edthgspeth.field('hgspeth.jumlah_siklus').val();
						if(!jumlah_siklus || jumlah_siklus == ''){
							edthgspeth.field('hgspeth.jumlah_siklus').error( 'Wajib diisi!' );
						}
						if(jumlah_siklus <= 0 ){
							edthgspeth.field('hgspeth.jumlah_siklus').error( 'Inputan harus > 0' );
						}
						if(isNaN(jumlah_siklus) ){
							edthgspeth.field('hgspeth.jumlah_siklus').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hgspeth.jumlah_siklus
				}
				
				if ( edthgspeth.inError() ) {
					return false;
				}
			});
			
			edthgspeth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgspeth.field('finish_on').val(finish_on);
			});

			edthgspeth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// tblhgspeth.rows().deselect();
				tblhgspeth.ajax.reload(null, false);
			});

			//start datatables
			tblhgspeth = $('#tblhgspeth').DataTable( {
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
						targets: [2]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: [0,1,3,4,5,6]
					}
				],
				ajax: {
					url: "../../models/hgspeth/hgspeth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgspeth = show_inactive_status_hgspeth;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hgspeth.id",visible:false },
					{ data: "hgspeth.kode" },
					{ data: "hemxxmh_data" },
					{ data: "htsptth.nama" },
					{ 
						data: "hgspeth.jumlah_siklus" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "hgspeth.tanggal_awal" },
					{ data: "hgspeth.tanggal_akhir" },
					{ data: "hgspeth.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgspeth';
						$table       = 'tblhgspeth';
						$edt         = 'edthgspeth';
						$show_status = '_hgspeth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
					{
						text: '<i class="fa fa-google"></i>',
						name: 'btnGenerateJadwal',
						className: 'btn btn-outline',
						titleAttr: '',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 

							notifyprogress = $.notify({
								message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
							},{
								z_index: 9999,
								allow_dismiss: false,
								type: 'info',
								delay: 0
							});

							$.ajax( {
								url: "../../models/hgspeth/hgspeth_fn_gen_jadwal.php",
								dataType: 'json',
								type: 'POST',
								data: {
									id_transaksi_h: id_transaksi_h
								},
								success: function ( json ) {

									$.notify({
										message: json.data.message
									},{
										type: json.data.type_message
									});

									tblhgspeth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgspeth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );

			tblhgspeth.searchPanes.container().appendTo( '#searchPanes1' );
			
			tblhgspeth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhgspeth);

				tblhgspeth.button( 'btnGenerateJadwal:name' ).disable();
			} );
			
			tblhgspeth.on( 'select', function( e, dt, type, indexes ) {
				hgspeth_data    = tblhgspeth.row( { selected: true } ).data().hgspeth;
				id_hgspeth      = hgspeth_data.id;
				id_transaksi_h = id_hgspeth; // dipakai untuk general
				is_approve     = hgspeth_data.is_approve;
				is_nextprocess = hgspeth_data.is_nextprocess;
				is_jurnal      = hgspeth_data.is_jurnal;
				is_active      = hgspeth_data.is_active;

				id_htsptth_old = hgspeth_data.id_htsptth;
				id_hemxxmh_old = hgspeth_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhgspeth);

				tblhgspeth.button( 'btnGenerateJadwal:name' ).enable();
			} );

			tblhgspeth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgspeth = 0;
				id_htsptth_old = 0, id_hemxxmh_old;

				// atur hak akses
				CekDeselectHeaderH(tblhgspeth);

				tblhgspeth.button( 'btnGenerateJadwal:name' ).disable();
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
