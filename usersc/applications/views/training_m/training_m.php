<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel    = 'sub_training_m';
	$nama_tabels_d = [];
	
	// include('../../models/training_m/fn_sub_training_m.php'); // Include the PHP file with your data

	// if (isset($data['rs_training_m']) && !empty($data['rs_training_m'])) {
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
</style>
<!-- begin content here -->

<div class="row">
	<div class="col-md-4">
		<button id="btnCreatetraining_m" class="btn btn-primary" title="New"><i class="fa fa-plus"></i> training</button>
		<div id="ibox-container"></div>
	</div>
	<div class="col-md-8">
		<h3 id="judul"></h3>
		<br>
		<div id="materi"></div>
	</div>
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
		var edtsub_training_m, tblsub_training_m, show_inactive_status_sub_training_m = 0, id_sub_training_m;
		var edttraining_m, tbltraining_m, show_inactive_status_training_m = 0, id_training_m;
		// ------------- end of default variable

		

		$(document).ready(function() {

			function reloadtraining(){
				$.ajax({
				url: "../../models/training_m/training_m.php",
				dataType: 'json',
					success: function(data) {
						if (Array.isArray(data.data)) {
							$('#ibox-container').empty();
							const iboxContainer = document.getElementById("ibox-container");
							data.data.forEach(function(item) {
								// Create an iBox for each header (nama)
								const ibox = document.createElement("div");
								ibox.className = "ibox";

								// Create the iBox title
								const title = document.createElement("div");
								title.className = "ibox-title";

								// Create the button and add it to the title
								const button = document.createElement("button");
								button.className = "btn btn btnCreatesub_training";
								button.title = "New";
								button.innerHTML = '<i class="fa fa-plus"></i>';
								title.appendChild(button);

								button.addEventListener("click", function() {
									edtsub_training_m.title('Create Sub Training').buttons(
										{
											label: 'Submit',
											className: 'btn btn-primary',
											action: function() {
												this.submit();
											}
										}
									).create();
								});
								const trainingTitle = document.createElement("h5");
								trainingTitle.innerHTML = item.training_m.nama;
								title.appendChild(trainingTitle);

								title.setAttribute("data-id-training-m", item.training_m.id);
								ibox.appendChild(title);

								// Create the iBox content
								const content = document.createElement("div");
								content.className = "ibox-content";
								ibox.appendChild(content);

								// Add the iBox to the container
								iboxContainer.appendChild(ibox);

								// Add event listeners to change the color on hover
								ibox.addEventListener('mouseenter', function() {
									trainingTitle.style.color = 'blue';
								});

								ibox.addEventListener('mouseleave', function() {
									trainingTitle.style.color = ''; // Reset the color to its default
								});

								// Add click event handler to load details via AJAX
								title.addEventListener("click", function() {
									const id_training_m = this.getAttribute("data-id-training-m");
									edtsub_training_m.field('sub_training_m.id_training_m').val(id_training_m);
									

									const contentElement = this.nextElementSibling;
									if (contentElement.style.display === "none") {
										trainingTitle.style.color = 'red'; 
										// Load details via AJAX when the header (nama) is clicked
										$.ajax({
											url: "../../models/training_m/sub_training_m_data.php",
											data: { id_training_m: id_training_m },
											dataType: 'json',
											success: function(data) {
												if (Array.isArray(data.data)) {
													let sub_trainingList = "<ul>";

													data.data.forEach(function(item) {
														let sub_training = item.sub_training_m;
														let id = item.DT_RowId;
														sub_trainingList += 
														`<div class="panel" data-editor-id="${id}">
															<li>
																<label for="sub-training-checkbox-${sub_training.id}">
																	${sub_training.nama}
																	<input id="sub-training-checkbox-${sub_training.id}" type="checkbox" class="sub-training-checkbox" value="${sub_training.id}">
																</label>
																<a href="#" class="edit" data-id="${sub_training.id}"><i class="fa fa-pencil"></i></a>
																<a href="#" class="remove" data-id="${sub_training.id}"><i class="fa fa-trash"></i></a>
															</li>
														</div>
														`;
													});

													sub_trainingList += "</ul>";

													contentElement.innerHTML = sub_trainingList;
													contentElement.style.display = "block";

													// Add a click event listener to capture the selected sub_training.id values
													const checkboxes = document.querySelectorAll('.sub-training-checkbox');
													let lastClickedCheckbox = null;

													checkboxes.forEach(function(checkbox) {
														checkbox.addEventListener('click', function() {
															$('#materi').empty();
															const materiVideo = document.getElementById('materi');

															if (this !== lastClickedCheckbox) {
																// Uncheck previously selected checkbox
																if (lastClickedCheckbox) {
																	lastClickedCheckbox.checked = false;
																}

																// Check the current checkbox
																this.checked = true;
																lastClickedCheckbox = this;

																const subTrainingId = this.value;
																console.log(`Selected sub_training.id: ${subTrainingId}`);

																// Update the <h3> text inside the #materi div
																data.data.forEach(function(item) {
																	let sub_training = item.sub_training_m;
																	if (sub_training.id == subTrainingId) {
																		var link_yt = sub_training.link_yt;
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
																		h3Title.textContent = sub_training.nama;

																		// Append the h3 title to materiVideo
																		materiVideo.innerHTML = video_yt;
																	}
																});
															} else {
																// Deselect the currently selected checkbox
																this.checked = false;
																lastClickedCheckbox = null;
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

			reloadtraining();
			//start datatables editor
			edttraining_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/training_m/training_m.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status_training_m = show_inactive_status_training_m;
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
					},	
					{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "training_m.nama"
					},	
					{
						label: "Keterangan",
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
					
				}
				
				if ( edttraining_m.inError() ) {
					return false;
				}
			});
			
			edttraining_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edttraining_m.field('finish_on').val(finish_on);
			});
			
			// Edit
			$('.training').on('click', 'a.edit', function () {
				
				var id = $(this).data('id'); // ambil id yang di klik sekarang
				var match = id.match(/\d+/); // karena hasil id yang didapat adalah row_1 string
				var number = match ? parseInt(match[0]) : null; // jadinya kita ambil angkanya saja dan parse jadi integer

				// ini adalah function untuk autofill data lama
				val_edit('training_m', number, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edttraining_m.on( 'preOpen', function( e, mode, action ) {
					edttraining_m.field('training_m.nama').val(edit_val.nama);
				});
				edttraining_m.title('Edit sub_training').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary', // Add the Bootstrap primary color
						action: function () {
							this.submit(); // This will submit the form
						}
					}
				).edit(id);
			});

			edttraining_m.on('postSubmit', function (e, json) {
				reloadtraining();
			});

			// Remove
			$('.training').on('click', 'a.remove', function () {
				var id = $(this).data('id'); // ambil id yang di klik sekarang
				var match = id.match(/\d+/); // karena hasil id yang didapat adalah row_1 string
				var number = match ? parseInt(match[0]) : null; // jadinya kita ambil angkanya saja dan parse jadi integer

				edttraining_m.title('Delete sub_training').buttons(
					{
						label: 'Delete',
						className: 'btn btn-danger', // Add the Bootstrap primary color
						action: function () {
							val_edit('training_m', number, 1);
							// location.reload();
							reloadtraining();
						}
					}
				).message('Are you sure you wish to delete this record?').remove(id);
			});

			document.getElementById("btnCreatetraining_m").addEventListener("click", function() {
				edttraining_m.title('Create Training').buttons(
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
			edtsub_training_m = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/training_m/sub_training_m.php",
					type: 'POST',
					data: function (d){
						d.id_training_m = id_training_m;
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
						def: "sub_training_m",
						type: "hidden"
					},	{
						label: "id_training_m",
						name: "sub_training_m.id_training_m",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "sub_training_m.is_active",
						type: "hidden",
						def: 1
					},	{
						label: "Kode <sup class='text-danger'>*<sup>",
						name: "sub_training_m.kode"
					}, 	{
						label: "Nama <sup class='text-danger'>*<sup>",
						name: "sub_training_m.nama"
					}, 	{
						label: "Link Youtube Video",
						name: "sub_training_m.link_yt"
					}, 	{
						label: "Keterangan",
						name: "sub_training_m.keterangan",
						type: "textarea"
					}
				]
			} );

			edtsub_training_m.on( 'preOpen', function( e, mode, action ) {
				edtsub_training_m.field('sub_training_m.id_training_m').val(id_training_m);
			});

			edtsub_training_m.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
			});

			edtsub_training_m.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi sub_training_m.kode
					if ( ! edtsub_training_m.field('sub_training_m.kode').isMultiValue() ) {
						kode = edtsub_training_m.field('sub_training_m.kode').val();
						if(!kode || kode == ''){
							edtsub_training_m.field('sub_training_m.kode').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik sub_training_m.kode
						if(action == 'create'){
							id_sub_training_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'sub_training_m',
								nama_field: 'kode',
								nama_field_value: '"'+kode+'"',
								id_transaksi: id_sub_training_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtsub_training_m.field('sub_training_m.kode').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik sub_training_m.kode
					}
					// END of validasi sub_training_m.kode
					
					// BEGIN of validasi sub_training_m.nama
					if ( ! edtsub_training_m.field('sub_training_m.nama').isMultiValue() ) {
						nama = edtsub_training_m.field('sub_training_m.nama').val();
						if(!nama || nama == ''){
							edtsub_training_m.field('sub_training_m.nama').error( 'Wajib diisi!' );
						}
						
						// BEGIN of cek unik sub_training_m.nama
						if(action == 'create'){
							id_sub_training_m = 0;
						}
						
						$.ajax( {
							url: '../../../helpers/validate_fn_unique.php',
							dataType: 'json',
							type: 'POST',
							async: false,
							data: {
								table_name: 'sub_training_m',
								nama_field: 'nama',
								nama_field_value: '"'+nama+'"',
								id_transaksi: id_sub_training_m
							},
							success: function ( json ) {
								if(json.data.count == 1){
									edtsub_training_m.field('sub_training_m.nama').error( 'Data tidak boleh kembar!' );
								}
							}
						} );
						// END of cek unik sub_training_m.nama
					}
					// END of validasi sub_training_m.nama

					
				}
				
				if ( edtsub_training_m.inError() ) {
					return false;
				}
			});
			
			edtsub_training_m.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtsub_training_m.field('finish_on').val(finish_on);
			});

			$(document).on('click', '.panel a.edit', function () {
				
				var id = $(this).data('id');

				// ini adalah function untuk autofill data lama
				val_edit('sub_training_m', id, 0); // nama tabel dan id yang parse int agar dinamis bisa digunakan banyak tabel dan is_delete

				// preopen saya pindah kesini karena biar data old ditampilkan dulu sebelum dibuka formnya
				edtsub_training_m.on( 'preOpen', function( e, mode, action ) {
					edtsub_training_m.field('sub_training_m.nama').val(edit_val.nama);
					edtsub_training_m.field('sub_training_m.kode').val(edit_val.kode);
					edtsub_training_m.field('sub_training_m.link_yt').val(edit_val.link_yt);
				});
				edtsub_training_m.title('Edit sub_training').buttons(
					{
						label: 'Submit',
						className: 'btn btn-primary',
						action: function () {
							this.submit();
						}
					}
				).edit(id);
			});

			edtsub_training_m.on('postSubmit', function (e, json) {
				reloadtraining();
			});

			// Remove
			$(document).on('click', '.panel a.remove', function () {
				var id = $(this).data('id');

				if (confirm('Are you sure you wish to delete this record?')) {
					val_edit('sub_training_m', id, 1);
					reloadtraining();
				}
			});

		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
