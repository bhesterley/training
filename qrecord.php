<?php
include("training.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Record Training</title>
</head>
<body>
<?php
    $id=$_REQUEST['sopid'];
    $rs=GetSOPDetail($conn, $id);
    $rows=sqlsrv_fetch_array($rs);
    $authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
    $sop_no=trim($_POST['sopnum']);
    $title=trim($_POST['title']);
    $rev=trim($_POST['rev']);
    $username=trim($_POST['authuser']);
    $initials=trim($_POST['initials']);

    sqlsrv_query($conn, "INSERT INTO Records (SOPNum, Category, Rev, TrainingID, Timestamp, Username, Initials, Evidence, Criteria, Type) VALUES ('$sop_no', '$title', '$rev', '$id', GETDATE(), '$username', '$initials', 'P', 'Review SOP', 'SOP')");

    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

    header("Location: qindex.php"); /* Redirect browser */
    exit();
?>


</body>
</html>
