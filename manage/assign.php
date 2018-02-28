<?php
include("../training.php");
$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName FROM Employees WHERE Name = '" . $authuser . "'");
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Assign SOPs</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="../css/sakura.css" />
      <link rel="stylesheet" type="text/css" href="../css/traininga.css" />
      <style type="text/css">
         .main-row-td-p {
            margin: 8px;
         }
      </style>
	</head>

	<body>
      <span class="logo">
         <img src="../images/loader_logo.png" />
      </span>
      <span class="user">
         Logged in user: <strong><?php echo $fullname?></strong>
      </span>
		<br />
      <div class="shadow">
		<div id="title">
         <span>Employee Training System</span>
      </div>
		<div id="topnav">
			<span class="navlink">
				<a href="..">Home</a>
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="navlink">
				<a href="../documentcontrol">Document Control</a>
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="navlink">
				<a href="./titles.php">Employees</a>
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="navlink-active">
				<a href=".">Assign Training</a>
                  </span>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <span class="navlink">
                        <a href="../manage/group.php">Group Training</a>
                  </span>
		</div>
      </div>
         <div align="center">
            <span id="edited" style="display:none;">0</span>
            <span id="groupindex" style="display:none;"></span>
<?php
$reqrows = array();
$reqpost = array();
if (isset($_REQUEST['id'])) {
	$groupid = $_REQUEST['id'];
} else {
	$rxs = sqlsrv_query($conn, "SELECT TOP 1 ID FROM Groups ORDER BY Name");
	while ($rows = sqlsrv_fetch_array($rxs)) {
		$groupid = $rows['ID'];
	}
}
$rs = sqlsrv_query($conn, "SELECT Name FROM Groups WHERE ID = '$groupid'");
$rqs=GetRequiredSOPs($conn, $groupid);
while($rows=sqlsrv_fetch_array($rqs)){
	$reqrows[]=$rows['SOPID'];
}
if (isset($_POST['action'])) {
	if($_POST['action']=="update"){
		if (!empty($_POST['required'])) {
			$reqpost = $_POST['required'];
			$diff = array();
			$diff = array_diff($reqpost, $reqrows);
			foreach($diff as $item) {
				sqlsrv_query($conn, "INSERT INTO RequiredSOPs (GroupID, SOPID) VALUES ('" . $groupid . "', '" . $item . "')");
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
			$diff = array_diff($reqrows, $reqpost);
			foreach($diff as $item) {
				sqlsrv_query($conn, "DELETE FROM RequiredSOPs WHERE GroupID = " . $groupid . " AND SOPID = " . $item);
			}
		} else {
			echo "<script>alert('Please assign at least one SOP.');</script>";
		}
		$reqrows = $reqpost;
	}
}
?>
         <br /><br />
         <table class="main shadow">
            <tr class="table-head">
               <td class="table-head-data" colspan=4>
                  <p align=center>
                     <span>
                        Assign SOPs
                     </span>
                  </p>
                  <p>
                     <span class='selectgroup-wrapper'>Title: 
                        <span id="selectgroup-span">
                           <select id="SelectGroup" onChange = "selectChange();">
<?php
	$rs = sqlsrv_query($conn, "SELECT * FROM TitleDepartment ORDER BY Name");
	while($rows=sqlsrv_fetch_array($rs)) {
		echo "<option value=\"" . $rows['ID'] . "\"" . ($groupid==$rows['ID'] ? " selected" : "") . ">" . $rows['Number'] . $rows['Name'] . "</option>";
	}
?>
                           </select>
                        </span>
                        &nbsp;
                        <span id="copyicon">
                           <a href="javascript:void(0);" onClick="document.getElementById('copycontrols').hidden=false;this.hidden=true;">
                              <sub>
                                 <img src="../images/copy-document.png" title="Copy group" />
                              </sub>
                           </a>
                        </span>
                     </span>
                  </p>
                  <p id="copycontrols" hidden>
                     
                        Copy to: 
                        <select id="CopyGroup">
<?php
	$rs = sqlsrv_query($conn, "SELECT * FROM TitleDepartment ORDER BY Name");
	while($rows=sqlsrv_fetch_array($rs)) {
		if ($rows['ID'] != $groupid) {
			echo "<option value=\"" . $rows['ID'] . "\">" . $rows['Number'] . $rows['Name'] . "</option>";
		}
	}
?>
                        </select>
                        <br /><br />
                        <button type="button" onClick="copyGroups();">Submit</button>
                        &nbsp;
                        <button type="button" onClick="location.reload();">Cancel</button>
                     
                  </p>
               </td>
            </tr>
            <tr class="table-header-row">
               <td class= "main-rowhead-td-first">
                  <p class="main-rowhead-td-p">
                     <strong>SOP No.</strong>
                  </p>
               </td>
               <td id="sop-title-head" class="main-rowhead-td">
                  <span class="selectall-span">
                     <input type="checkbox" id="selectall" onChange="selectAllClicked();" />
                  </span>
                  <p class="main-rowhead-td-p">
                     <strong>Title</strong>
                  </p>
               </td>
               <td class="main-rowhead-td" valign=top>
                  <p class="main-rowhead-td-p">
                     <strong>Rev</strong>
                  </p>
               </td>
               <td class="main-rowhead-td" valign=top>
                  <p class="main-rowhead-td-p">
                     <strong>Effective Date</strong>
                  </p>
               </td>
            </tr>
 
<?php
$rs=GetSOPIndex($conn);
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"assign.php?id=$groupid\" enctype=\"multipart/form-data\">";
$rowcount = 0;
while($rows=sqlsrv_fetch_array($rs)){
   $rowcount++;
   echo "<tr class='main-row' id='row-" . $rows['ID'] . "' onMouseOver='this.style.backgroundColor=\"#eee\";' onMouseOut='this.style.backgroundColor=\"".($rowcount % 2 == 0 ? "#fffefc" : "#e5ecdc")."\";' style='background-color:".($rowcount % 2 == 0 ? "#fffefc" : "#e5ecdc").";" . (in_array($rows['ID'], $reqrows) ? " font-weight:bold;" : "") . "'>
         <td class='main-rowhead-td-first'>
            <p class='main-row-td-p'>
               <span>".$rows['SOPNum']."</span>
            </p>
         </td> 
         <td class='main-rowhead-td'>
            <p id='sop-title' class='main-row-td-p'>
               <input type=\"checkbox\" id=\"chk-" . $rows['ID'] . "\" onChange=\"chkMark(this);\" name=\"required[]\" value=\"" . $rows['ID'] . "\" " . (in_array($rows['ID'], $reqrows) ? "checked" : "") . ">
               <a href='javascript:void(0);' onClick=\"javascript:window.open('../Released_SOPs/".$rows['Filename']."');\">".$rows['Title']."</a>
            </p>
         </td>
         <td class='main-rowhead-td'>
            <p class='main-row-td-p'>
               <span>".$rows['Rev']."</span>
            </p>
         </td>
         <td class='main-rowhead-td'>
            <p class='main-row-td-p'>
               <span>".$rows['EffectiveDate']."</span>
            </p>
         </td>
      </tr>";
}
?>
            </table>
         </div>
         <br/>
         <input type="hidden" name="action" value="update" />
         <div class="main-apply-cancel">
            <input class="button shadow" type="submit" name="Submit" id="Submit" value="Apply Changes" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input class="apply-button shadow" type="button" name="Cancel" id="Cancel" value="Cancel" onClick="location.reload();" />
         </div>
      </form>
      <p>
         &nbsp;
      </p>
      <script type="text/javascript" src="../js/training.js"></script>
   </body>
</html>