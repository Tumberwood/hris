<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );   
	
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
	
    $sql = "
		SELECT
			field
		FROM
			table
		WHERE
		";
	//echo $sql;
	$result = $db->sql($sql)->fetchAll();
	$count = $db->sql($sql)->count();
	
	if ($count > 0){
		$results = $result;
		echo json_encode( ['data' => $results]);
	}else{
		echo json_encode( [ "data" => [] ] );
	}
	
	exit();
?>