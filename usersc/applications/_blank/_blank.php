<?php require_once '../../../users/init.php'; ?>
<?php require_once $abs_us_root.$us_url_root. 'usersc/includes/inspinia/theme_header.php'; ?>

<!-- Additional CSS here -->
<?php require_once $abs_us_root.$us_url_root. 'usersc/includes/inspinia/theme_load_css_datatables.php';?>

<!-- 
	Custom CSS yang biasanya dipakai tapi tidak disemua form
	1. untuk memperlebar ukuran form datatables editor
	2. untuk memperlebar ukuran textarea
	3. untuk memperlebar ukuran ckeditor
	4. untuk memperlebar select2 form (non editor)
 -->
<!--
<style>
	div.modal-dialog {
		width: 90%;
	}
	div.DTE_Field_Type_textarea textarea {
		padding: 3px;
		width: 100%;
		height: 80px;
	}
	.ck-editor__editable_inline{
		min-height: 300px;
		width: 100%;
	}
	.select2-container {
		width: 100%!important;
	}
</style>
-->
<?php 
	// execute permission check
	if (!securePage($_SERVER['PHP_SELF'])){die();} 
?>

<?php
	// put UserSpice php here
?>

<body class="<?php echo $_SESSION['sidemenu_mode']; ?>">
	<div id="wrapper">
		<?php require_once $abs_us_root.$us_url_root. 'usersc/includes/inspinia/theme_sidebar.php';?>
		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
				<?php require_once $abs_us_root.$us_url_root. 'usersc/includes/inspinia/theme_topbar.php';?>
			</div>
			<div class="row wrapper border-bottom white-bg page-heading">
				<div class="col-sm-12">
					<h2><?= (($pageTitle != '') ? $pageTitle : ''); ?></h2>
					<ol class="breadcrumb">
						<li>
							<strong><i class="fa fa-home"></i></strong>
						</li>
						<li>
							<strong>Database</strong>
						</li>
						<li class="active" data-toggle="tooltip" data-placement="top" title="<?= (($pageInfo != '') ? $pageInfo : ''); ?>">
							<strong><?= (($pageTitle != '') ? $pageTitle : ''); ?></strong>
						</li>
					</ol>
				</div>
			</div>

			<div class="wrapper wrapper-content">
				<!-- userspice message -->
				<?php require_once $abs_us_root.$us_url_root.'usersc/includes/theme_us_message.php'; ?>
				
				<!-- standard Datatables  -->
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 "> 
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								
								<!-- start Custom Form Datatables Editor -->
								<div id="customForm">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-6">
												<editor-field name="namafield"></editor-field>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-6">
												<editor-field name="namafield"></editor-field>
											</div>
										</div>
									</div>
								</div>
								<!-- end Custom Form Datatables Editor -->
								
								<table id="tbl_blank" class="table table-striped table-bordered table-hover nowrap" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>Code</th>
											<th>Name</th>
											<th>Keterangan</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>ID</th>
											<th>Total</th>
											<th id="total"></th>
											<th></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div> <!-- END OF ROW -->


				<!-- with tab  -->
				<div class="row">
					<div class="col-xs-12 col-md-12"> 
						<div class="ibox float-e-margins">
							<div class="ibox-content">
								<div class="tabs-container">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#tab1">Tab 1</a></li>
										<li class=""><a data-toggle="tab" href="#tab2">Tab 2</a></li> 
									</ul>
									

									<div class="tab-content">
										<div id="tab1" class="tab-pane active">
											<div class="panel-body">		
												<table id="tbl1" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id__blankheader</th>
															<th>Kode</th>
															<th>Nama</th>
															<th>Keterangan</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>

										<div id="tab2" class="tab-pane">
											<div class="panel-body">
												<table id="tbl2" class="table table-striped table-bordered table-hover nowrap" width="100%">
													<thead>
														<tr>
															<th>ID</th>
															<th>id__blankheader</th>
															<th>Kode</th>
															<th>Nama</th>
															<th>Keterangan</th>
														</tr>
													</thead>
												</table>
											</div>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>
				</div> <!-- END OF ROW -->
				
			</div>
		<?php include 'includes/theme_footer.php'; ?>
		</div> <!-- page-wrapper -->
	</div> <!-- wrapper -->

	<!-- load default js -->
	<?php include 'includes/theme_load_js.php';?>
	
	<!-- load additional js non datatables &  datatables editor -->
	<script src="../../../usersc/assets/js/plugins/ckeditor/ckeditor.js"></script>
	
	<!-- load datatables editor and default setup -->
	<?php include 'includes/theme_load_js_datatables.php';?>
	<script src="../usersc/js/datatables_setup.js"></script>
	<script src="../../../usersc/assets/js/plugins/editor/editor.ckeditor5.js"></script>
	
	<?php
		// Load Userspice's Notification popup
		require_once $abs_us_root.$us_url_root.'users/includes/notifications.php';
	?>
	
	<script type="text/javascript">
		// ------------- default variable, do not erase
		var edt_blank;
		var tbl_blank;
		var show_inactive_status = 0;
		var pageTitle = "<?php echo $pageTitle; ?>";	// untuk editor_log_v02
		// ------------- end of default variable
		
		$(document).ready(function() {
			//start datatables editor
			edt_blank = new $.fn.dataTable.Editor( {
				ajax: {
					url: "function/_blank.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				table: "#tbl_blank",
				template: "#customForm",
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
					},	{
						label: "hidden",
						name: "hidden",
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
						def: "_blank",
						type: "hidden"
					},	{
						label: "Kode",
						name: "_blank.kode",
						fieldInfo: "Informasi terkait inputan",
					}, 	{
						label: "Nama",
						name: "_blank.nama"
					}, 	{
						label: "Keterangan",
						name: "_blank.keterangan"
					},	{
						label: "Active Status",
						name: "_blank.is_active",
						type: "select",
						placeholder : "Select",
						def: 1,
						options: [
							{ "label": "Yes", "value": 1 },
							{ "label": "No", "value": 0 }
						]
					},	{
						label: "Required <sup class='text-danger'>*<sup>",
						name: "_blank.namafield"
					},	{
						label: "Select2",
						name: "_blank.id",
						type: "select2",
						opts:{
							placeholder : "Select",
							allowClear: true,
							multiple: true // dipakai jika perlu multi select
							
							// ini digunakan untuk generate options select dari ajax 
							// karena jika dari php akan ter-load semua. loading time nya lama
							ajax: {
								url: '/splnote/usersc/function/load_select2_resi.php', //url disesuaikan dengan format
								dataType: 'json',
								initialValue: true,
								processResults: function (data) {
									return {
										results: $.map(data, function(obj) {
											return { id: obj.id, text: obj.noresi};
										})
									};
								}
							}
						}
					},	{
						label: "Selectize",
						name: "_blank.namafield",
                        type: "selectize",
						opts: {
							create: false,
							maxItems: 1,
							maxOptions: 20,
							allowEmptyOption: false,
							closeAfterSelect: true,
							placeholder: "Select",
							openOnFocus: false
						}
					},	{
						label: "Tanggal",
						name: "_blank.tanggal",
						type: "datetime",
						def: function () { 
							return new Date(); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'			// Select salah satu format sesuai kebutuhan
						format: 'DD MMM YYYY / HH:mm'
						format: 'HH:mm'
					}, {
						label: "Lampiran",
						name: "_blank.image",
						type: "upload",
						display: function ( fileId, counter ) {
							return '<img src="'+edt_blank.file( 'files', fileId ).web_path+'"/>';
						},
						noFileText: 'Belum ada gambar'
					}, 	{
						label: "Lampiran Multi:",
						name: "files[].id",
						type: "uploadMany",
						display: function ( fileId, counter ) {
							if (fileId == 0){
								return 'Belum ada lampiran';
							}else{
								return '<a href="'+edt_blank.file( 'files', fileId ).web_path+'" target="_blank">'+ edt_blank.file( 'files', fileId ).filename + '</a>'; 
							}
						},
						noFileText: 'Belum ada gambar'
					}, 	{
						label: "CKEditor",
						name: "_blank.namafield",
						type: "ckeditorClassic",
						opts: { 
							toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
							heading: {
								options: [
									{ model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
									{ model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
									{ model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
								]
							},
						}
					}
				]
			} );
			
			// add class di input only
			edt_blank.field('_blank.namafield').input().addClass('text-right');
			
			edt_blank.on( 'preOpen', function( e, mode, action ) {
				// start untuk editor_log_v02
				tanggaljamsekarang = moment().format('YYYY-MM-DD HH:mm:ss');
				edt_blank.field('created_on_start').val(tanggaljamsekarang);
				// end untuk editor_log_v02
				
				if (action == 'create'){
					edt_blank.field('kategori_dokumen').val('');
					edt_blank.field('kategori_dokumen_value').val('');
					edt_blank.field('field_tanggal').val('tanggal');	// jika menggunakan field tertentu
					edt_blank.field('field_tanggal').val('created_on');			// jika menggunakan created_on
				}

				// BEGIN populate selectize dari front end
				// harus ditambahkan parameter di where untuk load data saat edit
				if(action == 'create'){
					where = 'where_kondisi';
				}else if (action == 'edit'){
					where = '';
				}

				var opt_field;
				$.ajax( {
					url: '../../helpers/fn_editor_selectize.php',
					dataType: 'json',
					type: 'POST',
					data: {
						table_name: 'table_parent',
						field_name: 'id as value, CONCAT(kode," - ",nama) as label',
						where: where,
						orderby: 'nama'
					},
					success: function ( json ) {
						opt_field = json;
						edt_blank.clear('_blank.opt_field');
						edt_blank.add({
							label: 'Kandidat',
							name: "_blank.opt_field",
							type: "selectize",
							options: opt_field,
							opts: {
								create: false,
								maxItems: 1,
								maxOptions: 20,
								allowEmptyOption: false,
								closeAfterSelect: true,
								placeholder: "Select",
								openOnFocus: true
							}
						}, '_blank.opt_field');
					}
				} );
				// END populate selectize dari front end
			});

			edt_blank.dependent( '_blank.idDependent', function ( val, data, callback ) {
				// code here
				return {}
			}, {event: 'keyup change'});

			edt_blank.field('_blank.idDependent').input().on('change', function (e, d) {
				// code here
			});

			// BEGIN dependent field untuk load data ke field lain berdasarkan value dari field tertentu
			edt_blank.dependent( '_blank.idDependent', function ( val, data, callback ) {
				
				idWhere = edt_blank.field('_blank.idWhere').val();
				if(idWhere > 0){
					$.ajax( {
						url: '../../helpers/fn_select.php',
						dataType: 'json',
						type: 'POST',
						data: {
							sql: 'SELECT nama_field FROM nama_tabel WHERE id = ' + idWhere
						},
						success: function ( json ) {
							if(json.length > 0){
								nama_field = json[0].nama_field;
								edt_blank.field('nama_field').val(nama_field);
							}
						}
					} );
				}
			} );
			// END dependent field untuk load data ke field lain berdasarkan value dari field tertentu

			// BEGIN dependent field untuk generate options di field select berdasarkan value dari field tertentu
			// replace kata field_select menjadi nama field select
			edt_blank.dependent( '_blank.idDependent', function ( val, data, callback ) {
				
				idWhere = edt_blank.field('_blank.idWhere').val();
				if(idWhere > 0){
					var opt_field_select = [];
					$.ajax( {
						url: '../../helpers/fn_select.php',
						dataType: 'json',
						type: 'POST',
						data: {
							sql: 'SELECT id, nama FROM nama_tabel'
						},
						success: function ( json ) {
							var option_field_select = {};
							$.each(json, function(i, e) {
								option_field_select.label = e.nama;	// render label, bisa juga di concat, sesuaikan dengan output json
								option_field_select.value = e.id;

								opt_field_select.push(option_field_select);
								option_field_select = {};
							});
							edt_blank.field('nama_field_Select').update(opt_field_select);
						}
					} );
				}
			} );
			// END dependent field untuk generate options di field select berdasarkan value dari field tertentu
			
			// BEGIN beberapa hal yang bisa dipakai terkait manipulasi field
			
			// moment js menambah atau mengurangi hari
			tanggal_akhir = moment(tanggal_awal).add('month', 6).format('DD MMM YYYY');
			tanggal_akhir = moment(tanggal_akhir).subtract('day', 1).format('DD MMM YYYY');				
			
			// ambil text dari selected option. bisa untuk select, select2, selectize
			edt_blank.field('nama_field_select2').input().children(':selected').text();

			// hide / show field
			edt_blank.field('nama_field').hide();
			edt_blank.field('nama_field').show();

			// enable / disable field
			edt_blank.field('nama_field').enable();
			edt_blank.field('nama_field').disable();

			// END beberapa hal yang bisa dipakai terkait manipulasi field
			
			edt_blank.on( 'initSubmit', function (e, action) {
				// mengganti nilai field sebelum submit
				edt_blank.field( '_blank.namafield' ).val(nilaibaru);
			} );
			
			edt_blank.on( 'preSubmit', function (e, data, action) {
				/* 
					validasi
					Jika menggunakan field type select2, disarangkan untuk melakukan validasi disini, BUKAN di function bawaan datatables editor
					Karena ada bugs, jika menggunakan select2, saat terjadi error, selected value nya yang sudah diSelect sebelumnya akan ganti ke option urutan 1
					Sehingga menyebabkan data menjadi salah
					
					validasi hanya dilakukan jika actionya create dan edit
					harus ditambahkan pengecekan multifvalue untuk menghindari bug karena user behaviour
				*/

				// BEGIN validasi
				if(action != 'remove'){
					// BEGIN of validasi _blank.nama_field
					if ( ! edt_blank.field('_blank.nama_field').isMultiValue() ) {
						_blank_nama_field = edt_blank.field('_blank.nama_field').val(); // isi variable
						// mulai validasi disini
						// bisa lebih dari 1
					}
					// END of validasi _blank.nama_field

					// validasi field lain bisa ditambahkan juga setelahnya
					
				}

				if ( edt_blank.inError() ) {
					return false;
				}
				// END validasi
				


				// jenis validasi yang bisa digunakan
				
				//  validasi blank
				if(!variablecek || variablecek == ''){
					edt_blank.field('_blank.namafield').error( 'Wajib diisi!' );
				}
				
				// validasi min atau max angka
				if(variablecek <= 0 ){
					edt_blank.field('_blank.namafield').error( 'Inputan harus > 0' );
				}
				
				// validasi angka
				if(isNaN(variablecek) ){
					edt_blank.field('_blank.namafield').error( 'Inputan harus berupa Angka!' );
				}
				
				// validasi length
				variablecek   = namafield.length;
				if(variablecek != 10){
					edt_blank.field('_blank.namafield').error( 'Inputan harus 10 digit angka!' );
				}
				
				// begin validasi tanggal (menggunakan moment.js)
				tanggal       		= moment(edt_blank.field('_blank.namafieldtanggal').val()).format('YYYY-MM-DD');
				tanggalsekarang    	= moment().format('YYYY-MM-DD');

				// tanggal lebih besar atau kecil
				if( tanggal < tanggalsekarang ){
					edt_blank.field('_blank.namafieldtanggal').error( 'Tanggal harus lebih besar dari sekarang!' );
				}

				// format tanggal
				tanggal = edt_blank.field('_blank.namafieldtanggal').val()
				if(moment(tanggal, 'DD MMM YYYY').isValid() == false){
					edt_blank.field('_blank.namafieldtanggal').error( 'Format Tanggal Salah' );
				}
				// end validasi tanggal
				
				// selisih tanggal
				tanggal	        = moment(edt_blank.field('_blank.tanggal').val());
				tanggalsekarang = moment();
				selisih			= tanggalsekarang.diff(tanggal,'years');	// years, months, weeks, days, hours, minutes, seconds, 
				if( selisih < 15 ){
					edt_blank.field('_blank.tanggal').error( 'Tanggal salah!' );
				}

				// unik data per table
				// BEGIN of cek unik _blank.kode
				if(action == 'create'){
					id__blank = 0;
				}
				
				$.ajax( {
					url: '../../../helpers/validate_fn_unique.php',
					dataType: 'json',
					type: 'POST',
					async: false,
					data: {
						table_name: '_blank',
						nama_field: 'kode',
						nama_field_value: '"'+kode+'"',
						id_transaksi: id__blank
					},
					success: function ( json ) {
						if(json.data.count == 1){
							edt_blank.field('_blank.kode').error( 'Data tidak boleh kembar!' );
						}
					}
				} );
				// END of cek unik _blank.kode
				
				
				
			} );
			
			edt_blank.on( 'postCreate', function( e, json, data, id ) {
				// do something
			} );
			
			edt_blank.on( 'postEdit', function (e, json, data, id) {
				// do something
			} );
			
			edt_blank.on( 'postSubmit', function (e, json, data, action, xhr) {
				tbl_blank.rows().deselect();
				tbl_blank.ajax.reload(null, false);
				
				// kirim email 
				email_to           	= edt_blank.field('_blank.email_kepada').val();
				iduserstujuan 		= edt_blank.field('_blank.idmhpegawai_kepada').val();
				// auto kirim email on postSubmit

				$.ajax( {
					url: '../vincmeister/fn_sendemail.php',
					dataType: 'json',
					type: 'POST',
					data: {
						kodeemailtemplate : 'kode',				// related to shemailtemplate
						email_to          : email_to,			// email tujuan
						idmhpegawai_kepada: idmhpegawai_kepada	// iduser 
					},
					success: function ( json ) {
						$.notify({
							message: json.message
						},{
							type: json.type_message
						});
					}
				} );
				// perlu di reload mungkin
				
			} );
			
					
			//start datatables
			tbl_blank = $('#tbl_blank').DataTable( {
				ajax: {
					url: "function/_blank.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "_blank.id",visible:false },
					{ data: "_blank.kode" },
					{ data: "_blank.nama" },
					{ 
						data: "_blank.harga",
						render: $.fn.dataTable.render.number( ',', '.', 0,'Rp. ','' ),
						class: "text-right"
					},
					{ 
						data: "files.web_path" ,
						render: function (data){
							if(data){
								return '<a href="'+edt_blank.file( 'files', fileId ).web_path+'" target="_blank">'+ edt_blank.file( 'files', fileId ).filename + '</a>';
							}else{
								return '';
							}
						}
					},
					{ 
						data: "files",
						render: function (data){
							if(data.length > 0){
								var download_icon = '';
								Object.keys(data).forEach(function(key) {
									download_icon = download_icon + '<a href="'+ data[key]['web_path'] +'" target="_blank"><i class="fa fa-download"> </i></a>' + ' ';
								});
								return download_icon;
							}else{
								return '';
							}
						}
					},
					{
						data: "_blank.is_active",
						render: function (data){
							if (data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 0){
								return '<i class="fa fa-check text-danger"></i>';
							}
						}
					},
					{ 
						data: "_blank.is_approve" ,
						render: function (data){ 	// render model tulisan
							if (data == 1){
								return 'Approved';
							}else if(data == 2){
								return 'Approval Canceled';
							}else if(data == -1){
								return 'Rejected';
							}else if(data == -2){
								return 'Rejection Canceled';
							}else{
								return '';
							}
						}
						render: function (data){	// render model icon
							if (data == 1){
								return '<i class="fa fa-check text-navy"></i>';
							}else if(data == 2){
								return '<i class="fa fa-undo"></i>';
							}else if(data == -1){
								return '<i class="fa fa-remove text-danger"></i>';
							}else{
								return '';
							}
						}
					},
					{ 
						data: null,
						render: function (data, type, row) {
							// console.log(row);
							if(row.table_name.fieldname){
								return row.table_name.fieldname;
							}
					   	}
					},
					{ data: "_blank", render: "[, ].nama" }, // untuk render multi data many to many
				],
				buttons: [
					// button collection tools
					// terdiri dari 4 sub button: copy, excel, pdf, show/hide document
					{
						extend: 'collection',
						name: 'btnSetTools',
						text: '<i class="fa fa-wrench">',
						className: 'btn btn-outline',
						autoClose: true,
        				buttons: [
        					{ 
								extend: "copy", 
								name: 'btnCopy',
								text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', 
								className: 'btn btn-outline',
								titleAttr: 'Copy'
        					},
							{ 
								extend: "excel",
								name: 'btnExcel', 
								text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', 
								className: 'btn btn-outline',
								titleAttr: 'Export to Excel'
							},
							{ 
								extend: "pdf", 
								name: 'btnPdf', 
								text: '<i class="fa fa-file-pdf-o">&nbsp &nbsp pdf</i>', 
								className: 'btn btn-outline',
								titleAttr: 'Export to pdf'
							},
							{
								text: '<i class="fa fa-adjust" id="bf_active_status">&nbsp &nbsp Show / Hide Document</i>',
								name: 'btnShowHide', 
								className: 'btn btn-outline',
								titleAttr: 'Show / Hide Inactive Document',
								action: function ( e, dt, node, config, tbl_details ) {
									
									if (show_inactive_status<?php echo $show_status;?> == 0){
										$('#bf_active_status').addClass('text-danger');
										show_inactive_status<?php echo $show_status;?> = 1;
									} else if (show_inactive_status<?php echo $show_status;?> == 1){
										$('#bf_active_status').removeClass('text-danger');
										show_inactive_status<?php echo $show_status;?> = 0;
									}

									<?php echo $table;?>.rows().deselect();
									<?php echo $table;?>.ajax.reload(null,false);

								}
							}
						]
					},

					// button colvis (untuk show hide kolom)
					{ 
						extend: 'colvis',
						name: 'btnColVis',
						columns: colvisCount,
						columnText: function ( dt, idx, title ) {
							return title;
						},
						text: '<i class="fa fa-eye-slash"></i>',
						className: 'btn btn-outline',
						titleAttr: 'Show / Hide Column'
					},

					// button create
					{ 
						extend: 'create',
						name: 'btnCreate', 
						editor: <?php echo $edt;?>, 
						text: '<i class="fa fa-plus"></i>', 
						className: 'btn btn-outline', 
						titleAttr: 'New',
						key: {
							key: 'n',
							ctrlKey : true,
							altKey : true
						}
					},

					// button edit
					{ 
						extend: 'edit', 
						name: 'btnEdit', 
						editor: <?php echo $edt;?>,
						text: '<i class="fa fa-edit"></i>', 
						className: 'btn btn-outline',
						titleAttr: 'Edit',
						key: {
							key: 'e',
							ctrlKey : true,
							altKey : true
						}
					},

					// button remove (DELETE DATA)
					{
						extend: 'remove', 
						name: 'btnRemove',
						editor: <?php echo $edt;?>, 
						text: '<i class="fa fa-trash"></i>', 
						className: 'btn btn-outline text-danger',
						titleAttr: 'Remove',
						key: {
							key: 'd',
							ctrlKey : true,
							altKey : true
						}
					},

					// button view
					{
						extend: "selected",
						name: 'BtnView',
						text: '<i class="fa fa-window-maximize"></i>', 
						className: 'btn btn-outline',
						titleAttr: 'View Data',
						action: function(e, dt, node, config) {
							act = 'view';
							<?php echo $edt;?>.edit(<?php echo $table;?>.row({
								selected: true
							}).index(), {
								title: 'View'
							});
							<?php echo $edt;?>.disable();
							e.preventDefault();
						}
					},

					// button non aktif header
					// data dapat diaktifkan kembali dengan menggunakan tombol ini
					{ 
						text: '<i class="fa fa-trash" id="btnNonAktif"></i>', 
						name: 'btnNonAktif',
						className: 'btn btn-outline',
						titleAttr: 'Remove',
						action: function ( e, dt, node, config ) {
							if(is_active == 1){
								// jika data aktif, maka akan di non aktifkan
								state_active = 1;
								message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";
								$('#btnNonAktif').removeClass('text-danger');
							}else{
								// jika data non aktif, maka akan di aktifkan
								state_active = 0;
								message_is_delete = "Apakah Anda Yakin Akan Mengaktifkan Kembali Data?"
								$( '#btnNonAktif' ).addClass('text-danger');
							}

							is_delete = confirm(message_is_delete);
							if(is_delete == true){
								$.ajax( {
									url: '../../../helpers/fn_nonaktif.php',
									dataType: 'json',
									type: 'POST',
									data: {
										table_name	 : '<?php echo $table_name;?>',
										id_transaksi : id_transaksi_h,
										state_active : state_active
									},
									success: function ( json ) {
				
										$.notify({
											message: json.message
										},{
											type: json.type_message
										});
										
										<?php echo $table;?>.ajax.reload(null, false);
										<?php echo $table;?>.rows().deselect();
									}
								} );
							}

						}
					},

					// button non aktif detail
					// data dapat diaktifkan kembali dengan menggunakan tombol ini
					{ 
						text: '<i class="fa fa-trash" id="btnNonAktif"></i>', 
						name: 'btnNonAktif',
						className: 'btn btn-outline',
						titleAttr: 'Remove',
						action: function ( e, dt, node, config ) {
							var rows = <?php echo $table;?>.rows( {selected: true} ).indexes();
							
							if(is_active_d == 1){
								// jika data aktif, maka akan di non aktifkan
								state_active = 1;
								is_active_new = 0;
								message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";
								$('#btnNonAktif').removeClass('text-danger');
							}else{
								// jika data non aktif, maka akan di aktifkan
								state_active = 0;
								is_active_new = 1;
								message_is_delete = "Apakah Anda Yakin Akan Mengaktifkan Kembali Data?"
								$( '#btnNonAktif' ).addClass('text-danger');
							}

							is_delete = confirm(message_is_delete); 

							if(is_delete == true){
								$.ajax( {
									url: '../../../helpers/fn_nonaktif.php',
									dataType: 'json',
									type: 'POST',
									data: {
										table_name	 : '<?php echo $table_name;?>',
										id_transaksi : id_transaksi_d,
										state_active : state_active
									},
									success: function ( json ) {
				
										$.notify({
											message: json.message
										},{
											type: json.type_message
										});
										
										<?php echo $table;?>.ajax.reload(null, false);
										<?php echo $table;?>.rows().deselect();
									}
								} );
							}

						}
					},

					// button non aktif only detail. 
					// data tidak dapat diaktifkan kembali
					{ 
						text: '<i class="fa fa-trash" id="btnNonAktifOnly"></i>', 
						name: 'btnNonAktifOnly',
						className: 'btn btn-outline text-danger',
						titleAttr: 'Remove',
						action: function ( e, dt, node, config ) {
							var rows          = <?php echo $table;?>.rows( {selected: true} ).indexes();
							message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";

							is_delete = confirm(message_is_delete);
							if(is_delete == true){
								<?php echo $edt;?>
								.edit( rows, false )
								.set( '<?php echo $table_name;?>.is_active', 0 )
								.submit();    
							}

						}
					},

					// button set approval
					// terdiri dari approve dan void
					{
						extend: 'collection',
						text: '<i class="fa fa-check-square-o"></i>',
						name: 'btnSetApprove',
						className: 'btn btn-outline',
						autoClose: true,
						buttons: [
							{ 
								text: '<i class="fa fa-check text-navy">&nbsp &nbsp Approve</i>', 
								name: 'btnApprove',
								className: 'btn btn-outline',
								titleAttr: 'Approve',
								action: function ( e, dt, node, config ) {

									$.ajax( {
										url: '../../../helpers/tr_fn_approve.php',
										dataType: 'json',
										type: 'POST',
										data: {
											table_name: '<?php echo $table_name;?>',
											id_transaksi: id_transaksi_h
										},
										success: function ( json ) {

											$.ajax( {
												url: '../../../helpers/kode_fn_generate_a.php',
												dataType: 'json',
												type: 'POST',
												data: {
													state                   : 1,
													nama_tabel              : '<?php echo $table_name;?>',
													kategori_dokumen        : '',
													kategori_dokumen_value  : '',
													id_transaksi            : id_transaksi_h
												},
												success: function ( json ) {

													<?php 
														if(file_exists("../../models/" . $table_name . '/' . $table_name . "_post_approve.php")) {
													?>
														$.ajax( {
															url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_post_approve.php',
															dataType: 'json',
															type: 'POST',
															data: {
																state           : 1,
																id_transaksi_h  : id_transaksi_h,
																imtxxmh_kode    : imtxxmh_kode
															},
															success: function ( json ) {
																$.notify({
																	message: json.message
																},{
																	type: json.type_message
																});
															}
														} );
													<?php
														}
													?>

													is_message = 0;
													if(is_need_inventory == 1){
														$.ajax( {
															url: '../../../helpers/fn_inventory_r.php',
															dataType: 'json',
															type: 'POST',
															data: {
																state          : 1,
																id_transaksi_h : id_transaksi_h,
																imtxxmh_kode   : imtxxmh_kode
															},
															success: function ( json ) {

																$.notify({
																	message: json.message
																},{
																	type: json.type_message
																});
															}
														} );
													}else{
														is_message = is_message + 1;
													}

													if(is_need_jurnal == 1){
														$.ajax( {
															url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_jv.php',
															dataType: 'json',
															type: 'POST',
															data: {
																state           : 1,
																id_transaksi_h  : id_transaksi_h,
																imtxxmh_kode    : imtxxmh_kode
															},
															success: function ( json ) {

																$.notify({
																	message: json.message
																},{
																	type: json.type_message
																});
															}
														} );
													}else{
														is_message = is_message + 1;
													}
													
													if( is_message == 2){
														$.notify({
															message: json.message
														},{
															type: json.type_message
														});
													}
													
													<?php echo $table;?>.ajax.reload(null, false);
												}
											} );

										}
									});
								}
							},
							{ 
								text: '<i class="fa fa-remove text-danger">&nbsp &nbsp Void</i>', 
								name: 'btnVoid',
								className: 'btn btn-outline',
								titleAttr: 'Void',
								action: function ( e, dt, node, config ) {
									$.ajax( {
										url: '../../../helpers/tr_fn_void.php',
										dataType: 'json',
										type: 'POST',
										data: {
											table_name: '<?php echo $table_name;?>',
											id_transaksi: id_transaksi_h
										},
										success: function ( json ) {
											if(is_need_inventory == 1){
														
												$.ajax( {
													url: '../../../helpers/fn_inventory_r.php',
													dataType: 'json',
													type: 'POST',
													data: {
														state          : -9,
														id_transaksi_h : id_transaksi_h,
														
														imtxxmh_kode   : imtxxmh_kode
													},
													success: function ( json ) {

														$.notify({
															message: json.message
														},{
															type: json.type_message
														});
													}
												} );

											}else{
												$.notify({
													message: json.message
												},{
													type: json.type_message
												});
											}
											
											<?php echo $table;?>.ajax.reload(null, false);
										}
									} );
								}
							}
						]
					}
					
					// button print
					// print menggunakan mpdf
					{
						text: '<i class="fa fa-print"></i>',
						name: 'btnPrint',
						className: 'btn btn-outline',
						titleAttr: 'Print Quotation',
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							var url = $(this).attr('href'); 
							window.open('print_penawaran.php?id_blank='+id_blank, '_blank');
						}
					},

					// custom button
					{
						text: '<i class="fa fa-icon">Title</i>',	// icon atau pakai title
						name: 'btnCustom',							// name, dipakai untuk disable enable button. lihat bagian table init, select dan deselect
						className: 'btn btn-outline',				// css class button
						titleAttr: '',								// tooltips
						action: function ( e, dt, node, config ) {
							e.preventDefault(); 
							// action here
							// bebas mau melakukan apa
						}
					},

					// END additional button
				],
				rowCallback: function( row, data, index ) {
					// format kolom 
					$('td:eq(3)', row).addClass('bg-danger');
					
					if ( data._blank.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
					if ( data._blank.is_approve == 1 ) {
						$('td', row).addClass('text-warning');
					}else if ( data._blank.is_approve == -1 ) {
						$('td', row).addClass('text-danger');
					}
				},
				footerCallback: function ( row, data, start, end, display ) {
					var api       = this.api(), data;
					var numFormat = $.fn.dataTable.render.number( '\,', '.', 2, '' ).display; 
					// hitung jumlah 
					total = api.column( 7 ).data().sum();

					// Update footer v1
					$( '#total' ).html( numFormat(total) );

					// Update footer v2
					// ( 7 ) -> menunjukkan index kolom pada footer
					$( api.column( 7 ).footer() ).html(
						numFormat(total)
					);
				}
			} );
			
			tbl_blank.on( 'init', function ( e, settings, json ) {
				tbl_blank.button( 'btnCustom:name' ).disable();
			} );
			
			tbl_blank.on( 'select', function( e, dt, type, indexes ) {
				_blank_data    = tbl_blank.row( { selected: true } ).data()._blank;
				id__blank      = _blank_data.id;
				id_transaksi_h = id__blank; // dipakai untuk general
				is_approve     = _blank_data.is_approve;
				is_nextprocess = _blank_data.is_nextprocess;
				is_jurnal      = _blank_data.is_jurnal;
				is_active      = _blank_data.is_active;

				tbl_blank.button( 'btnCustom:name' ).enable();
			} );
			
			tbl_blank.on( 'deselect', function () {
				id__blank = '';
				tbl_blank.button( 'btnCustom:name' ).disable();
			} );
			
		} );// end of document.ready
	
	</script>
	
</body>
</html>