<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel   = 'pages';
	$nama_tabels_d = [];
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblpages" class="table table-striped table-bordered table-hover nowrap responsive" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Page</th>
                                <th>Title</th>
                                <th>Info</th>
                                <th>C-U-D-A</th>
                                <th>Hal Setting</th>
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
		var edtpages, tblpages, show_inactive_status = 0, id_pages;
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edtpages = new $.fn.dataTable.Editor( {
				formOptions: {
					main: {
						focus: 3,
					}
				},
				ajax: {
					url: "../../models/core/pages_extend.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				table: "#tblpages",
				formOptions: {
					main: {
						focus: 5
					}
				},
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
						def: "pages",
						type: "hidden"
					},	{
						label: "Page",
						name: "pages.page",
                        type: "readonly"
					}, 	{
						label: "Title",
						name: "pages.title",
                        type: "readonly"
					}, 	{
						label: "Info",
						name: "pages_extend.info"
					}, 	{
						label: "Perlu Atur C-U-D-A",
						name: "pages_extend.is_crud",
						type: "select",
						fieldInfo: "Akses untuk Menambahkan Data (C), Edit (U), Delete (D), Approve (A).<br>Jika Ya, maka harus diatur hak akses Data nya. <a href='udpxxsh.php' target='_blank'>Atur Hak Akses Data</a>",
						placeholder : "Pilih",
						def: 1,
						options: [
							{ "label": "Ya", "value": 1 },
							{ "label": "Tidak", "value": 0 }
						]
					}, 	{
						label: "Halaman Setting",
						name: "pages_extend.is_setting",
						type: "select",
						placeholder : "Pilih",
						def: 0,
						options: [
							{ "label": "Tidak", "value": 0 },
							{ "label": "Ya", "value": 1 }
						]
					}
				]
			} );

			edtpages.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpages.field('start_on').val(start_on);
			});

			edtpages.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			})

			edtpages.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtpages.field('finish_on').val(finish_on);
			});
			
			//start datatables
			tblpages = $('#tblpages').DataTable( {
				ajax: {
					url: "../../models/core/pages_extend.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "pages.id",visible:false },
					{ data: "pages.page" },
					{ data: "pages.title" },
					{ data: "pages_extend.info", className: 'editable' },
					{ 
						data: "pages_extend.is_crud", 
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else{
								return '';
							}
						},
						className: 'editable' 
					},
					{ 
						data: "pages_extend.is_setting", 
						render: function (data){
							if (data == 0){
								return 'Tidak';
							}else if(data == 1){
								return 'Ya';
							}else{
								return '';
							}
						},
						className: 'editable' 
					}
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_pages';
						$table       = 'tblpages';
						$edt         = 'edtpages';
						$show_status = '_pages';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];;
						$arr_buttons_action 	= ['edit'];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				]
			} );

			tblpages.on( 'click', 'tbody td.editable', function (e) {
        		edtpages.inline( this );
    		} );

			tblpages.on( 'click', 'tbody ul.dtr-details li', function (e) {
				edtpages.inline( $('span.dtr-data', this) );
			} );
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
