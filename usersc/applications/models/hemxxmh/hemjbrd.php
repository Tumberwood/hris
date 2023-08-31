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
	$show_inactive_status = $_POST['show_inactive_status_hemjbrd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemjbrd' )
			->debug(true)
			->fields(
				Field::inst( 'hemjbrd.id' ),
				Field::inst( 'hemjbrd.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hemjbrd.id_hesxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hemjbrd.id_harxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hemjbrd.kode' )
					->setFormatter( function ( $val ) {
						return strtoupper($val);
					} ),
				Field::inst( 'hemjbrd.tanggal_awal' )
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
				Field::inst( 'hemjbrd.tanggal_akhir' )
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
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),

				Field::inst( 'hesxxmh.nama' ),
				Field::inst( 'harxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hemjbrd.id_hemxxmh' )
			->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbrd.id_hesxxmh' )
			->leftJoin( 'harxxmh','harxxmh.id','=','hemjbrd.id_harxxmh' )

			->where('hemjbrd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		
		include( "hemjbrd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>