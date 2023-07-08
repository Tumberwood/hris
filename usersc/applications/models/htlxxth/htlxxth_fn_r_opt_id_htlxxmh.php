<?php
    /*
    *   Digunakan untuk generate dependent options pada field select.
    
    */
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );   
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

    $opt_id_htlxxmh = $db
        ->select( 'htlxxmh', ['id as value', 'nama as label'], ['id_htlgrmh' => $_REQUEST['values']['htlxxth.id_htlgrmh']] )
        ->fetchAll();
    
    echo json_encode( [
        'options' => [
            'htlxxth.id_htlxxmh' => $opt_id_htlxxmh
        ]
    ] );

?>