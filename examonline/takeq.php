<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require_once 'sql.php';

$conn = mysqli_connect($host, $user, $ps, $project);
if (!$conn) {
    die("<script>alert('Database error, retry after some time!');</script>");
}

$type1 = $_SESSION["type"];
$username1 = $_SESSION["username"];

// Fetch student details
$sql = "SELECT * FROM $type1 WHERE mail=?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username1);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $dbmail = $row['mail'];
    $dbname = $row['name'];
    $dbusn = $row['usn'];
    $dbphno = $row['phno'];
    $dbgender = $row['gender'];
    $dbdob = $row['DOB'];
    $dbdept = $row['dept'];
} else {
    die("<script>alert('Student not found!'); window.location='homestud.php';</script>");
}

$qid = isset($_GET["qid"]) ? $_GET["qid"] : null;
$score = null;
$total = 0;
$questions = [];

// Fetch questions
if ($qid) {
    $sql = "SELECT * FROM questions WHERE quizid=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $qid);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res && mysqli_num_rows($res) > 0) {
        $questions = mysqli_fetch_all($res, MYSQLI_ASSOC);
        $total = count($questions);

        // If form submitted, calculate score
        if (isset($_POST["submit"])) {
            
            // Check if student already submitted this quiz
            $check_sql = "SELECT * FROM score WHERE mail=? AND quizid=?";
            $check_stmt = mysqli_prepare($conn, $check_sql);
            mysqli_stmt_bind_param($check_stmt, "ss", $dbmail, $qid);
            mysqli_stmt_execute($check_stmt);
            $check_res = mysqli_stmt_get_result($check_stmt);

            if ($check_res && mysqli_num_rows($check_res) > 0) {
                echo "<script>alert('You have already submitted this quiz!'); window.location.replace('homestud.php');</script>";
                exit;
            }

            // Calculate score
            $score = 0;
            $i = 1;
            foreach ($questions as $q) {
                if (isset($_POST["ans".$i]) && $_POST["ans".$i] == $q["answer"]) {
                    $score++;
                }
                $i++;
            }

            // Insert score
            $insert_sql = "INSERT INTO score (score, mail, quizid, totalscore) VALUES (?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "issi", $score, $dbmail, $qid, $total);
            mysqli_stmt_execute($insert_stmt);

            echo "<script>alert('You scored $score out of $total'); window.location.replace('homestud.php');</script>";
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Smartquest</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            font-family: 'Poppins', sans-serif;

        }

        .h-font {
            font-family: "Merienda", cursive;

        }

        .google-font {
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            font-family: 'Courier New', Courier, monospace;
            background-color:#B0DAD0F1;   
            color:#042A38;
            font-weight: bold;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            background: #fff;
            color: #042A38;
            padding: 1vw;
            position: fixed;
            width: 100%;
            top: 0;
            font-size: 1.2vw;
            font-weight: bold;
        }

        .navbar ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }

        .navbar li {
            margin: 0 1.5vw;
            cursor: pointer;
        }

        .navbar li:hover {
            text-decoration: underline;
        }

        .container {
            margin-top: 6vw;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .question {
            background: #fff;
            color: #042A38;
            padding: 1.5vw;
            border-radius: 10px;
            margin-bottom: 2vw;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .question p {
            font-size: 1.2vw;
        }

        .options {
            margin-left: 1.5vw;
        }

        .options label {
            display: block;
            margin-bottom: .8vw;
            cursor: pointer;
        }

        #btn {
            display: block;
            margin: 2vw auto;
            padding: 1vw 2vw;
            font-size: 1.2vw;
            font-family: 'Courier New', Courier, monospace;
            font-weight: bold;
            border-radius: 10px;
            border: 2px solid black;
            background-color: lightblue;
            cursor: pointer;
        }

        #btn:hover {
            background-color:#1d725ef1;
            color: white;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <section class="h-font"> QUIZMASTER-HUB</section>
        <ul>
            <li onclick="window.location='homestud.php'">Dashboard</li>
            <li onclick="window.location='index.php'">Sign Out</li>
        </ul>
    </div>

    <div class="container">
        <?php if ($qid && count($questions) > 0): ?>
        <form method="POST">
            <?php $i=1; foreach ($questions as $row): ?>
            <div class="question">
                <p>
                    <?php echo $i.". ".$row["qs"]; ?>
                </p>
                <div class="options">
                    <label><input type="radio" name="ans<?php echo $i; ?>" value="1">
                        <?php echo $row["op1"]; ?>
                    </label>
                    <label><input type="radio" name="ans<?php echo $i; ?>" value="2">
                        <?php echo $row["op2"]; ?>
                    </label>
                    <label><input type="radio" name="ans<?php echo $i; ?>" value="3">
                        <?php echo $row["op3"]; ?>
                    </label>
                    <label><input type="radio" name="ans<?php echo $i; ?>" value="4">
                        <?php echo $row["op4"]; ?>
                    </label>
                </div>
            </div>
            <?php $i++; endforeach; ?>
            <input id="btn" type="submit" name="submit" value="Submit Quiz">
        </form>
        <?php elseif ($qid): ?>
        <p style="color:white;text-align:center;">No questions found under this quiz. Please come later.</p>
        <?php else: ?>
        <p style="color:white;text-align:center;">Quiz ID missing in URL. Open with ?qid=QUIZ_ID</p>
        <?php endif; ?>
    </div>  
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@700&display=swap" rel="stylesheet">
 <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Merienda:wght@700&display=swap" rel="stylesheet">
</body>

</html>