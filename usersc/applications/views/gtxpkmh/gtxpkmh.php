<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'gtxpkmh';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblgtxpkmh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Keterangan</th>
                                <th>Amount</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/gtxpkmh/fn/gtxpkmh_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtgtxpkmh, tblgtxpkmh, show_inactive_status_gtxpkmh = 0, id_gtxpkmh;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtgtxpkmh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/gtxpkmh/gtxpkmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gtxpkmh = show_inactive_status_gtxpkmh;
					}
				},
				table: "#tblgtxpkmh",
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
						def: "gtxpkmh",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "gtxpkmh.is_active",
                        type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "gtxpkmh.kode"
					}, 	{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "gtxpkmh.keterangan",
						type: "textarea"
					}, 	{
						label: "Nominal<sup class='text-danger'>*<sup>",
						name: "gtxpkmh.amount"
					}
				]
			} );

			edtgtxpkmh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgtxpkmh.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblgtxpkmh.rows().deselect();
				}
			});

			edtgtxpkmh.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edtgtxpkmh.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi gtxpkmh.kode
					if ( ! edtgtxpkmh.field('gtxpkmh.kode').isMultiValue() ) {
						kode = edtgtxpkmh.field('gtxpkmh.kode').val();
						if(!kode || kode == ''){
							edtgtxpkmh.field('gtxpkmh.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik gtxpkmh.kode
						if(action == 'create'){
							id_gtxpkmh = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'gtxpkmh',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_gtxpkmh
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtgtxpkmh.field('gtxpkmh.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik gtxpkmh.kode
					}
					// END of validasi gtxpkmh.kode
					
					// BEGIN of validasi gtxpkmh.keterangan
					if ( ! edtgtxpkmh.field('gtxpkmh.keterangan').isMultiValue() ) {
						keterangan = edtgtxpkmh.field('gtxpkmh.keterangan').val();
						if(!keterangan || keterangan == ''){
							edtgtxpkmh.field('gtxpkmh.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi gtxpkmh.keterangan
					
					// BEGIN of validasi gtxpkmh.amount
					if ( ! edtgtxpkmh.field('gtxpkmh.amount').isMultiValue() ) {
						amount = edtgtxpkmh.field('gtxpkmh.amount').val();
						if(!amount || amount == ''){
							edtgtxpkmh.field('gtxpkmh.amount').error( 'Wajib diisi!' );
						}
						if(amount <= 0 ){
							edtgtxpkmh.field('gtxpkmh.amount').error( 'Inputan harus > 0' );
						}
						
						// validasi angka
						if(isNaN(amount) ){
							edtgtxpkmh.field('gtxpkmh.amount').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi gtxpkmh.amount
				}
				
				if ( edtgtxpkmh.inError() ) {
					return false;
				}
			});
			
			edtgtxpkmh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtgtxpkmh.field('finish_on').val(finish_on);
			});

			//start datatables
			tblgtxpkmh = $('#tblgtxpkmh').DataTable( {
				ajax: {
					url: "../../models/gtxpkmh/gtxpkmh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_gtxpkmh = show_inactive_status_gtxpkmh;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "gtxpkmh.id",visible:false },
					{ data: "gtxpkmh.kode" },
					{ data: "gtxpkmh.keterangan" },
					{ 
						data: "gtxpkmh.amount",
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right" 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_gtxpkmh';
						$table       = 'tblgtxpkmh';
						$edt         = 'edtgtxpkmh';
						$show_status = '_gtxpkmh';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.gtxpkmh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblgtxpkmh.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblgtxpkmh);
			} );
			
			tblgtxpkmh.on( 'select', function( e, dt, type, indexes ) {
				gtxpkmh_data    = tblgtxpkmh.row( { selected: true } ).data().gtxpkmh;
				id_gtxpkmh      = gtxpkmh_data.id;
				id_transaksi_h = id_gtxpkmh; // dipakai untuk general
				is_approve     = gtxpkmh_data.is_approve;
				is_nextprocess = gtxpkmh_data.is_nextprocess;
				is_jurnal      = gtxpkmh_data.is_jurnal;
				is_active      = gtxpkmh_data.is_active;

				// atur hak akses
				CekSelectHeaderH(tblgtxpkmh);
			} );

			tblgtxpkmh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_gtxpkmh = '';

				// atur hak akses
				CekDeselectHeaderH(tblgtxpkmh);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
