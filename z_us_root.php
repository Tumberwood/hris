<?php
$path=['','users/','usersc/applications/views/core/','usersc/applications/views/hovxxmh/','usersc/applications/views/hodxxmh/','usersc/applications/views/hosxxmh/','usersc/applications/views/hobxxmh/','usersc/applications/views/hevxxmh/','usersc/applications/views/hetxxmh/','usersc/applications/views/hesxxmh/','usersc/applications/views/heyxxmh/','usersc/applications/views/htsxxmh/','usersc/applications/views/htlxxmh/','usersc/applications/views/htpxxmh/','usersc/applications/views/hthhdth/','usersc/applications/views/htsptth/','usersc/applications/views/htssctd/','usersc/applications/views/htlxxth/','usersc/applications/views/htlxxrh/','usersc/applications/views/hgsptth/','usersc/applications/views/htscctd/','usersc/applications/views/gipxxsh/','usersc/applications/views/htsprtd/','usersc/applications/views/htpxxth/','usersc/applications/views/htlgnth/','usersc/applications/views/htsrptd/','usersc/applications/views/holxxmd/','usersc/applications/views/htotpmh/','usersc/applications/views/htoxxth/','usersc/applications/views/hgtprth/','usersc/applications/views/htsprrd_sum/','usersc/applications/views/htoxxth_sum/','usersc/applications/views/htsprtd_ol/','usersc/applications/views/htsprrd/','usersc/applications/views/htoxxrd/','usersc/applications/views/hpcxxmh/','usersc/applications/views/hemxxmh/'];
$abs_us_root=$_SERVER['DOCUMENT_ROOT'];

$self_path=explode("/", $_SERVER['PHP_SELF']);
$self_path_length=count($self_path);
$file_found=FALSE;

for($i = 1; $i < $self_path_length; $i++){
	array_splice($self_path, $self_path_length-$i, $i);
	$us_url_root=implode("/",$self_path)."/";

	if (file_exists($abs_us_root.$us_url_root.'z_us_root.php')){
		$file_found=TRUE;
		break;
	}else{
		$file_found=FALSE;
	}
}
//redirect back to Userspice URL root (usually /)
header('Location: '.$us_url_root);
exit;
?>
