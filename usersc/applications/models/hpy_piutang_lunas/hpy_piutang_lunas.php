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
	$show_inactive_status = $_POST['show_inactive_status_hpy_piutang_lunas'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpy_piutang_lunas' )
		->debug(true)
		->fields(
			Field::inst( 'hpy_piutang_lunas.id' ),
			Field::inst( 'hpy_piutang_lunas.id_hpy_piutang_h' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_piutang_lunas.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_piutang_lunas.id_hpcxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_piutang_lunas.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpy_piutang_lunas.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpy_piutang_lunas.keterangan' ),
			Field::inst( 'hpy_piutang_lunas.is_active' ),
			Field::inst( 'hpy_piutang_lunas.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_piutang_lunas.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpy_piutang_lunas.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_piutang_lunas.is_approve' ),
			Field::inst( 'hpy_piutang_lunas.is_defaultprogram' ),
			Field::inst( 'hpy_piutang_lunas.nominal' ),
			Field::inst( 'hpy_piutang_lunas.tenor' ),
			Field::inst( 'hpy_piutang_lunas.cicilan_per_bulan' ),
			Field::inst( 'hpy_piutang_lunas.cicilan_terakhir' ),
			Field::inst( 'hpy_piutang_lunas.tanggal_mulai' )
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
			Field::inst( 'hpy_piutang_lunas.tanggal_akhir' )
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
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hpy_piutang_lunas.id_hemxxmh' )
		->leftJoin( 'hpcxxmh','hpcxxmh.id','=','hpy_piutang_lunas.id_hpcxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpy_piutang_lunas.is_active', 1);
	}
	
	include( "hpy_piutang_lunas_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>