<html>

<head>
    <title>Online Examination System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<?php
session_start();
require_once 'sql.php';

// ---------------- Database connection ----------------
$conn = mysqli_connect($host, $user, $ps, $project);
if (!$conn) {
    die("<script>alert('Database connection failed!');</script>");
}
// Set charset for Unicode / emoji safety
mysqli_set_charset($conn, "utf8mb4");

// ---------------- Validate session ----------------
if (empty($_SESSION["type"]) || empty($_SESSION["username"])) {
    die("Unauthorized access. Please login first.");
}

$type1     = $_SESSION["type"];
$username1 = $_SESSION["username"];

// ---------------- Fetch logged-in user details ----------------
$sql = "SELECT * FROM `$type1` WHERE mail='" . mysqli_real_escape_string($conn, $username1) . "'";
$res = mysqli_query($conn, $sql);

if ($res && mysqli_num_rows($res) > 0) {
    $row      = mysqli_fetch_assoc($res);
    $dbmail   = $row['mail'] ?? '';
    $dbname   = $row['name'] ?? '';
    $dbusn    = $row['usn'] ?? '';
    $dbphno   = $row['phno'] ?? '';
    $dbgender = $row['gender'] ?? '';
    $dbdob    = $row['DOB'] ?? '';
    $dbdept   = $row['dept'] ?? '';
} else {
    die("User not found in database.");
}

// ---------------- Get quiz ID from URL ----------------
$qid = isset($_GET["qid"]) ? intval($_GET["qid"]) : 0;
if ($qid === 0) {
    die("Quiz ID missing. Open this page with ?qid=QUIZ_ID");
}

// ---------------- Insert Question function ----------------
function insertQuestion($conn, $qid, $qs, $op1, $op2, $op3, $op4, $ans) {
    // Remove any emoji or special chars that can break DB
    $qs  = mysqli_real_escape_string($conn, strip_tags($qs));
    $op1 = mysqli_real_escape_string($conn, strip_tags($op1));
    $op2 = mysqli_real_escape_string($conn, strip_tags($op2));
    $op3 = mysqli_real_escape_string($conn, strip_tags($op3));
    $op4 = mysqli_real_escape_string($conn, strip_tags($op4));
    $ans = mysqli_real_escape_string($conn, $ans);

    $sql = "INSERT INTO questions (qs, op1, op2, op3, op4, answer, quizid) 
            VALUES ('$qs', '$op1', '$op2', '$op3', '$op4', '$ans', '$qid')";
    return mysqli_query($conn, $sql);
}

// ---------------- Insert Question (stay on page) ----------------
if (isset($_POST['submit'])) {
    $qs  = trim($_POST["qs"]);
    $op1 = trim($_POST["op1"]);
    $op2 = trim($_POST["op2"]);
    $op3 = trim($_POST["op3"]);
    $op4 = trim($_POST["op4"]);
    $ans = $_POST["ans"];

    if (insertQuestion($conn, $qid, $qs, $op1, $op2, $op3, $op4, $ans)) {
        echo '<script>alert("Question added successfully!");</script>';
    } else {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
    }
}

// ---------------- Insert Question (redirect to staff home) ----------------
if (isset($_POST['submit1'])) {
    $qs  = trim($_POST["qs"]);
    $op1 = trim($_POST["op1"]);
    $op2 = trim($_POST["op2"]);
    $op3 = trim($_POST["op3"]);
    $op4 = trim($_POST["op4"]);
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

    input,
    textarea,
    select {
        width: 100%;
        padding: 0.5vw;
        border-radius: 5px;
        border: 2px solid black;
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
</style>

<body style="margin: 0;font-family: 'Courier New', Courier, monospace;color:#042A38;">
    <div style="background-color: #B0DAD0F1;min-height: auto;">
        <!-- Navbar -->
        <div class="navbar shadow-none"
            style="display: inline-flex;width: 100%;color:#042A38;position:fixed;background:#fff;box-shadow:0 2px 5px rgba(0,0,0,0.2);">
            <section style="margin: 1vw;font-weight:bold;" class="h-font"> QUIZMASTER-HUB</section>
            <ul
                style="display: inline-flex;padding: 0;margin: 0;float: right;right: 0;position: fixed;width: 50vw;justify-content:flex-end;">
                <li class="google-font " onclick="dash()" style="margin:1vw;cursor:pointer;">Dashboard</li>
                <li class="google-font " onclick="prof()" style="margin:1vw;cursor:pointer;">Profile</li>
                <li class="google-font " onclick="score()" style="margin:1vw;cursor:pointer;">Quiz's</li>
                <li class="google-font " onclick="lo()" style="margin:1vw;cursor:pointer;">Sign Out</li>
            </ul>
        </div><br><br><br>

        <!-- Add Question Section -->
        <section class="dash" style="margin-top:4vw">
            <center>
                <form
                    style="margin: 0;width: 60vw;background:#fff;padding:2vw;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.3);"
                    method="post">
                    <h2 style="color:#042A38;">➕ Add Question</h2><br>

                    <label>Question</label><br>
                    <textarea name="qs" placeholder="Enter question" required></textarea><br><br>

                    <label>Option 1</label><br>
                    <input type="text" name="op1" placeholder="Option 1" required><br><br>

                    <label>Option 2</label><br>
                    <input type="text" name="op2" placeholder="Option 2" required><br><br>

                    <label>Option 3</label><br>
                    <input type="text" name="op3" placeholder="Option 3" required><br><br>

                    <label>Option 4</label><br>
                    <input type="text" name="op4" placeholder="Option 4" required><br><br>

                    <label>Correct Answer</label><br>
                    <select name="ans" required>
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
    </div>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@700&display=swap" rel="stylesheet">
</body>

</html>