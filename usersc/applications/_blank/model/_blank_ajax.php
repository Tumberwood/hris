<?php
	/**

	 */

     include( "../../../../users/init.php" );
     include( "../../../../usersc/lib/DataTables.php" );
 
     use
         DataTables\Editor,
         DataTables\Editor\Query,
         DataTables\Editor\Result;
	
	$param       = $_POST['param'];
	// do something here
    // select update insert etc
	
    // query
    // echo
	echo json_encode($result);
?>