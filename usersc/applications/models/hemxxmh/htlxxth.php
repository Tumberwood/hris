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
	$show_inactive_status = $_POST['show_inactive_status_htlxxth'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htlxxth' )
			->debug(true)
			->fields(
				Field::inst( 'htlxxth.id' ),
				Field::inst( 'htlxxth.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htlxxth.id_htlxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htlxxth.id_files_lampiran' )
					->setFormatter( Format::ifEmpty( 0 ) )
					->upload( Upload::inst(  $abs_us_root.$us_url_root.'usersc/files/cuti/__ID__.__EXTN__' )
						->db( 'files', 'id', array(
							'filename'    => Upload::DB_FILE_NAME,
							'filesize'    => Upload::DB_FILE_SIZE,
							'web_path'    => Upload::DB_WEB_PATH,
							'system_path' => Upload::DB_SYSTEM_PATH,
							'extn' 		  => Upload::DB_EXTN
						) )
						->validator( Validate::fileSize( 500000, 'Ukuran lampiran maksimal 500Kb' ) )
						->validator( Validate::fileExtensions( array( 'png', 'jpg', 'jpeg'), "Hanya boleh format png, jpg atau jpeg" ) )
					),
				Field::inst( 'htlxxth.kode' )
					->setFormatter( function ( $val ) {
						return strtoupper($val);
					} ),
				Field::inst( 'htlxxth.nama' )
					->setFormatter( function ( $val ) {
						return ucwords($val);
					} ),
				Field::inst( 'htlxxth.keterangan' ),
				Field::inst( 'htlxxth.is_active' ),
				Field::inst( 'htlxxth.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htlxxth.created_on' )
					->set( Field::SET_CREATE )
					->getFormatter( function ( $val, $data, $opts ) {
						if ($val === "0000-00-00" || $val === null){
							echo "";
						}else{
							return date( 'd M Y', strtotime( $val ) );
						}
					} ),
				Field::inst( 'htlxxth.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htlxxth.is_approve' ),
				Field::inst( 'htlxxth.is_defaultprogram' ),
				Field::inst( 'htlxxth.tanggal_awal' )
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
				Field::inst( 'htlxxth.tanggal_akhir' )
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
				
				Field::inst( 'htlxxmh.nama' ),
	
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
	
				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' )
			)
			->leftJoin( 'htlxxmh','htlxxmh.id','=','htlxxth.id_htlxxmh' )
			->leftJoin( 'hemxxmh','hemxxmh.id','=','htlxxth.id_hemxxmh' )
			->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id','LEFT' )
			->leftJoin('hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh','LEFT' )
			->leftJoin('hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh','LEFT' )
			->where('htlxxth.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htlxxth.is_active', 1);
		}
		
		include( "htlxxth_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>