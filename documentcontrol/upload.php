<?php
include("../training.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Upload SOP</title>    
  </head>

<body>

<?php

$msg="";

$sop_no="";
$title="";
$rev="";
$effective_date="";

if (isset($_POST['action'])) {
if($_POST['action']=="add") {

$sop_no=trim($_POST['SOPNum']);
$title=trim($_POST['Title']);
$rev=trim($_POST['Rev']);
$effective_date=trim($_POST['EffectiveDate']);
$tmp_name = $_FILES['Filename']['tmp_name'];
$file_name = $_FILES['Filename']['name'];
	
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
	if(trim($file_name)=="" || strtolower(substr(strrchr($file_name,'.'),1)) != "pdf") {
		$msg.="Please select a PDF file.<br />";
  }
  if(!isset($_POST['Approvers'])) {
    $msg.="Please select at least one Approver.<br />";
  }
	
	if($msg=="") {
	
		$sop_no=trim($_POST['SOPNum']);
		$title=trim($_POST['Title']);
		$rev=trim($_POST['Rev']);
		$effective_date=trim($_POST['EffectiveDate']);
		// $download_pdf=trim($_POST['Filename']);
    $upload_name = UploadSOP($file_name);

		sqlsrv_query($conn, "INSERT INTO SOPs (SOPNum, Title, Rev, Minimum, EffectiveDate, Filename, TypeID) VALUES ('$sop_no', '$title', '$rev', '$rev', '$effective_date', '$upload_name', '2')");
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
        } 
    
    $lastrs = sqlsrv_query($conn, "SELECT IDENT_CURRENT('SOPs')");
    while ($lastrows = sqlsrv_fetch_array($lastrs)) {
      $last_id = $lastrows[0];
    }    
        
    foreach ($_POST['Approvers'] as $id) {
      $rsa = GetEmployeeDetailsById($conn, $id);
      while ($rowsa = sqlsrv_fetch_array($rsa)) {
        $username = $rowsa['Username'];
        sqlsrv_query($conn, "INSERT INTO Records (Username, SOPNum, Category, Rev, Evidence, Criteria, Type, TrainingID, Initials, Timestamp) VALUES ('$username', '$sop_no', '$title', '$rev', 'P', 'Review SOP', 'SOP', '$last_id', 'APPROVER', GETDATE())");
      }
    }

		echo "Document Uploaded Successfully.<br />";
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
        <td align="center">UPLOAD SOP</td>
        </tr>
      <tr>
        <td align="center"><form id="form1" name="form1" method="post" enctype="multipart/form-data" action="">
          <table width="373" border="0">
            <tr>
              <th width="128" align="left" scope="col">SOP No.</th>
              <th width="235" align="left" scope="col"><input name="SOPNum" type="text" id="SOPNum" value="<?php echo $sop_no;?>"  /></th>
              </tr>
            <tr>
              <td align="left">Title:</td>
              <td align="left"><input name="Title" type="text" id="Title" value="<?php echo $title;?>"  /></td>
              </tr>
            <tr>
              <td align="left">Rev:</td>
              <td align="left"><input name="Rev" type="text" id="Rev" value="<?php echo $rev;?>"  /></td>
              </tr>
            <tr>
              <td align="left">Effective Date</td>
              <td align="left"><input name="EffectiveDate" type="text" id="EffectiveDate" value="<?php echo $effective_date;?>" /></td>
              </tr>
            <tr>
              <td colspan="2" align="center">Upload Pdf:</td>
              </tr>
            <tr>
              <td colspan="2" align="center"><input name="Filename" type="file" id="Filename" />
			</td>
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
