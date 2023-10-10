<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'potongan_apd';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblpotongan_apd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/potongan_apd/fn/potongan_apd_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtpotongan_apd, tblpotongan_apd, show_inactive_status_potongan_apd = 0, id_potongan_apd;
		var id_hemxxmh_old = 0;
		var id_hpcxxmh_old = 0;
		is_need_approval = 1;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtpotongan_apd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/potongan_apd/potongan_apd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_potongan_apd = show_inactive_status_potongan_apd;
					}
				},
				table: "#tblpotongan_apd",
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
						def: "potongan_apd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "potongan_apd.is_active",
                        type: "hidden",
						def: 1
					}, 	
					{
						label: "Karyawan <sup class='text-danger'>*<sup>",
						name: "potongan_apd.id_hemxxmh",
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
					// {
					// 	label: "jenis <sup class='text-danger'>*<sup>",
					// 	name: "potongan_apd.id_hpcxxmh",
					// 	type: "select2",
					// 	opts: {
					// 		placeholder : "Select",
					// 		allowClear: true,
					// 		multiple: false,
					// 		ajax: {
					// 			url: "../../models/hpcxxmh/hpcxxmh_fn_opt.php",
					// 			dataType: 'json',
					// 			data: function (params) {
					// 				var query = {
					// 					id_hpcxxmh_old: id_hpcxxmh_old,
					// 					is_denda: 1,
					// 					search: params.term || '',
					// 					page: params.page || 1
					// 				}
					// 					return query;
					// 			},
					// 			processResults: function (data, params) {
					// 				return {
					// 					results: data.results,
					// 					pagination: {
					// 						more: true
					// 					}
					// 				};
					// 			},
					// 			cache: true,
					// 			minimumInputLength: 1,
					// 			maximum: 10,
					// 			delay: 500,
					// 			maximumSelectionLength: 5,
					// 			minimumResultsForSearch: -1,
					// 		},
					// 	}
					// }, 	
					{
						label: "Mulai Tanggal<sup class='text-danger'>*<sup>",
						name: "potongan_apd.tanggal",
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
						label: "Keterangan<sup class='text-danger'>*<sup>",
						name: "potongan_apd.keterangan",
						type: "textarea"
					}
				]
			} );

			edtpotongan_apd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpotongan_apd.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblpotongan_apd.rows().deselect();
				}
			});

			edtpotongan_apd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtpotongan_apd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi potongan_apd.id_hemxxmh 
					id_hemxxmh = edtpotongan_apd.field('potongan_apd.id_hemxxmh').val();
					if(!id_hemxxmh || id_hemxxmh == ''){
						edtpotongan_apd.field('potongan_apd.id_hemxxmh').error( 'Wajib diisi!' );
					}
					// END of validasi potongan_apd.id_hemxxmh 
					
					// BEGIN of validasi potongan_apd.id_hpcxxmh 
					// jenis = edtpotongan_apd.field('potongan_apd.id_hpcxxmh').val();
					// if(!jenis || jenis == ''){
					// 	edtpotongan_apd.field('potongan_apd.id_hpcxxmh').error( 'Wajib diisi!' );
					// }
					// END of validasi potongan_apd.id_hpcxxmh 
					
					// BEGIN of validasi potongan_apd.keterangan 
					keterangan = edtpotongan_apd.field('potongan_apd.keterangan').val();
					if(!keterangan || keterangan == ''){
						edtpotongan_apd.field('potongan_apd.keterangan').error( 'Wajib diisi!' );
					}
					// END of validasi potongan_apd.keterangan 
					
					// BEGIN of validasi potongan_apd.tanggal 
					tanggal = edtpotongan_apd.field('potongan_apd.tanggal').val();
					if(!tanggal || tanggal == ''){
						edtpotongan_apd.field('potongan_apd.tanggal').error( 'Wajib diisi!' );
					}
					// END of validasi potongan_apd.tanggal 
					
				}
				
				if ( edtpotongan_apd.inError() ) {
					return false;
				}
			});
			
			edtpotongan_apd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpotongan_apd.field('finish_on').val(finish_on);
			});

			//start datatables
			tblpotongan_apd = $('#tblpotongan_apd').DataTable( {
				ajax: {
					url: "../../models/potongan_apd/potongan_apd.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_potongan_apd = show_inactive_status_potongan_apd;
					}
				},
				responsive: false,
				order: [[ 0, "desc" ]],
				columns: [
					{ data: "potongan_apd.id",visible:false },
					{ data: "hemxxmh_data" },
					{ data: "hpcxxmh.nama" },
					{ data: "potongan_apd.tanggal" },
					{ data: "potongan_apd.keterangan" },
					{ 
						data: "potongan_apd.is_approve" ,
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
						$id_table    = 'id_potongan_apd';
						$table       = 'tblpotongan_apd';
						$edt         = 'edtpotongan_apd';
						$show_status = '_potongan_apd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= ['approve'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.potongan_apd.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblpotongan_apd.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblpotongan_apd);
			} );
			
			tblpotongan_apd.on( 'select', function( e, dt, type, indexes ) {
				potongan_apd_data    = tblpotongan_apd.row( { selected: true } ).data().potongan_apd;
				id_potongan_apd      = potongan_apd_data.id;
				id_transaksi_h = id_potongan_apd; // dipakai untuk general
				is_approve     = potongan_apd_data.is_approve;
				is_nextprocess = potongan_apd_data.is_nextprocess;
				is_jurnal      = potongan_apd_data.is_jurnal;
				is_active      = potongan_apd_data.is_active;
				id_hemxxmh_old      = potongan_apd_data.id_hemxxmh;
				id_hpcxxmh_old      = potongan_apd_data.id_hpcxxmh;

				// atur hak akses
				CekSelectHeaderH(tblpotongan_apd);
			} );

			tblpotongan_apd.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_potongan_apd = '';
				id_hemxxmh_old = 0;
				id_hpcxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblpotongan_apd);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
