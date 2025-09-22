<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>
<link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/plugins/pivottable/pivot.min.css" rel="stylesheet">

<?php
	$nama_tabel    = 'htsprrd';
	$nama_tabels_d = [];

	if (isset($_GET['id_hemxxmh'])){
		$id_hemxxmh		= $_GET['id_hemxxmh'];
	} else {
		$id_hemxxmh		= 0;
	}
	if (isset($_GET['start_date'])){
		$awal		= ($_GET['start_date']);
	} else {
		$awal = null;
	}
?>

<!-- begin content here -->

<div class="row">
    <div class="col">
        <div class="ibox" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp
                <button class="btn btn-primary btn-xs collapse-link"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" id="frmhtsprrd">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periode</label>
                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">
                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>
					<div class="form-group row">
						<label class="col-sm-2 col-form-label">Employee</label>
						<div class="col-sm-4">
							<div class="input-group">
								<div class="row">
									<div class="col-md-10">
										<select class="form-control" id="select_hemxxmh" name="select_hemxxmh"></select>
									</div>
									<div class="col-md-2">
										<button class="btn btn-danger" id="clearSelect"><i class="fa fa-times"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button class="btn btn-primary" type="submit" id="go">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
				<div class="table-responsive">
                    <table id="tblhtsprrd" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>NRP</th>
                                <th>Nama</th>
                                <th>Saldo Awal</th>
                                <th>Plus</th>
                                <th>Minus</th>
                                <th>Sisa Saldo</th>
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
		var edthtsprrd, tblhtsprrd, show_inactive_status_htsprrd = 0;
		// ------------- end of default variable
		var notifyprogress = '';
		var id_hemxxmh_old = 0;
		var id_hemxxmh_filter = 0, tanggal_old = '';
		
		// BEGIN datepicker init
		$('#periode').datepicker({
			setDate: new Date(),
			autoclose: true,
			todayHighlight: true,
			clearBtn: true,
			format: "yyyy",
			minViewMode: 'month' 
		});
		$('#start_date').datepicker('setDate', tanggal_hariini_dmy);
        // END datepicker init
        
        // BEGIN select2 init
        $("#select_hemxxmh").select2({
			placeholder: 'Ketik atau TekanTanda Panah Kanan',
			ajax: {
				url: "../../models/hemxxmh/hemxxmh_fn_opt_all.php",
				dataType: 'json',
				data: function (params) {
					var query = {
						id_hemxxmh_old: id_hemxxmh_old,
						search: params.term || '',
						page: params.page || 1
					}
						return query;
				},
				processResults: function (data, params) {
					var options = data.results.map(function (result) {
						return {
							id: result.id,
							text: result.text
						};
					});

					//add by ferry agar auto select 07 sep 23
					if (params.page && params.page === 1) {
						$('#select_hemxxmh').empty().select2({ data: options });
					} else {
						$('#select_hemxxmh').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
					}

					return {
						results: options,
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
			}
			
		});
        // END select2 init

		$("#clearSelect").click(function () {
			$("#select_hemxxmh").val(null).trigger('change');
			id_hem_get = 0;
		});
		
		$(document).ready(function() {
			id_hemxxmh = $('#select_hemxxmh').val();
			start_date = moment($('#start_date').val()).format('YYYY');

			//start datatables
			tblhtsprrd = $('#tblhtsprrd').DataTable( {
				ajax: {
					url: "../../models/kartu_cuti/kartu_cuti.php",
					type: 'POST',
					data: function (d){
						d.start_date = start_date;
						d.id_hemxxmh = id_hemxxmh;
						d.show_inactive_status_htsprrd = show_inactive_status_htsprrd;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "id_hemxxmh",visible:false },
					{ data: "tanggal" },
					{ data: "kode" },
					{ data: "nrp" },
					{ data: "nama" },
					{ data: "saldo_awal", class: "text-right" },
					{ data: "plus", class: "text-right" },
					{ data: "minus", class: "text-right" },
					{ data: "sisa_saldo", class: "text-right" },
				],
				buttons: [
					// BEGIN breaking generate button
					<?php
						$id_table    = 'id_htsprrd';
						$table       = 'tblhtsprrd';
						$edt         = 'edthtsprrd';
						$show_status = '_htsprrd';
						$table_name  = $nama_tabel;

						$arr_buttons_tools 		= ['show_hide','copy','excel','colvis'];
						$arr_buttons_action 	= [];
						$arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
				}
			} );

			$("#frmhtsprrd").submit(function(e) {
				e.preventDefault();
			}).validate({
				rules: {
	
				},
				submitHandler: function(frmhtsprrd) {
					id_hemxxmh = $('#select_hemxxmh').val();
					start_date = moment($('#start_date').val()).format('YYYY');
					
					// notifyprogress = $.notify({
					// 	message: 'Processing ...</br> Jangan tutup halaman sampai notifikasi ini hilang!'
					// }, {
					// 	z_index: 9999,
					// 	allow_dismiss: false,
					// 	type: 'info',
					// 	delay: 0
					// });

					tblhtsprrd.ajax.reload(null,false);
					// addCeklok();
					return false;
				}
			});
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
