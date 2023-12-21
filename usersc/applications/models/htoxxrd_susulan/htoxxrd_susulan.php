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
	$show_inactive_status = $_POST['show_inactive_status_htoxxrd_susulan'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htoxxrd_susulan' )
		->debug(true)
		->fields(
			Field::inst( 'htoxxrd_susulan.id' ),
			Field::inst( 'htoxxrd_susulan.id_hpyxxth' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htoxxrd_susulan.id_htotpmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htoxxrd_susulan.id_hemxxmh' )
			->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htoxxrd_susulan.id_heyxxmh' )
			->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htoxxrd_susulan.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htoxxrd_susulan.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htoxxrd_susulan.keterangan' ),
			Field::inst( 'htoxxrd_susulan.is_active' ),
			Field::inst( 'htoxxrd_susulan.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxrd_susulan.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htoxxrd_susulan.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxrd_susulan.is_approve' ),
			Field::inst( 'htoxxrd_susulan.is_defaultprogram' ),
			Field::inst( 'htoxxrd_susulan.tanggal' )
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
			Field::inst( 'htoxxrd_susulan.jam_awal' ),
			Field::inst( 'htoxxrd_susulan.jam_akhir' ),
			Field::inst( 'htoxxrd_susulan.durasi_lembur_jam' ),
			Field::inst( 'htoxxrd_susulan.durasi_jam_final' ),
			Field::inst( 'htoxxrd_susulan.upah_lembur' ),
			Field::inst( 'htoxxrd_susulan.is_istirahat' ),
			Field::inst( 'htoxxrd_susulan.pot_ti' ),
			Field::inst( 'hpyxxth.tanggal_awal' )
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
			Field::inst( 'hpyxxth.tanggal_akhir' )
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
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'htotpmh.nama' )

		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htoxxrd_susulan.id_hemxxmh' )
		->leftJoin( 'hpyxxth','hpyxxth.id','=','htoxxrd_susulan.id_hpyxxth' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'htotpmh','htotpmh.id','=','htoxxrd_susulan.id_htotpmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->where( 'htoxxrd_susulan.tanggal', $start_date, '>=' )
		->where( 'htoxxrd_susulan.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htoxxrd_susulan.is_active', 1);
	}
	
	if($_POST['id_hemxxmh'] > 0){
		$editor->where( 'htoxxrd_susulan.id_hemxxmh', $_POST['id_hemxxmh'] );
	}
	
	include( "htoxxrd_susulan_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>