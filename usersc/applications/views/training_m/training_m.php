<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'materi_m';
	$nama_tabels_d = [];
?>
<style>
	/* Add this to your CSS */
	.hovered {
		color: transparent;
		background-color: inherit;
		border-color: currentColor;
	}

	.trainingh3 {
		color: black;
	}
	.ditekan {
		color: blue;
	}
	.trainingh3:hover {
		color: blue;
	}
</style>
<!-- begin content here -->

<button id="btnCreatetraining_m" class="btn btn-primary" title="New"><i class="fa fa-plus"></i> training</button>
<div class="row">
	<div class="col-lg-4" id="tr-kiri">
		<div class="ibox">
			<div class="ibox-title">
				<h5>Training</h5>
				<div class="ibox-tools">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
				</div>
			</div>
			<!-- <div class="ibox-content ibox-heading">
				<div class="foto_training"></div>
			</div> -->
			<div class="ibox-content">
				<div class="feed-activity-list">
					<div id="training-container"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8" id="tr-kanan">
		<div class="ibox ">
			<div class="ibox-title" id="judul-tr">
				<h3 class="judul_training"></h3>
				<div class="ibox-tools">
					<a id="tr-sidebar" title="Close Training"><i class="fa fa-times text-danger"></i></a>
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
				</div>
			</div>
			<!-- <div class="ibox-content ibox-heading">
				<div class="foto_training"></div>
			</div> -->
			<div class="ibox-content">
				<div id="hide-tr">
					<div class="img-training"></div>
					<br>
					<div class="keterangan_traning"></div>
					<br>
				</div>
				<div class="row">
					<div class="col-lg-4" id="materi-kiri">
						<button id="btnCreatesub_materi_m" class="btn btn-primary" title="New"><i class="fa fa-plus"></i> sub_materi</button>
						<div id="sub_materi_content"></div>
					</div>
					<div class="col-lg-8" id="materi-kanan">
						<div class="ibox">
							<div class="ibox-title">
								<div class="row">
									<div class="col-md-6">
										<button id="materi-sidebar" class="btn btn-primary" title="Fullscreen Materi"><i class="fa fa-expand"></i></button>
									</div>
									<div class="col-md-6">
										<h3 id="judul"></h3>
									</div>
								</div>
							</div>
							<div id="materi"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-10"></div>
	<div class="col-md-2"></div>
</div>


<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_modal_log.php'; ?>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/training_m/fn/training_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmateri_m, tblmateri_m, show_inactive_status_materi_m = 0, id_materi_m;
		var edttraining_m, tbltraining_m, show_inactive_status_training_m = 0, id_training_m;
		// ------------- end of default variable

		$(document).ready(function() {
			
			$("#tr-kanan").hide();
			
			$("#tr-sidebar").click(function () {
				$("#tr-kiri").show(); 
				$("#tr-kanan").hide();
				$("#btnCreatetraining_m").show();
			});

			$("#materi-kanan").hide();
			
			$("#materi-sidebar").click(function () {
				$("#materi-kiri").toggleClass("d-none"); 
				$("#judul-tr").toggleClass("d-none"); 
				$("#hide-tr").toggleClass("d-none"); 
				$("#materi-kanan").toggleClass("col-lg-12"); 
				const icon = $(this).find('i');
				if (icon.hasClass("fa-expand")) {
					icon.removeClass("fa-expand").addClass("fa-compress");
				} else {
					icon.removeClass("fa-compress").addClass("fa-expand");
				}
			});

			function reloadtraining(){
				$.ajax({
					url: "../../models/training_m/training_m.php",
					dataType: 'json',
					success: function(data) {
						if (Array.isArray(data.data)) {
							$('#training-container').empty();
							const iboxContainer = document.getElementById("training-container");
							data.data.forEach(function(train) {
								const ibox = document.createElement("div");
								ibox.className = "feed-element";
								let id = train.DT_RowId;

								const editTraining = createButton("edit btn btn-primary btn-sm", "fa fa-pencil", train.training_m.id, train.training_m.id_files_foto);
								const removeTraining = createButton("remove btn btn-danger btn-sm", "fa fa-trash", train.training_m.id, 0);
								const createdOn = new Date(train.training_m.created_on);

								trainingDibuat(createdOn);
								formatTime(createdOn);

								ibox.innerHTML = `
								<div class="tr_panel" data-editor-id="${id}">
									<a class="konten-tr" title="Fullscreen Materi"><i class="fa fa-chevron-up"></i></a>
									<small class="float-right">${timeAgoText} ago</small>
									<strong>
										<h3 class="trainingh3" style="cursor: pointer;" 
											data-training-id="${train.training_m.id}">
											${train.training_m.nama}
										</h3>
									</strong>
									<div class="tr-up">
										<div>${train.training_m.keterangan}</div>
										<small class="text-muted">${formatTime(createdOn)} - ${createdOn.toLocaleDateString()}</small>		
										<br>					
										<br>					
										<div class="row">
											<div class="col-md-4">
												${editTraining.outerHTML}
												${removeTraining.outerHTML}
											</div>
										</div>
									</div>	
								</div>
								`;

								const trKonten = ibox.querySelector('.konten-tr');
								trKonten.addEventListener('click', function() {
									const trUpElement = this.parentNode.querySelector('.tr-up'); // Find tr-up within the same .tr_panel
									$(trUpElement).toggleClass("d-none");

									const chevronIcon = this.querySelector('i');
									if ($(chevronIcon).hasClass("fa-chevron-up")) {
										$(chevronIcon).removeClass("fa-chevron-up").addClass("fa-chevron-down");
									} else {
										$(chevronIcon).removeClass("fa-chevron-down").addClass("fa-chevron-up");
									}
								});

								const h3Element = ibox.querySelector('h3[data-training-id]');
								const trKanan = $("#tr-kanan");
								h3Element.addEventListener('click', function() {
									$("#btnCreatetraining_m").hide();
									trKanan.removeClass("col-lg-8").addClass("col-lg-12");
									$('#tr-kiri').hide();
									$('#materi-kanan').hide();
									$('#materi').empty();
									$('#judul').empty();
									const h3Training = document.querySelector('.judul_training'); 
									const img_training = document.querySelector('.img-training'); 
									const keterangan_traning = document.querySelector('.keterangan_traning'); 

									h3Training.textContent = `${train.training_m.nama}`; 
									img_training.innerHTML = `<img width="100%" src="${train.files.web_path}">`; 
									keterangan_traning.innerHTML = `<br>Keterangan: <br> ${train.training_m.keterangan}<br>`; 
									$("#tr-kanan").show();
									id_training_m = train.training_m.id;
									// console.log(id_training_m);

									genSubMateri(id_training_m);
									
									edtsub_materi_m.on('postSubmit', function (e, json) {
										genSubMateri(id_training_m);
									});
								});

								ibox.setAttribute("data-id-training-m", train.training_m.id);
								
								// Add the ibox to the container
								iboxContainer.appendChild(ibox);

							});

						} else {
							// console.log("No data available.");
						}
					},
					error: function() {
						// console.log("Error fetching data.");
					}
				});
			}

			reloadtraining();
			//start datatables editor
			edttraining_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/training_m/training_m.php",
					type: 'POST',
					data: function (d){
						// d.id_sub_training_m = id_sub_training_m;
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
						def: "training_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "training_m.is_active",
						type: "hidden",
						def: 1
					}, 	{
						label: "Nama Training<sup class='text-danger'>*<sup>",
						name: "training_m.nama"
					}, 
					{
						label: "Thumbnail <sup class='text-danger'>*<sup>",
						name: "training_m.id_files_foto",
						type: "upload",
						display: function ( fileId, counter, action ) {
							// console.log(fileId);
							if(fileId.length > 5){
								return '<img src="'+fileId+'"/>';
							} else {
								return '<img src="'+edttraining_m.file( 'files', fileId ).web_path+'"/>';
							}
						},
						// display: function ( fileId, counter ) {
						// 	console.log(fileId);
						// 	if (fileId > 0){
						// 		return '<img src="'+edttraining_m.file( 'files', fileId ).web_path+'"/>';
						// 	}
						// },
						noFileText: 'Belum ada lampiran'
					}, 	{
						label: "Keterangan<sup class='text-danger'>*<sup>",
						name: "training_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edttraining_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edttraining_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					if(action == 'create'){
						// BEGIN of validasi training_m.nama
						if ( ! edttraining_m.field('training_m.nama').isMultiValue() ) {
							nama = edttraining_m.field('training_m.nama').val();
							if(!nama || nama == ''){
								edttraining_m.field('training_m.nama').error( 'Wajib diisi!' );
							}
							
							// BEGIN of cek unik training_m.nama
							if(action == 'create'){
								id_training_m = 0;
							}
							
							$.ajax( {
								url: '../../../helpers/validate_fn_unique.php',
								dataType: 'json',
								type: 'POST',
								async: false,
								data: {
									table_name: 'training_m',
									nama_field: 'nama',
									nama_field_value: '"'+nama+'"',
									id_transaksi: id_training_m
								},
								success: function ( json ) {
									if(json.data.count == 1){
										edttraining_m.field('training_m.nama').error( 'Data tidak boleh kembar!' );
									}
								}
							} );
							// END of cek unik training_m.nama
						}
						// END of validasi training_m.nama

						keterangan = edttraining_m.field('training_m.keterangan').val();
						if(!keterangan || keterangan == ''){
							edttraining_m.field('training_m.keterangan').error( 'Wajib diisi!' );
						}

						id_files_foto = edttraining_m.field('training_m.id_files_foto').val();
						if(!id_files_foto || id_files_foto == ''){
							edttraining_m.field('training_m.id_files_foto').error( 'Wajib diisi!' );
						}
					}
				}
				
				if ( edttraining_m.inError() ) {
					return false;
				}
			});

			edttraining_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttraining_m.field('finish_on').val(finish_on);
			});

			document.getElementById("btnCreatetraining_m").addEventListener("click", function() {
				edttraining_m.title('Create training').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary', // Add the Bootstrap primary color
						action: function () {
							this.submit(); // This will submit the form
						}
					}
				).create();
			});

			$(document).on('click', '.tr_panel a.edit', function () {
				var id = $(this).data('id');
				var foto = $(this).data('foto');
				// console.log(foto);

				// ini adalah function untuk autofill data lama
				val_edit('training_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edttraining_m.on( 'preOpen', function( e, mode, action ) {
					edttraining_m.field('training_m.nama').val(edit_val.nama);
					edttraining_m.field('training_m.keterangan').val(edit_val.keterangan);
					edttraining_m.field('training_m.id_files_foto').val(edit_val.link_foto);
				});
				
				edttraining_m.title('Edit Training').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
							reloadtraining();
						}
					}
				).edit(id);
			});

			edttraining_m.on('postSubmit', function (e, json) {
				reloadtraining();
			});

			// Remove
			$(document).on('click', '.tr_panel a.remove', function () {
				var id = $(this).data('id');

				if (confirm('Anda yakin ingin menghapus data ini?')) {
					val_edit('training_m', id, 1);
					reloadtraining();
				}
			});
			
			edttraining_m.on( 'close', function () {
				edttraining_m.enable();
			} );

			
			//start datatables editor
			edtsub_materi_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/sub_materi_m/sub_materi_m.php",
					type: 'POST',
					data: function (d){
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
						def: "sub_materi_m",
						type: "hidden"
					},	{
						label: "id_training_m",
						name: "sub_materi_m.id_training_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "sub_materi_m.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "sub_materi_m.nama"
					}
				]
			} );

			edtsub_materi_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				edtsub_materi_m.field('sub_materi_m.id_training_m').val(id_training_m);
			});

            edtsub_materi_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					
					// BEGIN of validasi sub_materi_m.nama
					if ( ! edtsub_materi_m.field('sub_materi_m.nama').isMultiValue() ) {
						nama = edtsub_materi_m.field('sub_materi_m.nama').val();
						if(!nama || nama == ''){
							edtsub_materi_m.field('sub_materi_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik sub_materi_m.nama
						if(action == 'create'){
							id_training_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'sub_materi_m',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_training_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtsub_materi_m.field('sub_materi_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik sub_materi_m.nama
					}
					// END of validasi sub_materi_m.nama
					
				}
				
				if ( edtsub_materi_m.inError() ) {
					return false;
				}
			});
			
			edtsub_materi_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtsub_materi_m.field('finish_on').val(finish_on);
			});
			
			// Edit
			$('.sub_materi').on('click', 'a.edit', function () {
				
				var id = $(this).data('id'); // ambil id yang di klik sekarang
				var match = id.match(/\d+/); // karena hasil id yang didapat adalah row_1 string
				var number = match ? parseInt(match[0]) : null; // jadinya kita ambil angkanya saja dan parse jadi integer

				// ini adalah function untuk autofill data lama
				val_edit('sub_materi_m', number, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edtsub_materi_m.on( 'preOpen', function( e, mode, action ) {
					edtsub_materi_m.field('sub_materi_m.nama').val(edit_val.nama);
				});
				edtsub_materi_m.title('Edit Sub Materi').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary', // Add the Bootstrap primary color
						action: function () {
							this.submit(); // This will submit the form
						}
					}
				).edit(id);
			});

			
			document.getElementById("btnCreatesub_materi_m").addEventListener("click", function() {
				edtsub_materi_m.title('Create sub_materi').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary', // Add the Bootstrap primary color
						action: function () {
							this.submit(); // This will submit the form
						}
					}
				).create();
			});
			//start datatables editor
			edtmateri_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/sub_materi_m/materi_m.php",
					type: 'POST',
					data: function (d){
						// d.id_sub_materi_m = id_sub_materi_m;
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
						def: "materi_m",
						type: "hidden"
					},	{
						label: "id_sub_materi_m",
						name: "materi_m.id_sub_materi_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "materi_m.is_active",
						type: "hidden",
						def: 1
					},	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "materi_m.nama"
					}, 	
					{
						label: "Jenis <sup class='text-danger'>*<sup>",
						name: "materi_m.jenis",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Video", "value": 1 },
							{ "label": "Quiz", "value": 2 }
						]
					},
					{
						label: "Tipe Quiz <sup class='text-danger'>*<sup>",
						name: "materi_m.tipe_quiz",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "Essay", "value": "Essay" },
							{ "label": "Multiple Choice", "value": "Multiple Choice" }
						]
					},
					{
						label: "Link Youtube Video <sup class='text-danger'>*<sup>",
						name: "materi_m.link_yt"
					}, 	{
						label: "Keterangan",
						name: "materi_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtmateri_m.on( 'preOpen', function( e, mode, action ) {
			});

			edtmateri_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edtmateri_m.dependent( 'materi_m.jenis', function ( val, data, callback ) {
				if (val == 1) {
					edtmateri_m.field('materi_m.link_yt').val();
					edtmateri_m.field('materi_m.link_yt').show();
					edtmateri_m.field('materi_m.tipe_quiz').val('');
					edtmateri_m.field('materi_m.tipe_quiz').hide();
				} else {
					edtmateri_m.field('materi_m.link_yt').val('');
					edtmateri_m.field('materi_m.link_yt').hide();
					edtmateri_m.field('materi_m.tipe_quiz').val();
					edtmateri_m.field('materi_m.tipe_quiz').show();
				}
				return {}
			}, {event: 'keyup change'});

			edtmateri_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					if(action == 'create'){
						// BEGIN of validasi materi_m.nama
						if ( ! edtmateri_m.field('materi_m.nama').isMultiValue() ) {
							nama = edtmateri_m.field('materi_m.nama').val();
							if(!nama || nama == ''){
								edtmateri_m.field('materi_m.nama').error( 'Wajib diisi!' );
							}
							
							// BEGIN of cek unik materi_m.nama
							if(action == 'create'){
								id_materi_m = 0;
							}
							
							$.ajax( {
								url: '../../../helpers/validate_fn_unique.php',
								dataType: 'json',
								type: 'POST',
								async: false,
								data: {
									table_name: 'materi_m',
									nama_field: 'nama',
									nama_field_value: '"'+nama+'"',
									id_transaksi: id_materi_m
								},
								success: function ( json ) {
									if(json.data.count == 1){
										edtmateri_m.field('materi_m.nama').error( 'Data tidak boleh kembar!' );
									}
								}
							} );
							// END of cek unik materi_m.nama
						}
						// END of validasi materi_m.nama

						jenis = edtmateri_m.field('materi_m.jenis').val();
						if(jenis == ''){
							edtmateri_m.field('materi_m.jenis').error( 'Wajib diisi!' );
						}

						if (jenis == 1) {
							link_yt = edtmateri_m.field('materi_m.link_yt').val();
							if(!link_yt || link_yt == ''){
								edtmateri_m.field('materi_m.link_yt').error( 'Wajib diisi!' );
							}
						} else {
							tipe_quiz = edtmateri_m.field('materi_m.tipe_quiz').val();
							if(!tipe_quiz || tipe_quiz == ''){
								edtmateri_m.field('materi_m.tipe_quiz').error( 'Wajib diisi!' );
							}
						}
					}
				}
				
				if ( edtmateri_m.inError() ) {
					return false;
				}
			});
			
			edtmateri_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtmateri_m.field('finish_on').val(finish_on);
			});

			edtmateri_m.on( 'close', function () {
				edtmateri_m.enable();
			} );

			$(document).on('click', '.materi_panel a.edit', function () {
				
				var id = $(this).data('id');

				// ini adalah function untuk autofill data lama
				val_edit('materi_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edtmateri_m.on( 'preOpen', function( e, mode, action ) {
					edtmateri_m.field('materi_m.nama').val(edit_val.nama);
					edtmateri_m.field('materi_m.jenis').val(edit_val.jenis);
					edtmateri_m.field('materi_m.link_yt').val(edit_val.link_yt);
					edtmateri_m.field('materi_m.tipe_quiz').val(edit_val.tipe_quiz);
					edtmateri_m.field('materi_m.keterangan').val(edit_val.keterangan);
				});
				edtmateri_m.title('Edit Materi').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
						}
					}
				).edit(id);
			});

			//start datatables editor
			edtquiz_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/quiz_m/quiz_m.php",
					type: 'POST',
					data: function (d){
						// d.id_materi_m = id_materi_m;
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
						def: "quiz_m",
						type: "hidden"
					},	{
						label: "id_materi_m",
						name: "quiz_m.id_materi_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "quiz_m.is_active",
						type: "hidden",
						def: 1
					}
					,{
						name: "quiz_m.nama",
						type: "textarea"
					}
					,{
						label: "Jawaban A <sup class='text-danger'>*<sup>",
						name: "quiz_m.jawaban_a"
					}
					,{
						label: "Jawaban B <sup class='text-danger'>*<sup>",
						name: "quiz_m.jawaban_b"
					}
					,{
						label: "Jawaban C <sup class='text-danger'>*<sup>",
						name: "quiz_m.jawaban_c"
					}
					,{
						label: "Jawaban D <sup class='text-danger'>*<sup>",
						name: "quiz_m.jawaban_d"
					}
					,{
						label: "Jawaban Benar <sup class='text-danger'>*<sup>",
						name: "quiz_m.jawaban_benar",
						type: "select",
						placeholder : "Select",
						options: [
							{ "label": "A", "value": 1 },
							{ "label": "B", "value": 2 },
							{ "label": "C", "value": 3 },
							{ "label": "D", "value": 4 }
						]
					}
				]
			} );

			edtquiz_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});
			
			edtquiz_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtquiz_m.field('finish_on').val(finish_on);
			});

			edtquiz_m.on( 'close', function () {
				edtquiz_m.enable();
			} );

			// $(document).on('click', '.materi_panel a.edit', function () {
				
			// 	var id = $(this).data('id');

			// 	// ini adalah function untuk autofill data lama
			// 	val_edit('quiz_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

			// 	// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
			// 	edtquiz_m.on( 'preOpen', function( e, mode, action ) {
			// 		edtquiz_m.field('quiz_m.nama').val(edit_val.nama);
			// 	});
			// 	edtquiz_m.title('Edit Quiz').buttons(
			// 		{
			// 			label: 'Submit',
			// 			className: 'btn btn-primary',
			// 			action: function () {
			// 				this.submit();
			// 			}
			// 		}
			// 	).edit(id);
			// });
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
