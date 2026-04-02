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
	$show_inactive_status = $_POST['show_inactive_status_htpr_sub_tipe'];
	// -----------
	
	$editor = Editor::inst( $db, 'htpr_sub_tipe' )
		->debug(true)
		->fields(
			Field::inst( 'htpr_sub_tipe.id' ),
			
			Field::inst( 'htpr_sub_tipe.id_heyxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpr_sub_tipe.id_hesxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpr_sub_tipe.id_heyxxmd' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				
			Field::inst( 'htpr_sub_tipe.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htpr_sub_tipe.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htpr_sub_tipe.keterangan' ),
			Field::inst( 'htpr_sub_tipe.is_active' ),
			Field::inst( 'htpr_sub_tipe.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_sub_tipe.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htpr_sub_tipe.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_sub_tipe.is_approve' ),
			Field::inst( 'htpr_sub_tipe.is_defaultprogram' ),
			Field::inst( 'htpr_sub_tipe.tanggal_efektif' )
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
			Field::inst( 'htpr_sub_tipe.nominal_lembur' ),
			Field::inst( 'htpr_sub_tipe.nominal_pot_absen' ),
			Field::inst( 'htpr_sub_tipe.grup_hk' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'hesxxmh.nama' ),
			Field::inst( 'heyxxmd.nama' ),
		)
		->leftJoin( 'heyxxmh','heyxxmh.id','=','htpr_sub_tipe.id_heyxxmh' )
		->leftJoin( 'hesxxmh','hesxxmh.id','=','htpr_sub_tipe.id_hesxxmh' )
		->leftJoin( 'heyxxmd','heyxxmd.id','=','htpr_sub_tipe.id_heyxxmd' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htpr_sub_tipe.is_active', 1);
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>