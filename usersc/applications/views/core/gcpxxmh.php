<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gcpxxmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgcpxxmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>BU</th>
                                <th>Perusahaan</th>
                                <th>Alamat</th>
                                <th>Kota</th>
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

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtgcpxxmh, tblgcpxxmh, show_inactive_status_gcpxxmh = 0, id_gcpxxmh;
		// ------------- end of default variable

		var id_gbuxxmh_old = 0, id_gctxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edtgcpxxmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/gcpxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gcpxxmh = show_inactive_status_gcpxxmh;
					}
				},
				table: "#tblgcpxxmh",
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
						def: "gcpxxmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "gcpxxmh.is_active",
                        type: "hidden",
						def: 1
					}, 	{
						label: "Badan Usaha",
						name: "gcpxxmh.id_gbuxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gbuxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gbuxxmh_old: id_gbuxxmh_old,
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
						label: "Perusahaan <sup class='text-danger'>*<sup>",
						name: "gcpxxmh.nama"
					}, 	{
						label: "Alamat <sup class='text-danger'>*<sup>",
						name: "gcpxxmh.alamat"
					},	{
						label: "Kota <sup class='text-danger'>*<sup>",
						name: "gcpxxmh.id_gctxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/core/gctxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_gctxxmh_old: id_gctxxmh_old,
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
					}
				]
			} );

			edtgcpxxmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgcpxxmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgcpxxmh.rows().deselect();
				}
			});

			edtgcpxxmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgcpxxmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

                    // BEGIN of validasi gcpxxmh.nama
					if ( ! edtgcpxxmh.field('gcpxxmh.nama').isMultiValue() ) {
						gcpxxmh_nama = edtgcpxxmh.field('gcpxxmh.nama').val();
						if(!gcpxxmh_nama || gcpxxmh_nama == ''){
							edtgcpxxmh.field('gcpxxmh.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gcpxxmh.nama

                    // BEGIN of validasi gcpxxmh.alamat
					if ( ! edtgcpxxmh.field('gcpxxmh.alamat').isMultiValue() ) {
						alamat = edtgcpxxmh.field('gcpxxmh.alamat').val();
						if(!alamat || alamat == ''){
							edtgcpxxmh.field('gcpxxmh.alamat').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gcpxxmh.alamat
					
					// BEGIN of validasi gcpxxmh.id_gctxxmh
					if ( ! edtgcpxxmh.field('gcpxxmh.id_gctxxmh').isMultiValue() ) {
						id_gctxxmh = edtgcpxxmh.field('gcpxxmh.id_gctxxmh').val();
						if(!id_gctxxmh || id_gctxxmh == ''){
							edtgcpxxmh.field('gcpxxmh.id_gctxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gcpxxmh.id_gctxxmh
				}
				
				if ( edtgcpxxmh.inError() ) {
					return false;
				}
			});

			edtgcpxxmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgcpxxmh.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblgcpxxmh = $('#tblgcpxxmh').DataTable( {
				ajax: {
					url: "../../models/core/gcpxxmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gcpxxmh = show_inactive_status_gcpxxmh;
					}
				},
				order: [[ 2, "asc" ],[1,"asc"]],
				columns: [
					{ data: "gcpxxmh.id",visible:false },
					{ data: "gbuxxmh.kode" },
					{ data: "gcpxxmh.nama" },
					{ data: "gcpxxmh.alamat" },
					{ data: "gctxxmh.nama" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gcpxxmh';
						$table       = 'tblgcpxxmh';
						$edt         = 'edtgcpxxmh';
						$show_status = '_gcpxxmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gcpxxmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgcpxxmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgcpxxmh);
			} );
			
			tblgcpxxmh.on( 'select', function( e, dt, type, indexes ) {
				gcpxxmh_data    = tblgcpxxmh.row( { selected: true } ).data().gcpxxmh;
				id_gcpxxmh      = gcpxxmh_data.id;
				id_transaksi_h = id_gcpxxmh; // dipakai untuk general
				is_approve     = gcpxxmh_data.is_approve;
				is_nextprocess = gcpxxmh_data.is_nextprocess;
				is_jurnal      = gcpxxmh_data.is_jurnal;
				is_active      = gcpxxmh_data.is_active;

				id_gbuxxmh_old = gcpxxmh_data.id_gbuxxmh;
				id_gctxxmh_old = gcpxxmh_data.id_gctxxmh;

				// atur hak akses
				CekSelectHeaderH(tblgcpxxmh);
			} );

			tblgcpxxmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gcpxxmh = 0;
				id_gbuxxmh_old = 0;
				id_gctxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblgcpxxmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
