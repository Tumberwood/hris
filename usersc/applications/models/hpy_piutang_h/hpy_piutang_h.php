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
	$show_inactive_status = $_POST['show_inactive_status_hpy_piutang_h'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpy_piutang_h' )
		->debug(true)
		->fields(
			Field::inst( 'hpy_piutang_h.id' ),
			Field::inst( 'hpy_piutang_h.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_piutang_h.id_hpcxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_piutang_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpy_piutang_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpy_piutang_h.keterangan' ),
			Field::inst( 'hpy_piutang_h.is_active' ),
			Field::inst( 'hpy_piutang_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_piutang_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpy_piutang_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_piutang_h.is_approve' ),
			Field::inst( 'hpy_piutang_h.is_defaultprogram' ),
			Field::inst( 'hpy_piutang_h.nominal' ),
			Field::inst( 'hpy_piutang_h.tenor' ),
			Field::inst( 'hpy_piutang_h.cicilan_per_bulan' ),
			Field::inst( 'hpy_piutang_h.cicilan_terakhir' ),
			Field::inst( 'hpy_piutang_h.tanggal_mulai' )
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
			Field::inst( 'hpcxxmh.nama' ),
			Field::inst( 'hpy_piutang_h.tanggal_akhir' )
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

			Field::inst( 'concat(hemxxmh.kode, " - ", hemxxmh.nama, " - ", hetxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hpy_piutang_h.id_hemxxmh' )
		->leftJoin( 'hpcxxmh','hpcxxmh.id','=','hpy_piutang_h.id_hpcxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpy_piutang_h.is_active', 1);
	}
	
	include( "hpy_piutang_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>