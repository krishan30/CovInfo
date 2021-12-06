<?php
require_once "Classes/Pdo.php";

    session_start();
    if(isset($_SESSION["LogIn"])){
         header('Location:index.php');
         return;
    }
    if(isset($_POST["account_id"]) && isset($_POST["password"])) {
        unset($_SESSION["user_id"]); //Logout current user
        $pwd = md5($_POST["password"]);
        $sql = "SELECT user.user_id,status.status_name FROM user join status ON user.status_id=status.status_id  WHERE user.password=:password and user.account_id=:account_id";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':password' => $pwd, ':account_id' => $_POST["account_id"]));
        $row = $stmt->fetch(PDO:: FETCH_ASSOC);
        if ($row !== false && $row["status_name"] !== "Deceased") {
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["LogIn"] = true;
            header("Location:index.php");
        } elseif ($row["status_name"] == "Deceased") {
            $_SESSION["Deceased"] = true;
            header("Location:Login.php");
        } else {
            $_SESSION["UnsucessLogIn"] = true;
            header("Location:Login.php");
        }
        return;

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.css" rel="stylesheet">
    <title>CovInfo-Login</title>
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
</head>
<body>

<div class="container-fluid m-0 w-100 h-100"  >
    <div class="row p-0  vh-100 " >
        <div class="container-fluid col-4 px-5 pb-0 m-0" >
            <div class="container mb-3">
                <a href="statistic.php">
                    <img  class="img-fluid" src="logos/brand.png" alt="Logo">
                </a>
            </div>
            <h1 class="display-5 text-center mb-5" style="font-weight:400">Login</h1>
            <form class="<?=isset($_SESSION["UnsucessLogIn"]) ||isset($_SESSION["Deceased"]) ?'was-validated':'is-invalid'?>" method="post" action="Login.php">
                <div class="mb-4 mt-3">
                    <label for="account_id" class="form-label">Account ID</label>
                    <input type="text" name="account_id" class="form-control" placeholder="Enter Account ID"  required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label ">Password</label>
                    <input type="password" name="password" class="form-control"  placeholder="Enter password" required>
                    <?php
                    if(isset($_SESSION["UnsucessLogIn"])){
                        unset($_SESSION["UnsucessLogIn"]);
                        echo ('<div class="invalid-feedback">Incorrect Account ID or Password</div>');

                    }
                    if(isset($_SESSION["Deceased"])){
                        unset($_SESSION["Deceased"]);
                        echo ('<div class="invalid-feedback">Couldn\'t find your account</div>');
                    }
                    ?>
                </div>
                <!--<div class="form-check mb-4">
                    <label class="form-check-label">
                        <input class="form-check-input " type="checkbox" name="remember"> Remember me
                    </label>
                </div>-->
                <div class="text-center mt-5">
                    <button type="submit" name="sign-in" class="btn btn-outline-primary bg-gradient btn-lg ">Sign In</button>
                </div>
            </form>
        </div>
        <div class="container-fluid col-8  p-0" >
            <img class="img-fluid" src="images/Login_page.png" alt="covid-19 prevention" style="object-fit: inherit;height:100%">
        </div>
    </div>
</div>
<script src="js/bootstrap.js"></script>
</body>
</html>
