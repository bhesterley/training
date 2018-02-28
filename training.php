<?php

$dbhost = "techforms";
$connectionInfo = array( "Database"=>"Training", "UID"=>"training", "PWD"=>"Sec4242");
$conn = sqlsrv_connect($dbhost, $connectionInfo) or die( print_r( sqlsrv_errors(), true));

$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));

function GetSOPIndex($conn) {
	return sqlsrv_query($conn, "SELECT ID, SOPNum, Title, Rev, CONVERT(VARCHAR, EffectiveDate, 107) AS EffectiveDate, Filename, Active FROM SOPs WHERE Active = 1 AND TypeID = 2 ORDER BY FLOOR(CAST(SOPNum AS FLOAT)), CAST(RIGHT(SOPNum,LEN(SOPNum)-CHARINDEX('.',SOPNum)) AS INT)");
}

function GetQMIndex($conn) {
   return sqlsrv_query($conn, "SELECT ID, SOPNum, Title, Rev, CONVERT(VARCHAR, EffectiveDate, 107) AS EffectiveDate, Filename, Active FROM SOPs WHERE Active = 1 AND TypeID = 1");
}

function GetSOPDetail($conn, $id) {
	return sqlsrv_query($conn, "SELECT ID, SOPNum, Title, Rev, CONVERT(VARCHAR, EffectiveDate, 101) AS EffectiveDate, Filename FROM SOPs WHERE ID ='$id'");
}

function GetSOPDetailBySOPNum($conn, $id) {
	return sqlsrv_query($conn, "SELECT ID, SOPNum, Title, Rev, CONVERT(VARCHAR, EffectiveDate, 101) AS EffectiveDate, Filename FROM SOPs WHERE SOPNum ='$id'");
}

function GetRequiredSOPs($conn, $id) {
	return sqlsrv_query($conn, "SELECT SOPID FROM RequiredSOPs WHERE GroupID = '$id'");
}

function GetSOPGroups($conn, $id) {
	return sqlsrv_query($conn, "SELECT GroupID FROM RequiredSOPs WHERE SOPID = '$id'");
}

function GetLatestTraining($conn, $authuser, $sopnum) {
	return sqlsrv_query($conn, "SELECT MAX(Rev), CONVERT(VARCHAR, MAX(Timestamp), 101) FROM Records WHERE Username = '$authuser' AND SOPNum = '$sopnum'");
}

function GetGroupTraining($conn, $authuser) {
	return sqlsrv_query($conn, "SELECT Category, Attachment, Trainer, Rev, CONVERT(VARCHAR, DateCompleted, 101) AS DateCompleted FROM Records WHERE Username = '$authuser' AND Type = 'Group' ORDER BY Records.DateCompleted DESC");
}

function GetOtherTraining($conn, $authuser) {
	return sqlsrv_query($conn, "SELECT ID, Category, Attachment, Trainer, Rev, CONVERT(VARCHAR, DateCompleted, 101) AS DateCompleted FROM Records WHERE Username = '$authuser' AND Type = 'Other' ORDER BY Records.DateCompleted DESC");
}

function GetEmployeeDetails($conn) {
	return sqlsrv_query($conn, "SELECT ID AS EmployeeID, Name AS Username, FullName AS EmployeeName, EmailAddress, Supervisor, Active, GroupID, GroupName, DepartmentID, DepartmentNumber, DepartmentName FROM EmployeeTitleDepartment WHERE Active = 1 ORDER BY EmployeeName");
}

function GetEmployeeDetailsById($conn, $id) {
	return sqlsrv_query($conn, "SELECT ID AS EmployeeID, Name AS Username, FullName AS EmployeeName, EmailAddress, Supervisor, Active, GroupID, GroupName, DepartmentID, DepartmentNumber, DepartmentName FROM EmployeeTitleDepartment WHERE Active = 1 AND ID = '$id'");
}

function GetApprovers($conn) {
	return sqlsrv_query($conn, "SELECT * FROM Employees WHERE Approver = 1 ORDER BY Fullname");
}

function GetDepartments($conn) {
	return sqlsrv_query($conn, "SELECT * FROM Departments ORDER BY Name");
}

function UploadSOP($file_name) {
	$file_ext=strtolower(substr(strrchr($file_name,'.'),1));
	if($file_ext=="pdf"){
		$upload_name=time().'.'.$file_ext;
		$target_path = "../Released_SOPs/" . $upload_name;
		// echo $_FILES['Filename']['name'] . "<br/>" . $tmp_name . "<br/>" . $target_path . "<br/>";
		move_uploaded_file($_FILES['Filename']['tmp_name'], $target_path);
		return $upload_name;
	} else {
		return "";
	}
}

function UploadAttachment($file_name) {
	$target_path = "Attachments/" . $file_name;
	if (move_uploaded_file($_FILES['Filename']['tmp_name'], $target_path)) {
		return $file_name;
	} else {
		return "";
	}
}

function UploadGroupTraining($file_name) {
	$file_ext=strtolower(substr(strrchr($file_name,'.'),1));
	$newname = time() . '.' . $file_ext;
	$target_path = "../Attachments/" . $newname;
	if (move_uploaded_file($_FILES['Filename']['tmp_name'], $target_path)) {
		return $newname;
	} else {
		return "";
	}
}

function UploadOtherTraining($file_name) {
	$file_ext=strtolower(substr(strrchr($file_name,'.'),1));
	$newname = time() . '.' . $file_ext;
	$target_path = "./Attachments/" . $newname;
	if (move_uploaded_file($_FILES['Filename']['tmp_name'], $target_path)) {
		return $newname;
	} else {
		return "";
	}
}
?>