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
	$show_inactive_status = $_POST['show_inactive_status_htpr_hemxxmh'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htpr_hemxxmh' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_hemxxmh.id' ),
				Field::inst( 'htpr_hemxxmh.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_hemxxmh.id_hpcxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_hemxxmh.kode' ),
				Field::inst( 'htpr_hemxxmh.nama' ),
				Field::inst( 'htpr_hemxxmh.keterangan' ),
				Field::inst( 'htpr_hemxxmh.is_active' ),
				Field::inst( 'htpr_hemxxmh.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_hemxxmh.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
			 	Field::inst( 'htpr_hemxxmh.created_on' )
				 	->set( Field::SET_CREATE ),
			 	Field::inst( 'htpr_hemxxmh.tanggal_efektif' )
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
					) ),
			 	Field::inst( 'htpr_hemxxmh.nominal' ),
			 	Field::inst( 'hpcxxmh.nama' )
			)
			->leftJoin( 'hpcxxmh','hpcxxmh.id','=','htpr_hemxxmh.id_hpcxxmh' )
			->where('htpr_hemxxmh.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htpr_hemxxmh.is_active', 1);
		}
		
		include( "htpr_hemxxmh_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>