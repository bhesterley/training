<?php
include("../training.php");
if (isset($_GET['from'], $_GET['to'])) {
	$fromId = $_GET['from'];
	$toId = $_GET['to'];
	sqlsrv_query($conn, "DELETE FROM RequiredSOPs WHERE GroupID = " . $toId);
	$rsops = GetRequiredSOPs($conn, $fromId);
	while ($rows = sqlsrv_fetch_array($rsops)) {
		$qString = "INSERT INTO RequiredSOPs (GroupID, SOPID) VALUES (" . $toId . ", " . $rows['SOPID'] . ")";
		sqlsrv_query($conn, $qString);
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		}
	}
}
header("Location: assign.php?id=" . $toId);
?>