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
	$show_inactive_status = $_POST['show_inactive_status_hgsptth_v3'];
	$start_date = $_POST['start_date'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgsptth_v3' )
		->debug(true)
		->fields(
			Field::inst( 'hgsptth_v3.id' ),
			Field::inst( 'hgsptth_v3.id_htsptth_v3' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgsptth_v3.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgsptth_v3.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgsptth_v3.keterangan' ),
			Field::inst( 'hgsptth_v3.is_active' ),
			Field::inst( 'hgsptth_v3.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth_v3.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgsptth_v3.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth_v3.is_approve' ),
			Field::inst( 'hgsptth_v3.is_defaultprogram' ),
			Field::inst( 'hgsptth_v3.tanggal_awal' )
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
			Field::inst( 'hgsptth_v3.tanggal_akhir' )
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
			Field::inst( 'hgsptth_v3.dari_tanggal' )
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
			Field::inst( 'hgsptth_v3.tipe' ),
			
			Field::inst( 'hgsptth_v3.generated_on' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00 00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y H:i:s', strtotime( $val ) );
					}
				} ),

			Field::inst( 'htsptth.nama' )
		)
		->leftJoin( 'htsptth_v3 as htsptth','htsptth.id','=','hgsptth_v3.id_htsptth_v3' )
		->where( function ( $r ) {
			$start_date = $_POST['start_date'];
            $r
                ->where('hgsptth_v3.tanggal_awal', $start_date)
                ->or_where('hgsptth_v3.tanggal_akhir', $start_date);
        } )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgsptth_v3.is_active', 1);
	}
	
	// include( "../../../helpers/kode_fn_generate_c.php" );
	include( "hgsptth_v3_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>