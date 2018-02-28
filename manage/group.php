<?php
include("../training.php");
$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName, EmailAddress, AccessLevel FROM Employees WHERE Name = '" . $authuser . "'");
$sopquery = GetSOPIndex($conn);
$qmquery = GetQMIndex($conn);
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
   $email = $rows['EmailAddress'];
   $accesslevel = $rows['AccessLevel'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>Group Training</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="../css/sakura.css" />
      <link rel="stylesheet" type="text/css" href="../css/traininga.css" />
      <style type="text/css">
         .main-row-td-p {
            margin: 8px;
         }
      </style>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script>
            function SOPChange() {
                  if (document.getElementById('sop-check').checked == true) {
                        document.getElementById('sop-select').hidden = false;
                        document.getElementById('group-row-1').hidden = true;
                        document.getElementById('group-row-2').hidden = true;
                  } else {
                        document.getElementById('sop-select').hidden = true;
                        document.getElementById('group-row-1').hidden = false;
                        document.getElementById('group-row-2').hidden = false;
                  }
            }
            function ExpandDept (deptId) {
                if (document.getElementById('tb-'+deptId).style.display=='none') {
                    document.getElementById('tb-'+deptId).style.display = '';
                    document.getElementById('expand-'+deptId).innerHTML = '&minus;';
                } else {
                    document.getElementById('tb-'+deptId).style.display = 'none';
                    document.getElementById('expand-'+deptId).innerHTML = '&plus;';                    
                }
            }
      </script>
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
			<span class="navlink">
				<a href=".">Assign Training</a>
			</span>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;         
			<span class="navlink-active">
				<a href="javascript:window.location.reload();">Group Training</a>
			</span>    
		</div>
      </div>
      <div align="center">
         <span id="edited" style="display:none;">0</span>
         <span id="groupindex" style="display:none;"></span>
<?php
      if (isset($_POST['action'])) {
	if($_POST['action']=="update"){
            $username = '';
            $category = '';
            $dateCompleted = '';
            $sopnum = '';
            $selectid = '';
            $title = '';
            $rev = '';
            $evidence = 'P';
            $criteria = 'Attended Session';
            $trainer = '';
            $type = 'Group';
            $timestamp = '';
            $initials = 'N/A';
            $attachment = '';
            $filename = '';
            if (isset($_POST['topic'])) {$category = $_POST['topic'];}
            if (isset($_POST['date'])) {$dateCompleted = $_POST['date'];}
            if (isset($_POST['docnum'])) {$sopnum = $_POST['docnum'];}
            if (isset($_POST['rev'])) {$rev = $_POST['rev'];}
            if (isset($_POST['trainer'])) {$trainer = $_POST['trainer'];}
            $timestamp = $dateCompleted;
            $attachment = $_FILES['Filename']['name'];
            if ($attachment != '' && !isset($_POST['sop-check'])) {$filename = UploadGroupTraining($attachment);}
		if (!empty($_POST['employees'])) {
			$employees = $_POST['employees'];
			foreach($employees as $item) {
                        $rse = sqlsrv_query($conn, "SELECT Name FROM Employees WHERE ID = '$item'");
                        while($rwse=sqlsrv_fetch_array($rse)){
                              $username = $rwse['Name'];
                        }
                        if (isset($_POST['sop-check'])) {
                              $selectid = $_POST['sop-select'];
                              $rsd = GetSOPDetailBySOPNum($conn, $selectid);
                              while($rwsd=sqlsrv_fetch_array($rsd)){
                                    $title = $rwsd['Title'];
                                    $rev = $rwsd['Rev'];
                                    $id = $rwsd['ID'];
                              }
                              sqlsrv_query($conn, "INSERT INTO Records (SOPNum, Category, Rev, TrainingID, Timestamp, Username, Initials, Evidence, Criteria, Type) VALUES ('".$selectid."', '".$title."', '".$rev."', '".$id."', GETDATE(), '".$username."', 'GROUP', 'P', 'Review SOP', 'SOP')");
                        } else {
                              sqlsrv_query($conn, "INSERT INTO Records (Username, Category, DateCompleted, SOPNum, Rev, Evidence, Criteria, Trainer, Type, Timestamp, Initials, Attachment) VALUES ('".$username."','".$category."','".$dateCompleted."','".$sopnum."','".$rev."','".$evidence."','".$criteria."','".$trainer."','".$type."','".$timestamp."','".$initials."','".$filename."')");
                        }
				if( ($errors = sqlsrv_errors() ) != null) {
					foreach( $errors as $error ) {
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".$error[ 'message']."<br />";
					}
				}
			}
		} else {
			echo "<script>alert('Please assign at least one employee.');</script>";
		}

	      }
      }
?>
         <br /><br />
    
         <table class="main shadow">
            <tr class="table-head">
               <td class="table-head-data" colspan=3>
                  <p align=center>
                     <span>
                        Add Group Training Event
                     </span>
                  </p>
                  <?php
                    $rsdets=GetDepartments($conn);
                    
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"group.php\" enctype=\"multipart/form-data\">";
?>
                  <p style="font-size:80%;font-weight:bold;">Check here if this is SOP TRAINING: <input type="checkbox" id="sop-check" name="sop-check" onchange="SOPChange();"></p>
                  <p>
                        <select id="sop-select" name="sop-select" hidden>
                              <?php
                                    while ($rows=sqlsrv_fetch_array($qmquery)) {
                                          echo "<option value=\"".$rows['SOPNum']."\">".$rows['SOPNum']." ".$rows['Title']." (Rev ".$rows['Rev'].")</option>";
                                    }
                                    while ($rows=sqlsrv_fetch_array($sopquery)) {
                                          echo "<option value=\"".$rows['SOPNum']."\">".$rows['SOPNum']." ".$rows['Title']." (Rev ".$rows['Rev'].")</option>";
                                    }
                              ?>
                        </select>
                  </p>
               </td>
            </tr>

 

            
            <tr id="group-row-1" class="table-header-row">
               <td class="main-rowhead-td-first">
                  <p style="text-align:center;">
                     <input size="30" type="text" name="topic" placeholder="Topic" />
                  </p>
               </td>
                       
               <td class="main-rowhead-td">
                  <p style="text-align:center;">
                     <input size="30" type="text" name="trainer" placeholder="Trainer" />
                  </p>
               </td>
              
               <td class="main-rowhead-td">
                  <p class="main-rowhead-td-p" style="text-align:center;">
                     <input size="30" type="text" name="date" placeholder="Date" />
                  </p>
               </td>
               </tr> 
            <tr id="group-row-2" class="table-header-row">               
               
               <td class="main-rowhead-td-first">
                  <p style="text-align:center;">
                     <input size="30" type="text" name="docnum" placeholder="Document number (optional)" />
                  </p>
               </td>
                
               <td class="main-rowhead-td">
                  <p style="text-align:center;">
                     <input style="width:20em;" min="0" max="255" type="number" name="rev" placeholder="Revision (optional)" />
                  </p>
               </td>
              

                
               <td class="main-rowhead-td">
                  <p style="text-align:center;">
                     Attach file:
                     <input type="file" name="Filename" id="Filename" placeholder="Attachment" />
                  </p>
               </td>               
            </tr>
            

<?php

while($rowsout=sqlsrv_fetch_array($rsdets)) {
    echo "<tr style='background-color:#cde;' class='main-row' id='deptrow-" . $rowsout['id'] . "'>
            <td class='main-rowhead-td' colspan=3>
                <p class='main-row-td-p' style='text-align:left;font-weight:bold;font-size:1.6rem;'>
                    <a href=\"javascript:void(0);\" onclick=\"ExpandDept('".$rowsout['id']."');\">&nbsp;<span id=\"expand-".$rowsout['id']."\">&plus;</span>&nbsp;&nbsp;".$rowsout['Name']."</a>
                </p>
            </td>
          </tr>
          <tbody id='tb-".$rowsout['id']."' style='display:none;width:100%;'>";
    $rowcount = 0;
    $rs=GetEmployeeDetails($conn);
    while($rows=sqlsrv_fetch_array($rs)){
        if ($rows['DepartmentID'] == $rowsout['id']) {
            $rowcount++;
            echo "<tr class='main-row' id='row-" . $rows['EmployeeID'] . "' onMouseOver='this.style.backgroundColor=\"#eee\";' onMouseOut='this.style.backgroundColor=\"".($rowcount % 2 == 0 ? "#fffefc" : "#e5ecdc")."\";' style='width:100%;background-color:".($rowcount % 2 == 0 ? "#fffefc" : "#e5ecdc").";'>
                    <td class='main-rowhead-td' colspan=3>
                        <p id='employee-name' class='main-row-td-p' style='text-align:left;'>
                              <input type=\"checkbox\" id=\"chk-" . $rows['EmployeeID'] . "\" name=\"employees[]\" value=\"" . $rows['EmployeeID'] . "\">".$rows['EmployeeName']."
                        </p>
                    </td>
                </tr>";
        }
    }
    echo "</tbody>";
}

?>
            </table>

         </div>
         <br/>
         <input type="hidden" name="action" value="update" />
         <div class="main-apply-cancel">
            <input class="button shadow" type="submit" name="Submit" id="Submit" value="Submit Training" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input class="apply-button shadow" type="button" name="Cancel" id="Cancel" value="Cancel" onClick="location.reload();" />
         </div>
      </form>
      <p>
         &nbsp;
      </p>
      <!--<script type="text/javascript" src="../js/training.js"></script>-->
   </body>
</html>