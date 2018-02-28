<?php
include("../training.php");

if (!empty($_GET['id'])) {
   $id = $_GET['id'];
   if ($id == '9999') {
      $stmt = "INSERT INTO Employees (Name, GroupID, FullName, EmailAddress, Supervisor, Active) VALUES ('" . $_GET['username'] . "', '" . $_GET['title'] . "', '" . $_GET['fullname'] . "', '" . $_GET['email'] . "', '" . $_GET['supervisor'] . "', 1)";
   } else {
      $stmt = "UPDATE Employees SET Name = '" . $_GET['username'] . "', GroupID = " . $_GET['title'] . ", FullName = '" . $_GET['fullname'] . "', EmailAddress = '" . $_GET['email'] . "', Supervisor = '" . $_GET['supervisor'] . "' WHERE ID = " . $_GET['id'];
   }
	sqlsrv_query($conn, $stmt);
	
	if( ($errors = sqlsrv_errors() ) != null) {
		echo $stmt . "<br/>";
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

	
	header("Location: titles.php");
} else {
	echo "<script>alert(\"No employee selected!\");</script>";
}
?>