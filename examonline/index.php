<?php session_start(); ?>
<html>

<head>
    <title>Online Examination System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<?php
        if (isset($_POST['login'])) {
            if (isset($_POST['usertype']) && isset($_POST['username']) && isset($_POST['pass'])) {        require_once 'sql.php';
                $conn = mysqli_connect($host, $user, $ps, $project);if (!$conn) {
                    echo "<script>alert(\"Database error retry after some time !\")</script>";
                }
                $type = mysqli_real_escape_string($conn, $_POST['usertype']);
                $username = mysqli_real_escape_string($conn, $_POST['username']);
                $password = mysqli_real_escape_string($conn, $_POST['pass']);
                $password = crypt($password, 'rakeshmariyaplarrakesh');
                $sql = "select * from " . $type . " where mail='{$username}'";
                $res =   mysqli_query($conn, $sql);
                if ($res == true) {
                    global $dbmail, $dbpw;
                    while ($row = mysqli_fetch_array($res)) {
                        $dbpw = $row['pw'];
                        $dbmail = $row['mail'];
                        $_SESSION["name"] = $row['name'];
                        $_SESSION["type"] = $type;
                        $_SESSION["username"] = $dbmail;
                    }
                    if ($dbpw === $password) {
                        if ($type === 'student') {
                            header("location:homestud.php");
                        } elseif ($type === 'staff') {
                            header("Location: homestaff.php");
                        }
                    } elseif ($dbpw !== $password && $dbmail === $username) {
                        echo "<script>alert('password is wrong');</script>";
                    } elseif ($dbpw !== $password && $dbmail !== $username) {
                        echo "<script>alert('username name not found sing up');</script>";
                    }
                }
            }
        }
        ?>
<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    .h-font {
        font-family: "Merienda", cursive;

    }
    .google-font{
        font-family:Arial, Helvetica, sans-serif ;
    }

    @media screen and (max-width: 620px) {
        input {
            height: 6vw !important;
        }

        .seluse {
            display: grid;
        }

        .sub {
            width: 20vw !important;
        }
    }

    .inp {
        box-sizing: content-box !important;
        width: 30vw;
        height: 3vw;
        border-radius: 10px;
        border: 2px solid black;
        padding-left: 2vw;
        font-weight: bolder;
        outline: none;
    }

    label {
        font-weight: bolder;
        font-size: 1.5vw;
    }

    form {
        font-size: 1vw;
        margin: 0;
    }

    .bg {
        background-size: 100%;
    }

    a {
        color: #042A38;
    }

    .login {
        max-height: 90vh;
    }
    .btn::hover{
        background-color: white;
    }
</style>

<body style="margin:0;height: 97%;ouline:none;color: #042A38f !important;padding-botton:5vw;">
    <div class="container">
        <div class="bg overflow-hidden "
            style="font-weight: bolder;background-image: url(./images/exammm.png);background-repeat: no-repeat;padding: 0;margin: 0;background-size: cover;font-family: 'Courier New', Courier, monospace;opacity: 0.9;height: 95%; ">
            <center>
                <div class="sticky-top mb-3">
                    <h1 class="w3-containe h-font "
                        style=" color:#042A38;text-transform: uppercase;width: auto;background:white; padding-bottom: 4vh;">
                        QUIZMASTER-HUB</h1>
                    <h6 class="google-font" style="margin-top: -33px;">competitive and interactive vibe !</h6>
                </div>
            </center>

            <center>
                <div class="w3-card" class="login"
                    style="color: #042A38;width: 35vw;background-color:#CEE5DF;border: 2px solid black;padding: 2vw;font-weight: bolder;margin-top: 3vh;border-radius: 10px;">
                    <form method="POST">
                        <div class="seluser d-flex">
                            <input type="radio" name="usertype" value="student" required>STUDENT 
                            <input type="radio" name="usertype" value="staff" required>STAFF 
                        </div><br><br>
                        <div class="signin">
                            <label for="username" style="text-transform: uppercase;">Email</label><br><br>
                            <input type="email" name="username" placeholder=" Enter your email" class="inp" required>
                            <br><br>
                            <label for="password" style="text-transform: uppercase;">Password</label><br><br>
                            <input type="password" name="pass" placeholder="******" class="inp" required>
                            <br><br>
                            <input name="login" class="sub btn" type="submit" value="Login"
                                style="height: 3vw;width: 10vw;font-family: 'Courier New', Courier, monospace;font-weight: bolder;border-radius: 10px; border: 2px solid black;background-color:#b0dad0"><br>
                    </form><br>
                    <a style="text-decoration: none; " href="reset.php">Forgotten password?</a> &nbsp; New user !
                    <button class="sub google-font" style="height: 2vw;width: 10vw; Courier, monospace;font-weight: bold;border-radius: 5px;border: 2px solid black;background-color:#b0dad0"><a style="text-decoration: none; " href="signup.php">SIGN UP</a></button>
                </div>
        </div>
        </center>

    </div>

    <?php require("footer.php"); ?>

</body>

</html>