<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'hesxxtd_hk';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhesxxtd_hk" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Grup HK</th>
                                <th>Tanggal Awal</th>
                                <th>Approval</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/hesxxtd_hk/fn/hesxxtd_hk_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthesxxtd_hk, tblhesxxtd_hk, show_inactive_status_hesxxtd_hk = 0, id_hesxxtd_hk;
		// ------------- end of default variable
		var is_need_approval = 1;
		var id_hemxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthesxxtd_hk = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/hesxxtd_hk/hesxxtd_hk.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd_hk = show_inactive_status_hesxxtd_hk;
					}
				},
				table: "#tblhesxxtd_hk",
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
						def: "hesxxtd_hk",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "hesxxtd_hk.is_active",
                        type: "hidden",
						def: 1
					},
					{
						label: "Nama<sup class='text-danger'>*<sup>",
						name: "hesxxtd_hk.id_hemxxmh",
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
						label: "Tanggal Awal <sup class='text-danger'>*<sup>",
						name: "hesxxtd_hk.tanggal_awal",
						type: "datetime",
						format: 'DD MMM YYYY',
						type: "datetime",
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Grup HK <sup class='text-danger'>*<sup>",
						name: "hesxxtd_hk.grup_hk",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "5 HK", "value": 1 },
							{ "label": "6 HK", "value": 2 }
						]
					},
					{
						label: "Keterangan",
						name: "hesxxtd_hk.keterangan",
						type: "textarea"
					}
				]
			} );

			edthesxxtd_hk.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd_hk.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhesxxtd_hk.rows().deselect();
					edthesxxtd_hk.field('kategori_dokumen').val('');
					edthesxxtd_hk.field('kategori_dokumen_value').val('');
					edthesxxtd_hk.field('field_tanggal').val('created_on');
				}
			});

			edthesxxtd_hk.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthesxxtd_hk.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					id_hemxxmh = edthesxxtd_hk.field('hesxxtd_hk.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edthesxxtd_hk.field('hesxxtd_hk.id_hemxxmh').error( 'Wajib diisi!' );
					}
					grup_hk = edthesxxtd_hk.field('hesxxtd_hk.grup_hk').val();
					if(!grup_hk || grup_hk == ''){
						edthesxxtd_hk.field('hesxxtd_hk.grup_hk').error( 'Wajib diisi!' );
					}
					tanggal_awal = edthesxxtd_hk.field('hesxxtd_hk.tanggal_awal').val();
					if(!tanggal_awal || tanggal_awal == ''){
						edthesxxtd_hk.field('hesxxtd_hk.tanggal_awal').error( 'Wajib diisi!' );
					}
				}
				
				if ( edthesxxtd_hk.inError() ) {
					return false;
				}
			});
			
			edthesxxtd_hk.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthesxxtd_hk.field('finish_on').val(finish_on);
			});

			edthesxxtd_hk.on( 'postSubmit', function (e, json, data, action, xhr) {
				tblhesxxtd_hk.ajax.reload(null,false);
			} );

			//start datatables
			tblhesxxtd_hk = $('#tblhesxxtd_hk').DataTable( {
				ajax: {
					url: "../../models/hesxxtd_hk/hesxxtd_hk.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_hesxxtd_hk = show_inactive_status_hesxxtd_hk;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "hesxxtd_hk.id",visible:false },
					{ data: "hesxxtd_hk.kode" },
					{ data: "hemxxmh_data" },
					{ 
						data: "hesxxtd_hk.grup_hk",
						render: function (data){ 	
							if (data == 1){
								return '5 HK';
							}else if(data == 2){
								return '6 HK';
							}else{
								return '';
							}
						} 
					},
					{ data: "hesxxtd_hk.tanggal_awal" },
					{ 
						data: "hesxxtd_hk.is_approve",
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo text-muted"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							} else {
								return '';
							}
						} 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_hesxxtd_hk';
						$table       = 'tblhesxxtd_hk';
						$edt         = 'edthesxxtd_hk';
						$show_status = '_hesxxtd_hk';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve','cancel_approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.hesxxtd_hk.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhesxxtd_hk.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhesxxtd_hk);
			} );
			
			tblhesxxtd_hk.on( 'select', function( e, dt, type, indexes ) {
				hesxxtd_hk_data    = tblhesxxtd_hk.row( { selected: true } ).data().hesxxtd_hk;
				id_hesxxtd_hk      = hesxxtd_hk_data.id;
				id_transaksi_h = id_hesxxtd_hk; // dipakai untuk general
				is_approve     = hesxxtd_hk_data.is_approve;
				is_nextprocess = hesxxtd_hk_data.is_nextprocess;
				is_jurnal      = hesxxtd_hk_data.is_jurnal;
				is_active      = hesxxtd_hk_data.is_active;
				id_hemxxmh_old      = hesxxtd_hk_data.id_hemxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhesxxtd_hk);
			} );

			tblhesxxtd_hk.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_hesxxtd_hk = '';
				id_hemxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhesxxtd_hk);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
