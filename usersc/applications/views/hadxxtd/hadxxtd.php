<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hadxxtd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhadxxtd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Department</th>
                                <th>Jabatan</th>
                                <th>Jenis</th>
                                <th>Pelanggaran</th>
                                <th>Tanggal Berlaku</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hadxxtd/fn/hadxxtd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthadxxtd, tblhadxxtd, show_inactive_status_hadxxtd = 0, id_hadxxtd;
		// ------------- end of default variable

		var id_hemxxmh_old = 0, id_havxxmh_old =0, id_hadxxmh_old = 0;
		var id_hadxxmh_saran = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthadxxtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hadxxtd/hadxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxtd = show_inactive_status_hadxxtd;
					}
				},
				table: "#tblhadxxtd",
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
						def: "hadxxtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hadxxtd.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Nama",
						name: "hadxxtd.id_hemxxmh",
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
						label: "Pelanggaran <sup class='text-danger'>*<sup>",
						name: "hadxxtd.id_havxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/havxxmh/havxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_havxxmh_old: id_havxxmh_old,
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
						label: "Tindakan <sup class='text-danger'>*<sup>",
						name: "hadxxtd.id_hadxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hadxxmh/hadxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hadxxmh_saran: id_hadxxmh_saran,
										id_hadxxmh_old: id_hadxxmh_old,
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
						label: "Tanggal Mulai Berlaku",
						name: "hadxxtd.tanggal_awal",
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
						label: "Tanggal Selesai Berlaku",
						name: "hadxxtd.tanggal_akhir",
						type: "datetime",
						format: 'DD MMM YYYY'
					},
					{
						label: "Bukti",
						name: "hadxxtd.id_files_bukti",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthadxxtd.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada lampiran'
					},
					{
						label: "Dokumen",
						name: "hadxxtd.id_files_dokumen",
						type: "upload",
						display: function ( fileId, counter ) {
							if(fileId > 0){
								return '<img src="'+edthadxxtd.file( 'files', fileId ).web_path+'"/>';
							}
						},
						noFileText: 'Belum ada lampiran'
					},
					{
						label: "Keterangan",
						name: "hadxxtd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthadxxtd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxtd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhadxxtd.rows().deselect();
				}
				
				edthadxxtd.field('hadxxtd.tanggal_akhir').disable();

			});

			edthadxxtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edthadxxtd.dependent( 'hadxxtd.id_havxxmh', function ( val, data, callback ) {
				havxxmh_load_hadxxmh ();
				hadxxmh_fn_tanggal_akhir();
				return {}
			}, {event: 'keyup change'});

			edthadxxtd.dependent( 'hadxxtd.id_hadxxmh', function ( val, data, callback ) {
				hadxxmh_fn_tanggal_akhir();
				return {}
			}, {event: 'keyup change'});

			edthadxxtd.dependent( 'hadxxtd.tanggal_awal', function ( val, data, callback ) {
				hadxxmh_fn_tanggal_akhir();
				return {}
			}, {event: 'keyup change'});

            edthadxxtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi hadxxtd.id_hemxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_hemxxmh').isMultiValue() ) {
						id_hemxxmh = edthadxxtd.field('hadxxtd.id_hemxxmh').val();
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthadxxtd.field('hadxxtd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_hemxxmh

					// BEGIN of validasi hadxxtd.id_havxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_havxxmh').isMultiValue() ) {
						id_havxxmh = edthadxxtd.field('hadxxtd.id_havxxmh').val();
						if(!id_havxxmh || id_havxxmh == ''){
							edthadxxtd.field('hadxxtd.id_havxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_havxxmh

					// BEGIN of validasi hadxxtd.id_hadxxmh
					if ( ! edthadxxtd.field('hadxxtd.id_hadxxmh').isMultiValue() ) {
						id_hadxxmh = edthadxxtd.field('hadxxtd.id_hadxxmh').val();
						if(!id_hadxxmh || id_hadxxmh == ''){
							edthadxxtd.field('hadxxtd.id_hadxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi hadxxtd.id_hadxxmh
				}
				
				if ( edthadxxtd.inError() ) {
					return false;
				}
			});
			
			edthadxxtd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthadxxtd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhadxxtd = $('#tblhadxxtd').DataTable( {
				ajax: {
					url: "../../models/hadxxtd/hadxxtd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hadxxtd = show_inactive_status_hadxxtd;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hadxxtd.id",visible:false },
					{ data: "hadxxtd.kode" },
					{ data: "hemxxmh_data" },
					{ data: "hodxxmh.nama" },
					{ data: "hetxxmh.nama" },
					{ data: "hadxxmh.nama" },
					{ data: "havxxmh.nama" },
					{ data: "hadxxtd.tanggal_awal" },
					{ data: "hadxxtd.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hadxxtd';
						$table       = 'tblhadxxtd';
						$edt         = 'edthadxxtd';
						$show_status = '_hadxxtd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hadxxtd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhadxxtd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhadxxtd);
			} );
			
			tblhadxxtd.on( 'select', function( e, dt, type, indexes ) {
				hadxxtd_data    = tblhadxxtd.row( { selected: true } ).data().hadxxtd;
				id_hadxxtd      = hadxxtd_data.id;
				id_transaksi_h = id_hadxxtd; // dipakai untuk general
				is_approve     = hadxxtd_data.is_approve;
				is_nextprocess = hadxxtd_data.is_nextprocess;
				is_jurnal      = hadxxtd_data.is_jurnal;
				is_active      = hadxxtd_data.is_active;

				id_hemxxmh_old      = hadxxtd_data.id_hemxxmh;
				id_havxxmh_old      = hadxxtd_data.id_havxxmh;
				id_hadxxmh_old      = hadxxtd_data.id_hadxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhadxxtd);
			} );

			tblhadxxtd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hadxxtd = 0;
				id_havxxmh_old = 0;
				id_hadxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhadxxtd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
