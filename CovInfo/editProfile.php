<?php

require_once ("Classes/classes.php");

session_start();
$connection = PDOSingleton::getInstance();

$logged_user = isset($_SESSION["LogIn"]);
$user = null;
if($logged_user){
    $user_id = $_SESSION["user_id"];
    $userProxyFactory = new UserProxyFactory();
    $user = $userProxyFactory->build($user_id);
}else{
    header("Location:Login.php");
    return;
}
$searchProfile = null;
$selfEdit = false;

if($user->getUserType() == "public"){
    header("Location:index.php");
    return;
}else{
    if(isset($_SESSION["searchedId"])){
        $userFactory = new UserFactory();
        $searchProfile = $userFactory->build($_SESSION["searchedId"]);
        if($_SESSION["user_id"] == $_SESSION["searchedId"]){
            header("Location:editmyprofile.php");
            return;
        }
    }else{
        header("Location:search.php");
        return;
    }
}
if(isset($_SESSION["ep-needUpdate"])){
    $searchProfile->updateProfile($_SESSION["ep-email"],$_SESSION["ep-firstName"],$_SESSION["ep-middleName"],$_SESSION["ep-lastName"],$_SESSION["ep-nic"],
        $_SESSION["ep-dob"],$_SESSION["ep-gender"],$_SESSION["ep-district"],$_SESSION["ep-province"],$_SESSION["ep-moh"],$_SESSION["ep-address"],
        $_SESSION["ep-phoneNumber"],$_SESSION["ep-bloodType"],$_SESSION["ep-medical"],$_SESSION["ep-userType"]);
    unset($_SESSION["ep-needUpdate"]);
    $goto = $_SESSION["ep-id"];
    header("Location:profile-view.php?id=$goto");
    return;
}

if(isset($_POST["update"])){
    $_SESSION["ep-firstName"] = $_POST["firstName"];
    $_SESSION["ep-middleName"] = $_POST["middleName"];
    $_SESSION["ep-lastName"] = $_POST["lastName"];
    $_SESSION["ep-nic"] = $_POST["nic"];
    $_SESSION["ep-dob"] = $_POST["dob"];
    $_SESSION["ep-gender"] = $_POST["gender"];
    $_SESSION["ep-phoneNumber"] = $_POST["phoneNumber"];
    $_SESSION["ep-email"] = $_POST["email"];
    $_SESSION["ep-address"] = $_POST["address"];
    $_SESSION["ep-district"] = $_POST["district"];
    $districtId = $_POST["district"];
    $province = $connection->query("SELECT province_id FROM district WHERE district_id = $districtId");
    while ($row = $province->fetch(PDO::FETCH_ASSOC)){
        $_SESSION["ep-province"] = $row["province_id"];
    }
    $_SESSION["ep-bloodType"] = $_POST["bloodType"];
    if($_POST["bloodType"] == ""){
        $_SESSION["ep-bloodType"] = 9;
    }
    $_SESSION["ep-moh"] = $_POST["moh"];
    $_SESSION["ep-medical"] = $_POST["medical"];
    $_SESSION["ep-id"] = $_SESSION["searchedId"];
    $_SESSION["ep-userType"] = $_POST["userType"];
    $_SESSION["ep-needUpdate"] = true;
    header("Location:editProfile.php");
    return;
}

$genderList = $connection->query("SELECT gender FROM gender");
$districtList = $connection->query("SELECT district_id,name FROM district");
$bloodTypeList = $connection->query("SELECT blood_type_name FROM blood_type");
$mohDivisionList = $connection->query("SELECT moh_division_id,moh_name FROM moh_division");
$userTypesList = $connection->query("SELECT user_type_name FROM user_type");



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CovInfo | Edit Profile</title>
    <link rel="stylesheet" href="https://www.cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="https://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
 <!--   <script src="scripts\profilepic.js"></script>
       <link href="
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
                    <a class="nav-link active" href="index.php">Home</a>
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
                            <a class="nav-link" aria-current="page" href="settings.php">Settings</a>
                        </li>
                    <?php }
                }
                ?>
            </ul>

            <ul class="nav navbar-nav ">
                <li class="dropdown">
                    <a  href="#" class="nav-link" style="border-bottom: none" role="button" data-bs-toggle="dropdown" id="notify" aria-expanded="false">
                        <?php  if($user->isNewNotificationsAvailable()) {?>    <!--   have_notifications-->
                        <img src="images/notification.png" alt="" width="24" height="24">
                        <span class="badge bg-primary"><?= $user->getNewNotificationCount() ?></span>
                        </button>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;overflow-y:scroll;height:50vh;">
                        <div class="notif" style="width:31vw">
                            <li class="notif-header">
                                <div class="d-flex">
                                    <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                    </div><div class=""><a href="notificationPage.php" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                    </div></div>
                            </li>
                            <div class="notif-items" >
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
                                        <span class='fw-lighter '>$notificationReceivedDate</span>
                                        <span style='padding-left:60%' class='fw-lighter  '>$notificationReceivedTime</span>
                                        </li>");
                                }
                                ?>

                            </div>
                        </div>
                    </ul>
                    <?php } else { ?>
                        <img src="images/notification.svg" alt="" width="24" height="24">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notify" style="list-style-type: none;">
                            <div class="notif">
                                <li class="notif-header">
                                    <div class="d-flex">
                                        <div class="me-auto" style="font-weight: 500; color: #0C91E6;font-size: 20px">Notifications
                                        </div><div class=""><a href="notificationPage.php" class="btn btn-primary btn-sm rounded-0" style="color: white">Read all</a>
                                        </div></div>
                                </li>
                                <div class="notif-items">
                                    <li class="dropdown-item">
                                        <span class="item-name">No New Notifications!</span>
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
<p class="h-4 m-3 p-3 row justify-content-center border border-2 rounded-3 boxy" style="font-weight: bold">Update User</p>
<br>
<div class="container">
    <div class="row gutters">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100 boxy-blue">
                <div class="card-body">
                    <br><br>
                    <div class="account-settings">
                        <div class="user-profile">
                            <div class="profile-pic">
                                <br>
                                <br>
                                <br>
                                <!--onchange="loadFile(event)"-->
                                <?php if($searchProfile->getGender() == "Male"){?>
                                    <img src="images/User-big.png" id="output" width="200" />
                                <?php }else{ ?>
                                    <img src=images/User-female.png" id="output" width="200" />
                                <?php } ?>

                            </div>
                            <br>
                            <br>

                        </div>
                    </div>

                    <div class="account-settings">
                        <div class="user-profile">
                            <h5 class="user-name"><?php echo $searchProfile->getFirstName()." ".$searchProfile->getLastName() ?></h5>
                            <h6 class="user-email"></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
            <form method="post">
            <div class="card h-100 boxy-blue">
                <div class="card-body">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mb-2 text-primary">Personal Details</h6>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="firstName" class="mx-1">First Name</label>
                                <input type="text" value="<?php echo $searchProfile->getFirstName();?>" class="form-control" id="firstName"  name="firstName" placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="middleName" class="mx-1">Middle Name</label>
                                <input type="text" value="<?php echo $searchProfile->getMiddleName();?>" class="form-control" id="middleName" name="middleName" placeholder="Enter middle name" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="lastName" class="mx-1">Last Name</label>
                                <input type="text" value="<?php echo $searchProfile->getLastName();?>" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="nic" class="mx-1">NIC</label>
                                <input type="text" value="<?php echo $searchProfile->getNICNumber();?>" class="form-control" id="nic" name="nic" placeholder="Enter NIC no.">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="dob" class="mx-1">Date of Birth</label>
                                <input type="date" value="<?php echo $searchProfile->getDOBString();?>" class="form-control" id="dob" name="dob" placeholder="Enter date of birth" required>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-2">
                            <div class="form-group">
                                <label for="gender" class="mx-1">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="" <?php echo $searchProfile->getGender() == "" ? "selected" : "" ?> hidden>Select Gender</option>

                                    <?php $i = 1;
                                    while ($row = $genderList->fetch(PDO::FETCH_ASSOC)){?>
                                        <option value="<?php echo $i++ ?>" <?php echo $searchProfile->getGender() == $row["gender"] ? "selected" : "" ?>><?php echo $row["gender"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2 text-primary">Contact Details</h6>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="phone" class="mx-1">Phone</label>
                                <input type="text" class="form-control" value="<?php echo $searchProfile->getPhoneNumber();?>" id="phone" name="phoneNumber" placeholder="Enter phone number">
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label for="email" class="mx-1">Email</label>
                                <input type="email" class="form-control" value="<?php echo $searchProfile->getEmailAddress();?>" id="email" name="email" placeholder="Enter email ID">
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="address" class="mx-1">Address</label>
                                <textarea type="text" class="form-control" required value="<?php echo $searchProfile->getAddress();?>" id="address" name="address" rows="3" placeholder=""Enter address"><?php echo $searchProfile->getAddress() ?> </textarea>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="district" class="mx-1">District</label>
                                <select class="form-select" id="district" required name="district">
                                    <option value="" <?php echo $searchProfile->getDistrict() == "" ? "selected" : "" ?> hidden>Select District</option>

                                    <?php $i = 1;
                                    while ($row = $districtList->fetch(PDO::FETCH_ASSOC)){?>
                                        <option value="<?php echo $row["district_id"] ?>" <?php echo $searchProfile->getDistrict() == $row["name"] ? "selected" : "" ?>><?php echo $row["name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <!--<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="province" class="mx-1">Province</label>
                                <select class="form-select" id="province">
                                    <option value="" selected hidden>Select province</option>
                                    <option value="1" <?php //echo $province == "Western province" ? "selected" : "" ?>>Western</option>
                                </select>
                            </div>
                        </div> -->
                    </div>
                    <br>
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2 text-primary">Medical Details</h6>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="bloodtype" class="mx-1">Blood Type</label>
                                <select class="form-select" id="bloodtype" name="bloodType">
                                    <option value="" <?php echo $searchProfile->getBloodType() == "" ? "selected" : "" ?> hidden>Select Blood type</option>

                                    <?php $i = 1;
                                    while ($row = $bloodTypeList->fetch(PDO::FETCH_ASSOC)){
                                        if($i == 9){
                                            $i++;
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $i++ ?>" <?php echo $searchProfile->getBloodType() == $row["blood_type_name"] ? "selected" : "" ?>><?php echo $row["blood_type_name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="moh" class="mx-1">MOH Division</label>
                                <select class="form-select" id="moh"  required name="moh">
                                    <option value="" <?php echo $searchProfile->getMOHDivision() == "" ? "selected" : "" ?> hidden>Select MOH division</option>

                                    <?php $i = 1;
                                    while ($row = $mohDivisionList->fetch(PDO::FETCH_ASSOC)){?>
                                        <option value="<?php echo $row["moh_division_id"] ?>" <?php echo $searchProfile->getMOHDivision() == $row["moh_name"] ? "selected" : "" ?>><?php echo $row["moh_name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="form-group">
                                <label for="medical" class="mx-1">Special Medical Conditions</label>
                                <textarea type="text" class="form-control" value="<?php echo $searchProfile->getMedicalRemarks()?>" id="medical" name="medical" rows="3" placeholder="Enter any chronic health problems or allergies"><?php echo $searchProfile->getMedicalRemarks() ?></textarea>
                            </div>
                        </div>
                    </div>
                    <br>

                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <h6 class="mt-3 mb-2 text-primary">Other Details</h6>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-4">
                            <div class="form-group">
                                <label for="userType" class="mx-1">User Type</label>
                                <select class="form-select" id="userType"  required name="userType">
                                    <option value="" <?php echo $searchProfile->getUserType() == "" ? "selected" : "" ?> hidden>Select MOH division</option>

                                    <?php $i = 1;
                                    while ($row = $userTypesList->fetch(PDO::FETCH_ASSOC)){
                                        if($row["user_type_name"] == "Admin"){
                                            $i++;
                                            continue;
                                        }
                                        ?>
                                        <option value="<?php echo $i++ ?>" <?php echo $searchProfile->getUserType() == $row["user_type_name"] ? "selected" : "" ?>><?php echo $row["user_type_name"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br>


                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="text-end">
                                <a href="profile-view.php?id=<?php echo $_SESSION["searchedId"] ?>"><button type="button" id="cancel" name="cancel" class="btn btn-outline-secondary mx-2">Cancel</button></a>
                                <button type="submit" id="update" name="update" class="btn btn-outline-primary mx-2">Update</button>
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