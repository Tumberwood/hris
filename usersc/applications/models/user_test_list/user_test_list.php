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
	$show_inactive_status = $_POST['show_inactive_status_user_test_list'];
	// -----------
	
	$editor = Editor::inst( $db, 'user_test_list' )
		->debug(true)
		->fields(
			Field::inst( 'user_test_list.id' ),
			Field::inst( 'user_test_list.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'user_test_list.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'user_test_list.keterangan' ),
			Field::inst( 'user_test_list.is_active' ),
			Field::inst( 'user_test_list.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'user_test_list.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'user_test_list.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'user_test_list.is_approve' ),
			Field::inst( 'user_test_list.is_defaultprogram' ),
			Field::inst( 'user_test_list.menu' ),
			Field::inst( 'user_test_list.username' ),
			Field::inst( 'user_test_list.catatan_user' ),
			Field::inst( 'user_test_list.catatan_omf' ),
			Field::inst( 'user_test_list.tanggal_user_tes' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) 
			),
			Field::inst( 'user_test_list.tanggal_done' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) 
			),
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'user_test_list.is_active', 1);
	}
	
	include( "user_test_list_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>