<?php
session_start();
include("training.php");
$authuser = $_SESSION['Username'];
$rs = sqlsrv_query($conn, "SELECT GroupID, FullName, AccessLevel FROM Employees WHERE Name = '" . $authuser . "'");
while($rows=sqlsrv_fetch_array($rs)) {
   $groupid = $rows['GroupID'];
   $fullname = $rows['FullName'];
   $accesslevel = $rows['AccessLevel'];
}
$sopnum = $_GET['id'];
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <script type="text/javascript" src="scripts/quizlib.1.0.0.min.js"></script>
        <script type="text/javascript" src="scripts/main.js"></script>
        <script>
            window.onload = function() {
                quiz = new Quiz('quiz', [
                    <?php
                        $rsq = sqlsrv_query($conn, "SELECT * FROM Questions WHERE SOPNumber = '" . $sopnum . "'");
                        while($rowsq=sqlsrv_fetch_array($rsq)) {
                             if ($rowsq['Answer']!="") {
                                 echo "'".strtolower($rowsq['Answer'])."',";
                             }
                        }
                    ?>
                ]);
            };
        </script>
        <link rel="stylesheet" type="text/css" href="styles/quizlib.min.css" media="screen">
        <link rel="stylesheet" type="text/css" href="styles/stylesheet.css" media="screen">
        
        <title>Review Questions</title>
    </head>

    <body>
        <div class="card" style="text-align:center; color:#444; background-color:#e5f3f5;">
<?php
    $rsd = sqlsrv_query($conn, "SELECT * FROM SOPs WHERE SOPNum = '" . $sopnum . "'");
    while($rowsd=sqlsrv_fetch_array($rsd)) {
        $title = $rowsd['Title'];
        $rev = $rowsd['Rev'];
        $sopid = $rowsd['ID'];
        $filename = $rowsd['Filename'];
    }
    echo "<script>window.open('Released_SOPs/".$filename."');</script>";
?>
            <h3>SOP <?php echo $sopnum?> r<?php echo $rev?><br /><?php echo $title?></h3>
            <hr />
            <h4>Review Questions</h4>
        </div>

        <!-- Quiz Results -->

        
        <!-- Quiz Container -->
        <div id="quiz">
            <?php
                $rsz = sqlsrv_query($conn, "SELECT * FROM Questions WHERE SOPNumber = '" . $sopnum . "'");
                $i = 0;
                while($rowsz=sqlsrv_fetch_array($rsz)) {
                    $i++;
                    echo "<div class=\"card quizlib-question\">
                          <div class=\"quizlib-question-title\">".$i.". ".$rowsz['Question']."</div>
                          <div class=\"quizlib-question-answers\">
                          <ul>";
                            if($rowsz['A']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"a\">".$rowsz['A']."</label></li>";
                            }
                            if($rowsz['B']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"b\">".$rowsz['B']."</label></li>";
                            }
                            if($rowsz['C']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"c\">".$rowsz['C']."</label></li>";
                            }
                            if($rowsz['D']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"d\">".$rowsz['D']."</label></li>";
                            }
                            if($rowsz['E']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"e\">".$rowsz['E']."</label></li>";
                            }
                            if($rowsz['F']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"f\">".$rowsz['F']."</label></li>";
                            }
                            if($rowsz['G']!=''){
                                echo "<li><label><input type=\"radio\" name=\"q".$i."\" value=\"g\">".$rowsz['G']."</label></li>";
                            }
                            echo "</ul>
                            </div>
                        </div>";
                        }    
            ?>


            <!-- Answer Button -->
            <button id="check" type="button" onclick="showResults();">Check Answers</button>
            <span id="continue" style="float:right; font-weight:bold; opacity:0; transition:1s;">
                Initial to certify that you have read and understood this material:
                <form action="qrecord.php" method="POST" style="display:inline;">
                    <input type="text" id="initials" name="initials" size="3" maxlength="5" style="margin-right:20px; text-transform:uppercase;" oninput="if(this.value.length>1){document.getElementById('continueBtn').disabled=false;document.getElementById('continueBtn').style.opacity=1;}else{document.getElementById('continueBtn').disabled=true;document.getElementById('continueBtn').style.opacity=0.4;}">
                    <button disabled style="opacity:0.4;" id="continueBtn" type="button" onclick="document.forms[0].submit();">Continue</button>
                    <input type="hidden" name="sopnum" value="<?php echo $sopnum?>">
                    <input type="hidden" name="title" value="<?php echo $title?>">
                    <input type="hidden" name="sopnum" value="<?php echo $sopnum?>">
                    <input type="hidden" name="rev" value="<?php echo $rev?>">
                    <input type="hidden" name="sopid" value="<?php echo $sopid?>">
                    <input type="hidden" name="authuser" value="<?php echo $authuser?>">
                    <input type="submit" style="display:none;">
                </form>
            </span>
            <br /><br/>
            <div id="quiz-result" class="card">
                <h3>You Scored <span id="quiz-percent"></span>% - <span id="quiz-score"></span>/<span id="quiz-max-score"></span></h3>
            </div>
            <br />
            <br />
            <div style="text-align:center; font-size:11pt;">Logged in user: <span style="font-weight:bold;"><?php echo $fullname?></span></div>
            <br />
            <br />
            <br />
        </div>
    </body>
</html>
