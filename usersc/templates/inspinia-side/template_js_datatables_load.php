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
</script>