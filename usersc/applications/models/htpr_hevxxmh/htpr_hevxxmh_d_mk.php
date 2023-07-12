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
	$show_inactive_status = $_POST['show_inactive_status_htpr_hevxxmh_mk'];
	// -----------
	
	if ( ! isset($_POST['id_hevxxmh']) || ! is_numeric($_POST['id_hevxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htpr_hevxxmh_mk' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_hevxxmh_mk.id' ),
				Field::inst( 'htpr_hevxxmh_mk.id_hevxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_hevxxmh_mk.id_hpcxxmh' )
					->setValue(31),
				Field::inst( 'htpr_hevxxmh_mk.kode' ),
				Field::inst( 'htpr_hevxxmh_mk.nama' ),
				Field::inst( 'htpr_hevxxmh_mk.keterangan' ),
				Field::inst( 'htpr_hevxxmh_mk.is_active' ),
				Field::inst( 'htpr_hevxxmh_mk.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_hevxxmh_mk.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_hevxxmh_mk.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htpr_hevxxmh_mk.tanggal_efektif' )
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
				Field::inst( 'htpr_hevxxmh_mk.tahun_min' ),
				Field::inst( 'htpr_hevxxmh_mk.tahun_max' ),
				Field::inst( 'htpr_hevxxmh_mk.nominal' ),
			)
			->where('htpr_hevxxmh_mk.id_hevxxmh',$_POST['id_hevxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htpr_hevxxmh_mk.is_active', 1);
		}
		
		include( "htpr_hevxxmh_d_mk_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>