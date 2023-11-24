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
	$show_inactive_status = $_POST['show_inactive_status_hibksmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hibksmh' )
		->debug(true)
		->fields(
			Field::inst( 'hibksmh.id' ),
			Field::inst( 'hibksmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hibksmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hibksmh.keterangan' ),
			Field::inst( 'hibksmh.is_active' ),
			Field::inst( 'hibksmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hibksmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hibksmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hibksmh.is_approve' ),
			Field::inst( 'hibksmh.tanggal_efektif' )
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
			Field::inst( 'hibksmh.is_defaultprogram' ),
			Field::inst( 'hibksmh.persen_perusahaan' ),
			Field::inst( 'hibksmh.persen_karyawan' ),
			Field::inst( 'hibksmh.gaji_max' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hibksmh.is_active', 1);
	}
	
	include( "hibksmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>