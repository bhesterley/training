<?php
include("../training.php");
$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName FROM Employees WHERE Name = '" . $authuser . "'");
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
}
?>
<!doctype html>
<html>
	<head>
		<title>Employee Details</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <link rel="stylesheet" type="text/css" href="../css/sakura.css">
	  <link rel="stylesheet" type="text/css" href="../css/traininga.css" />
      <style type="text/css">
         .main-row-td-p {
            text-align:left;
            margin: 8px;
         }
	  </style>
		<script type="text/javascript">
			function ReplaceContentInContainer(id, content) {
				var anchors = document.getElementsByTagName("a");
				while (anchors.length) {
					var span = document.createElement("span");
					span.innerHTML = anchors[0].innerHTML;
					anchors[0].parentNode.replaceChild(span, anchors[0]);
				}
				var rows = document.getElementsByTagName("tr");
				for (var i = 0, row; row = rows[i]; i++) {
					row.removeAttribute('onMouseOver');
				}
				//document.body.style.backgroundColor = "#e8e8e8";
				document.getElementById('td-9999').removeAttribute('onClick');
				document.getElementById('td-9999').removeAttribute('onMouseOver');
				document.getElementById('td-9999').removeAttribute('onMouseOut');
				document.getElementById('td-9999').style.backgroundColor = "#e8e8e8";
				var container = document.getElementById(id);
				container.innerHTML = content;
			}
			
			function ConfirmDelete(id) {
				if (confirm("Delete this user?")) {
					location.href='delete-user.php?id=' + id;
				}
			}
			
			function UpdateUser(id) {
				location.href='update-user.php?id=' + id + '&fullname=' + document.getElementById('fullname-' + id).value + '&title=' + document.getElementById('title-' + id).value + '&username=' + document.getElementById('username-' + id).value + '&email=' + document.getElementById('email-' + id).value + '&supervisor=' + document.getElementById('super-' + id).value;
			}

			function addTitle (action, id) {
				if (action == 9999) {
					alert('Coming soon!');
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
      <div class="shadow" style="min-width:800px;">
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
            <span class="navlink-active">
               <a href="./titles.php">Employees</a>
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="navlink">
               <a href=".">Assign Training</a>
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         	<span class="navlink">
            	<a href="../manage/group.php">Group Training</a>
         	</span>
         </div>
      </div>
      <div align="center">
<?php
$reqrows = array();
$reqpost = array();
$alert = "";
if (isset($_POST['action'])) {
	if($_POST['action']=="update"){
		foreach($_POST as $key => $value) {
			$chk = explode("-", $key);
			if ($chk[0] == "user") {
				sqlsrv_query($conn, "UPDATE Employees SET GroupID = " . $value . "WHERE ID = " . $chk[1]);
			}
		}
	}
}
?>
         <br /><br />
         <table class="main shadow">
            <tr class="table-head">
               <td class="table-head-data" colspan=5 style="background-color:#fcfcfc;">
                  <p align=center style="padding: 10px;">
                     <span style="font-size: 2.25rem;">
                        Employee Details
                     </span>
                  </p>
               </td>
            </tr>
            <tr class="table-header-row">
               <td class="main-rowhead-td-first">
                  <p class="main-rowhead-td-p">
                     <strong>Name</strong>
                  </p>
               </td>
               <td class="main-rowhead-td">
                  <p class="main-rowhead-td-p">
                     <strong>Title</strong>
                  </p>
               </td>
               <td class="main-rowhead-td">
                  <p class="main-rowhead-td-p">
                     <strong>Username</strong>
                  </p>
               </td>
               <td class="main-rowhead-td">
                  <p class="main-rowhead-td-p">
                     <strong>Email Address</strong>
                  </p>
               </td>
               <td class="main-rowhead-td">
                  <p class="main-rowhead-td-p">
                     <strong>Supervisor</strong>
                  </p>
               </td>
            </tr>
<?php
	$titles = "<select onChange=&quot;addTitle(this.value, 9999);&quot; id=&quot;title-9999&quot;>";
	$titles .= "<option value=&quot;&quot;>(Select a title)</option>";	
	$titles .= "<option value=&quot;9999&quot;>Create new title...</option>";
	$rsz = sqlsrv_query($conn, "SELECT * FROM TitleDepartment ORDER BY Name");	
	while($rowsz=sqlsrv_fetch_array($rsz)) {	
		$titles .= "<option value=&quot;" . $rowsz['ID'] . "&quot;>" . $rowsz['Number'] . " " . $rowsz['Name'] . "</option>";
	}
	$titles .= "</select>";
	$supervisors = "<select id=&quot;super-9999&quot;>";
	$supervisors .= "<option value=&quot;&quot;>(Select a name)</option>";
	$rsz = sqlsrv_query($conn, "SELECT * FROM Employees WHERE Active = 1 ORDER BY FullName");	
	while($rowsz=sqlsrv_fetch_array($rsz)) {	
		$supervisors .= "<option value=&quot;" . $rowsz['FullName'] . "&quot;>" . $rowsz['FullName'] . "</option>";
	}
	$supervisors .= "</select>";
	
	echo "
	<tr class=\"table-header-row\" id=\"row-9999\">				
		<td id=\"td-9999\" class=\"main-rowhead-td-first\" onclick=\"ReplaceContentInContainer('row-9999', '<td valign=top style=&quot;background-color: #fff9e6; border: 0; padding:0in 5.4pt 0in 5.4pt;&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;&quot;><strong><small>Full Name:</small></strong><br/><input type=&quot;text&quot; id=&quot;fullname-9999&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border: 0; padding:0in 5.4pt 0in 5.4pt;&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;&quot;><strong><small>Title:</small></strong><br/>" . $titles . "</p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; border-right: 0; border-top: 1; border-bottom: 1; padding:0in 5.4pt 0in 5.4pt;&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;&quot;><strong><small>Username:</small></strong><br/><input type=&quot;text&quot; id=&quot;username-9999&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; border-right: 0; border-top: 1; border-bottom: 1; padding:0in 5.4pt 0in 5.4pt;&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;&quot;><strong><small>Email Address:</small></strong><br/><input type=&quot;text&quot; id=&quot;email-9999&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; padding:0in 5.4pt 0in 5.4pt;&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;&quot;><strong><small>Supervisor:</small></strong><br/>" . $supervisors . "<span style=&quot;float:right;&quot;><a href=&quot;javascript:void(0);&quot; onclick=&quot;UpdateUser(9999);&quot;><img src=&quot;../images/check-mark.png&quot; title=&quot;Save Changes&quot;/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=&quot;javascript:void(0);&quot; onclick=&quot;location.reload();&quot;><img src=&quot;../images/error.png&quot; title=&quot;Cancel&quot;/></a>&nbsp;</span></p></td>');\" onMouseOver=\"this.style.backgroundColor='rgba(230,230,230,0.5)'\" onMouseOut=\"this.style.backgroundColor='rgba(255,255,255,0.5)'\" colspan=5 style=\"background-color:rgba(255,255,255,0.5);\">
			<p class=\"main-rowhead-td-p\">
				<span style=\"padding-top: 12px; padding-left: 10px; padding-right: 10px; border: 1px solid #777; border-radius: 3px; background-color: #fafafa;\" onMouseOver=\"this.style.backgroundColor='#e9e9e9';\" onMouseOut=\"this.style.backgroundColor='#fafafa';\">
               <img src=\"../images/plus.png\"/>
               <b>&nbsp;<sup>Add New User</sup></b>
            </span>
			</p>
		</td>
	</tr>";
 
if ($alert!="") { echo "<script type=\"text/javascript\">alert(\"$alert\");</script>"; }
$rs=GetEmployeeDetails($conn);
echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"titles.php\" enctype=\"multipart/form-data\">";
$rowcount = 0;
while($rows=sqlsrv_fetch_array($rs)){
	if ($rows['Active']) {
      $rowcount++;
		echo "<tr class='main-row' style='background-color:".($rowcount % 2 == 0 ? "#fdfdfd" : "#f8f4e9")."; ' onMouseOver='this.style.backgroundColor=\"#eee\";' onMouseOut='this.style.backgroundColor=\"".($rowcount % 2 == 0 ? "#fdfdfd" : "#f8f4e9")."\";' id=\"row-" . $rows['EmployeeID'] . "\">
				<td valign=top class='main-rowhead-td-first' style='width:18%;'>
					<p class='main-row-td-p'>
						<span>".$rows['EmployeeName']."</span>
					</p>
				</td>
            <td valign=top class='main-rowhead-td' style='width:22%;'>
					<p class='main-row-td-p'>
						<span>".$rows['GroupName']."</span>
					</p>
				</td>
            <td valign=top class='main-rowhead-td' style='width:12%;'>
					<p class='main-row-td-p'>
						<span>".$rows['Username']."</span>
					</p>
				</td>
            <td valign=top class='main-rowhead-td' style='width:22%;'>
					<p class='main-row-td-p'>
						<span>".$rows['EmailAddress']."</span>
					</p>
				</td>
			<td class='main-rowhead-td'>
				<p class='main-row-td-p'>
					<span>".$rows['Supervisor']."</span>";
		$titles = "<select onChange=&quot;addTitle(this.value, " . $rows['EmployeeID'] . ");&quot; id=&quot;title-" . $rows['EmployeeID'] . "&quot;>";
		if ($rows['GroupID'] == "") { $titles .= "<option value=&quot;&quot;>(Select a title)</option>"; }
		$titles .= "<option value=&quot;9999&quot;>Create new title...</option>";	
		$rsz = sqlsrv_query($conn, "SELECT * FROM TitleDepartment ORDER BY Name");	
		while($rowsz=sqlsrv_fetch_array($rsz)) {	
			$titles .= "<option value=&quot;" . $rowsz['ID'] . "&quot;" . ($rows['GroupID']==$rowsz['ID'] ? " selected" : "") . ">" . $rowsz['Number'] . " " . $rowsz['Name'] . "</option>";
		}
		$titles .= "</select>";
		$supervisors = "<select id=&quot;super-" . $rows['EmployeeID'] . "&quot;>";
		if ($rows['Supervisor'] == "") { $supervisors .= "<option value=&quot;&quot;>(Select a name)</option>"; }	
		$rsz = sqlsrv_query($conn, "SELECT * FROM Employees WHERE Active = 1 ORDER BY FullName");	
		while($rowsz=sqlsrv_fetch_array($rsz)) {	
			$supervisors .= "<option value=&quot;" . $rowsz['FullName'] . "&quot;" . ($rows['Supervisor']==$rowsz['FullName'] ? " selected" : "") . ">" . $rowsz['FullName'] . "</option>";
		}
		$supervisors .= "</select>";
		echo "&nbsp;&nbsp;<span style=\"float:right;\"><a href=\"javascript:void(0);\" onclick=\"ReplaceContentInContainer('row-" . $rows['EmployeeID'] . "', '<td valign=top style=&quot;background-color: #fff9e6; border: 0; padding:0in 5.4pt 0in 5.4pt&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in&quot;><strong><small>Full Name:</small></strong><br/><input type=&quot;text&quot; id=&quot;fullname-" . $rows['EmployeeID'] . "&quot; value=&quot;" . $rows['EmployeeName'] . "&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border: 0; padding:0in 5.4pt 0in 5.4pt&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in&quot;><strong><small>Title:</small></strong><br/>" . $titles . "</p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; border-right: 0; border-top: 1; border-bottom: 1; padding:0in 5.4pt 0in 5.4pt&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in&quot;><strong><small>Username:</small></strong><br/><input type=&quot;text&quot; id=&quot;username-" . $rows['EmployeeID'] . "&quot; value=&quot;" . $rows['Username'] . "&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; border-right: 0; border-top: 1; border-bottom: 1; padding:0in 5.4pt 0in 5.4pt&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in&quot;><strong><small>Email Address:</small></strong><br/><input type=&quot;text&quot; id=&quot;email-" . $rows['EmployeeID'] . "&quot; value=&quot;" . $rows['EmailAddress'] . "&quot;></p></td><td valign=top style=&quot;background-color: #fff9e6; border-left: 0; padding:0in 5.4pt 0in 5.4pt&quot;><p style=&quot;margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in&quot;><strong><small>Supervisor:</small></strong><br/>" . $supervisors . "<span style=&quot;float:right;&quot;><a href=&quot;javascript:void(0);&quot; onclick=&quot;UpdateUser(" . $rows['EmployeeID'] . ");&quot;><img src=&quot;../images/check-mark.png&quot; title=&quot;Save Changes&quot;/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=&quot;javascript:void(0)&quot; onclick=&quot;location.reload();&quot;><img src=&quot;../images/error.png&quot; title=&quot;Cancel&quot;/></a>&nbsp;</span></p></td>');\"><img src=\"../images/edit.png\" title=\"Edit User\"/></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(0);\" onclick=\"ConfirmDelete(" . $rows['EmployeeID'] . ");\"><img src=\"../images/rubbish-bin.png\" title=\"Delete User\"/></a>&nbsp;</span></p></td></tr>"; 
	} 
}
echo "</table><br/></div></form>";
?>

</body>
</html>
