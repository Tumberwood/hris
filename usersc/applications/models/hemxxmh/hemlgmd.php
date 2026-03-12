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
	$show_inactive_status = $_POST['show_inactive_status_hemlgmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemlgmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemlgmd.id' ),
				Field::inst( 'hemlgmd.id_hemxxmh' ),
				Field::inst( 'hemlgmd.id_hlgxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) )
					->options( Options::inst()
						->table( 'hlgxxmh' )
						->value( 'id' )
						->label( ['nama'] )
						->where( function ($q) {
							$q->where( 'is_active', 1 );
						})
						->render( function ( $row ) {
							return $row['nama'];
						} )
						->order( 'nama' )
					),
				Field::inst( 'hemlgmd.kode' ),
				Field::inst( 'hemlgmd.nama' ),
				Field::inst( 'hemlgmd.keterangan' ),
				Field::inst( 'hemlgmd.is_active' ),
				Field::inst( 'hemlgmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemlgmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemlgmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hemlgmd.mendengar' ),
				Field::inst( 'hemlgmd.membaca' ),
				Field::inst( 'hemlgmd.menulis' ),
				Field::inst( 'hemlgmd.percakapan' ),

				Field::inst( 'hlgxxmh.nama' )
			)
			->leftJoin( 'hlgxxmh','hlgxxmh.id','=','hemlgmd.id_hlgxxmh' )
			->where('hemlgmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemlgmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>