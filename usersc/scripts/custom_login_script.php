<?php
//Whatever you put here will happen after the username and password are verified and the user is "technically" logged in, but they have not yet been redirected to their starting page.  This gives you access to all the user's data through $user->data()

//Where do you want to redirect the user after login
//in this example, admins will go to the dashboard and others will go to the location you configured
//in the dashboard under settings->general->Redirect After Login
// if(hasPerm([2],$user->data()->id)){
//   Redirect::to($us_url_root.'users/admin.php');
// }else{
// Redirect::to($us_url_root.$settings->redirect_uri_after_login);
// }

/*
	$_SESSION['where_data_permission']
	0: All
	1: Self
	2: Belum Didefinisikan
*/

$_SESSION['user']     = $user->data()->id;
$_SESSION['username'] = $user->data()->username;

// general setting
$qs_general_s = "
	SELECT 
		*
	FROM general_s";
$query_general_s  = $db->query($qs_general_s);
$result_general_s = $query_general_s->results();

if($_SESSION['user'] < 101){

	$qs_gcpxxmh = "
		SELECT 
			id,
			kode
		FROM gcpxxmh
		WHERE is_default = 1";
	$query_gcpxxmh                  = $db->query($qs_gcpxxmh);
	$result_gcpxxmh                 = $query_gcpxxmh->results();
	$_SESSION['id_gcpxxmh']   	    = $result_gcpxxmh[0]->id;
	$_SESSION['arr_ha_gcpxxmh']     = array(0);
	$_SESSION['str_arr_ha_gcpxxmh'] = 0;
	
	$qs_gbrxxmh = "
		SELECT 
			id,
			kode
		FROM gbrxxmh
		WHERE is_default = 1";
	$query_gbrxxmh                  = $db->query($qs_gbrxxmh);
	$result_gbrxxmh                 = $query_gbrxxmh->results();
	$_SESSION['id_gbrxxmh']   		= $result_gbrxxmh[0]->id;
	$_SESSION['arr_ha_gbrxxmh'] 	= array(0);
	$_SESSION['str_arr_ha_gbrxxmh'] = 0;
	
	$_SESSION['where_data_permission'] 	  = 0;

	$_SESSION['id_hemxxmh'] = 1;
	
	Redirect::to($us_url_root.'usersc/applications/views/core/dashboard.php');

}else{
	
	// BEGIN cek apakah sudah dibuatkan hak akses
	$qs_udpxxsh = "
		SELECT 
			a.id as id,
			a.data_permission as data_permission
		FROM udpxxsh a
		WHERE 
			a.id_users = " . $_SESSION['user'];
	
	$query_udpxxsh = $db->query($qs_udpxxsh);
	$count_udpxxsh = $query_udpxxsh->count();

	if($count_udpxxsh > 0){
		$result_udpxxsh = $query_udpxxsh->results();
		$_SESSION['where_data_permission'] = $result_udpxxsh[0]->data_permission;

		// BEGIN get data hemxxmh
		$qs_hemxxmh = "
			SELECT 
				id
			FROM hemxxmh a
			WHERE 
				a.id_users = " . $_SESSION['user'];
		$query_hemxxmh = $db->query($qs_hemxxmh);
		$c_ha_hemxxmh = $query_hemxxmh->count();
		if($c_ha_gcpxxmh  > 0){
			$rs_hemxxmh = $query_gcpxxmh->results();
			$_SESSION['id_hemxxmh'] = $rs_hemxxmh[0]>id;
		}
		// END get data hemxxmh

		// BEGIN get hak akses gcpxxmh
		// $qs_gcpxxmh = "
		// 	SELECT 
		// 		id_gcpxxmh
		// 	FROM udpcpsd a
		// 	WHERE 
		// 		a.is_active = 1 AND 
		// 		a.id_udpxxsh = " . $result_udpxxsh[0]->id;
		// $query_gcpxxmh = $db->query($qs_gcpxxmh);
		// $c_ha_gcpxxmh = $query_gcpxxmh->count();

		// if($c_ha_gcpxxmh  > 0){
		// 	$rs_ha_gcpxxmh = $query_gcpxxmh->results();
			
		// 	$arr_ha_gcpxxmh=array();
		// 	foreach ($rs_ha_gcpxxmh as $key => $value) {	
		// 		array_push($arr_ha_gcpxxmh,$rs_ha_gcpxxmh[$key]->id_gcpxxmh);
		// 	}
		// 	$_SESSION['arr_ha_gcpxxmh'] 		= $arr_ha_gcpxxmh;
		// 	$_SESSION['str_arr_ha_gcpxxmh'] 	= implode(",",$_SESSION['arr_ha_gcpxxmh']);
		// }else{
		// 	$_SESSION['arr_ha_gcpxxmh'] 		= [0];
		// 	$_SESSION['str_arr_ha_gcpxxmh'] 	= (0);
		// }
		// END get hak akses gcpxxmh

		// BEGIN get hak akses gbrxxmh
		// $qs_gbrxxmh = "
		// 	SELECT 
		// 		id_gbrxxmh
		// 	FROM udpbrsd a
		// 	WHERE 
		// 		a.is_active = 1 AND 
		// 		a.id_udpxxsh = " . $result_udpxxsh[0]->id;
		// $query_gbrxxmh = $db->query($qs_gbrxxmh);
		// $c_ha_gbrxxmh = $query_gbrxxmh->count();

		// if($c_ha_gbrxxmh  > 0){
		// 	$rs_ha_gbrxxmh = $query_gbrxxmh->results();
			
		// 	$arr_ha_gbrxxmh=array();
		// 	foreach ($rs_ha_gbrxxmh as $key => $value) {	
		// 		array_push($arr_ha_gbrxxmh,$rs_ha_gbrxxmh[$key]->id_gbrxxmh);
		// 	}
		// 	$_SESSION['arr_ha_gbrxxmh'] 		= $arr_ha_gbrxxmh;
		// 	$_SESSION['str_arr_ha_gbrxxmh'] 	= implode(",",$_SESSION['arr_ha_gbrxxmh']);
		// }else{
		// 	$_SESSION['arr_ha_gbrxxmh'] 		= [0];
		// 	$_SESSION['str_arr_ha_gbrxxmh'] 	= (0);
		// }
		// END get hak akses gbrxxmh



		Redirect::to($us_url_root.'usersc/applications/views/core/dashboard.php');

	}else{
		Redirect::to($us_url_root.'users/logout.php?x=1');
	}

}

?>
