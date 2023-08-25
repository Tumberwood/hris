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
	$show_inactive_status = $_POST['show_inactive_status_htoemtd'];
	// -----------
	
	if ( ! isset($_POST['id_htoxxth']) || ! is_numeric($_POST['id_htoxxth']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htoemtd' )
			->debug(true)
			->fields(
				Field::inst( 'htoemtd.id' ),
				Field::inst( 'htoemtd.id_htoxxth' ),
				Field::inst( 'htoemtd.id_hemxxmh' ),
				Field::inst( 'htoemtd.id_htotpmh' ),
				Field::inst( 'htoemtd.kode' ),
				Field::inst( 'htoemtd.nama' ),
				Field::inst( 'htoemtd.keterangan' ),
				Field::inst( 'htoemtd.is_active' ),
				Field::inst( 'htoemtd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htoemtd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htoemtd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htoemtd.jam_awal' )
					->getFormatter( function ( $val, $data, $opts ) {
						if ($val === null){
							echo '';
						}else{
							return date( 'H:i', strtotime( $val ) );
						}
					} )
					->setFormatter( 'Format::datetime', array(
						'from' => 'H:i',
						'to' =>   'H:i:s'
					) ),
				Field::inst( 'htoemtd.jam_akhir' )
					->getFormatter( function ( $val, $data, $opts ) {
						if ($val === null){
							echo '';
						}else{
							return date( 'H:i', strtotime( $val ) );
						}
					} )
					->setFormatter( 'Format::datetime', array(
						'from' => 'H:i',
						'to' =>   'H:i:s'
					) ),
				Field::inst( 'htoemtd.durasi_lembur_jam' ),
				Field::inst( 'htoemtd.durasi_lembur_menit' ),
				Field::inst( 'htoemtd.jam_awal_istirahat' )
					->getFormatter( function ( $val, $data, $opts ) {
						if ($val === null){
							echo '';
						}else{
							return date( 'H:i', strtotime( $val ) );
						}
					} )
					->setFormatter( 'Format::datetime', array(
						'from' => 'H:i',
						'to' =>   'H:i:s'
					) ),
				Field::inst( 'htoemtd.jam_akhir_istirahat' )
					->getFormatter( function ( $val, $data, $opts ) {
						if ($val === null){
							echo '';
						}else{
							return date( 'H:i', strtotime( $val ) );
						}
					} )
					->setFormatter( 'Format::datetime', array(
						'from' => 'H:i',
						'to' =>   'H:i:s'
					) ),
				Field::inst( 'htoemtd.durasi_istirahat_menit' ),
				Field::inst( 'htoemtd.is_istirahat' ),
				Field::inst( 'htoemtd.is_valid_checkclock' ),
				
				Field::inst( 'htotpmh.nama' ),
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
			)
			->leftJoin( 'htotpmh','htotpmh.id','=','htoemtd.id_htotpmh' )
			->leftJoin( 'hemxxmh','hemxxmh.id','=','htoemtd.id_hemxxmh' )
			->where('htoemtd.id_htoxxth',$_POST['id_htoxxth']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htoemtd.is_active', 1);
		}
		
		include( "htoemtd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>