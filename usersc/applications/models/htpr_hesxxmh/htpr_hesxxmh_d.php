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
	$show_inactive_status = $_POST['show_inactive_status_htpr_hesxxmh'];
	// -----------
	
	if ( ! isset($_POST['id_hesxxmh']) || ! is_numeric($_POST['id_hesxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htpr_hesxxmh' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_hesxxmh.id' ),
				Field::inst( 'htpr_hesxxmh.id_hesxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_hesxxmh.id_hpcxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_hesxxmh.kode' ),
				Field::inst( 'htpr_hesxxmh.nama' ),
				Field::inst( 'htpr_hesxxmh.keterangan' ),
				Field::inst( 'htpr_hesxxmh.is_active' ),
				Field::inst( 'htpr_hesxxmh.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_hesxxmh.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_hesxxmh.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htpr_hesxxmh.tanggal_efektif' )
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
				Field::inst( 'htpr_hesxxmh.nominal' ),

				Field::inst( 'hpcxxmh.nama' )
			)
			->leftJoin( 'hpcxxmh','hpcxxmh.id','=','htpr_hesxxmh.id_hpcxxmh' )
			->where('htpr_hesxxmh.id_hesxxmh',$_POST['id_hesxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htpr_hesxxmh.is_active', 1);
		}
		
		include( "htpr_hesxxmh_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>