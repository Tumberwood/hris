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
	
	$editor = Editor::inst( $db, 'users' )
		->debug(true)
		->fields(
			Field::inst( 'users.id' ),
			Field::inst( 'users.username' ),
			Field::inst( 'users.fname' ),
			Field::inst( 'users.lname' ),
			Field::inst( 'users.active' )
		)
		->join(
			Mjoin::inst( 'permissions' )
				->link( 'users.id', 'user_permission_matches.user_id' )
				->link( 'permissions.id', 'user_permission_matches.permission_id' )
				->fields(
					Field::inst( 'id' ),
					Field::inst( 'name' )
				)
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'users.active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>