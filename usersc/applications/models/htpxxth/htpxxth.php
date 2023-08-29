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
	$show_inactive_status = $_POST['show_inactive_status_htpxxth'];
	// -----------
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$editor = Editor::inst( $db, 'htpxxth' )
		->debug(true)
		->fields(
			Field::inst( 'htpxxth.id' ),
			Field::inst( 'htpxxth.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpxxth.id_htpxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpxxth.id_files_bukti' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpxxth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htpxxth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htpxxth.keterangan' ),
			Field::inst( 'htpxxth.is_active' ),
			Field::inst( 'htpxxth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htpxxth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htpxxth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htpxxth.is_approve' ),
			Field::inst( 'htpxxth.is_defaultprogram' ),
			Field::inst( 'htpxxth.tanggal' )
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
			Field::inst( 'htpxxth.jam_awal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htpxxth.jam_akhir' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),

			Field::inst( 'htpxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htpxxth.id_hemxxmh' )
		->leftJoin( 'htpxxmh','htpxxmh.id','=','htpxxth.id_htpxxmh' )

		->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id','LEFT' )
		->leftJoin('hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh','LEFT' )
		->leftJoin('hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh','LEFT' )

		->where( 'htpxxth.tanggal', $start_date, '>=' )
		->where( 'htpxxth.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htpxxth.is_active', 1);
	}
	
	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('hemjbmh.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}
	
	include( "htpxxth_extra.php" );
	include( "../../../helpers/kode_fn_generate_c.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>