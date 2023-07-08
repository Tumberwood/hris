<script>
	// bug fix select2 di buka di firefox tidak mau di klik
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};
	
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	});
		
	// membuat left menu expand saat dipilih
	/** add active class and stay opened when selected */
	var url = window.location;

	// for side-menu
	$('ul.nav-second-level a').filter(function() {
		 return this.href == url;
	}).parentsUntil("#side-menu").addClass('active');

	// end of membuat left menu expand saat dipilih
	
	// toastr setup
	toastr.options = {
		closeButton: true,
		debug: false,
		newestOnTop: true,
		progressBar: true,
		positionClass: "toast-top-right",
		preventDuplicates: false,
		onclick: null,
		showDuration: "200",
		hideDuration: "1000",
		timeOut: "5000",
		extendedTimeOut: "1000",
		showEasing: "swing",
		hideEasing: "linear",
		showMethod: "fadeIn",
		hideMethod: "fadeOut"
	}
	// end of toastr setup

	// start of setup search menu
	// $("#selectmenu").select2({
	// 	placeholder: 'Search Menu ... ',
	// 	allowClear: true,
	// 	ajax: {
	// 		url: "../../helpers/load_select2_menu.php",
	// 		dataType: 'json',
	// 		data: function (params) {
	// 			return {
	// 				q          : params.term, // search term
	// 				page       : params.page,
	// 				rows       : 10
	// 			};
	// 		},
	// 		processResults: function (data, params) {
	// 			return {
	// 				results: data
	// 			};
	// 		},
	// 		minimumInputLength     : 2,
	// 		maximum                : 10,
	// 		delay                  : 500,
	// 		maximumSelectionLength : 5,
	// 		minimumResultsForSearch: -1,
	// 	}
	// }); 
	
	// $('#selectmenu').on('select2:select', function (e) { 
		
	// 	var data_pages = e.params.data.page;
	// 	var new_url = window.location.origin + "/solusiapp_starterpack/" + data_pages;
		
	// 	var url = window.location.href;  
	// 	window.location.href = new_url;
	// });
	// end of setup search menu

	// global variable
	var hak_c               = <?php echo json_encode($hak_c) ?>;
	var hak_u               = <?php echo json_encode($hak_u) ?>;
	var hak_d               = <?php echo json_encode($hak_d) ?>;
	var hak_a               = <?php echo json_encode($hak_a) ?>;
	
	var is_defaultprogram	= 0;
	var is_approve          = 0;	// status persetujuan transaksi
	var is_nextprocess      = 0;	// sudah ditarik ke proses selanjutnya atau belum
	var is_jurnal 		    = 0;
	var is_valid_harga_d    = 0;
	var is_need_generate_kode = 0;	// untuk generate kode saat approve
	var is_need_delete	    = 1; 	
	var is_need_approval    = 0;	// untuk cek apakah transaksi perlu approval atau tidak
	var is_need_inventory   = 0;	// menentukan apakah transaksi terkait inventory atau tidak
	var is_need_jurnal 	 	= 0;	// menentukan apakah transaksi terkait jurnal accounting atau tidak
	var is_active 	  	    = 0;
	var kategori_dokumen	= '';
	var kategori_dokumen_value = '';

	var act;	//pengganti action datatables editor, untuk dibawa ke dependent
	var is_pkp, is_ppn, persen_ppn, ppn_in_ex, is_pph, persen_pph, pph_in_ex;

	var tanggal_hariini_dmy = moment().format('DD MMM YYYY');
	var awal_bulan_dmy = moment().startOf('month').format('DD MMM YYYY');
	var akhir_bulan_dmy = moment().endOf('month').format('DD MMM YYYY');

	var tanggal_hariini_ymd = moment().format('YYYY-MM-DD');
	var awal_bulan_ymd = moment().startOf('month').format('YYYY-MM-DD');
	var akhir_bulan_ymd = moment().endOf('month').format('YYYY-MM-DD');

	var nama_tabels_d = <?php echo json_encode($nama_tabels_d);?>;
	var imtxxmh_kode = 0; // untuk movement type
</script>