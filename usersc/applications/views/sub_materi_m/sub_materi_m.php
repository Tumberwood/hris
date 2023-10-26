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
	
	// include('../../models/sub_materi_m/fn_materi_m.php'); // Include the PHP file with your data

	// if (isset($data['rs_sub_materi_m']) && !empty($data['rs_sub_materi_m'])) {
	// } else {
	// 	echo 'No data available.';
	// }
?>
<style>
	/* Style for the collapsible iBox */
	.ibox {
		background-color: #fff;
		margin-bottom: 25px;
		border: 1px solid #e7eaec;
		border-top: 2px solid #18A689;
		border-radius: 5px;
		box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
	}

	.ibox-title {
		padding: 10px 15px;
		background-color: #f3f3f4;
		border-bottom: 1px solid #e7eaec;
		cursor: pointer;
	}

	.ibox-content {
		padding: 15px;
		display: none;
	}
	.ibox:hover h5 {
		color: blue;
	}
	.d-none {
		display: none; /* CSS class to hide an element */
	}
</style>
<!-- begin content here -->

<button id="btnCreatesub_materi_m" class="btn btn-primary" title="New"><i class="fa fa-plus"></i> sub_materi</button>

<div class="row">
	<div class="col-md-4" id="kiri">
		<div id="ibox-container"></div>
	</div>
	<div class="col-md-8" id="kanan">
		<div class="ibox">
			<div class="ibox-title">
				<div class="row">
					<div class="col-md-6">
						<button id="toggleSidebar" class="btn btn-primary" title="Toggle Sidebar"><i class="fa fa-bars"></i></button>
					</div>
					<div class="col-md-6">
						<h3 id="judul"></h3>
					</div>
				</div>
			</div>
			<div id="materi"></div>
		</div>
		<br>
	</div>
</div>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_modal_log.php'; ?>

<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php require_once $abs_us_root . $us_url_root . 'usersc/applications/views/sub_materi_m/fn/sub_materi_m_fn.php'; ?>

<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtmateri_m, tblmateri_m, show_inactive_status_materi_m = 0, id_materi_m;
		var edtsub_materi_m, tblsub_materi_m, show_inactive_status_sub_materi_m = 0, id_sub_materi_m;
		// ------------- end of default variable

		

		$(document).ready(function() {
			$("#kanan").hide();
			
			$("#toggleSidebar").click(function () {
				$("#kiri").toggleClass("d-none"); // Toggle the "d-none" class to hide or show the element
				$("#kanan").toggleClass("col-md-12"); // Toggle "col-md-12" when "kiri" is hidden
			});
			
			function reloadsub_materi(){
				$.ajax({
					url: "../../models/sub_materi_m/sub_materi_m.php",
					dataType: 'json',
					success: function(data) {
						if (Array.isArray(data.data)) {
							$('#ibox-container').empty();
							const iboxContainer = document.getElementById("ibox-container");
							data.data.forEach(function(item) {
								// Create an iBox for each header (nama)
								const title = document.createElement("div");
								title.className = "ibox-title";
								title.style.display = "flex";
								title.style.justifyContent = "space-between"; // Align items to both ends of the title

								// Create the button and add it to the title
								const button = document.createElement("button");
								button.className = "btn btn btnCreatemateri btn-sm";
								button.title = "New";
								button.innerHTML = '<i class="fa fa-plus"></i>';

								button.addEventListener("click", function() {
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
								title.appendChild(button);
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
														materiList += 
														`<div class="panel" data-editor-id="${id}">
															<li>
																<div class="row">
																<div class="col-md-7">
																	<label for="sub-sub_materi-checkbox-${materi.id}">
																		${materi.nama}
																		<input id="sub-sub_materi-checkbox-${materi.id}" type="checkbox" class="sub-sub_materi-checkbox" value="${materi.id}">
																	</label>
																</div>
																<div class="col-md-5">
																	<a href="#" class="edit btn btn-primary btn-sm" data-id="${materi.id}"><i class="fa fa-pencil"></i></a>
																	<a href="#" class="remove btn btn-danger btn-sm" data-id="${materi.id}"><i class="fa fa-trash"></i></a>
																</div>
															</div>
															</li>
														</div>
														`;
													});

													materiList += "</ul>";

													contentElement.innerHTML = materiList;
													contentElement.style.display = "block";

													// Add a click event listener to capture the selected materi.id values
													const checkboxes = document.querySelectorAll('.sub-sub_materi-checkbox');
													let lastClickedCheckbox = null;

													checkboxes.forEach(function(checkbox) {
														checkbox.addEventListener('click', function() {
															$("#kanan").show();
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
																		var video_yt = '';

																		if (link_yt !== null && link_yt !== undefined) {
																			var match = link_yt.match(/[?&]v=([^&]+)/);
																			if (match) {
																				link = match[1];
																				video_yt = '<iframe id="videoFrame" width="100%" height="480px" src="https://www.youtube.com/embed/'+link+'" frameborder="0" allowfullscreen></iframe>';
																			}
																		}
																		 // Set the video title
																		var h3Title = document.getElementById('judul');
																		h3Title.textContent = materi.nama;

																		// Append the h3 title to materiVideo
																		materiVideo.innerHTML = video_yt;
																	}
																});
															} else {
																// Deselect the currently selected checkbox
																this.checked = false;
																lastClickedCheckbox = null;
																$("#kanan").hide();
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
			}

			reloadsub_materi();
			//start datatables editor
			edtsub_materi_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/sub_materi_m/sub_materi_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_sub_materi_m = show_inactive_status_sub_materi_m;
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
				reloadsub_materi();
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
							reloadsub_materi();
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
						d.id_sub_materi_m = id_sub_materi_m;
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
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "materi_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "materi_m.nama"
					}, 	{
						label: "Link Youtube Video",
						name: "materi_m.link_yt"
					}, 	{
						label: "Keterangan",
						name: "materi_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtmateri_m.on( 'preOpen', function( e, mode, action ) {
				edtmateri_m.field('materi_m.id_sub_materi_m').val(id_sub_materi_m);
			});

			edtmateri_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edtmateri_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					if(action == 'create'){
						// BEGIN of validasi materi_m.kode
						if ( ! edtmateri_m.field('materi_m.kode').isMultiValue() ) {
							kode = edtmateri_m.field('materi_m.kode').val();
							if(!kode || kode == ''){
								edtmateri_m.field('materi_m.kode').error( 'Wajib diisi!' );
							}
							
							// BEGIN of cek unik materi_m.kode
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
									nama_field: 'kode',
									nama_field_value: '"'+kode+'"',
									id_transaksi: id_materi_m
								},
								success: function ( json ) {
									if(json.data.count == 1){
										edtmateri_m.field('materi_m.kode').error( 'Data tidak boleh kembar!' );
									}
								}
							} );
							// END of cek unik materi_m.kode
						}
						// END of validasi materi_m.kode
						
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

			$(document).on('click', '.panel a.edit', function () {
				
				var id = $(this).data('id');

				// ini adalah function untuk autofill data lama
				val_edit('materi_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edtmateri_m.on( 'preOpen', function( e, mode, action ) {
					edtmateri_m.field('materi_m.nama').val(edit_val.nama);
					edtmateri_m.field('materi_m.kode').val(edit_val.kode);
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
				reloadsub_materi();
			});

			// Remove
			$(document).on('click', '.panel a.remove', function () {
				var id = $(this).data('id');

				if (confirm('Anda yakin ingin menghapus data ini?')) {
					val_edit('materi_m', id, 1);
					reloadsub_materi();
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
