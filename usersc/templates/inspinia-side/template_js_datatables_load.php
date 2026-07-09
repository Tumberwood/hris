<!-- datatables main -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/DataTables/datatables.min.js"></script>

<!-- datatables 3rd party plugins -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/DataTables/datatables.mark.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/DataTables/datetime-moment.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/DataTables/sum().js"></script>

<!-- datatables editor main -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/dataTables.editor.min.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/editor.bootstrap4.min.js"></script>

<!-- datatables editor plugins -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/editor.select2.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/editor.mask.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/editor.selectize.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/editor/editor.quill.js"></script>

<script>
// Setup Default for Datatables
// If you want to change the setup, please write individually on each page

var colCount;
var colvisCount;

$.fn.dataTable.moment('DD MMM YYYY');

$.extend( true, $.fn.dataTable.Editor.defaults, {
	formOptions: {
		main: {
			focus: 4,
			onBackground: 'none'
		}
	}
} );

$.extend( true, $.fn.dataTable.defaults, {
	dom: 
	"<'row'<'col-lg-4 col-md-4 col-sm-12 col-xs-12'l><'col-lg-8 col-md-8 col-sm-12 col-xs-12'f>>" +
	"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'B>>" +
	"<'row'<'col-lg-12 col-md-12 col-sm-12 col-xs-12'tr>>" +
	"<'row'<'col-lg-5 col-md-5 col-sm-12 col-xs-12'i><'col-lg-7 col-md-7 col-sm-12 col-xs-12'p>>",
	lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
	pageLength: 10,
	language: {
		paginate: {
			first: "<<",
			previous: "<",
			next: ">",
			last: ">>"
		}
	},
	mark: true,
	select: true,
	processing: true,
	deferRender: true,
	responsive: true,
	fixedHeader: {
		header: true,
		// footer: true
	},
	colReorder: true
	
} );

$('table').on( 'init.dt', function () {
	colCount = $('table').DataTable().columns().header().length;
	var i;
	colvisCount = '0'
	for (i = 1; i < colCount; i++) { 
		colvisCount += "," + i;
	}
} );

function autofillField(tbl_name, id_transaksi, fields_name){
    $.ajax( {
        url: '../../../helpers/fn_autofillField.php',
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            tbl_name: tbl_name,
            id_transaksi: id_transaksi,
            fields_name: fields_name
        },
        success: function ( json ) {
            if(json.data.status_code == 200){
                autofillData = json.data.rs_autofill;
            }else{
                autofillData = [];
            }
        }
    } );
}

function notifyLoading(){
	// Show loading notification
	var startTime = Date.now();
	var timerInterval;

	Swal.fire({
		title: 'Processing...',
		html: `
			<div>Jangan tutup halaman sampai proses selesai</div>
			<div style="margin-top:10px;font-size:16px;font-weight:bold">
				Waktu: <b id="elapsed">0</b> detik
			</div>
		`,
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();

			timerInterval = setInterval(() => {
				const seconds = Math.floor((Date.now() - startTime) / 1000);
				const el = Swal.getHtmlContainer().querySelector('#elapsed');
				if (el) {
					el.textContent = seconds;
				}
			}, 1000);
		},
		willClose: () => {
			clearInterval(timerInterval);
		}
	});

	notifyprogress = {
		close: function () {
			Swal.close();
		}
	};
}

function notifyLoadingDomba() {
	let startTime = Date.now();
	let timerInterval;

	Swal.fire({
		title: 'Processing...',
		html: `
		<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">

			<div style="font-size:15px;">
				Jangan tutup halaman sampai proses selesai
			</div>

			<div style="font-size:18px;font-weight:bold;margin:10px 0 15px;">
				⏱️ Waktu: <span id="elapsed">0</span> detik
			</div>

			<div style="width:100%;display:flex;justify-content:center;align-items:center;overflow:hidden;">
				<img
					src="https://media.tenor.com/qMhzJcj_lgMAAAAj/home-sheep-home-shaun-the-sheep.gif"
					alt="Running Sheep"
					style="
						width:100px;
						height:auto;
						display:block;
						transform:translate(-20px, 0px);
					"
				/>
			</div>

		</div>
		`,
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {

			timerInterval = setInterval(() => {
				const seconds = Math.floor((Date.now() - startTime) / 1000);
				const el = Swal.getHtmlContainer().querySelector('#elapsed');
				if (el) {
					el.textContent = seconds;
				}
			}, 1000);

		},
		willClose: () => {
			clearInterval(timerInterval);
		}
	});

	notifyprogress = {
		close: function () {
			Swal.close();
		}
	};
}

function notifyLoadingJerapah() {
	let startTime = Date.now();
	let timerInterval;

	Swal.fire({
		title: 'Processing...',
		html: `
		<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">

			<div style="font-size:15px;">
				Jangan tutup halaman sampai proses selesai
			</div>

			<div style="font-size:18px;font-weight:bold;margin:10px 0 15px;">
				⏱️ Waktu: <span id="elapsed">0</span> detik
			</div>

			<div style="width:100%;display:flex;justify-content:center;align-items:center;overflow:hidden;">
				<img
					src="https://media1.tenor.com/m/5NspfDPCF6UAAAAC/no-dont.gif"
					alt="Running Sheep"
					style="
						width:100px;
						height:auto;
						display:block;
						transform:translate(-20px, 0px);
					"
				/>
			</div>

		</div>
		`,
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {

			timerInterval = setInterval(() => {
				const seconds = Math.floor((Date.now() - startTime) / 1000);
				const el = Swal.getHtmlContainer().querySelector('#elapsed');
				if (el) {
					el.textContent = seconds;
				}
			}, 1000);

		},
		willClose: () => {
			clearInterval(timerInterval);
		}
	});

	notifyprogress = {
		close: function () {
			Swal.close();
		}
	};
}

function notifyLoadingJerapah_v2() {
	let startTime = Date.now();
	let timerInterval;

	Swal.fire({
		title: 'Processing...',
		html: `
		<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">

			<div style="font-size:15px;">
				Jangan tutup halaman sampai proses selesai
			</div>

			<div style="font-size:18px;font-weight:bold;margin:10px 0 15px;">
				⏱️ Waktu: <span id="elapsed">0</span> detik
			</div>

			<div style="width:100%;display:flex;justify-content:center;align-items:center;overflow:hidden;">
				<img
					src="https://solusiprogram.top/file_gif/jerapah_anim.gif"
					alt="Running Sheep"
					style="
						width:100px;
						height:auto;
						display:block;
						transform:translate(-20px, 0px);
					"
				/>
			</div>

		</div>
		`,
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {

			timerInterval = setInterval(() => {
				const seconds = Math.floor((Date.now() - startTime) / 1000);
				const el = Swal.getHtmlContainer().querySelector('#elapsed');
				if (el) {
					el.textContent = seconds;
				}
			}, 1000);

		},
		willClose: () => {
			clearInterval(timerInterval);
		}
	});

	notifyprogress = {
		close: function () {
			Swal.close();
		}
	};
}

function notifyLoadingKucing() {
	let startTime = Date.now();
	let timerInterval;

	Swal.fire({
		title: 'Processing...',
		html: `
		<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;width:100%;">

			<div style="font-size:15px;">
				Jangan tutup halaman sampai proses selesai
			</div>

			<div style="font-size:18px;font-weight:bold;margin:10px 0 15px;">
				⏱️ Waktu: <span id="elapsed">0</span> detik
			</div>

			<div style="width:100%;display:flex;justify-content:center;align-items:center;overflow:hidden;">
				<img
					src="../../files/uploads/Data Lembur & Makan Periode 18 12 2024 - 20 01 2025.xls'"
					alt="Running Sheep"
					style="
						width:100px;
						height:auto;
						display:block;
						transform:translate(-20px, 0px);
					"
				/>
			</div>

		</div>
		`,
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {

			timerInterval = setInterval(() => {
				const seconds = Math.floor((Date.now() - startTime) / 1000);
				const el = Swal.getHtmlContainer().querySelector('#elapsed');
				if (el) {
					el.textContent = seconds;
				}
			}, 1000);

		},
		willClose: () => {
			clearInterval(timerInterval);
		}
	});

	notifyprogress = {
		close: function () {
			Swal.close();
		}
	};
}
</script>