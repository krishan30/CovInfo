<?php

require_once ("Classes/classes.php");

session_start();

$logged_user = isset($_SESSION["LogIn"]);
$user = null;
if($logged_user){
    $user_id = $_SESSION["user_id"];
    $userFactory = new UserProxyFactory();
    $user = $userFactory->build($_SESSION["user_id"]);
    if($user->getUserType() != "Admin"){
        header("Location:index.php");
        return;
    }
}else{
    header("Location:login.php");
    return;
}



$connection = PDOSingleton::getInstance();

if(isset($_SESSION["set-needInsert"])){
    $sql = "UPDATE setting set vaccine_doses=:vaccine_doses where setting_id = 1";
    $stmt = $connection->prepare($sql);
    $stmt->execute(array(":vaccine_doses"=>$_SESSION["newDose"]));

    $userRecords = $connection->query("SELECT user_id, vaccine_status_id FROM user");
    while ($row = $userRecords->fetch(PDO::FETCH_ASSOC)){
        $sql = "SELECT vaccination_record.dose FROM vaccination_record where user_id=:user_id ORDER BY dose DESC LIMIT 1";
        $stmt = $connection->prepare($sql);
        $stmt->execute(array(':user_id'=>$row["user_id"]));
        $result=$stmt->fetch(PDO::FETCH_ASSOC);
        if($result) {
            $dose_count = $result["dose"];
        }else{
            $dose_count=0;
        }

        if($dose_count >= $_SESSION["newDose"]){
            $newState = 3;
        }else if($dose_count == 0){
            $newState = 1;
        }else{
            $newState = 2;
        }

        if($newState != $row["vaccine_status_id"]){
            $updateStateSql = "UPDATE user set vaccine_status_id=:vaccine_status_id where user_id=:user_id";
            $updateStateStmt = $connection->prepare($updateStateSql);
            $updateStateStmt->execute(array("vaccine_status_id"=>$newState,"user_id"=>$row["user_id"]));
        }
    }

    unset($_SESSION["set-needInsert"]);
    header("Location:index.php");
    return;
}

if(isset($_POST["set-register"])){
    $_SESSION["newDose"] = $_POST["dose"];
    $_SESSION["set-needInsert"] = true;
    header("Location:settings.php");
    return;
}


$genderList = $connection->query("SELECT gender FROM gender");
$districtList = $connection->query("SELECT name FROM district");
$bloodTypeList = $connection->query("SELECT blood_type_name FROM blood_type");
$mohDivisionList = $connection->query("SELECT moh_name FROM moh_division");




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo | Settings</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="scripts\profilepic.js"></script>
    <!--    <link href="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css
" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
 --><link rel="stylesheet" href="css\bootstrap.css">
    <link rel="stylesheet" href="styles\user-details.css">
    <link rel="stylesheet" href="styles\styles.css">
    <link rel = "icon" href = "logos/logo_icon.png" type = "image/x-icon">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow fixed-top bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="logos\brand.png" alt="Site logo" width="110px" height="auto"> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="statistic.php">Statistics</a>
                </li>
                <?php
                if($logged_user){
                    if($user->getUserType() == "Authority" || $user->getUserType() == "Medical"){?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="search.php">Search</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="user-create.php">Add New User</a>
                        </li>
                    <?php }

                    if($user->getUserType() == "Admin"){?>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Settings</a>
                        </li>
                    <?php }
                }
                ?>

            </ul>
            <ul class="nav navbar-nav ">
                <li class="dropdown">
                    <a  href="#" class="nav-link" style="border-bottom: none" role="button" data-bs-toggle="dropdown" id="notify" aria-expanded="false">
                        <?php  if($user->isNewNotificationsAvailable()) {?>    <!--   have_notifications-->
                        <img src="images/notification.svg" alt="" width="24" height="24">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                        <div class="notif">
                            <li class="notif-header">
                                <div class="d-flex">
                                    <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                    </div><div class=""><a href="#" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                    </div></div>
                            </li>
                            <div class="notif-items">
                                <?php
                                $connection = PDOSingleton::getInstance();
                                $query = "SELECT notification_id  FROM notification WHERE receiver_id=:receiver_id && read_status_id=1 ORDER BY sent_date_time DESC ";
                                $stmt = $connection->prepare($query);
                                $stmt->execute(array(":receiver_id" => $user->getUserId()));
                                $notificationsIDS = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                $notifications = array();
                                $index = 0;
                                foreach ($notificationsIDS as $notificationID) {
                                    $notification = NotificationFactory::buildNotification((int)$notificationID["notification_id"]);
                                    $notifications[$index] = $notification;
                                    $index++;
                                }
                                foreach ($notifications as $index => $notification) {
                                    $notificationMessage = $notification->getNotificationTypeHeading();
                                    $notificationReceivedTime = $notification->getReceivedTime();
                                    $notificationReceivedDate = $notification->getReceivedDate();
                                    echo("<li style='cursor: pointer' class='dropdown-item'>
                                        <span  class='item-name fw-bold'> $notificationMessage</span>
                                        <br>
                                        <span class='fw-lighter me-5 '>$notificationReceivedDate</span>
                                        <span class='fw-lighter ms-5 '>$notificationReceivedTime</span>
                                        </li>");
                                }
                                ?>

                            </div>
                        </div>
                    </ul>
                    <?php } else { ?>
                        <img src="images/bell.svg" alt="" width="24" height="24">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                            <div class="notif">
                                <li class="notif-header">
                                    <div class="d-flex"><div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications</div></div>
                                </li>
                                <div class="notif-items">
                                    <li class="dropdown-item">
                                        <span class="item-name">No new Notifications!</span>
                                    </li>
                                </div>
                            </div>
                        </ul>
                    <?php } ?>
                </li>
                <li class="nav-item"><a class="nav-link" href="profile.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M12 2.5a5.5 5.5 0 00-3.096 10.047 9.005 9.005 0 00-5.9 8.18.75.75 0 001.5.045 7.5 7.5 0 0114.993 0 .75.75 0 101.499-.044 9.005 9.005 0 00-5.9-8.181A5.5 5.5 0 0012 2.5zM8 8a4 4 0 118 0 4 4 0 01-8 0z"></path></svg>&nbsp;<?php echo $user->getFirstName()." ".$user->getLastName() ?></a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php" title=""><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M3 3.25c0-.966.784-1.75 1.75-1.75h5.5a.75.75 0 010 1.5h-5.5a.25.25 0 00-.25.25v17.5c0 .138.112.25.25.25h5.5a.75.75 0 010 1.5h-5.5A1.75 1.75 0 013 20.75V3.25zm16.006 9.5l-3.3 3.484a.75.75 0 001.088 1.032l4.5-4.75a.75.75 0 000-1.032l-4.5-4.75a.75.75 0 00-1.088 1.032l3.3 3.484H10.75a.75.75 0 000 1.5h8.256z"></path></svg>&nbsp;Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<br><br>
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">Settings</p>
<br>
<div class="container">
    <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <form method="post">
                <div class="card h-100 boxy-blue">
                    <div class="card-body">
                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <h6 class="mb-2 text-primary">Settings</h6>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                                <div class="form-group">
                                    <label for="firstName" class="mx-1">Vaccine Doses</label>
                                    <input type="number" class="form-control" required id="dose" name="dose" placeholder="Enter vaccine doses">
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row gutters">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="text-end">
                                    <a href="index.php"><button type="button" id="cancel" name="cancel" class="btn btn-outline-secondary">Cancel</button></a>
                                    <button type="submit" id="set-register" name="set-register" class="btn btn-outline-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<br>

<!--<script src="
https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js
" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
-->
<script src="js\bootstrap.js"></script>
</body>
</html>