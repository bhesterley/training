<?php
session_start();
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
</head>
<body>

<?php

$msg="";
$authuser = $_SESSION['Username'];

if (isset($_POST['action'])) {
if($_POST['action']=="add") {

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
		$filename=UploadAttachment($attachment);
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
	
		sqlsrv_query($conn, "INSERT INTO Records (Username, Category, Rev, Evidence, Criteria, Trainer, DateCompleted, Type, Attachment, Initials, Timestamp) VALUES ('$authuser', '$category', " . ($rev=="" ? "NULL" : "'".$rev."'") . ", '$evidence', '$criteria', '$trainer', '$date_completed', 'Other', '$attachment', '$initials', GETDATE())"); 
		
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

?>

<table width="400" height="349" border="2">
  <tr>
    <th valign="top" bgcolor="#EAEAEA" scope="col"><table width="380" border="0">
      <tr>
        <th width="370" scope="col" style="color:#FF0000"><?php echo $msg;?></th>
        </tr>
      <tr>
        <td align="center">ADD NEW TRAINING RECORD</td>
        </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
          <table width="373" border="0">
            <tr>
              <th width="128" align="left" scope="col">Category:<span style="color:#FF0000">*</span></th>
              <td align="left"><input name="Category" type="text" id="Category" size="34" value=""  /></td>
              </tr>
            <tr>
              <td align="left">Rev:</td>
              <td align="left"><input name="Rev" type="text" id="Rev" size="34" value=""  /></td>
              </tr>
			<tr>
              <td align="left">Evidence:<span style="color:#FF0000">*</span></td>
              <td align="left">
			  <select name="Evidence" id="Evidence" />
				<option value="">(Select one)</option>
				<option value="P">Procedure Comprehension</option>
				<option value="D">Demonstrated Ability</option>
				<option value="C">Certification Received</option>
				<option value="O">Other Training</option>
			  </select>
			  </td>
              </tr>
            <tr>
              <td align="left">Criteria:<span style="color:#FF0000">*</span><br /><font size="1">(brief description of criteria used in the training such as number of units manufactured, length of monitoring, etc.)</font></td>
              <td align="left"><textarea name="Criteria" id="Criteria" rows="8" cols="36"></textarea></td>
              </tr>
            <tr>
              <td align="left">Trainer:</td>
              <td align="left"><input name="Trainer" type="text" id="Trainer" size="34" value="" /></td>
              </tr>
			<tr>
              <td align="left">Date Completed:<span style="color:#FF0000">*</span></td>
              <td align="left"><input name="DateCompleted" type="text" id="DateCompleted" size="34" value="" /></td>
              </tr>
			<tr>
              <td colspan="2" align="center">Add Attachment:</td>
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
			  <input name="Username" type="text" id="Username" size="34" value="<?php echo $authuser;?>" readonly /></td>
              </tr>
            <tr>
			  <td align="left">Enter Initials:<span style="color:#FF0000">*</span></td>
			  <td align="left">
			  <input name="Initials" type="text" id="Initials" size="34" /></td>
              </tr>

            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2" align="center"><input type="hidden" name="action" value="add" /><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
              </tr>
          </table>
        </form></td>
      </tr>
    </table></th>
  </tr>
</table>
</body>
</html>
