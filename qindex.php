<?php
session_start();
include("training.php");
?>

<!doctype html>
<html>
   <head>
      <title>Employee Training</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <link rel="stylesheet" type="text/css" href="./css/sakura.css">
      <link rel="stylesheet" type="text/css" href="./css/traininga.css">      
   </head>
   <body>
      <span class="logo">
         <img src="./images/loader_logo.png" />
      </span>

      <br />
      <div class="shadow">
         <div id="title">
            <span>Employee Training System</span>
         </div>
      </div>
      
      <div align="center">
         
         
         
         
<?php
if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Username']))
{
$quser = $_SESSION['Username'];   
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName, AccessLevel FROM Employees WHERE Name = '" . $quser . "'");
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
   $accesslevel = $rows['AccessLevel'];
}   
   
     ?>
 
 <h5>Member Area</h5>    
 <p>Thanks for logging in! You are logged in as <strong><?php echo $_SESSION['Username']?></strong>.</p>
     
     <a href="logout.php" class="logout" style="color:white;"><div id="logout">LOGOUT</div></a>




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
      $last = sqlsrv_fetch_array(GetLatestTraining($conn, $quser, $rows['SOPNum']));
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
                     <a href='qreview.php?id=".$rows['SOPNum']."'>".$rows['Title']."</a><o:p></o:p>
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
      $last = sqlsrv_fetch_array(GetLatestTraining($conn, $quser, $rows['SOPNum']));
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
                     <a href='qreview.php?id=".$rows['SOPNum']."'>".$rows['Title']."</a><o:p></o:p>
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
                  <td class="table-head-data" colspan=4>
                     <p align=center>
                        <span style="font-size:14pt;">
                           Other Training
                        </span>
                     </p>
                  </td>
               </tr>
               <tr class="table-header-row">
                  <td class= "main-rowhead-td-first">
                     <p class="main-rowhead-td-p">
                        <strong>Trainer</strong>
                     </p>
                  </td>
                  <td id="sop-title-head" class="main-rowhead-td">
                     <p class="main-rowhead-td-p">
                        <strong>Category</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top>
                     <p class="main-rowhead-td-p">
                        <strong>Rev</strong>
                     </p>
                  </td>
                  <td class="main-rowhead-td" valign=top>
                     <p class="main-rowhead-td-p">
                        <strong>Date Completed</strong>
                     </p>
                  </td>
               </tr>
<?php
echo "<div style=\"float:left\"><a href=\"#\" onClick=\"javascript:window.open('qother.php','name','width=420,height=500,scrollbars=yes');\">Submit Other Training</a></div>";
/*
$rqs=GetRequiredSOPs($conn, $groupid);
$reqrows=array();
while($rows=sqlsrv_fetch_array($rqs)){
	$reqrows[]=$rows['SOPID'];
}
$rs=GetSOPIndex($conn);*/
$ro = GetOtherTraining($conn, $quser);
while($rows=sqlsrv_fetch_array($ro)){
   //if (in_array($rows['ID'], $reqrows)) {
      //$last = sqlsrv_fetch_array(GetLatestTraining($conn, $quser, $rows['SOPNum']));
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



<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:12.0pt;mso-bidi-font-size:10.0pt'><o:p>&nbsp;</o:p></span></p>



















     
     <?php
}
elseif(!empty($_POST['username']) && !empty($_POST['password']))
{
    $username = str_replace("'", "''", $_POST['username']);
    $password = str_replace("'", "''", $_POST['password']);
    $query = "SELECT * FROM Employees WHERE Name = '".$username."' AND Password = '".$password."'";
    
    echo "<script>console.log(\"$query\");</script>";
     
    $checklogin = sqlsrv_query($conn, $query);
     
    if(sqlsrv_has_rows($checklogin))
    {
        $row = sqlsrv_fetch_array($checklogin);
        $email = $row['EmailAddress'];
         
        $_SESSION['Username'] = $username;
        $_SESSION['EmailAddress'] = $email;
        $_SESSION['LoggedIn'] = 1;
         
        echo "<h1>Success</h1>";
        echo "<p>We are now redirecting you to the member area.</p>";
        header("location:qindex.php");
        echo "<meta http-equiv='refresh' content='=2;qindex.php' />";
    }
    else
    {
        echo "<h1>Error</h1>";
        echo "<p>Sorry, we were unable to log you in with the information provided. Please <a href=\"qindex.php\">click here to try again</a>.</p>";
    }
}
else
{
    ?>
     
   <h1>Employee Login</h1>
     
   <p>Thanks for visiting! Please log in below.</p>
     
    <form method="post" action="qindex.php" name="loginform" id="loginform">
    <fieldset>
        <label for="username">Username:</label><input type="text" name="username" id="username" /><br />
        <label for="password">Password:</label><input type="password" name="password" id="password" /><br />
        <input type="submit" class="apply-button" name="login" id="login" value="Login" />
    </fieldset>
    </form>
     
   <?php
}
?>
 

         
         
      </div>
         
   </body>
</html>