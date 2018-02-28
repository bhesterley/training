<?php
include("training.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Other Training</title>
<style type="text/css">
textarea {
   font-family: arial;
   font-size: inherit;
}
</style>
<link rel="stylesheet" type="text/css" href="./css/sakura.css">
<link rel="stylesheet" type="text/css" href="./css/traininga.css">
</head>
<body>

<?php

$msg="";
$filename="";
$usermatch = 0;

if (isset($_REQUEST['action'])) {
	if($_REQUEST['action']=="delete"){
		$usercheck = sqlsrv_query($conn, "SELECT * FROM Records WHERE Username = '$authuser' AND ID = '".$_REQUEST['id']."'");
		if (!sqlsrv_has_rows($usercheck)) {
			echo "Error:  Item does not exist in user's training record.";
			die();
		} else {
			sqlsrv_query($conn, "DELETE FROM Records WHERE ID = '".$_REQUEST['id']."'");
			echo "<br />Document Deleted Successfully.<br />";
			echo '<a href="JavaScript:window.opener.location.reload();window.close();">Close</a>';
			die();
		}
	}
}

if (isset($_POST['action'])) {
if($_POST['action']=="add") {

	$id=trim($_POST['id']);
	$category=trim($_POST['Category']);
	$rev=trim($_POST['Rev']);
	$evidence=trim($_POST['Evidence']);
	$criteria=trim($_POST['Criteria']);
	$trainer=trim($_POST['Trainer']);
	$date_completed=trim($_POST['DateCompleted']);
	$attachment=$_FILES['Filename']['name'];
	$initials=trim($_POST['Initials']);
	
	if($category==""){
		$msg.="Enter a category.<br />";
	}
	if($evidence==""){
		$msg.="Select evidence type.<br />";
	}
	if($criteria==""){
		$msg.="Enter training criteria.<br />";
	}
	if($date_completed==""){
		$msg.="Enter completion date.<br />";
	}
	if($initials==""){
		$msg.="Enter your initials.<br />";
	}
	
	if($attachment!="") {
		$filename=UploadOtherTraining($attachment);
		if($filename==""){
			$msg.="Error uploading file.<br />";
		}
	}

/*
	$file_name = $_FILES['Filename']['name'];
	$upload_name = UploadSOP($file_name);
	if ($upload_name == "") { $msg.="Enter PDF File.<br />"; }
*/
	
	if($msg=="") {
		
		sqlsrv_query($conn, "UPDATE Records SET Category='$category', Rev='$rev', Evidence='$evidence', Criteria='$criteria', Trainer='$trainer', DateCompleted='$date_completed', ".($attachment==""?"":"Attachment='$filename', ")."Initials='$initials', Timestamp=GETDATE() WHERE ID=" . $id); 
		
		if( ($errors = sqlsrv_errors() ) != null) {
			foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".$error[ 'message']."<br />";
			}
		} else {
			echo "Training records updated successfully.<br />";
		}
		
		echo '<a href="JavaScript:window.opener.location.reload();window.close();">Close</a>';
		die();
	
	}
	
}
}

if (isset($_GET['id'])) {
	$record = sqlsrv_query($conn, "SELECT * FROM Records WHERE Username = '" . $authuser . "' AND ID = " . $_GET['id']);
	if (!sqlsrv_has_rows($record)) {
		echo "Unable to load information for the requested ID.";
		die();
	} else {
		while ($rows = sqlsrv_fetch_array($record)) {
			$category = $rows['Category'];
			$rev = $rows['Rev'];
			$evidence = $rows['Evidence'];
			$criteria = $rows['Criteria'];
			$trainer = $rows['Trainer'];
			$date_completed = $rows['DateCompleted']->format('m/d/Y');
			$username = $rows['Username'];
			$initials = $rows['Initials'];
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
        <td align="center">UPDATE TRAINING RECORD</td>
        </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
          <table width="373" border="0">
            <tr>
							<th width="128" align="left" scope="col">Category:<span style="color:#FF0000">*</span></th>
							<input type=hidden name="id" value="<?php echo $_GET['id']?>" />
              <td align="left"><input name="Category" type="text" id="Category" size="34" value="<?php echo $category ?>"  /></td>
              </tr>
            <tr>
              <td align="left">Rev:</td>
              <td align="left"><input name="Rev" type="text" id="Rev" size="34" value="<?php echo $rev ?>"  /></td>
              </tr>
			<tr>
              <td align="left">Evidence:<span style="color:#FF0000">*</span></td>
              <td align="left">
			  <select name="Evidence" id="Evidence" />
				<option value="">(Select one)</option>
				<option value="P"<?php if($evidence[0]=="P"){echo " selected";} ?>>Procedure Comprehension</option>
				<option value="D"<?php if($evidence[0]=="D"){echo " selected";} ?>>Demonstrated Ability</option>
				<option value="C"<?php if($evidence[0]=="C"){echo " selected";} ?>>Certification Received</option>
				<option value="O"<?php if($evidence[0]=="O"){echo " selected";} ?>>Other Training</option>
			  </select>
			  </td>
              </tr>
            <tr>
              <td align="left">Criteria:<span style="color:#FF0000">*</span><br /><font size="1">(brief description of criteria used in the training such as number of units manufactured, length of monitoring, etc.)</font></td>
              <td align="left"><textarea name="Criteria" id="Criteria" rows="8" cols="36"><?php echo $criteria ?></textarea></td>
              </tr>
            <tr>
              <td align="left">Trainer:</td>
              <td align="left"><input name="Trainer" type="text" id="Trainer" size="34" value="<?php echo $trainer ?>" /></td>
              </tr>
			<tr>
              <td align="left">Date Completed:<span style="color:#FF0000">*</span></td>
              <td align="left"><input name="DateCompleted" type="text" id="DateCompleted" size="34" value="<?php echo $date_completed ?>" /></td>
              </tr>
			<tr>
              <td colspan="2" align="center">Update Attachment:</td>
              </tr>
            <tr>
              <td colspan="2" align="center"><input name="Filename" type="file" id="Filename" /></td>
              </tr>
			<tr>
			  <td colspan="2"><br /><strong><em>&quot;I certify that I have completed this training.&quot;</em></strong></td>
              </tr>
			<tr>
			  <td align="left">Username:</td>
			  <td align="left">
			  <input name="Username" type="text" id="Username" size="34" value="<?php echo $username; ?>" readonly /></td>
              </tr>
            <tr>
			  <td align="left">Enter Initials:<span style="color:#FF0000">*</span></td>
			  <td align="left">
			  <input name="Initials" type="text" id="Initials" size="34" value="<?php echo $initials ?>" /></td>
              </tr>

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="center"><input type="hidden" name="action" value="add" /><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
							</tr>
							<tr><td colspan="2" align="center"><a href="other-edit.php?id=<?php echo $_GET['id'];?>&action=delete" onclick="return confirm('Are you sure you want to delete this item from your training record?');"><br /><br /><br />>>> DELETE THIS ITEM <<<<br /></a></td></tr>
          </table>
        </form></td>
      </tr>
    </table></th>
  </tr>
</table>
</body>
</html>
