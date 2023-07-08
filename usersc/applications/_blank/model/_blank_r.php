<?php
	include( "../../../../users/init.php" );

	// DataTables PHP library
	include( "../../../../usersc/lib/DataTables.php" );
	
	// Alias Editor classes so they are easy to use
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, '_blank' )
		->debug(true)
		->fields(
			Field::inst( '_blank.id' ),
			Field::inst( '_blank.barang_m_kode' ),
			Field::inst( '_blank.barang_m_nama' ),
			Field::inst( '_blank.satuan_m_unit_kode' ),
			Field::inst( '_blank.jumlah_unit' ),
			Field::inst( '_blank.kode' ),
			Field::inst( '_blank.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === "0000-00-00" || $val === null){
						echo "";
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} ),
			Field::inst( '_blank.customer_m_nama' ),
			Field::inst( '_blank.gudang_m_nama' ),
			Field::inst( '_blank.is_approve' )
		)
		->where( '_blank.tanggal', $start_date, '>=' )
		->where( '_blank.tanggal', $end_date, '<=' );
	
	$editor
		->process( $_POST )
		->json();
?>