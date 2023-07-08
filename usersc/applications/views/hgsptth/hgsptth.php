<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hgsptth';
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
                    <table id="tblhgsptth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hgsptth/fn/hgsptth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthgsptth, tblhgsptth, show_inactive_status_hgsptth = 0, id_hgsptth;
		// ------------- end of default variable

		var id_htsptth_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthgsptth = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hgsptth/hgsptth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth = show_inactive_status_hgsptth;
					}
				},
				table: "#tblhgsptth",
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
						def: "hgsptth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hgsptth.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Pola <sup class='text-danger'>*<sup>",
						name: "hgsptth.id_htsptth",
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
					},	{
						label: "Siklus  <sup class='text-danger'>*<sup>",
						name: "hgsptth.jumlah_siklus"
					}, 	{
						label: "Tanggal Awal",
						name: "hgsptth.tanggal_awal",
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
						label: "Keterangan",
						name: "hgsptth.keterangan",
						type: "textarea"
					}
				]
			} );

			edthgsptth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhgsptth.rows().deselect();

					edthgsptth.field('kategori_dokumen').val('');
					edthgsptth.field('kategori_dokumen_value').val('');
					edthgsptth.field('field_tanggal').val('created_on');
				}
			});

			edthgsptth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthgsptth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi hgsptth.id_htsptth
					if ( ! edthgsptth.field('hgsptth.id_htsptth').isMultiValue() ) {
						id_htsptth = edthgsptth.field('hgsptth.id_htsptth').val();
						if(!id_htsptth || id_htsptth == ''){
							edthgsptth.field('hgsptth.id_htsptth').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgsptth.id_htsptth

					// BEGIN of validasi hgsptth.tanggal_awal
					if ( ! edthgsptth.field('hgsptth.tanggal_awal').isMultiValue() ) {
						tanggal_awal = edthgsptth.field('hgsptth.tanggal_awal').val();
						if(!tanggal_awal || tanggal_awal == ''){
							edthgsptth.field('hgsptth.tanggal_awal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hgsptth.tanggal_awal

					// BEGIN of validasi hgsptth.jumlah_siklus
					if ( ! edthgsptth.field('hgsptth.jumlah_siklus').isMultiValue() ) {
						jumlah_siklus = edthgsptth.field('hgsptth.jumlah_siklus').val();
						if(!jumlah_siklus || jumlah_siklus == ''){
							edthgsptth.field('hgsptth.jumlah_siklus').error( 'Wajib diisi!' );
						}
						if(jumlah_siklus <= 0 ){
							edthgsptth.field('hgsptth.jumlah_siklus').error( 'Inputan harus > 0' );
						}
						if(isNaN(jumlah_siklus) ){
							edthgsptth.field('hgsptth.jumlah_siklus').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi hgsptth.jumlah_siklus
				}
				
				if ( edthgsptth.inError() ) {
					return false;
				}
			});
			
			edthgsptth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthgsptth.field('finish_on').val(finish_on);
			});

			edthgsptth.on( 'postSubmit', function (e, json, data, action, xhr) {
				// tblhgsptth.rows().deselect();
				tblhgsptth.ajax.reload(null, false);
			});

			//start datatables
			tblhgsptth = $('#tblhgsptth').DataTable( {
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
					url: "../../models/hgsptth/hgsptth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hgsptth = show_inactive_status_hgsptth;
					}
				},
				order: [[ 1, "desc" ]],
				columns: [
					{ data: "hgsptth.id",visible:false },
					{ data: "hgsptth.kode" },
					{ data: "htsptth.nama" },
					{ 
						data: "hgsptth.jumlah_siklus" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "hgsptth.tanggal_awal" },
					{ data: "hgsptth.tanggal_akhir" },
					{ data: "hgsptth.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hgsptth';
						$table       = 'tblhgsptth';
						$edt         = 'edthgsptth';
						$show_status = '_hgsptth';
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
								url: "../../models/hgsptth/hgsptth_fn_gen_jadwal.php",
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

									tblhgsptth.ajax.reload(function ( json ) {
										notifyprogress.close();
									}, false);
								}
							} );
						}
					},
				],
				rowCallback: function( row, data, index ) {
					if ( data.hgsptth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );

			tblhgsptth.searchPanes.container().appendTo( '#searchPanes1' );
			
			tblhgsptth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhgsptth);

				tblhgsptth.button( 'btnGenerateJadwal:name' ).disable();
			} );
			
			tblhgsptth.on( 'select', function( e, dt, type, indexes ) {
				hgsptth_data    = tblhgsptth.row( { selected: true } ).data().hgsptth;
				id_hgsptth      = hgsptth_data.id;
				id_transaksi_h = id_hgsptth; // dipakai untuk general
				is_approve     = hgsptth_data.is_approve;
				is_nextprocess = hgsptth_data.is_nextprocess;
				is_jurnal      = hgsptth_data.is_jurnal;
				is_active      = hgsptth_data.is_active;

				id_htsptth_old = hgsptth_data.id_htsptth;

				// atur hak akses
				CekSelectHeaderH(tblhgsptth);

				tblhgsptth.button( 'btnGenerateJadwal:name' ).enable();
			} );

			tblhgsptth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hgsptth = 0;
				id_htsptth_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhgsptth);

				tblhgsptth.button( 'btnGenerateJadwal:name' ).disable();
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
