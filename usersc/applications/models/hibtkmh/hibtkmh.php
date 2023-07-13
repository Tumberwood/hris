<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
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
	
	// ----------- do not erase
	$show_inactive_status = $_POST['show_inactive_status_hibtkmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hibtkmh' )
		->debug(true)
		->fields(
			Field::inst( 'hibtkmh.id' ),
			Field::inst( 'hibtkmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hibtkmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hibtkmh.keterangan' ),
			Field::inst( 'hibtkmh.is_active' ),
			Field::inst( 'hibtkmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hibtkmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hibtkmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hibtkmh.is_approve' ),
			Field::inst( 'hibtkmh.is_defaultprogram' ),
			Field::inst( 'hibtkmh.persen_jht_perusahaan' ),
			Field::inst( 'hibtkmh.persen_jht_karyawan' ),
			Field::inst( 'hibtkmh.persen_jkk' ),
			Field::inst( 'hibtkmh.persen_jkm' ),
			Field::inst( 'hibtkmh.persen_jp_perusahaan' ),
			Field::inst( 'hibtkmh.persen_jp_karyawan' ),
			Field::inst( 'hibtkmh.gaji_max' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hibtkmh.is_active', 1);
	}
	
	include( "hibtkmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>