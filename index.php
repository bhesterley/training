<?php
include("training.php");
$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName, EmailAddress, AccessLevel FROM Employees WHERE Name = '" . $authuser . "'");
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
      <title>SOP Index</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <link rel="stylesheet" type="text/css" href="./css/sakura.css">
      <link rel="stylesheet" type="text/css" href="./css/traininga.css">
      <script>
        function OpenOther (id) {
          window.open('other-edit.php?id='+id,'name','width=600,height=720,scrollbars=yes');
        }
      </script>
   </head>

   <body>
      <span class="logo">
         <img src="./images/loader_logo.png" />
      </span>
      <span class="user">
         Logged in user: <strong><?php echo $fullname?></strong>
      </span>
      <br />
      <div class="shadow">
         <div id="title">
           <span>Employee Training System</span>
         </div>
      
<?php
   if ($accesslevel != 0) {
      echo "
         <div id=\"topnav\">
            <span class=\"navlink-active\">
               <a href=\"javascript:void(0);\">Home</a>
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class=\"navlink\">
               <a href=\"./documentcontrol\">Document Control</a>
            </span>
            
            ".($accesslevel<2?"":"
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class=\"navlink\">
               <a href=\"./manage/titles.php\">Employees</a>
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class=\"navlink\">
               <a href=\"./manage\">Assign Training</a>
            </span>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <span class=\"navlink\">
            <a href=\"./manage/group.php\">Group Training</a>
         </span>
            
            
         </div>");
   }      
?>  
         
      </div>
      <div align="center">
        <br />
         <a class="logout" id="logout" href="http://techforms:8089/ReportServer/Pages/ReportViewer.aspx?%2FTraining%20System%2FEmployee%20Training%20Log%20Part%20170212%20R7&Name=<?php echo str_replace(" ", "%20", $fullname); ?>&rs%3AParameterLanguage=en-US" target="_blank">Training Record</a>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <a <?php echo ($accesslevel<2?"style=\"display:none;\"":""); ?>class="logout red" id="logout" href="http://techforms:8089/ReportServer?%2FTraining%20System%2FTraining%20Exception%20List%20-%20Weekly&Supervisoremail=<?php echo str_replace("@", "%40", $email); ?>&rs%3AParameterLanguage=en-US" target="_blank">Exception List</a>
         <br /><br />
            <table class="main shadow">
               <tr class="table-head">
                  <td class="table-head-data" colspan=5>
                     <p align=center>
                        <span style="font-size:14pt;">
                           SOP Training Assignments
                        </span>
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
                     <p class="main-rowhead-td-p">
                        <strong>Title</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top>
                     <p class="main-rowhead-td-p">
                        <strong>Current Rev</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top>
                     <p class="main-rowhead-td-p">
                        <strong>Last Reviewed Rev</strong>
                     </p>
                  </td>                  
                  <td class="main-rowhead-td" valign=top>
                     <p class="main-rowhead-td-p">
                        <strong>Last Reviewed Date</strong>
                     </p>
                  </td>
               </tr>
<?php
$rq=GetQMIndex($conn);
while($rows=sqlsrv_fetch_array($rq)){
   //if (in_array($rows['ID'], $reqrows)) {
      $last = sqlsrv_fetch_array(GetLatestTraining($conn, $authuser, $rows['SOPNum']));
      echo "<tr class=\"employee-row\" ".($rows['Rev'] > $last[0] || $last[0]===NULL ? "style=\"background-color:#faa;\"" : "").">
               <td valign=top style='border:solid windowtext 1.0pt;
                  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
                  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['SOPNum']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        <a href='review.php?id=".$rows['SOPNum']."'>".$rows['Title']."</a><o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;
                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".($last[0]===NULL ? "N/A" : $last[0])."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".($last[1]===NULL ? "N/A" : $last[1])."<o:p></o:p>
                     </span>
                  </p>
               </td>
            </tr>";
 
   //}
}




$rqs=GetRequiredSOPs($conn, $groupid);
$reqrows=array();
while($rows=sqlsrv_fetch_array($rqs)){
	$reqrows[]=$rows['SOPID'];
}
$rs=GetSOPIndex($conn);
while($rows=sqlsrv_fetch_array($rs)){
   if (in_array($rows['ID'], $reqrows)) {
      $last = sqlsrv_fetch_array(GetLatestTraining($conn, $authuser, $rows['SOPNum']));
      //if(empty($last[0])){echo"alert('Empty!');";}
      echo "<tr class=\"employee-row\" ".($rows['Rev'] > $last[0] || $last[0]===NULL ? "style=\"background-color:#faa;\"" : "").">
               <td valign=top style='border:solid windowtext 1.0pt;
                  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
                  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['SOPNum']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                     <a href='review.php?id=".$rows['SOPNum']."'>".$rows['Title']."</a><o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;
                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".($last[0]===NULL ? "N/A" : $last[0])."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".($last[1]===NULL ? "N/A" : $last[1])."<o:p></o:p>
                     </span>
                  </p>
               </td>
            </tr>";
 
   }
}
?>
            </table>
            <br /><br />
            <table class="main shadow">
               <tr class="table-head">
                  <td class="table-head-data" colspan=5>
                     <p align=center>
                        <span style="font-size:14pt;">
                           Group Training
                        </span>
                     </p>
                  </td>
               </tr>
               <tr class="table-header-row">
                  <td class= "main-rowhead-td-first" style="width:30%;">
                     <p class="main-rowhead-td-p">
                        <strong>Trainer</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td" style="width:30%;">
                     <p class="main-rowhead-td-p">
                        <strong>Category</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td" style="width:10%;">
                     <p class="main-rowhead-td-p">
                        <strong>Attachment</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top style="width:10%;">
                     <p class="main-rowhead-td-p">
                        <strong>Rev</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top style="width:20%;">
                     <p class="main-rowhead-td-p">
                        <strong>Date Completed</strong>
                     </p>
                  </td>
               </tr>
<?php
$rg = GetGroupTraining($conn, $authuser);
while($rows=sqlsrv_fetch_array($rg)){
   //if (in_array($rows['ID'], $reqrows)) {
      //$last = sqlsrv_fetch_array(GetLatestTraining($conn, $authuser, $rows['SOPNum']));
      echo "<tr class=\"employee-row\">
               <td valign=top style='border:solid windowtext 1.0pt;
                  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
                  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['Trainer']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['Category']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p align=center style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                    <span style='font-size:12.0pt'>".($rows['Attachment']==""?"":"<a target='_blank' href='./Attachments/".$rows['Attachment']."'><img src='./images/paperclip.png' /></a>")."</span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;
                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['DateCompleted']."<o:p></o:p>
                     </span>
                  </p>
               </td>
            </tr>";
 
   //}
}
?>
            </table>
            <br /><br />
            <table class="main shadow">
               <tr class="table-head">
                  <td class="table-head-data" colspan=6>
                     <p align=center>
                        <span style="font-size:14pt;">
                           Other Training
                        </span>
                     </p>
                  </td>
               </tr>
               <tr class="table-header-row">
                  <td class= "main-rowhead-td-first" style="width:30%;">
                     <p class="main-rowhead-td-p">
                        <strong>Trainer</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td" style="width:30%;">
                     <p class="main-rowhead-td-p">
                        <strong>Category</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td" style="width:10%;">
                     <p class="main-rowhead-td-p">
                        <strong>Attachment</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top style="width:10%;">
                     <p class="main-rowhead-td-p">
                        <strong>Rev</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top style="width:15%;">
                     <p class="main-rowhead-td-p">
                        <strong>Date Completed</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top style="width:20%;">
                     <p class="main-rowhead-td-p">
                        <strong>Edit</strong>
                     </p>
                  </td>                  
               </tr>
<?php
echo "<div style=\"float:left\"><a href=\"javascript:void(0);\" onClick=\"javascript:window.open('other.php','name','width=600,height=700,scrollbars=yes');\">Submit Other Training</a></div>";
$ro = GetOtherTraining($conn, $authuser);
while($rows=sqlsrv_fetch_array($ro)){
   //if (in_array($rows['ID'], $reqrows)) {
      //$last = sqlsrv_fetch_array(GetLatestTraining($conn, $authuser, $rows['SOPNum']));
      echo "<tr class=\"employee-row\">
               <td valign=top style='border:solid windowtext 1.0pt;
                  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
                  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['Trainer']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
                     2.0pt;margin-left:0in'>
                     <span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>
                        ".$rows['Category']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;padding:0in 5.4pt 0in 5.4pt'>
               <p align=center style='margin-top:2.0pt;margin-right:0in;margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                 <span style='font-size:12.0pt'>".($rows['Attachment']==""?"":"<a target='_blank' href='./Attachments/".$rows['Attachment']."'><img src='./images/paperclip.png' /></a>")."</span>
               </p>
               </td>
               <td valign=top style='border-top:none;border-left:none;
                  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
                  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
                  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
                  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
                  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                     margin-bottom:2.0pt;margin-left:0in;text-align:center'>
                     <span
                        style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['DateCompleted']."<o:p></o:p>
                     </span>
                  </p>
               </td>
               <td valign=top style='border-top:none;border-left:
               none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
               mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
               mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
               <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
                  margin-bottom:2.0pt;margin-left:0in;text-align:center'>
               <a href='javascript:void(0);' onClick='OpenOther(".$rows['ID'].");'>
                  <img src='./images/contract.png' />
               </a>
               </p>
            </td>               
            </tr>";
 
   //}
}
?>
            </table>

</table>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:10.0pt'><o:p>&nbsp;</o:p></span></p>

</div>

</body>
</html>
