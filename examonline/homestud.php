<html>

<head>
    <title>
        Online Examination System
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
session_start();
require_once 'sql.php';

$conn = mysqli_connect($host, $user, $ps, $project);
if (!$conn) {
    echo "<script>alert(\"Database error retry after some time !\")</script>";
}
?>
<style>
    * {
        font-family: 'Poppins', sans-serif;
        text: black;
    }

    .h-font {
        font-family: "Merienda", cursive;
    }

    .google-font {
        font-family: Arial, Helvetica, sans-serif;
    }

    li {
        margin: 1.5vw;
        font-size: 1.5vw !important;
    }

    ul {
        list-style: none;
        width: auto !important;
        font-weight: 2vw !important;
    }

    .navbar {
        background-color: white !important;
        font-size: 1.5vw !important;
    }

    .navbar>ul>li:hover {
        color: #042A38;
        text-decoration: underline;
        font-weight: bold;
        cursor: default;
    }

    .navbar>ul>li>a:hover {
        color: #042A38;
        text-decoration: underline;
        font-weight: bold !important;
    }

    a {
        text-decoration: none;
        color: #fff;
    }

    .prof,
    #score {
        top: 3vw;
        position: fixed;
        width: 50vw !important;
        margin-left: 25vw !important;
        margin-right: 25vw !important;
        background-color: #E9F4F2 !important;
        display: none !important;
        border-radius: 10px;
        margin-top: 2vw;
        z-index: 1;
        padding: 1vw;
        padding-left: 2vw;
        color: #16404fff;
    }

    @media screen and (max-width: 450px) {
        .navbar {
            display: initial !important;
        }

        .navbar>ul {
            display: initial !important;
            left: 25vw !important;
            text-align: center;
            right: 25vw !important;
        }

        .navbar>ul>li {
            background-color: orange !important;
        }

        section {
            text-align: center;
            margin-top: 0 !important;
            background-color: orange !important;
            width: 100vw;
            margin: 0 !important;
        }

        p {
            color: #042A38 !important;
        }
    }

    table {
        width: 90vw;
        margin-left: 5vw;
        margin-right: 5vw;
        align-content: center;
        border: 1px solid black;
    }

    thead {
        font-weight: 900;
        font-size: 1.5vw;
    }

    td {
        width: auto;
        border: 1px solid black;
        text-align: center;
        height: 4vw;
        font-weight: bold;
    }

    #tq {
        text-decoration: underline;
        border: 3px solid #fff;
        padding: 0.5vw;
        border-radius: 10px;
    }

    #sc {
        width: 100% !important;
        margin: 0%;
        color: #042A38;
    }

    #le {
        margin-bottom: 2vw;
    }
</style>

<body
    style=" background-color:white; color: black !important;font-weight:bolder;margin: 0 !important;font-weight: bolder !important;font-family: 'Courier New', Courier, monospace;">
    <div style="background-color: #B0DAD0F1;height:auto;">
        <div class="navbar" style="display: inline-flex;width: 100%;color:#042A38;position:fixed;">
            <section class="h-font" style="margin: 1.5vw;" href="homestud.php"> QUIZMASTER-HUB</section>
            <ul
                style="display: inline-flex;padding: 0 !important;margin: 0;float: right;right: 0;position: fixed;width: 50vw;">
                <li class="google-font" onclick="dash()">Home</li>
                <li class="google-font" onclick="prof()">Profile</li>
                <li class="google-font" onclick="score()">Score</li>
                <li class="google-font" onclick="lo()">Sign Out</li>
            </ul>
        </div><br><br>
        <?php
        $type1 = $_SESSION["type"];
        $username1 = $_SESSION["username"];

        $sql = "select * from " . $type1 . " where mail='{$username1}'";
        $res = mysqli_query($conn, $sql);
        if ($res == true) {
            $row = mysqli_fetch_assoc($res);
            $dbmail = $row['mail'];
            $dbname = $row['name'];
            $dbphno = $row['phno'];
            $dbgender = $row['gender'];
            $dbdob = $row['DOB'];
            $dbdept = $row['dept'];

            // ✅ Fix: handle usn / staffid properly
            if (isset($row['usn'])) {
                $dbid = $row['usn'];
            } elseif (isset($row['staffid'])) {
                $dbid = $row['staffid'];
            } else {
                $dbid = "N/A";
            }
        }
        ?>
        <center>
            <section style="width:100vw;margin:0vw;margin-top:5vw;font-size:3vw;">Welcome to QuizMaster-Hub The Smartest
                Quiz Zone&nbsp;
                <?php echo $dbname ?>!
            </section>
        </center>
        <section style="color:#fff !important"><br><br><br>
            <?php
            $sql = "select * from quiz";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo "<center><h1 class=\"google-font\" style=\"font-size:2vw; color:black\">Take any Quiz</h1></center>";
                echo "<center><table><thead><tr><td>Quiz Title</td><td>Created on</td><td>Created By</td><td>  </td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr><td>" . $row["quizname"] . "</td><td>" . $row["date_created"] . "</td><td>" . $row["mail"] . "</td><td><a id=\"tq\" style=\"color:black;  border: 1px solid black;\" href='takeq.php?qid=" . $row['quizid'] . "'>Take Quiz</button></tr>";
                }
                echo "</table></center>";
            }
            ?>
        </section>
        <section class="prof" id="prof" style="display: none;color:#042A38;">
            <p><b>Type of User&nbsp;:&nbsp;
                    <?php echo $type1 ?>
                </b></p>
            <p><b>NAME&nbsp;:&nbsp;
                    <?php echo $dbname ?>
                </b></p>
            <p><b>EMAIL&nbsp;:&nbsp;
                    <?php echo $dbmail ?>
                </b></p>
            <p><b>Ph No.&nbsp;:&nbsp;
                    <?php echo $dbphno ?>
                </b></p>
            <p><b>ID&nbsp;:&nbsp;
                    <?php echo $dbid ?>
                </b></p>
            <p><b>GENDER&nbsp;:&nbsp;
                    <?php echo $dbgender ?>
                </b></p>
            <p><b>DOB&nbsp;:&nbsp;
                    <?php echo $dbdob ?>
                </b></p>
            <p><b>Dept.&nbsp;:&nbsp;
                    <?php echo $dbdept ?>
                </b></p>
        </section>
        <section id="score" style="display:none;">
            <?php
            $sql = "select * from score,quiz where score.mail='{$username1}' and score.quizid=quiz.quizid";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo "<h1>Scoreboard</h1>";
                echo "<table id=\"sc\"><thead><tr><td>Quiz Title</td><td>Score Obtained</td><td>Total Score</td><td>Remarks</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr><td>" . $row["quizname"] . "</td><td>" . $row["score"] . "</td><td>" . $row["totalscore"] . "</td><td>" . $row["remark"] . "</tr>";
                }
                echo "</table>";
            } else {
                echo " " . mysqli_error($conn);
            }
            ?><br><br><br>
        </section>
        <section style="color:#fff !important">
            <?php
            $sql = "call leaderboard;";
            $res = mysqli_query($conn, $sql);
            if ($res) {
                echo "<center><h1 class=\"google-font\" style=\"font-size: 2vw; margin-top:8vh; color:black;\">Leaderboard</h1></center>";
                echo "<table id=\"le\"><thead><tr><td>Quiz Title</td><td>Score</td><td>Total Score</td><td>Student name</td><td>Student Mail ID</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr><td>" . $row["quizname"] . "</td><td>" . $row["score"] . "</td><td>" . $row["totalscore"] . "</td><td>" . $row["name"] . "</td><td>" . $row["mail"] . "</td></tr>";
                }
                echo "</table><br><br><br>";
            } else {
                echo mysqli_error($conn);
            }
            ?>
        </section>
    </div>

    <?php require("footer.php"); ?>

</body>
<?php
echo '<script>' .
    "function prof(){" .
    "document.getElementById(\"prof\").style=\"display: block !important;\";" .
    "document.getElementById(\"score\").style=\"display: none !important;\";" .
    "}" .
    "function score(){" .
    "document.getElementById(\"prof\").style=\"display: none !important;\";" .
    "document.getElementById(\"score\").style=\"display: block !important;\";" .
    "}" .
    "function dash(){" .
    "document.getElementById(\"prof\").style=\"display: none !important;\";" .
    "document.getElementById(\"score\").style=\"display: none !important;\";" .
    "}" .
    "function lo(){" .
    "alert(\"Thank You for Using our Smartquest\");" .
    "window.location.replace(\"index.php\");" .
    "}</script>";
?>

</html>