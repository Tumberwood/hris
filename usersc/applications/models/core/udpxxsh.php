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
	$show_inactive_status = $_POST['show_inactive_status'];
	// -----------
	
	$editor = Editor::inst( $db, 'udpxxsh' )
		->debug(true)
		->fields(
			Field::inst( 'udpxxsh.id' ),
			Field::inst( 'udpxxsh.id_users' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'udpxxsh.kode' ),
			Field::inst( 'udpxxsh.nama' ),
			Field::inst( 'udpxxsh.keterangan' ),
			Field::inst( 'udpxxsh.is_active' ),
			Field::inst( 'udpxxsh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'udpxxsh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'udpxxsh.is_setting' ),
			Field::inst( 'udpxxsh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'udpxxsh.data_permission' ),
			Field::inst( 'hemxxmh.nama' ),
			Field::inst( 'users.username' )
				->set( Field::SET_NONE )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id_users','=','udpxxsh.id_users' )
		->leftJoin( 'users','users.id','=','udpxxsh.id_users' )
		->leftJoin( 'users_extend','users_extend.id','=','users.id' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'udpxxsh.is_active', 1);
	}
	
	include( "udpxxsh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>