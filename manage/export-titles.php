<?php
include("../training.php");

$filename = "EmployeeTitles.xls";
$contents = "Name \t Title \t \n";
$rs=GetEmployeeDetails($conn);

while($rows=sqlsrv_fetch_array($rs)){
	$contents .= $rows['EmployeeName'] . " \t " . $rows['GroupName'] . " \t \n";
}

header('Content-type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename='.$filename);
echo $contents;
?>