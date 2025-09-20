<html>

<head>
    <title> QUIZMASTER-HUB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
session_start();
require_once 'sql.php';

// DB connection
$conn = mysqli_connect($host, $user, $ps, $project);
if (!$conn) {
    die("<script>alert('Database error, retry after some time!');</script>");
}

// Validate session
if (!isset($_SESSION["type"]) || !isset($_SESSION["username"])) {
    die("Unauthorized access. Please login first.");
}

$type1 = $_SESSION["type"];
$username1 = $_SESSION["username"];

// Fetch user details
$sql = "SELECT * FROM " . $type1 . " WHERE mail='" . mysqli_real_escape_string($conn, $username1) . "'";
$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $dbmail   = $row['mail'];
    $dbname   = $row['name'];
    $dbusn    = $row['staffid'];
    $dbphno   = $row['phno'];
    $dbgender = $row['gender'];
    $dbdob    = $row['DOB'];
    $dbdept   = $row['dept'];
}

// ✅ Get quiz ID from URL
$qid = isset($_GET["qid"]) ? intval($_GET["qid"]) : 0;
if ($qid === 0) {
    die("Quiz ID missing. Open this page with ?qid=QUIZ_ID");
}

// Insert Question function
function insertQuestion($conn, $qid, $qs, $op1, $op2, $op3, $op4, $ans) {
    $qs  = mysqli_real_escape_string($conn, $qs);
    $op1 = mysqli_real_escape_string($conn, $op1);
    $op2 = mysqli_real_escape_string($conn, $op2);
    $op3 = mysqli_real_escape_string($conn, $op3);
    $op4 = mysqli_real_escape_string($conn, $op4);
    $ans = mysqli_real_escape_string($conn, $ans);

    $sql = "INSERT INTO questions (qs, op1, op2, op3, op4, answer, quizid) 
            VALUES ('$qs', '$op1', '$op2', '$op3', '$op4', '$ans', '$qid')";
    return mysqli_query($conn, $sql);
}

// ---------------- Insert Question (stay on page) ----------------
if (isset($_POST['submit'])) {
    $qs  = $_POST["qs"];
    $op1 = $_POST["op1"];
    $op2 = $_POST["op2"];
    $op3 = $_POST["op3"];
    $op4 = $_POST["op4"];
    $ans = $_POST["ans"]; // should be 1,2,3,4

    if (insertQuestion($conn, $qid, $qs, $op1, $op2, $op3, $op4, $ans)) {
        echo '<script>alert("Question added successfully!");</script>';
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
    }
}

// ---------------- Insert Question (then redirect) ----------------
if (isset($_POST['submit1'])) {
    $qs  = $_POST["qs"];
    $op1 = $_POST["op1"];
    $op2 = $_POST["op2"];
    $op3 = $_POST["op3"];
    $op4 = $_POST["op4"];
    $ans = $_POST["ans"];

    if (insertQuestion($conn, $qid, $qs, $op1, $op2, $op3, $op4, $ans)) {
        header("Location: homestaff.php");
        exit();
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
    }
}
?>

<style>
    * {
        font-family: 'Poppins', sans-serif;
        text: black
    }

    .h-font {
        font-family: "Merienda", cursive;
    }

    .google-font {
        font-family: Arial, Helvetica, sans-serif;
    }

    table {
        border: 1px solid black;
        width: 100% !important;
        font-weight: bolder;
        font-size: 2vw;
        color: #042A38;
    }

    td {
        border: 1px solid black;
        width: 20%;
        font-weight: bolder;
        font-size: 2vw;

    }

    li {
        margin: 1.5vw;
    }

    ul {
        list-style: none;
        width: auto !important;
    }

    .navbar {
        background-color: #fff !important;
        font-size: 1.5vw;
        margin-top: 0;
    }

    .navbar>ul>li:hover {
        color: black;
        text-decoration: underline;
        font-weight: bold;

    }

    .navbar>ul>li>a:hover {
        color: black;
        text-decoration: underline;
        font-weight: bold !important;
    }

    a {
        text-decoration: none;
        color: #042A38;
    }

    .prof,
    #score {
        top: 3vw;
        position: fixed;
        width: 50vw !important;
        margin-left: 25vw !important;
        margin-right: 25vw !important;
        background-color: #fff !important;
        display: none !important;
        border-radius: 10px;
        margin-top: 2vw;
        z-index: 1;
        padding: 1vw;
        padding-left: 2vw;
        color: #042A38;
    }

    button {
        height: 5vh;
        width: 10vw;
        background-color: lightgoldenrodyellow;
        color: black;
        outline: none;
        border: none;
        border-radius: 10px;
        margin: 5vw;
    }

    input {
        width: 30vw;
        height: 3vw;
        border-radius: 10px;
        border: 2px solid black;
        padding-left: 2vw;
        font-weight: bolder;
        outline: none;
    }

    ::placeholder {
        font-weight: bold;
        font-family: 'Courier New', Courier, monospace;
    }

    label {
        font-weight: bolder;
    }

    button:hover {
        background-color: blueviolet !important;
    }

    .bg {
        background-size: 100%;
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
    }

    #sc {
        width: 100% !important;
        margin: 0%;
        color: #042A38;
    }

    #le {
        width: 90vw;
        margin: 0;
        color: #fff;
    }
</style>

<<body
    style="margin: 0 !important;font-weight: bolder !important;font-family: 'Courier New', Courier, monospace;color:#fff !important">
    <div style="background-color: #B0DAD0F1;min-height: 100vh text-black;">
        <!-- Navbar -->
        <div class="navbar shadow-none"
            style="display: inline-flex;width: 100%;color:#042A38;position:fixed;background:#fff;box-shadow:0 2px 5px rgba(0,0,0,0.2);">
            <section style="margin: 1vw;font-weight:bold;" class="h-font"> QUIZMASTER-HUB</section>
            <ul
                style="display: inline-flex;padding: 0 !important;margin: 0;float: right;right: 0;position: fixed;width: 50vw;justify-content:flex-end;">
                <li onclick="dash()" style="margin:1vw;cursor:pointer;">Dashboard</li>
                <li onclick="prof()" style="margin:1vw;cursor:pointer;">Profile</li>
                <li onclick="score()" style="margin:1vw;cursor:pointer;">Quiz's</li>
                <li onclick="lo()" style="margin:1vw;cursor:pointer;">Sign Out</li>
            </ul>
        </div><br><br><br>

        <!-- Add Question Section -->
        <section class="dash" style="margin-top:4vw">
            <center>
                <form
                    style="margin: 0vw;width: 60vw;background:#fff;padding:2vw;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.3);"
                    method="post">
                    <h2 style="color:#042A38;">➕ Add Question</h2><br>

                    <label for="qs" style="color:#042A38;">Question</label><br>
                    <textarea name="qs" placeholder="Enter question" required
                        style="width:100%;padding:0.5vw;border-radius:5px;"></textarea><br><br>

                    <label style="color:#042A38;">Option 1</label><br>
                    <input type="text" name="op1" placeholder="Option 1" required
                        style="width:100%;padding:0.5vw;border-radius:5px;"><br><br>

                    <label style="color:#042A38;">Option 2</label><br>
                    <input type="text" name="op2" placeholder="Option 2" required
                        style="width:100%;padding:0.5vw;border-radius:5px;"><br><br>

                    <label style="color:#042A38;">Option 3</label><br>
                    <input type="text" name="op3" placeholder="Option 3" required
                        style="width:100%;padding:0.5vw;border-radius:5px;"><br><br>

                    <label style="color:#042A38;">Option 4</label><br>
                    <input type="text" name="op4" placeholder="Option 4" required
                        style="width:100%;padding:0.5vw;border-radius:5px;"><br><br>

                    <label for="ans" style="color:#042A38;">Correct Answer</label><br>
                    <select name="ans" required style="width:100%;padding:0.5vw;border-radius:5px;">
                        <option value="" disabled selected>Select correct option</option>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select><br><br>

                    <input type="submit" name="submit" value="➕ Add More"
                        style="height: 3vw;width: auto;padding:0 1.5vw;font-weight:bolder;border-radius:10px;border:2px solid #042A38;background-color: lightblue;cursor:pointer;">

                    <input type="submit" name="submit1" value="✅ Done"
                        style="height: 3vw;width: auto;padding:0 1.5vw;font-weight:bolder;border-radius:10px;border:2px solid #042A38;background-color: lightgreen;cursor:pointer;">
                </form>
            </center>
        </section>

        <!-- Profile Section -->
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
            <p><b>USN&nbsp;:&nbsp;
                    <?php echo $dbusn ?>
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

        <!-- Quiz List Section -->
        <section id="score" style="display:none;color:#042A38;">
            <?php 
            $sql ="select * from quiz where mail='{$username1}'";
            $res=mysqli_query($conn,$sql);
            if($res)
            {
                echo "<h1>📋 Your Quizzes</h1>";
                echo "<table id=\"sc\" style='width:80%;margin:auto;border-collapse:collapse;box-shadow:0 2px 10px rgba(0,0,0,0.2);'>";
                echo "<thead style='background:#042A38;color:#fff;'><tr><td style='padding:1vw;'>Quiz ID</td><td>Quiz Title</td><td>Created On</td></tr></thead>";
                while ($row = mysqli_fetch_assoc($res)) {                
                    echo "<tr><td style='padding:0.5vw;text-align:center;'>".$row["quizid"]."</td>
                          <td style='padding:0.5vw;text-align:center;'>".$row["quizname"]."</td>
                          <td style='padding:0.5vw;text-align:center;'>".$row["date_created"]."</td></tr>"; 
                }
                echo "</table>";
            }
        ?>
        </section>
    </div>
    <!-- <?php require("footer.php");?> -->
    </body>