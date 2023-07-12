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
	$show_inactive_status = $_POST['show_inactive_status_htpr_heyxxmh'];
	// -----------
	
	if ( ! isset($_POST['id_heyxxmh']) || ! is_numeric($_POST['id_heyxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htpr_heyxxmh' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_heyxxmh.id' ),
				Field::inst( 'htpr_heyxxmh.id_heyxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_heyxxmh.id_hpcxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_heyxxmh.kode' ),
				Field::inst( 'htpr_heyxxmh.nama' ),
				Field::inst( 'htpr_heyxxmh.keterangan' ),
				Field::inst( 'htpr_heyxxmh.is_active' ),
				Field::inst( 'htpr_heyxxmh.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_heyxxmh.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_heyxxmh.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htpr_heyxxmh.tanggal_efektif' )
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
				Field::inst( 'htpr_heyxxmh.nominal' ),

				Field::inst( 'hpcxxmh.nama' )
			)
			->leftJoin( 'hpcxxmh','hpcxxmh.id','=','htpr_heyxxmh.id_hpcxxmh' )
			->where('htpr_heyxxmh.id_heyxxmh',$_POST['id_heyxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htpr_heyxxmh.is_active', 1);
		}
		
		include( "htpr_heyxxmh_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>