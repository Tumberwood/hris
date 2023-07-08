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
	$show_inactive_status = $_POST['show_inactive_status_htoxxrd'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htoxxrd' )
		->debug(true)
		->fields(
			Field::inst( 'htoxxrd.id' ),
			Field::inst( 'htoxxrd.id_hemxxmh' ),
			Field::inst( 'htoxxrd.id_heyxxmh' ),
			Field::inst( 'htoxxrd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htoxxrd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htoxxrd.keterangan' ),
			Field::inst( 'htoxxrd.is_active' ),
			Field::inst( 'htoxxrd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxrd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htoxxrd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxrd.is_approve' ),
			Field::inst( 'htoxxrd.is_defaultprogram' ),

			Field::inst( 'htoxxrd.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} ),
			Field::inst( 'htoxxrd.jam_awal' ),
			Field::inst( 'htoxxrd.jam_akhir' ),
			Field::inst( 'htoxxrd.durasi_jam' ),
			Field::inst( 'htoxxrd.durasi_jam_final' ),
			Field::inst( 'htoxxrd.upah_lembur' ),
			Field::inst( 'htoxxrd.is_istirahat' ),

			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'htotpmh.nama' )

		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htoxxrd.id_hemxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'htotpmh','htotpmh.id','=','htoxxrd.id_htotpmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->where( 'htoxxrd.tanggal', $start_date, '>=' )
		->where( 'htoxxrd.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htoxxrd.is_active', 1);
	}
	
	include( "htoxxrd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>