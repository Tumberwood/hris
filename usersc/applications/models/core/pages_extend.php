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
	
	$editor = Editor::inst( $db, 'pages' )
		->debug(true)
		->fields(
			Field::inst( 'pages.id' )
				->set( Field::SET_NONE ),
			Field::inst( 'pages.page' )
				->set( Field::SET_NONE ),
			Field::inst( 'pages.title' )
				->set( Field::SET_NONE ),
			Field::inst( 'pages_extend.info' ),
			Field::inst( 'pages_extend.is_setting' ),
			Field::inst( 'pages_extend.is_crud' )
		)
		->leftJoin( 'pages_extend','pages_extend.id','=','pages.id' )
		->where( 'LEFT(pages.page,20)', 'usersc/applications/');
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>