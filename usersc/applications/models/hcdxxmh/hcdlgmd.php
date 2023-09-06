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
	$show_inactive_status = $_POST['show_inactive_status_hcdlgmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdlgmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdlgmd.id' ),
				Field::inst( 'hcdlgmd.id_hcdxxmh' ),
				Field::inst( 'hcdlgmd.id_hlgxxmh' )
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
				Field::inst( 'hcdlgmd.kode' ),
				Field::inst( 'hcdlgmd.nama' ),
				Field::inst( 'hcdlgmd.keterangan' ),
				Field::inst( 'hcdlgmd.is_active' ),
				Field::inst( 'hcdlgmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdlgmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdlgmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hcdlgmd.mendengar' ),
				Field::inst( 'hcdlgmd.membaca' ),
				Field::inst( 'hcdlgmd.menulis' ),
				Field::inst( 'hcdlgmd.percakapan' ),

				Field::inst( 'hlgxxmh.nama' )
			)
			->leftJoin( 'hlgxxmh','hlgxxmh.id','=','hcdlgmd.id_hlgxxmh' )
			->where('hcdlgmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdlgmd.is_active', 1);
		}
		
		include( "hcdlgmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>