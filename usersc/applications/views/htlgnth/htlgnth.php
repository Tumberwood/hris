<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htlgnth';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtlgnth" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htlgnth/fn/htlgnth_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtlgnth, tblhtlgnth, show_inactive_status_htlgnth = 0, id_htlgnth;
		// ------------- end of default variable
		
		is_need_approval = 1, is_need_generate_kode = 1;

		$(document).ready(function() {
			//start datatables editor
			edthtlgnth = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/htlgnth/htlgnth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlgnth = show_inactive_status_htlgnth;
					}
				},
				table: "#tblhtlgnth",
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
						def: "htlgnth",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htlgnth.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htlgnth.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "htlgnth.nama"
					}, 	{
						label: "Keterangan",
						name: "htlgnth.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtlgnth.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlgnth.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtlgnth.rows().deselect();

					edthtlgnth.field('kategori_dokumen').val('');
					edthtlgnth.field('kategori_dokumen_value').val('');
					edthtlgnth.field('field_tanggal').val('tanggal');
				}
			});

			edthtlgnth.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtlgnth.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){

					// BEGIN of validasi htlgnth.tanggal
					if ( ! edthtlgnth.field('htlgnth.tanggal').isMultiValue() ) {
						tanggal = edthtlgnth.field('htlgnth.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtlgnth.field('htlgnth.tanggal').error( 'Wajib diisi!' );
						}

						// BEGIN of cek unik htlgnth.tanggal
						if(action == 'create'){
							id_htlgnth = 0;
						}
						tanggal_ymd = moment(tanggal).format('YYYY-MM-DD');
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'htlgnth',
								nama_field: 'tanggal',
								nama_field_value: '"'+tanggal_ymd+'"',
								id_transaksi: id_htlgnth
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edthtlgnth.field('htlgnth.tanggal').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik htlgnth.tanggal

					}
					// END of validasi htlgnth.tanggal
					
					// BEGIN of validasi htlgnth.nama
					if ( ! edthtlgnth.field('htlgnth.nama').isMultiValue() ) {
						htlgnth_nama = edthtlgnth.field('htlgnth.nama').val();
						if(!htlgnth_nama || htlgnth_nama == ''){
							edthtlgnth.field('htlgnth.nama').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htlgnth.nama
				}
				
				if ( edthtlgnth.inError() ) {
					return false;
				}
			});
			
			edthtlgnth.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtlgnth.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtlgnth = $('#tblhtlgnth').DataTable( {
				ajax: {
					url: "../../models/htlgnth/htlgnth.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htlgnth = show_inactive_status_htlgnth;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htlgnth.id",visible:false },
					{ data: "htlgnth.tanggal" },
					{ data: "htlgnth.nama" },
					{ data: "htlgnth.keterangan" },
					{ 
						data: "htlgnth.is_approve" ,
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == -9){
								return '<i class="fa fa-remove text-danger"></i>';
							}
						}
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htlgnth';
						$table       = 'tblhtlgnth';
						$edt         = 'edthtlgnth';
						$show_status = '_htlgnth';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h','approve'];
						$arr_buttons_approve 	= ['approve','cancel_approve','void'];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htlgnth.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtlgnth.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtlgnth);
			} );
			
			tblhtlgnth.on( 'select', function( e, dt, type, indexes ) {
				htlgnth_data    = tblhtlgnth.row( { selected: true } ).data().htlgnth;
				id_htlgnth      = htlgnth_data.id;
				id_transaksi_h = id_htlgnth; // dipakai untuk general
				is_approve     = htlgnth_data.is_approve;
				is_nextprocess = htlgnth_data.is_nextprocess;
				is_jurnal      = htlgnth_data.is_jurnal;
				is_active      = htlgnth_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblhtlgnth);
			} );

			tblhtlgnth.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htlgnth = '';

				// atur hak akses
				CekDeselectHeaderH(tblhtlgnth);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
