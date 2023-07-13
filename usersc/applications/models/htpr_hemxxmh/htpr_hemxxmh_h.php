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
	$show_inactive_status = $_POST['show_inactive_status_hemxxmh'];
	// -----------

	$editor = Editor::inst( $db, 'hemxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hemxxmh.id' ),
			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
			Field::inst( 'hemxxmh.is_active' ),
			Field::inst( 'hemxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hemxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hemxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hemxxmh.is_approve' ),
			Field::inst( 'hemxxmh.is_defaultprogram' ),

			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hosxxmh.nama' ),
			Field::inst( 'hevxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' )
		)
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
		->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hemxxmh.is_active', 1);
	}
	
	include( "htpr_hemxxmh_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>