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
	.trainingh3 {
		color: black;
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
									console.log(id_training_m);
									$.ajax({
										url: "../../models/sub_materi_m/sub_materi_m_data.php",
										data: { id_training_m: id_training_m },
										dataType: 'json',
										success: function(data) {
											if (Array.isArray(data.data)) {
												$('#sub_materi_content').empty();
												const iboxContainer = document.getElementById("sub_materi_content");
												data.data.forEach(function(item) {
													// Create an iBox for each header (nama)
													const title = document.createElement("div");
													title.className = "ibox-title";
													title.style.display = "flex";
													title.style.justifyContent = "space-between"; // Align items to both ends of the title

													// Create the button and add it to the title
													const btn_materi = createButton("btnCreatemateri btn btn-primary btn-sm", "fa fa-plus");
													// const button = document.createElement("button");
													// button.className = "btn btn btnCreatemateri btn-sm";
													// button.title = "New";
													// button.innerHTML = '<i class="fa fa-plus"></i>';

													btn_materi.addEventListener("click", function() {
														edtmateri_m.title('Create Sub sub_materi').buttons(
															{
																label: 'Submit',
																className: 'btn btn-primary',
																action: function() {
																	this.submit();
																}
															}
														).create();
													});

													// Create the sub_materiTitle
													const sub_materiTitle = document.createElement("h5");
													sub_materiTitle.innerHTML = item.sub_materi_m.nama;

													// Create the "toggle" button (chevron icon)
													const toggleButton = document.createElement("i");
													toggleButton.className = "fa fa-chevron-up toggle-chevron";

													// Append elements to the title
													title.appendChild(btn_materi);
													title.appendChild(sub_materiTitle);
													title.appendChild(toggleButton);

													// Create the iBox and add the title to it
													const ibox = document.createElement("div");
													ibox.className = "ibox";
													ibox.appendChild(title);
													title.setAttribute("data-id-sub_materi-m", item.sub_materi_m.id);
													ibox.appendChild(title);

													// Create the iBox content
													const content = document.createElement("div");
													content.className = "ibox-content";
													ibox.appendChild(content);

													// Add the iBox to the container
													iboxContainer.appendChild(ibox);

													// Add event listeners to change the color on hover
													ibox.addEventListener('mouseenter', function() {
														sub_materiTitle.style.color = 'blue';
													});

													ibox.addEventListener('mouseleave', function() {
														sub_materiTitle.style.color = ''; // Reset the color to its default
													});

													// Add click event handler to load details via AJAX
													const chevronIcon = ibox.querySelector('.toggle-chevron');
													title.addEventListener("click", function() {
														const id_sub_materi_m = this.getAttribute("data-id-sub_materi-m");
														edtmateri_m.field('materi_m.id_sub_materi_m').val(id_sub_materi_m);

														const contentElement = this.nextElementSibling;
														if (contentElement.style.display === "none") {
															sub_materiTitle.style.color = 'red'; 
															
															chevronIcon.classList.remove('fa-chevron-up');
															chevronIcon.classList.add('fa-chevron-down');
															// Load details via AJAX when the header (nama) is clicked
															$.ajax({
																url: "../../models/sub_materi_m/materi_m_data.php",
																data: { id_sub_materi_m: id_sub_materi_m },
																dataType: 'json',
																success: function(data) {
																	var isi = data.data.length;
																	
																	if (Array.isArray(data.data)) {
																		let materiList = "<ul>";

																		data.data.forEach(function(item) {
																			let materi = item.materi_m;
																			let id = item.DT_RowId;
																			const createdOn_materi = new Date(materi.created_on);

																			trainingDibuat(createdOn_materi);
																			formatTime(createdOn_materi);

																			materiList += 
																			`
																			<div class="materi_panel" data-editor-id="${id}">
																				<small class="float-right">${timeAgoText} ago</small>
																				<strong>
																				<h3 for="sub-sub_materi-checkbox-${materi.id}">
																					<div class="row">
																						<div class="col-md-2">
																							${materi.jenis == 1
																								? `<i class="fa fa-film"></i>`
																								: `<i class="fa fa-pencil-square-o"></i>`
																							}
																						</div>
																						<div class="col-md-10">
																							${materi.nama}
																					<input id="sub-sub_materi-checkbox-${materi.id}" type="checkbox" class="sub-sub_materi-checkbox" value="${materi.id}">
																						</div>
																					</div>
																				</h3>
																				</strong>
																				<div class="tr-up">
																					<div>${materi.keterangan}</div>
																					<small class="text-muted">${formatTime(createdOn_materi)} - ${createdOn_materi.toLocaleDateString()}</small>		
																					<br>					
																					<br>					
																					<div class="row">
																						<div class="col-md-6">
																							<a href="#" class="edit btn btn-primary btn-sm" data-id="${materi.id}"><i class="fa fa-pencil"></i></a>
																							<a href="#" class="remove btn btn-danger btn-sm" data-id="${materi.id}"><i class="fa fa-trash"></i></a>
																						</div>
																					</div>
																				</div>	
																			</div>
																			<hr>
																			`
																			// `<div class="materi_panel" data-editor-id="${id}">
																			// 	${tipe}
																			// 		<div class="row">
																			// 		<div class="col-md-7">
																			// 			<label for="sub-sub_materi-checkbox-${materi.id}">
																			// 				${materi.nama}
																			// 				<input id="sub-sub_materi-checkbox-${materi.id}" type="checkbox" class="sub-sub_materi-checkbox" value="${materi.id}">
																			// 			</label>
																			// 		</div>
																			// 		<div class="col-md-5">
																			// 			<a href="#" class="edit btn btn-primary btn-sm" data-id="${materi.id}"><i class="fa fa-pencil"></i></a>
																			// 			<a href="#" class="remove btn btn-danger btn-sm" data-id="${materi.id}"><i class="fa fa-trash"></i></a>
																			// 		</div>
																			// 	</div>
																				
																			// </div>
																			// `
																			;
																		});

																		materiList += "</ul>";

																		contentElement.innerHTML = materiList;
																		contentElement.style.display = "block";

																		// Add a click event listener to capture the selected materi.id values
																		const checkboxes = document.querySelectorAll('.sub-sub_materi-checkbox');
																		let lastClickedCheckbox = null;

																		checkboxes.forEach(function(checkbox) {
																			checkbox.addEventListener('click', function() {
																				$("#materi-kanan").show();
																				const materiVideo = document.getElementById('materi');

																				if (this !== lastClickedCheckbox) {
																					// Uncheck previously selected checkbox
																					if (lastClickedCheckbox) {
																						lastClickedCheckbox.checked = false;
																					}

																					// Check the current checkbox
																					this.checked = true;
																					lastClickedCheckbox = this;

																					const subsub_materiId = this.value;
																					console.log(`Selected materi.id: ${subsub_materiId}`);

																					// Update the <h3> text inside the #materi div
																					data.data.forEach(function(item) {
																						let materi = item.materi_m;
																						if (materi.id == subsub_materiId) {
																							var link_yt = materi.link_yt;
																							var jenis_materi = materi.jenis;
																							var video_yt = '';
																							var button_quiz = '';
																							
																							if (jenis_materi == 1) {
																								if (link_yt !== null && link_yt !== undefined) {
																									var match = link_yt.match(/[?&]v=([^&]+)/);
																									if (match) {
																										link = match[1];
																										video_yt = '<iframe id="videoFrame" width="100%" height="480px" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe>';
																									}
																								}
																								materiVideo.innerHTML = video_yt;
																							} else {
																								$('#materi').empty();
																								const button_quiz = createButton("btnCreatemateri btn btn-primary btn-sm", "fa fa-plus");
																								const button_start_quiz = createButton("btnStart_quiz btn btn-primary btn-sm", "fa fa-fx");
																								
        																						button_start_quiz.innerHTML = `Start`;
																								materiVideo.appendChild(button_quiz);
																								materiVideo.appendChild(button_start_quiz);

																								let isCountdownStarted = false;

																								button_start_quiz.addEventListener("click", function() {
																									const materiKanan = $("#materi-kanan");
																									materiKanan.removeClass("col-lg-8").addClass("col-lg-12");
																									$("#materi-kiri").hide();
																									$("#judul-tr").hide() 
																									$("#hide-tr").hide() 
																									$("#materi-sidebar").hide() 
																									countdownMinutes = 1; // Reset countdown minutes
																									countdownSeconds = 0; // Reset countdown seconds
																									console.log('kawoawkokawokawo');
																									
																									materiVideo.innerHTML = `
																										<div class="row">
																											<div class="col-lg-8">
																											</div>
																											<div class="col-lg-4">
																												<div class="widget navy-bg p-sm text-center">
																													<div class="m-b-sm">
																														<i class="fa fa-clock-o fa-4x"></i>
																														<h3 class="m-xs" id="countdown-timer"></h3>
																													</div>
																												</div>
																											</div>
																										</div>
																									`
																									;
																									updateCountdown(); // Start the countdown
																									countdownInterval = setInterval(updateCountdown, 1000);
																									window.addEventListener("beforeunload", function (e) {
																										e.returnValue = "Leaving this page will stop the countdown. Are you sure?";
																									});
																								});

																								button_quiz.addEventListener("click", function() {
																									edtmateri_m.title('Create Sub sub_materi').buttons(
																										{
																											label: 'Submit',
																											className: 'btn btn-primary',
																											action: function() {
																												this.submit();
																											}
																										}
																									).create();
																								});
																							}
																							// Set the video title
																							var h3Title = document.getElementById('judul');
																							h3Title.textContent = materi.nama;

																							// Append the h3 title to materiVideo
																						}
																					});
																				} else {
																					// Deselect the currently selected checkbox
																					this.checked = false;
																					lastClickedCheckbox = null;
																					$("#materi-kanan").hide();
																					$('#materi').empty();
																				}
																			});
																		});
																	} else {
																		contentElement.innerHTML = "<p>Data structure is invalid.</p>";
																		contentElement.style.display = "block";
																	}
																},
																error: function() {
																	contentElement.innerHTML = "<p>Failed to load details.</p>";
																	contentElement.style.display = "block";
																}
															});
														} else {
															contentElement.style.display = "none";
															chevronIcon.classList.remove('fa-chevron-down');
															chevronIcon.classList.add('fa-chevron-up');
														}
													});
												});
											} else {
												console.log("No data available.");
											}
										},
										error: function() {
											console.log("Error fetching data.");
										}
									});
								});

								ibox.setAttribute("data-id-training-m", train.training_m.id);
								
								// Add the ibox to the container
								iboxContainer.appendChild(ibox);

							});

						} else {
							console.log("No data available.");
						}
					},
					error: function() {
						console.log("Error fetching data.");
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
							console.log(fileId);
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
				console.log(foto);

				// ini adalah function untuk autofill data lama
				val_edit('training_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edttraining_m.on( 'preOpen', function( e, mode, action ) {
					edttraining_m.field('training_m.nama').val(edit_val.nama);
					edttraining_m.field('training_m.keterangan').val(edit_val.keterangan);
					edttraining_m.field('training_m.id_files_foto').val(edit_val.link_foto);
				});
				
				edttraining_m.title('Edit materi').buttons(
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
					},	
					{
						label: "Keterangan",
						name: "sub_materi_m.keterangan",
						type: "textarea"
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
							id_sub_materi_m = 0;
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
								id_transaksi: id_sub_materi_m
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
				edtsub_materi_m.title('Edit materi').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary', // Add the Bootstrap primary color
						action: function () {
							this.submit(); // This will submit the form
						}
					}
				).edit(id);
			});

			edtsub_materi_m.on('postSubmit', function (e, json) {
				reloadtraining();
			});

			// Remove
			$('.sub_materi').on('click', 'a.remove', function () {
				var id = $(this).data('id'); // ambil id yang di klik sekarang
				var match = id.match(/\d+/); // karena hasil id yang didapat adalah row_1 string
				var number = match ? parseInt(match[0]) : null; // jadinya kita ambil angkanya saja dan parse jadi integer

				edtsub_materi_m.title('Delete materi').buttons(
					{
						label: 'Delete',
						className: 'btn btn-danger', // Add the Bootstrap primary color
						action: function () {
							val_edit('sub_materi_m', number, 1);
							// location.reload();
							reloadtraining();
						}
					}
				).message('Anda yakin ingin menghapus data ini?').remove(id);
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
				});
				edtmateri_m.title('Edit materi').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
						}
					}
				).edit(id);
			});

			edtmateri_m.on('postSubmit', function (e, json) {
				reloadtraining();
			});

			// Remove
			$(document).on('click', '.materi_panel a.remove', function () {
				var id = $(this).data('id');

				if (confirm('Anda yakin ingin menghapus data ini?')) {
					val_edit('materi_m', id, 1);
					reloadtraining();
				}
			});
			

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
