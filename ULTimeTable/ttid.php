<?php 

include("/home/proxykillah/DatabaseManager.php");
include("/home/proxykillah/db.php");

$id = mysql_real_escape_string( $_GET['term'] );
$len = strlen($id);
$sql = "SELECT * FROM studentids where SUBSTRING(id, 1, $len) = '$id'";

$ids = $db->query( $sql , "array" );

$idps = array();

foreach( $ids as $id )
{
	$idps[] .= $id[0];  
}

$response = json_encode( $idps );  

print $response;
