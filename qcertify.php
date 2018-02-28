<?php
session_start();
include("training.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Enter Initials</title>
</head>
<body>
<?php
$id=$_REQUEST['id'];
$rs=GetSOPDetail($conn, $id);
$rows=sqlsrv_fetch_array($rs);
$authuser = $_SESSION['Username'];

$msg="";

if (isset($_POST['action'])) {
if($_POST['action']=="certify"){
$msg="";
	if(trim($_POST['SOPNum'])==""){
		$msg.="Enter SOP No.<br />";
	}
	if(trim($_POST['Title'])==""){
		$msg.="Enter Title.<br />";
	}
	if(trim($_POST['Rev'])==""){
		$msg.="Enter REV.<br />";
	}
	if(trim($_POST['EffectiveDate'])==""){
		$msg.="Enter Effective Date.<br />";
	}
	if(trim($_POST['Username'])==""){
		$msg.="Enter Username.<br />";
	}
	if(trim($_POST['Initials'])==""){
		$msg.="Enter Initials.<br />";
	}
	
if($msg==""){
$id=$_REQUEST['id'];
	$sop_no=trim($_POST['SOPNum']);
	$title=trim($_POST['Title']);
	$rev=trim($_POST['Rev']);
	$effective_date=trim($_POST['EffectiveDate']);
	$username=trim($_POST['Username']);
	$initials=trim($_POST['Initials']);

	sqlsrv_query($conn, "INSERT INTO Records (SOPNum, Category, Rev, TrainingID, Timestamp, Username, Initials, Evidence, Criteria, Type) VALUES ('$sop_no', '$title', '$rev', '$id', GETDATE(), '$username', '$initials', 'P', 'Review SOP', 'SOP')");

	if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
	
	echo "<br />Training Records Updated.<br />";
	echo '<a href="JavaScript:window.close();">Close</a>';
	die();
}
}
}

?>

<table width="400" height="349" border="2">
  <tr>
    <th valign="top" bgcolor="#EAEAEA" scope="col"><table width="380" border="0">
      <tr>
        <th width="370" scope="col" style="color:#FF0000"><?php echo $msg;?></th>
        </tr>
      <tr>
        <td align="center">TRAINING REVIEW FORM</td>
        </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" action="certify.php?id=<?php echo $_REQUEST['id'];?>" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $rows['ID'];?>"  />
        <table width="373" border="0">
            <tr>
				<th width="128" align="left" scope="col">SOP No.</th>
				<th width="235" align="left" scope="col"><input name="SOPNum" type="text" id="SOPNum" value="<?php echo $rows['SOPNum'];?>" readonly /></th>
            </tr>
            <tr>
				<td align="left">Title:</td>
				<td align="left"><input name="Title" type="text" id="Title" value="<?php echo $rows['Title'];?>" readonly /></td>
            </tr>
            <tr>
				<td align="left">Rev:</td>
				<td align="left"><input name="Rev" type="text" id="Rev" value="<?php echo $rows['Rev'];?>" readonly /></td>
            </tr>
            <tr>
				<td align="left">Effective Date</td>
				<td align="left"><input name="EffectiveDate" type="text" id="EffectiveDate" value="<?php echo $rows['EffectiveDate'];?>" readonly /></td>
            </tr>
            <tr>
				<td colspan="2">&nbsp;</td>
            </tr>
            <tr>
				<td colspan="2"><strong><em>&quot;I certify that I have read this training document&quot;</em></strong></td>
            </tr>
			<tr>
				<td align="left">Username:</td>
				<td align="left">
				<input name="Username" type="text" id="Username" value="<?php echo $authuser;?>" readonly /></td>
            </tr>
            <tr>
				<td align="left">Enter Initials:</td>
				<td align="left">
				<input name="Initials" type="text" id="Initials" /></td>
            </tr>
            <tr>
              <td colspan="2" align="center"><br /><input type="hidden" name="action" value="certify" /><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
            </tr>
          </table>
        </form></td>
      </tr>
    </table></th>
  </tr>
</table>
</body>
</html>
