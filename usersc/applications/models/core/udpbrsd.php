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
	if ( ! isset($_POST['id_udpxxsh']) || ! is_numeric($_POST['id_udpxxsh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'udpbrsd' )
			->debug(true)
			->fields(
				Field::inst( 'udpbrsd.id' ),
				Field::inst( 'udpbrsd.id_udpxxsh' ),
				Field::inst( 'udpbrsd.id_gbrxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) )
					->options( Options::inst()
						->table( 'gbrxxmh' )
						->value( 'id' )
						->label( ['nama'] )
						->where( function ($q) {
							$q->where( 'is_active', 1 );
						})
						->render( function ( $row ) {
							return $row['nama'];
						} )
					),
				Field::inst( 'udpbrsd.kode' ),
				Field::inst( 'udpbrsd.nama' ),
				Field::inst( 'udpbrsd.keterangan' ),
				Field::inst( 'udpbrsd.is_active' ),
				Field::inst( 'udpbrsd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'udpbrsd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'udpbrsd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'gbrxxmh.nama' )
			)
			->leftJoin( 'gbrxxmh','gbrxxmh.id','=','udpbrsd.id_gbrxxmh' )
			->where('udpbrsd.id_udpxxsh',$_POST['id_udpxxsh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'udpbrsd.is_active', 1);
		}
		
		// input log
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>