<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hesxxtd';
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
                    <table id="tblhesxxtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>NIK Baru</th>
                                <th>Department</th>
                                <th>Jabatan</th>
                                <th>Jenis</th>
                                <th>Keputusan</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Bulan Selesai</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hesxxtd/fn/hesxxtd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxtd, tblhesxxtd, show_inactive_status_hesxxtd = 0, id_hesxxtd;
		// ------------- end of default variable

		var id_hemxxmh_old = 0, id_havxxmh_old =0, id_hadxxmh_old = 0;
		var id_hadxxmh_saran = 0;
		var id_hesxxmh_old = 0;
		var id_hesxxmh_old_tetap = 0;
		var is_need_approval = 1;
		var is_need_generate_kode = 1;
		
		$(document).ready(function() {
			//start datatables editor
			edthesxxtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hesxxtd/hesxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd = show_inactive_status_hesxxtd;
					}
				},
				table: "#tblhesxxtd",
				fields: [ 
					{
						// untuk generate_kode
						label: "kategori_dokumen",
						name: "kategori_dokumen",
						type: "hidden"
					},	{
						// untuk generate_kode
						label: "kategori_dokumen_value",
						name: "kategori_dokumen_value",
						type: "hidden"
					},	{
						// untuk generate_kode
						label: "field_tanggal",
						name: "field_tanggal",
						type: "hidden"
					},
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
						def: "hesxxtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hesxxtd.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Jenis<sup class='text-danger'>*<sup>",
						name: "hesxxtd.id_hesxxmh",
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
										id_hesxxmh_old: id_hesxxmh_old,
										id_hesxxmh_tetap: '2,3,4',
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
						label: "Nama<sup class='text-danger'>*<sup>",
						name: "hesxxtd.id_hemxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hesxxtd/hemxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									id_hesxxmh = edthesxxtd.field('hesxxtd.id_hesxxmh').val();
									console.log(id_hesxxmh);
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
										id_hesxxmh: id_hesxxmh,
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
						label: "Status Ke",
						name: "hesxxtd.status_ke",
						type: "readonly"
					},
					{
						label: "Tanggal Awal NIK Lama",
						name: "tanggal_masuk",
						type: "readonly"
					},
					{
						label: "Tanggal Akhir NIK Lama",
						name: "tanggal_keluar",
						type: "readonly"
					},
					{
						label: "Keputusan<sup class='text-danger'>*<sup>",
						name: "hesxxtd.keputusan",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Rekontrak", "value": "Rekontrak" },
							{ "label": "Kontrak", "value": "Kontrak" },
							{ "label": "Perpanjangan Latihan", "value": "Perpanjangan Latihan" },
							{ "label": "Tetap", "value": "Tetap" },
							{ "label": "Reguler", "value": "Reguler" },
							{ "label": "Terminasi", "value": "Terminasi" }
						]
					},	
					{
						label: "Tanggal Mulai",
						name: "hesxxtd.tanggal_mulai",
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
						name: "hesxxtd.tanggal_selesai",
						type: "datetime",
						format: 'DD MMM YYYY',
						type: "datetime",
						def: function () { 
							var currentDate = new Date();
							currentDate.setMonth(currentDate.getMonth() + 6);
							return currentDate;
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Jenis Tetap<sup class='text-danger'>*<sup>",
						name: "hesxxtd.id_hesxxmh_tetap",
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
										id_hesxxmh_old: id_hesxxmh_old_tetap,
										id_hesxxmh_tetap: '1,5',
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
						label: "NIK Baru<sup class='text-danger'>*<sup>",
						name: "hesxxtd.nik_baru"
					},
					{
						label: "Keterangan",
						name: "hesxxtd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthesxxtd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd.field('start_on').val(start_on);
				edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').hide();
				
				if(action == 'create'){
					tblhesxxtd.rows().deselect();
					edthesxxtd.field('kategori_dokumen').val('');
					edthesxxtd.field('kategori_dokumen_value').val('');
					edthesxxtd.field('field_tanggal').val('created_on');			// jika menggunakan created_on
				}

			});

			edthesxxtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthesxxtd.dependent( 'hesxxtd.id_hesxxmh', function ( val, data, callback ) {
				edthesxxtd.field('hesxxtd.id_hemxxmh').val('');
				edthesxxtd.field('hesxxtd.status_ke').val('');
				return {}
			}, {event: 'keyup change'});

			edthesxxtd.dependent( 'hesxxtd.tanggal_mulai', function ( val, data, callback ) {
				tanggal_selesai = moment(val).add('month', 6).subtract(1, 'day').format('DD MMM YYYY');
				edthesxxtd.field('hesxxtd.tanggal_selesai').val(tanggal_selesai);
				return {}
			}, {event: 'keyup change'});
			
			edthesxxtd.dependent( 'hesxxtd.id_hemxxmh', function ( val, data, callback ) {
				if (val > 0) {
					find_status();
				}
				return {}
			}, {event: 'keyup change'});
			
			edthesxxtd.dependent( 'hesxxtd.keputusan', function ( val, data, callback ) {
				if (val == 'Tetap') {
					edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').show();
				} else {
					edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').hide();
				}

				if (val == 'Terminasi') {
					edthesxxtd.field('hesxxtd.tanggal_selesai').label("Tanggal Selesai <sup class='text-danger'>*<sup>")
					edthesxxtd.field('hesxxtd.tanggal_mulai').val('');
					edthesxxtd.field('hesxxtd.tanggal_mulai').hide();
					edthesxxtd.field('hesxxtd.nik_baru').val('');
					edthesxxtd.field('hesxxtd.nik_baru').hide();
					
				} else {
					edthesxxtd.field('hesxxtd.tanggal_selesai').label("Tanggal Selesai")
					edthesxxtd.field('hesxxtd.tanggal_mulai').show();
					edthesxxtd.field('hesxxtd.tanggal_selesai').show();
					edthesxxtd.field('hesxxtd.tanggal_mulai').val();
					edthesxxtd.field('hesxxtd.tanggal_selesai').val();
					edthesxxtd.field('hesxxtd.nik_baru').val();
					edthesxxtd.field('hesxxtd.nik_baru').show();
					
				}

				//Jika Kontrak maka tanggal_awal baru == tanggal_akhir lama + 2;
				find_status();
				
				return {}
			}, {event: 'keyup change'});

            edthesxxtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hesxxtd.id_hemxxmh
					id_hemxxmh = edthesxxtd.field('hesxxtd.id_hemxxmh').val();
					if ( ! edthesxxtd.field('hesxxtd.id_hemxxmh').isMultiValue() ) {
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthesxxtd.field('hesxxtd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hesxxtd.id_hemxxmh

					// BEGIN of validasi hesxxtd.id_hesxxmh
					if ( ! edthesxxtd.field('hesxxtd.id_hesxxmh').isMultiValue() ) {
						id_hesxxmh = edthesxxtd.field('hesxxtd.id_hesxxmh').val();
						if(!id_hesxxmh || id_hesxxmh == ''){
							edthesxxtd.field('hesxxtd.id_hesxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hesxxtd.id_hesxxmh

					// BEGIN of validasi hesxxtd.keputusan
					keputusan = edthesxxtd.field('hesxxtd.keputusan').val();
					if ( ! edthesxxtd.field('hesxxtd.keputusan').isMultiValue() ) {
						if(!keputusan || keputusan == ''){
							edthesxxtd.field('hesxxtd.keputusan').error( 'Wajib diisi!' );
						}
					}
					
					if (keputusan == 'Tetap') {
						if ( ! edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').isMultiValue() ) {
							id_hesxxmh_tetap = edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').val();
							if(!id_hesxxmh_tetap || id_hesxxmh_tetap == ''){
								edthesxxtd.field('hesxxtd.id_hesxxmh_tetap').error( 'Wajib diisi!' );
							}
						}
					}
					
					
					// END of validasi hesxxtd.keputusan

					// BEGIN of validasi hesxxtd.nik_baru 
					if (keputusan != 'Terminasi') {
						
						nik_baru = edthesxxtd.field('hesxxtd.nik_baru').val();
						if(!nik_baru || nik_baru == ''){
							edthesxxtd.field('hesxxtd.nik_baru').error( 'Wajib diisi!' );
						}
						if(nik_baru <= 0 ){
							edthesxxtd.field('hesxxtd.nik_baru').error( 'Inputan harus > 0' );
						}
						if(isNaN(nik_baru) ){
							edthesxxtd.field('hesxxtd.nik_baru').error( 'Inputan harus berupa Angka!' );
						}
						if(nik_baru.length != 8 ){
							edthesxxtd.field('hesxxtd.nik_baru').error( 'Inputan harus 8 Digit Angka!' );
						}

						//BEGIN of cek unik hesxxtd.kode
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
									nama_field_value: '"' + nik_baru + '"',
									id_transaksi: id_hemxxmh
								},
								success: function ( json ) {
									if (json.data.count >= 1) {
										edthesxxtd.field('hesxxtd.nik_baru').error( 'Data NIK tidak boleh kembar!' );		
									}
								}
							} );
						// END of validasi hesxxtd.nik_baru 
					} 

					if (keputusan == 'Terminasi') {
						tanggal_selesai = edthesxxtd.field('hesxxtd.tanggal_selesai').val();
						// console.log(tanggal_selesai);
						if(tanggal_selesai == null || tanggal_selesai == "Invalid date"){
							edthesxxtd.field('hesxxtd.tanggal_selesai').error( 'Wajib diisi!' );
						}
					}
				}
				
				if ( edthesxxtd.inError() ) {
					return false;
				}
			});
			
			edthesxxtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd.field('finish_on').val(finish_on);
			});

			edthesxxtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblhesxxtd.rows().deselect();
				tblhesxxtd.ajax.reload(null, false);
			} );

			//start datatables
			tblhesxxtd = $('#tblhesxxtd').DataTable( {
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
						targets: [1,2,4,5,6,7,8,9,10]
					},
					{
						searchPanes:{
							show: false,
						},
						targets: '_all'
					},
				],
				ajax: {
					url: "../../models/hesxxtd/hesxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd = show_inactive_status_hesxxtd;
					}
				},
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "hesxxtd.id",visible:false },
					{ data: "hesxxtd.kode" },
					{ data: "hemxxmh.nama" },
					{ data: "hesxxtd.nik_baru" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "hesxxmh.nama" },
					{ data: "hesxxtd.keputusan" },
					{ data: "hesxxtd.tanggal_mulai" },
					{ data: "hesxxtd.tanggal_selesai" },
					{ 
						data: "hesxxtd.tanggal_selesai",
						render: function (data, type, row) {
							if (!data) return "";
							let date = new Date(data);
							if (isNaN(date)) return data; // kalau bukan tanggal valid, tampilkan apa adanya
							return date.toLocaleString('en-US', { month: 'short', year: 'numeric' }); 
						}
					},
					{ data: "hesxxtd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hesxxtd';
						$table       = 'tblhesxxtd';
						$edt         = 'edthesxxtd';
						$show_status = '_hesxxtd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hesxxtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				},
				initComplete: function() {
					this.api().searchPanes.rebuildPane();
				}
			} );

			tblhesxxtd.searchPanes.container().appendTo( '#searchPanes1' );
			
			tblhesxxtd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhesxxtd);
			} );
			
			tblhesxxtd.on( 'select', function( e, dt, type, indexes ) {
				hesxxtd_data    = tblhesxxtd.row( { selected: true } ).data().hesxxtd;
				id_hesxxtd      = hesxxtd_data.id;
				id_transaksi_h = id_hesxxtd; // dipakai untuk general
				is_approve     = hesxxtd_data.is_approve;
				is_nextprocess = hesxxtd_data.is_nextprocess;
				is_jurnal      = hesxxtd_data.is_jurnal;
				is_active      = hesxxtd_data.is_active;

				id_hesxxmh_old      = hesxxtd_data.id_hesxxmh;
				id_hesxxmh_old_tetap      = hesxxtd_data.id_hesxxmh_tetap;
				id_hemxxmh_old      = hesxxtd_data.id_hemxxmh;
				id_havxxmh_old      = hesxxtd_data.id_havxxmh;
				id_hadxxmh_old      = hesxxtd_data.id_hadxxmh;
				find_status();
				// atur hak akses
				CekSelectHeaderH(tblhesxxtd);
			} );

			tblhesxxtd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hesxxtd = 0;
				id_hesxxmh_old_tetap = 0;
				id_hesxxmh_old = 0;
				id_hemxxmh_old = 0;
				id_havxxmh_old = 0;
				id_hadxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhesxxtd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
