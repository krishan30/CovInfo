<?php
require_once "Classes/Pdo.php";
require_once "Classes/classes.php";
session_start();


$logged_user = true ; // $logged_user = isset($_SESSION["LogIn"]);

/*
 * below $_SESSION["user_id"] should be get from session global variable after connecting
 * with other pages. For the testing, it hard coded as 2.
 */
$_SESSION["user_id"] = 2;
$auth_id = $_SESSION["user_id"];

if(isset($_SESSION["user_id"])) {
    if (isset($_POST["end-date"]) && isset($_POST["place-of-quarantine"])) {
        unset($_SESSION["QRegistration"]);
        $sql = "INSERT INTO  quarantine_record (user_id, start_date, end_date, administrator_id,place_id) VALUES (:user_id,:start_date,:end_date,:administrator_id,:place_id)";
        $stmt = $connection->prepare($sql);
        $administrator_id = $connection->query("SELECT administrator_id FROM administrator WHERE user_id=".$_SESSION["user_id"])->fetch(PDO:: FETCH_ASSOC);
        $user_id=$_POST["user-id"];
        $stmt->execute(array(':user_id' =>$user_id, ':start_date' => $_POST["start-date"], ':end_date' => $_POST["end-date"], ':administrator_id' => $administrator_id["administrator_id"],':place_id'=>$_POST['place-of-quarantine']));
        $sql = "UPDATE user set status_id=2  where user_id=:user_id";
        $stmt=$connection->prepare($sql);
        $stmt->execute(array(':user_id' => $user_id));
        $_SESSION["QRegistration"]=true;
        header("Location:QuarantineReport.php");
        return;
    }elseif (isset($_POST["backToHome"])){
        unset($_SESSION["QRegistration"]);
        header("Location:index.php");
        return;
    }
}else{
    header("Location:Login.php");
    return;
}

?>
<?php

    $user_details= $connection->query("SELECT user.account_id,user.password,user.email_address,user.first_name,user.middle_name,user.last_name,user.nic_number,user.birth_day,gender.gender,district.name,province.prov_name,moh_division.moh_name,user.address,user.phone_number,status.status_name,vaccine_status.vaccine_status_name,blood_type.blood_type_name
                                        FROM user,gender,district,province,moh_division,status,vaccine_status,blood_type
                                        WHERE user_id = $auth_id AND user.gender_id = gender.gender_id AND user.district_id = district.district_id AND user.province_id = province.province_id AND user.blood_type_id = blood_type.blood_type_id AND user.moh_division_id = moh_division.moh_division_id AND user.status_id = status.status_id AND user.vaccine_status_id = vaccine_status.vaccine_status_id");
    $user = null;
    while ($row = $user_details->fetch(PDO::FETCH_ASSOC)) {
        $user = new User($row["account_id"],$row["password"],$row["email_address"],$row["first_name"],$row["middle_name"],$row["last_name"],$row["nic_number"],
            $row["birth_day"],$row["gender"],$row["name"],$row["prov_name"],$row["moh_name"],$row["address"],$row["phone_number"],$row["status_name"],$row["vaccine_status_name"],$row["blood_type_name"]);
    }

    $accountId="19991026000"; //Temporary for evaluation purpose
    $sql="SELECT user.user_id,user.first_name,user.middle_name,user.last_name,user.nic_number,user.birth_day,gender.gender FROM user join gender ON user.gender_id=gender.gender_id  WHERE user.account_id=:account_id";
    $stmt=$connection->prepare($sql);
    $stmt->execute(array(':account_id'=>$accountId));
    $row=$stmt->fetch(PDO:: FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
   --> <link href="css/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <title>CovInfo - Quarantine Registration</title>
    <link rel = "icon" href = "logos/logo_icon.png"
          type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light  ">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><img src="logos/brand.png" alt="Site logo" width="110px" height="auto"> </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="statistic.php">Statistics</a>
                    </li>
                </ul>
                
                <?php if ($logged_user) { ?>
                    <ul class="nav navbar-nav ">
                        <li class="nav-item"><a class="nav-link" href="profile.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg><?php echo $user->getFirstName()." ".$user->getLastName() ?></a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm16.006 9.5l-3.3 3.484a.75.75 0 001.088 1.032l4.5-4.75a.75.75 0 000-1.032l-4.5-4.75a.75.75 0 00-1.088 1.032l3.3 3.484H10.75a.75.75 0 000 1.5h8.256z"></path></svg>Logout</a></li>
                    </ul>
                <?php } else { ?>
                    <span class="navbar-text">Already have an account?</span>
                    <ul class="navbar-nav ">
                        <li class="nav-item">
                            <a class="nav-link" role="button" aria-expanded="false" href="#"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm9.994 9.5l3.3 3.484a.75.75 0 01-1.088 1.032l-4.5-4.75a.75.75 0 010-1.032l4.5-4.75a.75.75 0 011.088 1.032l-3.3 3.484h8.256a.75.75 0 010 1.5h-8.256z"></path></svg>Login</a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </nav>
    <br><br><br><br>
<div class="container bg-white boxy-blue p-4 mb-4 rounded-3">
<div class="container d-grid gap-3 bg-white p-3  ">
    <div class="row ">
        <p class="fs-5 text-center fw-bold">Quarantine Registration Form</p>
    </div>
    <?php if(!isset($_SESSION['QRegistration'])){?>
    <div class="row border-bottom border-primary">
        <p class="fs-5 text-justify">Personal Details</p>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="first-name" class="form-label">First Name</label>
            <input type="text" id="fist-name" form="quarantine-form" name="first-name" class="form-control" value="<?=$row['first_name'] ?>" readonly>
        </div>
        <div class="col-sm">
            <label for="second-name" class="form-label">Second Name</label>
            <input type="text" id="second-name" name="second-name" form="quarantine-form" class="form-control" value="<?=$row['middle_name']?>" readonly>
        </div>
        <div class="col-sm">
            <label for="last-name" class="form-label">Last Name</label>
            <input type="text" id="last-name" name="last-name" form="quarantine-form" class="form-control" value="<?=$row['last_name'] ?>" readonly>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <label for="id-num" class="form-label">NIC Number</label>
            <input type="text" id="id-num" name="id-num" form="quarantine-form" class="form-control" value="<?=$row['nic_number'] ?>" readonly>
        </div>
        <div class="col-sm">
            <label for="birth-day" class="form-label">Birth Date</label>
            <input type="text" id="birth-day" name="birth-day" form="quarantine-form" class="form-control" value="<?=$row['birth_day'] ?>" readonly>
        </div>
        <div class="col-sm">
            <label for="sex" class="form-label">Sex</label>
            <input type="text" id="sex" name="sex" form="quarantine-form" class="form-control" value="<?=$row['gender'] ?>" readonly>
        </div>
    </div>
</div>

<div class="container d-grid gap-3 bg-white p-3">
    <div class="row border-bottom border-primary">
        <p class="fs-5 text-justify">Quarantine Details</p>
    </div>
    <form action="" method="post" id="quarantine-form">
        <div class="row">
            <div class="col-sm">
                <input type="text" name="user-id" value="<?=$row['user_id']?>" style="display: none" readonly>
                <label for="start-date" class="form-label">Quarantine Start Date</label>
                <input type="date" id="start-date" name="start-date" class="form-control" value="<?= date('Y-m-d', time());?>" readonly>
            </div>
            <div class="col-sm">
                <label for="end-date" class="form-label">Quarantine End Date</label>
                <input type="date" min="<?=date('Y-m-d', time());?>" id="end-date" name="end-date" class="form-control" required>
            </div>
            <div class="col-sm">
                <label for="place" class="form-label">Quarantine Location</label>
                <select class="form-select" id="place" aria-label="Default select example" name="place-of-quarantine" required>
                        <option  selected value="">Select a place</option>
                        <?php
                        $quarantinePlaces = $connection->query("SELECT * FROM Quarantine_Place")->fetchAll(PDO:: FETCH_ASSOC);
                        foreach ($quarantinePlaces as $row){
                            echo ("<option value=$row[quarantine_place_id]>$row[quarantine_place_name]</option>");
                        }

                        ?>
                </select>
            </div>
        </div>
        <br><br>
        <div class="row">
                <div class="col-sm text-center">
                    <button type="submit" class="btn btn-outline-secondary btn-lg ">Cancel</button>
                </div>
                <div class="col-sm text-center">
                    <button type="submit" class="btn btn-outline-primary btn-lg " >Register</button>
                </div>
        </div>
    </form>
    <?php } else { ?>
        <div class=" container d-grid gap-3 p-4">
            <img class="container" src="images/RegistrationSuccess.png" alt="Registration complete" style="width:150px;">
        </div>
        <div class="alert alert-success text-center">
            <strong>Registration success!</strong>
        </div>
        <form method="post">
        <div class="text-center pb-5">
            <input type="text" name = "backToHome" value="1" hidden>
            <button type="submit" class="btn btn-outline-secondary">Back To Home</button>
        </div>
        </form>

    <?php } ?>

</div>
</div>

<script src="js/bootstrap.js"></script>
</body>
</html>