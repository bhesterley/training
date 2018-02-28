<?php
include("../training.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Update SOP</title>
</head>
<body>
<?php
$id=$_REQUEST['id'];
$rs=GetSOPDetail($conn, $id);
$rows=sqlsrv_fetch_array($rs);

if (isset($_REQUEST['action'])) {
if($_REQUEST['action']=="delete"){

$grs = GetSOPGroups($conn, $id);
$groups = array();
$groups = sqlsrv_fetch_array($grs);

sqlsrv_query($conn, "UPDATE SOPs SET Active = 0 WHERE ID = '$id'");
echo "<br />Document Deleted Successfully.<br />";

echo '<a href="JavaScript:window.opener.location.reload();window.close();">Close</a>';
die();
?>
<script type="text/javascript">
	window.location="update.php?id=<?php echo $id ?>";
</script>
<?php
}
}

$msg="";

$SOPNum="";
$Title="";
$Rev="";
$EffectiveDate="";
$notRequired = 0;

if (isset($_REQUEST['action'])) {
if($_POST['action']=="update") {

	$id=$_POST['id'];
	$SOPNum=trim($_POST['SOPNum']);
	$Title=trim($_POST['Title']);
	$Rev=trim($_POST['Rev']);
	$EffectiveDate=trim($_POST['EffectiveDate']);
  $file_name = $_FILES['Filename']['name'];
  
  if (isset($_POST['not-required']) && $_POST['not-required'] == 'Yes') {
    $notRequired = 1;
  }

	if($SOPNum==""){
		$msg.="Enter SOP No.<br />";
	}
	if($Title==""){
		$msg.="Enter Title.<br />";
	}
	if($Rev==""){
		$msg.="Enter REV.<br />";
	}
	if($EffectiveDate==""){
		$msg.="Enter Effective Date.<br />";
  }
  if(!isset($_POST['Approvers'])) {
    $msg.="Please select at least one Approver.<br />";
  }

	if ($msg == "") {

		$upload_name = UploadSOP($file_name);
		if ($upload_name != "") {
			unlink('../Released_SOPs/'.$rows['Filename']);
			sqlsrv_query($conn, "UPDATE SOPs SET Filename = '$upload_name' WHERE ID = '$id'");
		}

		sqlsrv_query($conn, "UPDATE SOPs SET SOPNum = '$SOPNum', Title = '$Title', Rev = '$Rev'".($notRequired == 0 ? ", Minimum = '$Rev'" : "").", EffectiveDate = '$EffectiveDate' WHERE ID = '$id'"); 
    
    foreach ($_POST['Approvers'] as $appid) {
      $rsa = GetEmployeeDetailsById($conn, $appid);
      while ($rowsa = sqlsrv_fetch_array($rsa)) {
        $username = $rowsa['Username'];
        sqlsrv_query($conn, "INSERT INTO Records (Username, SOPNum, Category, Rev, Evidence, Criteria, Type, TrainingID, Initials, Timestamp) VALUES ('$username', '$SOPNum', '$Title', '$Rev', 'P', 'Review SOP', 'SOP', '$id', 'APPROVER', GETDATE())");
      }
    }    
    
    echo "<br />Data Updated Successfully.<br />";
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
        <td align="center">UPDATE SOP</td>
        </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" action="update.php?id=<?php echo $_REQUEST['id'];?>" enctype="multipart/form-data">
<input name="id" type="hidden" value="<?php echo $rows['ID'];?>"  />
          <table width="423" border="0">
            <tr>
              <th width="228" align="left" scope="col">SOP No.</th>
              <th width="235" align="left" scope="col"><input name="SOPNum" type="text" id="SOPNum" value="<?php echo $rows['SOPNum'];?>"  /></th>
            </tr>
            <tr>
              <td align="left">Title:</td>
              <td align="left"><input name="Title" type="text" id="Title" value="<?php echo $rows['Title'];?>"  /></td>
            </tr>
            <tr>
              <td align="left">Rev:</td>
              <td align="left"><input name="Rev" type="text" id="Rev" value="<?php echo $rows['Rev'] + 1;?>"  /></td>
            </tr>
            <tr>
              <td align="left">Effective Date</td>
              <td align="left"><input name="EffectiveDate" type="text" id="EffectiveDate" value="<?php echo $rows['EffectiveDate'];?>" /></td>
            </tr>
            <tr>
              <td align="left">Upload File</td>
              <td align="left"><input name="Filename" type="file" /></td>
            </tr>
            <tr>
              <td colspan="2" align="center" style="font-weight:normal;"><br /><input type="checkbox" name="not-required" value="Yes"> Check here if this Rev does <strong>NOT</strong> require training.</input></td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
              <td colspan="2">Select the Approvers for this release:<hr></td>              
            </tr>
            <tbody style="font-weight:normal;font-size:14px;">
            
              <?php 
                $rs = GetApprovers($conn);
                while ($rows=sqlsrv_fetch_array($rs)) {
                  echo "<tr><td align=\"left\">".$rows['FullName']."</td>
                  <td align=\"left\"><input name=\"Approvers[]\" type=\"checkbox\" value=\"".$rows['ID']."\" /></td></tr>";
                }
              ?>
                          
            </tbody>            
            <tr>
              <td colspan="2" align="center"><br /><input type="hidden" name="action" value="update" /><input type="submit" name="Submit" id="Submit" value="Submit" /></td>
            </tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
			<td colspan="2" align="center"><a href="update.php?id=<?php echo $_REQUEST['id'];?>&action=delete" onclick="return confirm('Are you sure you want to delete this SOP?');">Delete this SOP</a></td>
			</tr>
          </table>
        </form></td>
      </tr>
    </table></th>
  </tr>
</table>
</body>
</html>
