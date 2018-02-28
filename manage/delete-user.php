<?php
include("../training.php");

if (!empty($_GET['id'])) {
	sqlsrv_query($conn, "UPDATE Employees SET Active = 0 WHERE ID = " . $_GET['id']);
	header("Location: titles.php");
} else {
	echo "<script>alert(\"No employee selected!\");</script>";
}
?>