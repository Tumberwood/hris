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

	// BEGIN Notifications
	var is_notification, notification_interval_ms;
	$.ajax( {
		url: '../../../helpers/fn_notification_setting.php',
		dataType: 'json',
		type: 'POST',
		async: false,
		data: {},
		success: function ( json ) {
			notification_interval_ms = json.data.rs_notification_interval_setting.notification_interval_ms;
			is_notification = json.data.rs_notification_interval_setting.is_notification;
		}
	} );

	if(is_notification == 1){
		setInterval(cekNotification, notification_interval_ms);
	}

	function cekNotification(){
		$('#notification_dropdown').empty();
		// load notif
		$.ajax( {
			url: '../../../helpers/fn_notification_load.php',
			dataType: 'json',
			type: 'POST',
			data: {
				id_users: <?php echo $_SESSION['user']; ?>
			},
			success: function ( json ) {
				str = '';
				if( json.data.c_rs_notifications_unread > 0){
					$('#c_notification_unread').show();
					$('#c_notification_unread').html(json.data.c_rs_notifications_unread);
					$.each( json.data.rs_notifications, function( key, value ) {
						// Update Ferry 03 Jan 2024, Berikan data-id dengan value id_notif di dalam element li agar mudah untuk mengambil id nya di function click
						var id_notifications = value.id_notifications;

						str = '<li data-id="' + id_notifications + '">'+
								'<div id="notif_' + id_notifications + '">'+
									value.message +
									'<span class="float-right text-muted small">'+moment(value.date_created).fromNow()+'</span>'+
								'</div>'+
							'</li>';
						if(key+1 != json.data.c_rs_notifications_unread){
							str=str+'<li class="dropdown-divider"></li>';
						}
						$("#notification_dropdown").append(str);
					});
				}else{
					$('#c_notification_unread').hide();
					str='<li>No Unread Notification</li>';
					$("#notification_dropdown").append(str);
				}
			
			}
		} );
	}

	$("#notification_dropdown").on("click", "li", function () {
		//Ambil data id element li yang di click dengan this data id
		var id_notifications = $(this).data("id");
		// console.log(id_notifications);
		$.ajax({
			url: '../../../helpers/fn_notification_read.php',
			dataType: 'json',
			type: 'POST',
			data: {
				id_notifications: id_notifications
			},
			success: function (json) {
				// After successfully reading the notification, refresh the notification list
				cekNotification();
			}
		});
	});

	// END Notifications

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

	$(document).ready(function() {
		// BEGIN notification
		if(is_notification == 1){
			$("#notification_parent").show();
			cekNotification();
		}else{
			$("#notification_parent").hide();
		}
		// END notification

	} );// end of document.ready global
</script>