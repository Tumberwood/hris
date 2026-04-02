<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'htpr_sub_tipe';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtpr_sub_tipe" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipe</th>
                                <th>Sub Tipe</th>
                                <th>Status</th>
                                <th>Hari</th>
                                <th>Tanggal Efektif</th>
                                <th>Lembur Mati / Jam</th>
                                <th>Potongan Absen</th>
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

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/htpr_sub_tipe/fn/htpr_sub_tipe_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edthtpr_sub_tipe, tblhtpr_sub_tipe, show_inactive_status_htpr_sub_tipe = 0, id_htpr_sub_tipe;
		// ------------- end of default variable
		var id_heyxxmh_old = 0;
		var id_heyxxmd_old = 0;
		var id_hesxxmh_old = 0;
		
		$(document).ready(function() {
			//start datatables editor
			edthtpr_sub_tipe = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htpr_sub_tipe/htpr_sub_tipe.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_sub_tipe = show_inactive_status_htpr_sub_tipe;
					}
				},
				table: "#tblhtpr_sub_tipe",
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
						def: "htpr_sub_tipe",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htpr_sub_tipe.is_active",
                        type: "hidden",
						def: 1
					},	
					
					{
						label: "Tipe <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.id_heyxxmh",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmh_old: id_heyxxmh_old,
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
						label: "Sub Tipe <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.id_heyxxmd",
						type: "select2",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/heyxxmd/heyxxmd_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_heyxxmd_old: id_heyxxmd_old,
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
						label: "Status <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.id_hesxxmh",
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
						label: "Grup Hari Kerja <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.grup_hk",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "5 HK", "value": 1 },
							{ "label": "6 HK", "value": 2 }
						]
					},
					{
						label: "Tanggal Efektif",
						name: "htpr_sub_tipe.tanggal_efektif",
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
						label: "Lembur Mati / Jam <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.nominal_lembur"
					}, 	
					{
						label: "Potongan Absen <sup class='text-danger'>*<sup>",
						name: "htpr_sub_tipe.nominal_pot_absen"
					}, 	
					{
						label: "Keterangan",
						name: "htpr_sub_tipe.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtpr_sub_tipe.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_sub_tipe.field('start_on').val(start_on);
				
				if(action == 'create'){
					tblhtpr_sub_tipe.rows().deselect();
				}
			});

			edthtpr_sub_tipe.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

            edthtpr_sub_tipe.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					const requiredFields = [
						'id_heyxxmh',
						'id_heyxxmd',
						'id_hesxxmh',
						'grup_hk',
						'tanggal_efektif',
					]
					requiredFields.forEach(fields => {
						if ( ! edthtpr_sub_tipe.field('htpr_sub_tipe.'+fields).isMultiValue() ) {
								if(!edthtpr_sub_tipe.field('htpr_sub_tipe.'+fields).val() || edthtpr_sub_tipe.field('htpr_sub_tipe.'+fields).val() == ''){
								edthtpr_sub_tipe.field('htpr_sub_tipe.'+fields).error( 'Wajib diisi!' );
							}
						}
					});

					// BEGIN of validasi htpr_sub_tipe.nominal_lembur
					if ( ! edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_lembur').isMultiValue() ) {
						nominal_lembur = edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_lembur').val();
						if(!nominal_lembur || nominal_lembur == ''){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_lembur').error( 'Wajib diisi!' );
						}
						if(nominal_lembur <= 0 ){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_lembur').error( 'Inputan harus > 0' );
						}
						if(isNaN(nominal_lembur) ){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_lembur').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htpr_sub_tipe.nominal_lembur

					// BEGIN of validasi htpr_sub_tipe.nominal_pot_absen
					if ( ! edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_pot_absen').isMultiValue() ) {
						nominal_pot_absen = edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_pot_absen').val();
						if(!nominal_pot_absen || nominal_pot_absen == ''){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_pot_absen').error( 'Wajib diisi!' );
						}
						if(nominal_pot_absen <= 0 ){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_pot_absen').error( 'Inputan harus > 0' );
						}
						if(isNaN(nominal_pot_absen) ){
							edthtpr_sub_tipe.field('htpr_sub_tipe.nominal_pot_absen').error( 'Inputan harus berupa Angka!' );
						}
					}
					// END of validasi htpr_sub_tipe.nominal_pot_absen
				}
				
				if ( edthtpr_sub_tipe.inError() ) {
					return false;
				}
			});
			
			edthtpr_sub_tipe.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtpr_sub_tipe.field('finish_on').val(finish_on);
			});

			//start datatables
			tblhtpr_sub_tipe = $('#tblhtpr_sub_tipe').DataTable( {
				ajax: {
					url: "../../models/htpr_sub_tipe/htpr_sub_tipe.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_htpr_sub_tipe = show_inactive_status_htpr_sub_tipe;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "htpr_sub_tipe.id",visible:false },
					{ data: "heyxxmh.nama" },
					{ data: "heyxxmd.nama" },
					{ data: "hesxxmh.nama" },
					{ 
						data: "htpr_sub_tipe.grup_hk",
						render: function (data){
							if (data == 0){
								return '';
							}else if(data == 1){
								return '5HK';
							}else if(data == 2){
								return '6HK';
							}else{
								return '<span class="text-danger"> Data Invalid</span>';
							}
						}
					},
					{ data: "htpr_sub_tipe.tanggal_efektif" },
					{ 
						data: "htpr_sub_tipe.nominal_lembur" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ 
						data: "htpr_sub_tipe.nominal_pot_absen" ,
						render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
						class: "text-right"
					},
					{ data: "htpr_sub_tipe.keterangan" }
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htpr_sub_tipe';
						$table       = 'tblhtpr_sub_tipe';
						$edt         = 'edthtpr_sub_tipe';
						$show_status = '_htpr_sub_tipe';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['create', 'edit', 'nonaktif_h'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.htpr_sub_tipe.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );
			
			tblhtpr_sub_tipe.on( 'init', function () {
				// atur hak akses
				CekInitHeaderH(tblhtpr_sub_tipe);
			} );
			
			tblhtpr_sub_tipe.on( 'select', function( e, dt, type, indexes ) {
				htpr_sub_tipe_data    = tblhtpr_sub_tipe.row( { selected: true } ).data().htpr_sub_tipe;
				id_htpr_sub_tipe      = htpr_sub_tipe_data.id;
				id_transaksi_h = id_htpr_sub_tipe; // dipakai untuk general
				is_approve     = htpr_sub_tipe_data.is_approve;
				is_nextprocess = htpr_sub_tipe_data.is_nextprocess;
				is_jurnal      = htpr_sub_tipe_data.is_jurnal;
				is_active      = htpr_sub_tipe_data.is_active;

				id_heyxxmh_old   = data_htpr_sub_tipe.id_heyxxmh;
				id_heyxxmd_old   = data_htpr_sub_tipe.id_heyxxmd;
				id_hesxxmh_old   = data_htpr_sub_tipe.id_hesxxmh;

				// atur hak akses
				CekSelectHeaderH(tblhtpr_sub_tipe);
			} );

			tblhtpr_sub_tipe.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_htpr_sub_tipe = '';
				id_heyxxmh_old = 0;
				id_heyxxmd_old = 0;
				id_hesxxmh_old = 0;

				// atur hak akses
				CekDeselectHeaderH(tblhtpr_sub_tipe);
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
