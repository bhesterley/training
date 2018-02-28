<?php
include("../training.php");
$authuser = strtolower(substr(strstr($_SERVER['AUTH_USER'], '\\'), 1));
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName, AccessLevel FROM Employees WHERE Name = '" . $authuser . "'");
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
   $accesslevel = $rows['AccessLevel'];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
   <head>
      <title>Document Control</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link rel="stylesheet" type="text/css" href="../css/sakura.css" />
      <link rel="stylesheet" type="text/css" href="../css/traininga.css" />
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
   
<?php
if ($accesslevel != 0) {
   echo "
      <div id=\"topnav\">
         <span class=\"navlink\">
            <a href=\"..\">Home</a>
         </span>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span class=\"navlink-active\">
            <a href=\"javascript:void(0);\">Document Control</a>
         </span>
         
         ".($accesslevel<2?"":"
         
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span class=\"navlink\">
            <a href=\"../manage/titles.php\">Employees</a>
         </span>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span class=\"navlink\">
            <a href=\"../manage\">Assign Training</a>
         </span>
         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <span class=\"navlink\">
            <a href=\"../manage/group.php\">Group Training</a>
         </span>
         
         
         
      </div>");
}      
?>  
      
   </div>
   <div align="center">

   <br />

   <a href="#" onClick="javascript:window.open('upload.php','name','width=470,height=500');"  style="color:white; border:none;"><div class="logout" id="logout">Upload New SOP</div></a>


      <br /><br />
      <table class="main shadow">
         <tr class="table-head">
            <td class="table-head-data" colspan=4>
               <p align=center>
                  <span style="font-size:14pt;">
                     SOP Management
                  </span>
               </p>
            </td>
         </tr>
         <tr class="table-header-row">
                  <td class= "main-rowhead-td-first" style="width:10%;">
                     <p class="main-rowhead-td-p">
                        <strong>SOP No.</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td">
                     <p class="main-rowhead-td-p">
                        <strong>Title</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td">
                     <p class="main-rowhead-td-p">
                        <strong>Revision</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td">
                     <p class="main-rowhead-td-p">
                        <strong>Effective Date</strong>
                     </p>
                  </td>                  
         </tr>


<?php

$rq=GetQMIndex($conn);

if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

while($rows=sqlsrv_fetch_array($rq)){
 echo "<tr class='employee-row' style='mso-yfti-irow:2'>
  <td valign=top style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
  2.0pt;margin-left:0in'><span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['SOPNum']."<o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
  2.0pt;margin-left:0in'><span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>";

echo "<font size=\"2\">(<a href='#' onClick=\"javascript:window.open('update.php?id=".$rows['ID']."','name','width=500,height=600'); return false;\">edit</a>)</font> <a href='#' onClick=\"javascript:window.open('../Released_SOPs/".$rows['Filename']."'); return false;\">".$rows['Title']."</a><o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
  margin-bottom:2.0pt;margin-left:0in;text-align:center'><span
  style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
  margin-bottom:2.0pt;margin-left:0in;text-align:center'><span
  style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['EffectiveDate']."<o:p></o:p></span></p>
  </td>
 </tr>";
}



$rs=GetSOPIndex($conn);

if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

while($rows=sqlsrv_fetch_array($rs)){
 echo "<tr class='employee-row' style='mso-yfti-irow:2'>
  <td valign=top style='border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .75pt;mso-border-alt:
  solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
  2.0pt;margin-left:0in'><span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['SOPNum']."<o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal style='margin-top:2.0pt;margin-right:0in;margin-bottom:
  2.0pt;margin-left:0in'><span style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>";

echo "<font size=\"2\">(<a href='#' onClick=\"javascript:window.open('update.php?id=".$rows['ID']."','name','width=500,height=600'); return false;\">edit</a>)</font> <a href='#' onClick=\"javascript:window.open('../Released_SOPs/".$rows['Filename']."'); return false;\">".$rows['Title']."</a><o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
  margin-bottom:2.0pt;margin-left:0in;text-align:center'><span
  style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['Rev']."<o:p></o:p></span></p>
  </td>
  <td valign=top style='border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .75pt;mso-border-left-alt:solid windowtext .75pt;
  mso-border-alt:solid windowtext .75pt;padding:0in 5.4pt 0in 5.4pt'>
  <p class=MsoNormal align=center style='margin-top:2.0pt;margin-right:0in;
  margin-bottom:2.0pt;margin-left:0in;text-align:center'><span
  style='font-size:12.0pt;mso-bidi-font-size:10.0pt'>".$rows['EffectiveDate']."<o:p></o:p></span></p>
  </td>
 </tr>";
}
?>






</table>



<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:10.0pt'><o:p>&nbsp;</o:p></span></p>

</body>
</html>
